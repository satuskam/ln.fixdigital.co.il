<?php
/*
Plugin Name:  Web Group CRM Plugin
Description: Services to interact with Web Group CRM Plugin
Version: 2.4.1
Author: satuskam
Author URI: atuskam@gmail.com
*/

require_once 'FixdigitalCrmUtils.php';
require_once 'FixdigitalLeadsLog.php';
require_once 'FixdigitalCrmIntegrationApi.php';
require_once 'UploadedFileLinkFinder.php';

use WebGroupCrm\UploadedFileLinkFinder;

define('WEB_GROUP_CRM_PLUGIN_ID', 'web_group_crm_id');
define('WEB_GROUP_CRM_PLUGIN_NAME', 'Web Group CRM');
define('WEB_GROUP_CRM_OPTIONS', 'web_group_crm_options');


class WP_WebGroupUcoCrmIntergration {
    private $_version = '2.4.1';
    
    private $_fieldsNamesTypesMap = null;
    private $_leadsLog = null;
    private $_utils = null;
    private $_api = null;
    private $_mailer = null;
    
    private $_defaultCrmUrlForFormsLeads   = '//www.fixdigital.co.il/api/v1.2/lead/addwordpress';
    private $_defaultCrmUrlForPagesViews   = '//www.fixdigital.co.il/api/v1.2/view/addwordpress';
    private $_defaultCrmUrlForPhoneNumbers = '//www.fixdigital.co.il/api/phones/forchannel/';
    private $_defaultLabelsForNameMapping = "שם\nname";
    
    
    public function __construct()
    {
        $this->_leadsLog = new FixdigitalLeadsLog();
        $this->_utils = new FixdigitalCrmUtils();
        $this->_api = new FixdigitalCrmIntegrationApi();
    }
    
    public function init()
    {
        if (is_multisite()) {
            add_action('network_admin_menu', array($this, 'addWebGroupCrmSettingsToNetworkAdminMenu'));
        } else {
            add_action('admin_menu', array($this, 'addWebGroupCrmSettingsToAdminMenu'));
        }
        
        add_action( 'wp_enqueue_scripts', array($this, 'initWebgroupUtilsScript'));
        
        add_action( 'wp_enqueue_scripts', array($this, 'initPageViewCrmInfoScript'));
        
        add_action( 'wp_enqueue_scripts', array($this, 'initFormInfoCrmScript'));
        
        add_action( 'wp_enqueue_scripts', array($this, 'initReplacePhoneByChannelIdScript'));
        
        add_action( 'phpmailer_init', array($this, 'contactFormSent'), 10000);
        
        // cron-hooks
        add_action('resentFailedLeadsToCrmCron', array($this, 'resentFailedLeadsFromLog'));
        
        add_filter('cron_schedules', [$this, 'addCronSchedules']);
        
        // create 'wp_fxdl_leads_log' table in DB on plugin's activation
        register_activation_hook( __FILE__, [$this, 'onActivate'] );
        register_deactivation_hook( __FILE__, [$this , 'onDeactivate']);
        
        // init API to emulate the leads sending (need for dubug)
        add_action( 'wp_ajax_sendLeadDataToCrm', [$this, 'sendLeadDataToCrmApi'] );
        add_action( 'wp_ajax_nopriv_sendLeadDataToCrm', [$this, 'sendLeadDataToCrmApi'] );
    }
    
    
    public function addWebGroupCrmSettingsToNetworkAdminMenu()
    {
        if (is_multisite() && !is_main_site()) return;

        add_menu_page(
            WEB_GROUP_CRM_PLUGIN_NAME,
            '<span style="color: yellow">' . WEB_GROUP_CRM_PLUGIN_NAME . '</span>',
            'options',
            WEB_GROUP_CRM_PLUGIN_ID,
            array($this, 'renderWebGroupCrmSettingsPage')
        );	
    }
    
    /*
     * Add plugin's item to admin settings menu for usual site
     */
    public function addWebGroupCrmSettingsToAdminMenu()
    {
        add_menu_page(
            WEB_GROUP_CRM_PLUGIN_NAME,
            '<span style="color: yellow">' . WEB_GROUP_CRM_PLUGIN_NAME . '</span>',
            'manage_options',
            WEB_GROUP_CRM_PLUGIN_ID,
            array($this, 'renderWebGroupCrmSettingsPage')
        );	
    }
    
    
    public function renderWebGroupCrmSettingsPage()
    {
        if (isset($_POST[WEB_GROUP_CRM_OPTIONS])) {
            function filter(&$value) {
                $value = stripslashes( trim($value) );
            }

            array_walk_recursive($_POST[WEB_GROUP_CRM_OPTIONS], 'filter');

            if (is_multisite()) {
                update_site_option(WEB_GROUP_CRM_OPTIONS, $_POST[WEB_GROUP_CRM_OPTIONS]);
            } else {
                update_option(WEB_GROUP_CRM_OPTIONS, $_POST[WEB_GROUP_CRM_OPTIONS]);
            }

            echo '<div id="message" class="updated">Settings saved</div>';
        }

        $html = join('', array(
            '<h1>Web Group CRM Integration Settings</h1>',
            '<form method="POST">',
                '<table>',
                    $this->renderFields(),
                '</table>',
                '<input type="submit" value="Save">',
            '</form>'

        ));

        echo($html);
    }
    
    
    public function renderFields()
    {
        $html = '';
        $fieldsData = array();
        
        if ($this->_utils->isCustomBlog()) {
            $clientId = $this->getOption('clientId');
            $clientIdFieldName = WEB_GROUP_CRM_OPTIONS."[clientId]";

            $tenantId = $this->getOption('tenantId');
            $tenantIdFieldName = WEB_GROUP_CRM_OPTIONS."[tenantId]";
            
//            $labelsForNameField = $this->getOption('labelsForNameField');
            $labelsForNameFieldName = WEB_GROUP_CRM_OPTIONS."[labelsForNameField]";
            
            $fieldsData = array(
                array(
                    'label' => 'Client ID', 'name' => $clientIdFieldName, 'value' => $clientId
                ),
                array(
                    'label' => 'Tenant ID', 'name' => $tenantIdFieldName, 'value' => $tenantId
                ),
                
                array(
                    'label' => 'Labels for "name" field mapping',
                    'name' => $labelsForNameFieldName,
                    'value' => $this->getLabelsForNameMapping(false),
                    'type' => 'textarea',
                    'placeholder' => $this->_defaultLabelsForNameMapping
                )
            );
            
        } else {
            $fieldsData[] = array(
                'label' => 'Multisite ID for CRM',
                'name' => WEB_GROUP_CRM_OPTIONS.'[multisiteCrmId]',
                'value' => $this->getOption('multisiteCrmId')
            );
        }
        
        $formSentRequestUrl = $this->getRequestUrlToCrm('form', false, false);
        $formSentRequestUrlFieldName = WEB_GROUP_CRM_OPTIONS."[formSentRequestUrl]";

        $pageOpenedRequestUrl = $this->getRequestUrlToCrm('page', false, false);
        $pageOpenedRequestUrlFieldName = WEB_GROUP_CRM_OPTIONS."[pageOpenedRequestUrl]";
        
        $fieldsData[] = array(
            'label' => 'Request URI to send form data to CRM',
            'name' => $formSentRequestUrlFieldName,
            'value' => $formSentRequestUrl,
            'placeholder' => $this->_defaultCrmUrlForFormsLeads
        );
        
        $fieldsData[] = array(
            'label' => 'Request URI to send opened page data to CRM',
            'name' => $pageOpenedRequestUrlFieldName,
            'value' => $pageOpenedRequestUrl,
            'placeholder' => $this->_defaultCrmUrlForPagesViews
        );
        
        $phoneFromCrmUrl = $this->getRequestUrlToCrm('phone', false, false);
        $phoneFromCrmUrlFieldName = WEB_GROUP_CRM_OPTIONS."[phoneFromCrmUrl]";

        $fieldsData[] = array(
            'label' => 'Request URI to get phone number by channelID from CRM',
            'name' => $phoneFromCrmUrlFieldName,
            'value' => $phoneFromCrmUrl,
            'placeholder' => $this->_defaultCrmUrlForPhoneNumbers
        );
        
        foreach ($fieldsData as $field) {
            $html .= join('', array(
                '<tr>',
                    '<td>',
                        "<label style='font-weight: bold'>{$field['label']}</label>",
                    '</td>',
                    '<td>',
                        $this->getFieldMarkup($field),
                    '</td>',
                '</tr>'
            ));
        }
        
        return $html;
    }
    
    
    public function getFieldMarkup($field)
    {
        if (isset($field['type']) && $field['type'] === 'textarea') {
            $html = sprintf(
                '<textarea rows=5 name="%s" placeholder="%s" style="min-width: 600px">%s</textarea>',
                $field['name'],
                isset($field['placeholder']) ? $field['placeholder'] : '',
                $field['value']
            );
            
        } else {
            $html = sprintf(
                '<input type="text" name="%s" value="%s" placeholder="%s" style="min-width: 600px">',
                $field['name'],
                $field['value'],
                isset($field['placeholder']) ? $field['placeholder'] : ''
            );
        }
        
        return $html;
    }
    
    
    public function initPageViewCrmInfoScript()
    {
        if (is_admin()) return;

        global $wp_query;

        $scriptUrl = plugin_dir_url(__FILE__) . 'js/sendPageInfoToCrmOnView.js';
        wp_register_script( 'addPageViewCrmInfoScript', $scriptUrl, array('jquery', 'WebgroupUtils'), $this->_version, true );
        wp_enqueue_script( 'addPageViewCrmInfoScript' );

        $urlSchema = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

        $blogId = is_multisite() ? get_current_blog_id() : 0;

        // pass params from PHP to the included stript
        $pageInfoData = array(
            'multisiteCrmId'  => (int) $this->getOption('multisiteCrmId'),
            'ClientID'        => $this->getOption('clientId'),
            'TenantID'        => $this->getOption('tenantId'),
            'projectID'       => $blogId,
            'pageId'          => $wp_query->post->ID,
            'projectTypeID'   => $this->getPagesType($blogId),
            'pageUrl'         => $urlSchema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'requestUrlToCrm' => $this->getRequestUrlToCrm('page', true, false),
            'currReferer'     => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
            'propertyId'      => $this->getProjectId($blogId),
            'propertyTypeId'  => $this->getPagesType($blogId),
            'system'          => $this->_utils->isCustomBlog() ? 0 : 1
        );
        
        $pageInfoData = array_merge($_GET, $pageInfoData);

        wp_localize_script( 'addPageViewCrmInfoScript', 'pageInfoDataFromPhp', $pageInfoData );
    }
    
    
    /**
     *  If current blog is LP -catalog then return the current page's ID
     *  otherwire the blog's ID will be returned.
     *  
     * @param type $blogId
     */
    private function getProjectId($blogId)
    {
        global $wp_query;
        $id = $blogId;
        
        if ($this->getPagesType($blogId) === 2) {
            $id = $wp_query->post->ID;
        }
        
        return $id;
    }
    
    
    public function initReplacePhoneByChannelIdScript()
    {
        if (is_admin()) return;

        global $post;

        $scriptUrl = plugin_dir_url(__FILE__) . 'js/replacePhoneNumbersByChannelId.js';
        wp_register_script( 'replacePhoneNumbersByChannelId', $scriptUrl, array('jquery', 'WebgroupUtils'), $this->_version, true );
        wp_enqueue_script( 'replacePhoneNumbersByChannelId' );
        
        
        $scriptData = array(
            'urlToGetPhone' => $this->getRequestUrlToCrm('phone', true, false)
        );

        wp_localize_script( 'addPageViewCrmInfoScript', 'dataForPhoneReplacingScriptFromPhp', $scriptData );
    }
    
    
    public function initFormInfoCrmScript()
    {
        if (is_admin()) return;

        global $post;

        $scriptUrl = plugin_dir_url(__FILE__) . 'js/sendFormInfoToCrmOnSubmit.js';
        wp_register_script( 'addDiviFormCrmInfoScript', $scriptUrl, array('jquery', 'WebgroupUtils'), $this->_version, true );
        wp_enqueue_script( 'addDiviFormCrmInfoScript' );

         // pass params from PHP to the included stript
        $formPageData = array(
            'pageId' => $post->ID,
            'formPageUrl' => $urlSchema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'getParams' => $_GET,
            'referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null
        );

        wp_localize_script( 'addDiviFormCrmInfoScript', 'formPageDataFromPhp', $formPageData );
    }
    
    
    public function initWebgroupUtilsScript()
    {
        if (is_admin()) return;

        $scriptUrl = plugin_dir_url(__FILE__) . 'js/WebgroupUtils.js';
        wp_enqueue_script( 'WebgroupUtils' );
        wp_register_script( 'WebgroupUtils', $scriptUrl, array('jquery'), $this->_version, true );

    }

    
    public function getRequestUrlToCrm($type, $useDefaultValue=true, $attachQuery=true)
    {
        if ($type === 'form') {
            $opt = 'formSentRequestUrl';
        } else if ($type === 'page') {
            $opt = 'pageOpenedRequestUrl';
        } else if ($type === 'phone') {
            $opt = 'phoneFromCrmUrl';
        }
        
        $url = trim( $this->getOption($opt) );
        
        if (!$url && $useDefaultValue) {
            if ($type === 'form') {
                $url = $this->_defaultCrmUrlForFormsLeads;
            } else if ($type === 'page') {
                $url = $this->_defaultCrmUrlForPagesViews;
            } else if ($type === 'phone') {
                $url = $this->_defaultCrmUrlForPhoneNumbers;
            }
        }
        
        if ($attachQuery) {
            $url = $url . $_COOKIE['fixdigital-origin_query_string_for_crm'];
        }

        return $url;
    }
    
    
    private function getLabelsForNameMapping($useDefaultValue=true)
    {
        $labels = trim( $this->getOption('labelsForNameField') );
        
        if (!$labels && $useDefaultValue) {
            $labels = $this->_defaultLabelsForNameMapping;
        }
        
        return $labels;
    }
    
    
    public function getOption($opt)
    {
        $options = is_multisite() ? get_site_option(WEB_GROUP_CRM_OPTIONS)
                : get_option(WEB_GROUP_CRM_OPTIONS);

        return $options[$opt];
    }
    
    
    public function makePostRequest($url, $data)
    {
         // if url is started from '//' then remove them to provide properly work for curl
        $url = preg_replace('@^//@', '', $url);
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response['message'] = trim(curl_exec($curl));
        $response['code'] = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response['error'] = curl_error($curl);

        curl_close($curl);
        
        return $response;
    }
    
    
    /*
     *  Send info of sending form to CRM
     */
    function sendFormInfoToCrm($formData)
    {
        $blogId = 0;
        
        if (is_multisite()) {
            $blogId = get_current_blog_id();
            switch_to_blog($blogId);
        }

        $urlSchema = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
        $pageUrl = $urlSchema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $formPageData = array();
        if (isset($formData['form_page_data_for_uco_crm_integration'])) {
            $formPageData = $this->formFieldJsonStringToArray($formData['form_page_data_for_uco_crm_integration']);

            if (isset($formPageData['formPageUrl'])) {
                $pageUrl = $formPageData['formPageUrl'];
            }

            if (isset($formPageData['pageId'])) {
                $pageId = $formPageData['pageId'];
            }
            
            if (isset($formPageData['referrer'])) {
                $referrer= $formPageData['referrer'];
            }

            $getParams = isset($formPageData['getParams']) ? $formPageData['getParams'] : array();

            unset( $formData['form_page_data_for_uco_crm_integration'] );
        }
        
        if (isset($formData['form_fields_types_for_uco_crm_integration'])) {
            $this->_fieldsNamesTypesMap = $this->_prepareFieldsNamesTypesMap( $formData['form_fields_types_for_uco_crm_integration'] );

            unset($formData['form_fields_types_for_uco_crm_integration']);
        }

        $formData['form_fields'] = $this->_getFormFieldsDataAsJsonString($formData);
        
        if (!$pageId) {
            $pageUrlWithoutGetParams = strtok($pageUrl, '?');
            $pageId = url_to_postid( $pageUrlWithoutGetParams );
        }

        // pass params from PHP to the included stript
        $formInfoData = array(
            'multisiteCrmId'  => (int) $this->getOption('multisiteCrmId'),
            'ClientID'        => $this->getOption('clientId'),
            'TenantID'        => $this->getOption('tenantId'),
            'guid'            => $_COOKIE['ucoClientUniqId'],
            'blogId'          => $blogId,
            'pageId'          => $pageId,
            'projectTypeID'   => $this->getPagesType($blogId),
            'pageUrl'         => $pageUrl,
//            'UrlRefer'        => $_COOKIE['ucoTempReferer'],
            'referrer'        => $referrer,
            'origin_referrer' => $_COOKIE['fixdigital-origin_referrer'],
            'channelID'       => $_COOKIE['fixdigital-origin_channelid'],
            'viewID'          => $_COOKIE['fixdigital-origin_viewid'],
            'visitorID'       => $_COOKIE['fixdigital-origin_visitorid'],
            'propertyId'      => $this->getPagesType($blogId) === 2 ? $pageId : $blogId,
            'propertyTypeId'  => $this->getPagesType($blogId),
            'system'          => $this->_utils->isCustomBlog() ? 0 : 1,
            'event'           => 'formSent',
            'formType'        => $_POST['form_type_for_uco_crm_integration']
        );

        if ($formInfoData['projectTypeID'] === 2) {
            $formInfoData['projectID'] = $pageId;
        } else {
            $formInfoData['projectID'] = $blogId;
        }

        $formInfoData = array_merge($getParams, $formInfoData, $formData);

        $url = $this->getRequestUrlToCrm('form');
        
        // make three attempts to send form data to CRM
        $logRowId = null;
        for ($i=0; $i<3; $i++) {
            sleep($i);
            
            $res = $this->makePostRequest($url, $formInfoData);
            $logRowId = $this->_leadsLog->log($res, $formInfoData, $url, $logRowId);
                
            if (isset($res['code']) && $res['code'] == 200) break;
        }
        
        $this->resentFailedLeadsFromLog();
    }
    
    
    /*
     *  Send contact form 7 data to CRM after data was mailing.
     */
/*    public function sendContactForm7InfoToCrm($cf7)
    {
        $formData = null;

        if (isset($_POST) && is_array($_POST)) {
            $formData = $_POST;
        }

        $this->sendFormInfoToCrm($formData);
    } */
    
    
    
    public function contactFormSent($mailer)
    {
        if (isset($_POST['form_fields_types_for_uco_crm_integration']) && $_POST['form_page_data_for_uco_crm_integration']) {
            $this->_mailer = $mailer;
            
            $formData = $_POST;
            $this->sendFormInfoToCrm($formData);
//            $this->_leadsLog->logTestInfo($mailer);
           
            // It's necessary to unset these fields to avoid sending of duplicate leads to CRM
            // (for example if the mail is sending to several recipients
            // then the phpmailer_init' hook can be fired several times for one mail)
            unset($_POST['form_fields_types_for_uco_crm_integration']);
            unset($_POST['form_page_data_for_uco_crm_integration']);
        }
    }
    
    
    /*
     *  Convert json-string which was gotten from submitted form text field to array
     */
    public function formFieldJsonStringToArray($jsonStr)
    {
         // It's necessary to remove slashes before quotes to provide  valid json-string decoding
        $jsonStr = str_replace('\"', '"', $jsonStr);
        $jsonStr = str_replace("\\'",  "'", $jsonStr);
        
        $formPageData = json_decode($jsonStr, true);

        return $formPageData;
    }
    
    
    /**
     *  True if current site is NOT webgroup uco site. 
     * 
     * @return boolean
     */
//    function isCustomBlog()
//    {
//        $result = true;
//
//        if (is_multisite() && get_site_option('isWebGroupUcoSite')) {
//            $result = false;
//        }
//
//        return $result;
//    }
    
    
    /*
     *  Returns 1 if blog contains usual pages and returns 2 if blog contains landing pages
     */
    public function getPagesType($blogId)
    {
        if ($this->_utils->isCustomBlog()) {
            $type = 0;

        } else {
            $type = 1;

            $blogType = get_blog_option($blogId, 'uco_blog_type');

            if ($blogType === 'landing-pages-catalog') {
                $type = 2;
            }
        }

        return $type;
    }
    
    
    /*
     * Result is array like: [ ['name' => 'some_field_name', 'type' => 'field_type, 'val' => 'some_val'], ... ]
     */
    private function _getElementorFormFieldsData($formData)
    {
        $formFields = array();
        
        $linkFinder = new UploadedFileLinkFinder($this->_mailer);
        
         if ( !($formData['form_fields'] && is_array($formData['form_fields'])) ) return $formFields;
             
        foreach ($formData['form_fields'] as $idx => $val) {
            $fName = "form_fields[$idx]";
            $fType = isset($this->_fieldsNamesTypesMap[$fName]['type']) ? $this->_fieldsNamesTypesMap[$fName]['type'] : null;

            // value for checkbox should always be array
            if ($fType === 'checkbox') {
                if ($val && !is_array($val)) {
                    $val = [$val];
                }
            }
            
            $formFields[] = array(
                'name' => $fName,
                'val' => $val,
                'type' => $fType,
                'placeholder' => isset($this->_fieldsNamesTypesMap[$fName]['placeholder']) ? $this->_fieldsNamesTypesMap[$fName]['placeholder'] : null,
                'label' => isset($this->_fieldsNamesTypesMap[$fName]['label']) ? $this->_fieldsNamesTypesMap[$fName]['label'] : null
            );
        }
        
        // try to get uploaded file links
        foreach ($this->_fieldsNamesTypesMap as $fName => $fData) {
            $fieldType = $fData['type'];
        
            // process the field with type: file. Try to get link for uploader file from mail body
            if ($fieldType !== 'file') continue;

            $formData[$fName] = $linkFinder->getLinksToUploadedFilesByElementor($fName);

            if (empty($formData[$fName])) continue;
          
            $formFields[] = array(
                'name' => $fName,
                'val' => $formData[$fName],
                'type' => $fieldType,
                'placeholder' => $fData['placeholder'],
                'label' => $fData['label']
            );
        }
     
        return $formFields;
    }
    
    
    /*
     *  Get data about fields from POJO
     *  
     *  Result is array like: [ ['name' => 'some_field_name', 'type' => 'field_type, 'val' => 'some_val'], ... ]
     */
    private function _getPojoFormFieldsData($formData)
    {
        $formFields = array();

        $fRegex = '/^form_field_[\d]+(\[\]){0,1}$/';
        
        $linkFinder = new UploadedFileLinkFinder($this->_mailer);
           
        foreach ($this->_fieldsNamesTypesMap as $fName => $fData) {
            if ( !preg_match($fRegex, $fName) ) continue;
            
            $fName = str_replace('[]', '', $fName); // for multiselect
            
            $fieldType = $fData['type'];
        
            // process the field with type: file. Try to get link for uploader file from mail body
            if ($fieldType === 'file') {
                $formData[$fName] = $linkFinder->getLinkToUploadedFile($fName);
            }

            if ( !isset($formData[$fName]) ) continue;
            
            $formFields[] = array(
                'name' => $fName,
                'val' => $formData[$fName],
                'type' => $fieldType,
                'placeholder' => $fData['placeholder'],
                'label' => $fData['label']
            );
        }

        return $formFields;
    }
    
    
    /*
     *  Get data about fields from DIVI
     *  
     *  Result is array like: [ ['name' => 'some_field_name', 'type' => 'field_type, 'val' => 'some_val'], ... ]
     */
    private function _getDiviFormFieldsData($formData)
    {
        $formFields = array();
        
        $fieldRegex = '/^et_pb_contact_[\S]+$/';  // DIVI form field
     
        foreach ($this->_fieldsNamesTypesMap as $fName => $fData) {
            if (preg_match($fieldRegex, $fName)) {
                $formFields[] = array(
                    'name' => $fName,
                    'val' => $formData[$fName],
                    'type' => $this->_getDiviFieldType($fData),
                    'placeholder' => $fData['placeholder'],
                    'label' => $fData['label']
                );
            }
        }
      
        return $formFields;
    }
    
    
    private function _getDiviFieldType($fieldData)
    {
        $type = $fieldData['type'];
         if ($type !== 'text') return $type;
         
        $label = $fieldData['label'];
        $placeholder = $fieldData['placeholder'];
        
        $phoneNames = ['tel', 'phone', 'טלפון'];
        foreach ($phoneNames as $pName) {
            if (mb_stristr($label, $pName) !== false || mb_stristr($placeholder, $pName) !== false) {
                $type = 'tel';
                break;
            }
        }
        
        $emailNames = ['email', 'אימייל' ];
        foreach ($emailNames as $eName) {
            if (mb_stristr($label, $eName) !== false || mb_stristr($placeholder, $eName) !== false) {
                $type = 'email';
                break;
            }
        }
        
        return $type;
    }
    
    
    /*
     *  Get data about fields from Contact Form 7
     *  
     *  Result is array like: [ ['name' => 'some_field_name', 'type' => 'field_type, 'val' => 'some_val'], ... ]
     */
    private function _getContactForm7FieldsData($formData)
    {
        $formFields = array();
        
        $linkFinder = new UploadedFileLinkFinder($this->_mailer);
       
        // Contact Form 7
        if (!count($formFields)) {
            foreach ($this->_fieldsNamesTypesMap as $fName => $fData) {
                if (preg_match('/^_/', $fName)) continue;
                
                $fName = str_replace('[]', '', $fName); // for multiselect
                
                $fieldType = $fData['type'];
                
                // process the field with type: file. Try to get link for uploader file from mail body
                if ($fieldType === 'file') {
                    $formData[$fName] = $linkFinder->getLinkToUploadedFile($fName);
                }
                
                if ( !isset($formData[$fName]) ) continue;

                $formFields[] = array(
                    'name' => $fName,
                    'val' => $formData[$fName],
                    'type' => $fieldType,
                    'placeholder' => $fData['placeholder'],
                    'label' => $fData['label']
                );
            }
        }

        return $formFields;
    }
    
    
    private function _getFormFieldsDataAsJsonString($formData)
    {
        $formFields = array();
   
        if ($this->_fieldsNamesTypesMap) {
            // assume that form is Elementor
            $formFields = $this->_getElementorFormFieldsData($formData);
            
            if (!count($formFields)) {
                // assume that form is DIVI
                $formFields = $this->_getDiviFormFieldsData($formData);
            }
            
            if (!count($formFields)) {
                // assume that form is POJO
                $formFields = $this->_getPojoFormFieldsData($formData);
            }
           
            if (!count($formFields)) {
                // assume that form is Contact Form 7
                $formFields = $this->_getContactForm7FieldsData($formData);

            }
        }
        
        // try to define 'Name' field is plugin is installed on custom blog
        if ($this->_utils->isCustomBlog()) {
            foreach ($formFields as $idx => $field) {
                if ($field['type'] !== 'text') continue;
                
                $fieldName = $field['name'];
                $fieldLabel = $this->_fieldsNamesTypesMap[$fieldName]['label'];
                $fieldPlaceholder = $this->_fieldsNamesTypesMap[$fieldName]['placeholder'];
                
                if ($this->isLabelName($fieldLabel) || $this->isLabelName($fieldPlaceholder)) {
                    $formFields[$idx]['type'] = 'fullname';
//                    break;  // to provide to send last name and first name to CRM as two fields with type 'fullname'
                }
            }
        }
        
        $formFields = $this->_stripslashesRec($formFields);

        return json_encode($formFields);
    }
    
    
    private function isLabelName($label)
    {
        $result = false;
        $nameLabelsTmpl = $this->_getNameLabels();
    
        foreach ($nameLabelsTmpl as $lTmpl) {
            if (mb_stristr($label, $lTmpl) !== false) {
                $result = true;
                break;
            }
        }
        
        return $result;
    }
    
    
    private function _prepareFieldsNamesTypesMap($data)
    {
        $result = $this->formFieldJsonStringToArray($data);
        
        if (is_array($result)) {
            foreach ($result as $fName => $fData) {
                if (isset($fData['placeholder']) && $fData['placeholder']) {
                    $fData['placeholder'] = urldecode( $fData['placeholder'] );
                }
                
                if (isset($fData['label']) && $fData['label']) {
                    $fData['label'] = urldecode( $fData['label'] );
                }
                
                $result[$fName] = $fData;
            }
        }
      
        return $result;
    }
    
    
    private function _getNameLabels()
    {
        $labels = array();
        
        $labelsStr = $this->getLabelsForNameMapping('labelsForNameField');
        $labels = explode("\n", $labelsStr);

        $labels = array_map('trim', $labels);
        $labels = array_filter($labels);
        
        return $labels;
    }
    
    
    public function resentFailedLeadsFromLog()
    {
        $logRecords = $this->_leadsLog->getFailedLeads();
        
        foreach ($logRecords as $rec) {
            $id = $rec->id;
            $url = isset($rec->url) ? $rec->url : $this->getRequestUrlToCrm('form'); // need for compatibility with previous version
            $lead = unserialize( trim($rec->lead) );
            
            $res = $this->makePostRequest($url, $lead);
            $this->_leadsLog->log($res, $lead, $rec->url, $id);
        }
    }
    
   
    public function onActivate()
    {
//        if ($this->_utils->isCustomBlog()) return;

        $this->_leadsLog->onActivate();
        
        wp_clear_scheduled_hook( 'resentFailedLeadsToCrmCron' );
        wp_schedule_event( time(), '10sec', 'resentFailedLeadsToCrmCron');
    }
    
    
    public function onDeactivate()
    {
//        $this->_leadsLog->onDeactivate();
        
        // remove cron-hook
        wp_clear_scheduled_hook('resentFailedLeadsToCrmCron');
    }
    
    
    public function sendLeadDataToCrmApi()
    {
        $result = [];
        
        $lead = $_POST['lead'];
        
        $url = trim($_POST['url']);
        $url = $url ? $url : $this->getRequestUrlToCrm('form');
        
        if (!is_serialized($lead)) return 'Error: Bad unserialized input data';
       
        $lead= str_replace('\"', '"', $lead);
        $lead= str_replace('\\\\', '\\', $lead);

        $leadData = unserialize( trim($lead) );
        $result = $this->makePostRequest($url, $leadData);
        
        die( json_encode( $result ) );
    }
    
    
    public function addCronSchedules($schedules)
    {
        if(!isset($schedules["10sec"])){
            $schedules["10sec"] = array(
                'interval' => 10,
                'display' => __('Once every 10 seconds'));
        }
        return $schedules;
    }
    
    
    private function _stripslashesRec($input)
    {
        return is_array($input) ? array_map(array($this, '_stripslashesRec'), $input) : stripslashes($input);
    }
}


$WP_WebGroupUcoCrmIntergration = new WP_WebGroupUcoCrmIntergration();
$WP_WebGroupUcoCrmIntergration->init();
