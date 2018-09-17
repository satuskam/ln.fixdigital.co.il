<?php

namespace WebGroupApi;

class HeaderFooterScriptsApi extends Api
{
    protected $apiHooksData = [
        'getAllScriptsForBlog' => 'getAllScriptsForBlogApi',
        'getAllScriptsByUrl'   => 'getAllScriptsByUrlApi',
        'setScript'            => 'setScriptApi'
    ];
    
    public function __construct()
    {
//        $this->initApiHooks();
    }
    
    
    public function getAllScriptsForBlogApi()
    {
        $this->checkAuthorizationByToken();
        $this->checkBlogAndSwitch();
        
        $result = $this->_getCommonScriptsData();

        global $wpdb;

        $data = $wpdb->get_results( $wpdb->prepare(
            "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key = %s OR meta_key = %s",
            '_inpost_head_script',
            '_inpost_footer_script'
        ) );

        foreach ($data as $row) {
            $scriptData = $this->_getScriptData($row->meta_value);

            $result[] = [
                'pageId' => $row->post_id,
                'placement' => $this->_getScriptPlacement($row->meta_key),
                'pageUrl' => $this->getRelativePermalink($row->post_id),
                'code' => isset($scriptData['code']) ? $scriptData['code'] : '',
                'isActive' => isset($scriptData['is_active']) ? (bool) $scriptData['is_active'] : false
            ];
        }

        die( json_encode( $result ) );
    }
    
    
    public function getAllScriptsByUrlApi()
    {
        $result = [];
  
        $this->checkAuthorizationByToken();
        $this->checkBlogAndSwitch();

        $pageUrl = isset( $_POST['pageUrl'] ) ? trim( $_POST['pageUrl'] ) : '';
        
        if (!$pageUrl) {
            $this->returnError(400, "Parameter 'pageUrl' is required");
        }
       
        // try to get page by relative url
        if ($pageUrl === '/') {
            $pageId = get_option('page_on_front');

            if ($pageId) {
                $page = get_post($pageId);
            } else {
                $this->returnError(464, 'Front page is not static');
            }
            
        } else {
            try  {
                $page = get_page_by_path($pageUrl); // sometimes this function generate fatal error if page not found
            } catch (\Throwable $t) {
                $this->returnError(462, "Page with url: '$pageUrl' not found");
            }
        }
        
        if ( !$page ) {
            $this->returnError(462, "Page with url: '$pageUrl' not found");
        }
       
        $data = [
            'head' => '_inpost_head_script',
            'footer' => '_inpost_footer_script'
        ];
        
        foreach ($data as $place => $option) {
            $scriptData = get_post_meta( $page->ID, $option, TRUE );
            if (is_array($scriptData)) {
                $result[] = [
                    'pageId' => $page->ID,
                    'placement' => $place,
                    'code' => isset($scriptData['code']) ? $scriptData['code'] : '',
                    'isActive' => isset($scriptData['is_active']) ? (bool) $scriptData['is_active'] : false
                ];
            }
        }

        die( json_encode( $result ) );
    }
    
    
    public function setScriptApi()
    {
        $this->checkAuthorizationByToken();
        $this->checkBlogAndSwitch();
        
        $this->checkPage();
        
        $this->checkRequiredParams(['placement', 'code', 'isActive'], 'POST');
        
        // check 'placement' param
        $placement = isset($_POST['placement']) ? $_POST['placement'] : null;
        if (!in_array($placement, ['head', 'footer'])) {
            $this->returnError(464, 'Script placement is invalid');
        }

        $code = trim($_POST['code']);
        $code = stripslashes($code);
 
        $isActive = (bool) $_POST['isActive'];
        
        // if global script
        if ($this->pageId === 0) {
            if ($placement === 'head') {
                $codeOption = 'shfs_insert_header';
                $isActiveOption = 'shfs_insert_header_is_active';
                
            } else if ($placement === 'footer') {
                $codeOption = 'shfs_insert_footer';
                $isActiveOption = 'shfs_insert_footer_is_active';
            }
            
            if (!$code) {
                delete_option($codeOption);
                delete_option($isActiveOption);
            } else {
                update_option($codeOption, $code);
                update_option($isActiveOption, $isActive);
            }
            
        // if page's script    
        } else {
            if ($placement === 'head') {
                $option = '_inpost_head_script';
                
            } else if ($placement === 'footer') {
                $option = '_inpost_footer_script';
            }
            
            $value = null;
            
            if ($code) {
                $value = ['code' => $code, 'is_active' => $isActive];
            }
            
            if (!is_array($value)) {
                delete_post_meta($this->pageId, $option);
            } else {
                update_post_meta($this->pageId, $option, $value);
            }
        }
    
        restore_current_blog();
    
    	// clear cache
        exec('wp cache flush');
        exec('wp nginx-helper purge-all'); 
            
        die( json_encode(
            [
                'status' => 'Ok'
            ]
        ) );
    }
   
    
    public function removeAllBlogScripts($blogId)
    {
        switch_to_blog((int) $blogId);
        
        global $wpdb;
         
        // remove all scripts assigned for pages.
        $wpdb->get_results( $wpdb->prepare(
            "DELETE FROM $wpdb->postmeta WHERE meta_key = %s OR meta_key = %s",
            '_inpost_head_script',
            '_inpost_footer_script'
        ) );
        
        // remove common scripts
        $opts = [
            'shfs_insert_footer_is_active',
            'shfs_insert_header_is_active',
            'shfs_insert_header',
            'shfs_insert_footer'
        ];
        foreach ($opts as $opt) {
            delete_option($opt);
        }
    }
    
    
    public function removePageScripts($blogId, $pageId)
    {
        switch_to_blog((int) $blogId);
        
        global $wpdb;
         
        // remove all scripts assigned for pages.
        $wpdb->get_results( $wpdb->prepare(
            "DELETE FROM $wpdb->postmeta WHERE post_id = %d AND (meta_key = %s OR meta_key = %s)",
            $pageId,
            '_inpost_head_script',
            '_inpost_footer_script'
        ) );
    }
    

    private function _getScriptPlacement($option)
    {
        $placement = null;

        $scriptPlacements = [
            'head'   => ['shfs_insert_header', '_inpost_head_script'],
            'footer' => ['shfs_insert_footer', '_inpost_footer_script']
        ];

        foreach ($scriptPlacements as $place => $options) {
            if (in_array($option, $options, true)) {
                $placement = $place;
                break;
            }
        }

        if (!$placement) {
            $this->returnError(463, "Script placement not defined for option: '$option'");
        }

        return $placement;
    }


    private function _getScriptData($scriptData)
    {
        $data = [];

        if (is_serialized($scriptData)) {
            $data = unserialize($scriptData);
        }

        return $data;
    }
    
    
    private function _getCommonScriptsData()
    {
        $result = [
            [
                'pageId' => null,
                'placement' => 'head',
                'pageUrl' => '/*',
                'code' => get_option( 'shfs_insert_header', '' ),
                'isActive' => (bool) get_option( 'shfs_insert_header_is_active', false )
            ],
            [
                'pageId' => null,
                'placement' => 'footer',
                'pageUrl' => '/*',
                'code' => get_option( 'shfs_insert_footer', '' ),
                'isActive' => (bool) get_option( 'shfs_insert_footer_is_active', false )
            ]
        ];
        
        return $result;
    }
    
    
    private function getRelativePermalink($pageId)
    {
        $result = '/*';
        
        if ($pageId) {
            $pageLink = get_page_link($pageId);
            $pageLink = preg_replace ('/^(http)?s?:?\/\/[^\/]*(\/?.*)$/i', '$2', '' . $pageLink);
            $result = preg_replace ('/\/$/i', '', '' . $pageLink);
        }
        
        return $result;
    }
    
    
    private function _writeLog($val)
    {
        if (!in_array($_POST['blogId'], [1211, 1089])) return;
        
        $logPath = __DIR__ . '/log.txt';
        
        $data = "\n\n --------- \n" .
                (string) $val . "\n " .
                 date("Y-m-d H:i").
                ' ' .
                $_POST['blogId'];

        $fileContent = file_get_contents($logPath);
        file_put_contents($logPath, $data.$fileContent);
    }
    
}
