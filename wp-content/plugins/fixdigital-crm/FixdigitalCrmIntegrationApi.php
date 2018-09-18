<?php

/**
 * Description of FixdigitalCrmIntegrationApi
 *
 * @author satuskam
 */
class FixdigitalCrmIntegrationApi
{
    public function __construct()
    {
        // init API to emulate the leads sending (need for dubug)
//        add_action( 'wp_ajax_getUserBlogIds', [$this, 'getUserBlogIdsApi'] );
//        add_action( 'wp_ajax_nopriv_getUserBlogIds', [$this, 'getUserBlogIdsApi'] );
        
        add_action( 'wp_ajax_getFirstLeadTime', [$this, 'getFirstLeadTimeApi'] );
        add_action( 'wp_ajax_nopriv_getFirstLeadTime', [$this, 'getFirstLeadTimeApi'] );
        
        add_action( 'wp_ajax_renderMissedLeadsLog', [$this, 'renderMissedLeadsLogApi'] );
        add_action( 'wp_ajax_nopriv_renderMissedLeadsLog', [$this, 'renderMissedLeadsLogApi'] );
    }
    
    public function getFirstLeadTimeApi()
    {
        $result = array();
       
        global $wpdb;
        $row = $wpdb->get_row(
            "SELECT submit_time FROM wp_cf7dbplugin_submits_summary ORDER BY submit_time LIMIT 1;"
        );
   
        $result = date("Y-m-d H:m:s", (int) $row->submit_time);
        
        die( json_encode( $result ) );
    }
    
    
    public function renderMissedLeadsLogApi()
    {
        global $wpdb;
        $result = array();
        
        $sentLeadsData = array();
        $sentLeadsFields = array();
        $unsentLeads = array();
        
        $cf7DbLogData = $wpdb->get_results(
            "SELECT * FROM wp_cf7dbplugin_submits_summary ORDER BY blog_id, submit_time;"
        );

        foreach ($cf7DbLogData as $data) {
            $sentLeadsData[$data->blog_id][$data->submit_time][] = $data;
        }
        
        $i=0; // filtered
        $j=0; // common
      
        foreach ($sentLeadsData as $blogId => $blogLeads) {
            foreach ($blogLeads as $submitTime => $leads) {
                $leadFieldsValues = $this->getFieldsValue($blogId, $leads);
                $sentLeadsFields[$blogId][$submitTime][] = $leadFieldsValues;
                
                $wasSent = $this->checkHasLeadBeAcceptedByCrm($leadFieldsValues, date("Y-m-d H:m:s", (int) $submitTime), $blogId);
                $j++;
                if (!$wasSent) {
                    $i++;
                    $unsentLeads[$blogId][$submitTime] = $leadFieldsValues;
                }
            }
        }
        
        $this->renderUnsentLeadsHtml($unsentLeads);
        
        die;
    }
    
    
    public function checkHasLeadBeAcceptedByCrm($lead, $submitTime, $blogId)
    {
        global $wpdb;
        
        $conditions = array();
        
        if (isset($lead['name'])) {
            $name = $lead['name'];
            $conditions[] = "Fullname = '$name'";
        }
        
        if (isset($lead['phone'])) {
            $phone = $lead['phone'];
            $conditions[] = "Phone = '$phone'";
        }
        
        if (isset($lead['email'])) {
            $email = $lead['email'];
            $conditions[] = "Email = '$email'";
        }
        
        $conditions =  ' ' . join(' AND ', $conditions) .  ' ';
        
        $request = "SELECT * FROM wp_crm_leads WHERE $conditions ;";
   
        $leads = $wpdb->get_results(
            $request
        );
        
        foreach ($leads as $r) {
            $matches = array();

            $jsonData = $r->JsonData;
            
            $regexPageId = '/"pageId":"(\d+)"/';
            $regexBlogId = '/"blogId":"(\d+)"/';
            
            $res = preg_match($regexPageId, $jsonData, $matches);
            if ($res && isset($matches[1])) {
                $pageId = (int) $matches[1];
            }
            
            $res = preg_match($regexBlogId, $jsonData, $matches);
            if ($res && isset($matches[1])) {
                $blogIdFromJson = (int) $matches[1];
            } else {
                continue;
            }
            
            if ($blogIdFromJson === $blogId) {
                $leads = array(1);  // lead exists
                break;
            }
        }
        
        
        return !! count($leads);
        
    }
    
    
    public function getFieldsValue($blogId, $leads)
    {
        $result = array(
            'name' => $this->getNameValue($blogId, $leads),
            'phone' => $this->getPhoneValue($blogId, $leads),
            'email' => $this->getEmailValue($blogId, $leads)
        );
        
        return $result;
    }
    
    
    public function getNameValue($blogId, $leads)
    {
        $result = array();
        
        $selectors = array(
            0 => array('שם', "שֵׁם", 'Name'),
            317 => array('0'),
            364 => array('0'),
            392 => array('0'),
            414 => array('0'),
            418 => array('0', 'שם', "שֵׁם"),
            431 => array('0'),
        );
  
        $selector = isset($selectors[$blogId]) ? $selectors[$blogId] : $selectors[0];
        
        foreach ($leads as $lead) {
            foreach ($selector as $s) {
                if (mb_strstr($lead->field_name, $s) !== false) {
                    $result[] = $lead->field_value;
                }
            }
        }
        
        if (count($result) > 1) $this->returnError(401, 'Multiple names');
        
        $result = isset($result[0]) ? $result[0] : null;
        
        return $result;
    }
    
    
    public function getPhoneValue($blogId, $leads)
    {
        $result = array();
        
        $selectors = array(
            0 => array('טלפון', 'Phone'),
            254 => array('1'),
            317 => array('1'),
            364 => array('2'),
            392 => array('1'),
            414 => array('1'),
            418 => array('טלפון', '1'),
            431 => array('1'),
        );
  
        $selector = isset($selectors[$blogId]) ? $selectors[$blogId] : $selectors[0];
        
        foreach ($leads as $lead) {
            foreach ($selector as $s) {
                if (mb_strstr($lead->field_name, $s) !== false) {
                    $result[] = $lead->field_value;
                }
            }
        }
      
        if (count($result) > 1) $this->returnError(401, 'Multiple names');
        
        $result = isset($result[0]) ? $result[0] : null;
        
        return $result;
    }
    
    
    public function getEmailValue($blogId, $leads)
    {
        $result = array();
        
        $selectors = array(
            0   => array('אימייל', 'דוא"ל', 'Email'),
            317 => array('2'),
            364 => array('1'),
            392 => array('2'),
            418 => array('אימייל', 'דוא"ל', 'Email', 2)
        );
  
        $selector = isset($selectors[$blogId]) ? $selectors[$blogId] : $selectors[0];
        
        foreach ($leads as $lead) {
            foreach ($selector as $s) {
                if (mb_strstr($lead->field_name, $s) !== false) {
                    $result[] = $lead->field_value;
                }
            }
        }
      
        if (count($result) > 1) $this->returnError(401, 'Multiple names');
        
        $result = isset($result[0]) ? $result[0] : null;
        
        return $result;
    }
    
    
    public function returnError($code, $msg, $json=null)
    {
        status_header($code, $msg);

        if ($json) {
            die(json_encode($json));
        }

        exit;
    }
    
    
    public function renderUnsentLeadsHtml($unsentLeads)
    {
        ?>

<style>
    td {
	border-top: 1px solid #ccc;
	padding: 0.5em 1em;
	border-left: 1px solid #ccc;
    }
</style>

<table>
    <tr>
        <th>Blog ID</th>
        <th>Blog Url</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Date</th>
    </tr>
    <?php foreach ($unsentLeads as $blogId => $blogLeads) :
          $blogData = get_blog_details($blogId, true);
          $blogDomain = $blogData->domain;
          $blogUrl = $blogData->siteurl;
    
            foreach ($blogLeads as $submitDate => $lead) :
                $submitDate = date("Y-m-d H:m:s", (int) $submitDate);
    ?>
    <tr>
            <td><?= $blogId ?></td>
            <td><?= $blogUrl ?></td>
            <td><?= $lead['name'] ?></td>
            <td><?= $lead['phone'] ?></td>
            <td><?= $lead['email'] ?></td>
            <td><?= $submitDate ?></td>
    </tr>
    <?php endforeach;?>
    <?php endforeach;?>
</table>
        <?php
    }
}


