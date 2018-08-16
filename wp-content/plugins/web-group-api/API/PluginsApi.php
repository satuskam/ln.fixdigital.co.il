<?php

namespace WebGroupApi;
 
class PluginsApi extends Api
{
    protected $apiHooksData = [
        'listBlogsWherePluginActivated'   => 'listBlogsWherePluginActivatedApi',
//        'getScriptsPluginsIntersections' => 'getScriptsPluginsIntersectionsApi',
       // 'mergeScripts' => 'mergeScriptsApi'
    ];
    
    
    public function __construct()
    {
//        add_action( 'init', [$this, 'initUnreadPostStatus'] );
    }
    
    
    public function mergeScriptsApi()
    {
        $result = [];
        
        $blogsIds = $this->_getBlogsIdsWithIsertPlugin();
        foreach ($blogsIds as $blogId) {
            $inHeaderScript = trim( get_blog_option($blogId, 'ihaf_insert_header') );
            if ($inHeaderScript) {
                $inHeaderScript = "<!-- Start script from plugin: 'Insert Headers and Footers' (automerging)  -->\n"
                        . wp_unslash($inHeaderScript)
                        . "\n<!-- End script from plugin: 'Insert Headers and Footers' (automerging)  -->\n";
                
                $inHeaderScript .= "\n" . trim( get_blog_option($blogId, 'shfs_insert_header', '') );
                
                update_blog_option($blogId, 'shfs_insert_header', $inHeaderScript);
                update_blog_option($blogId, 'shfs_insert_header_is_active', 1);
            }
            
            $inFooterScript = get_blog_option($blogId, 'ihaf_insert_footer');
            if ($inFooterScript) {
                $inFooterScript = "<!-- Start script from plugin: 'Insert Headers and Footers' (automerging) -->\n"
                        . wp_unslash($inFooterScript)
                        . "\n<!-- End script from plugin: 'Insert Headers and Footers' (automerging)  -->\n";
                
                $inFooterScript .= "\n" . trim( get_blog_option($blogId, 'shfs_insert_footer', '') );
                
                update_blog_option($blogId, 'shfs_insert_footer', $inFooterScript);
                update_blog_option($blogId, 'shfs_insert_footer_is_active', 1);
            }
        }
        
        $result = $blogsIds;
        
        die( json_encode( $result ) );
    }
    
    public function getScriptsPluginsIntersectionsApi()
    {
        $result = [];
        
        $blogsIdsWithInsertScript = [ 'header' => [], 'footer'=>[] ];
        $blogsIdsWithFixScript = [ 'header' => [], 'footer'=>[] ];
        $blogsIdsWithFixScriptIsActivated = [ 'header' => [], 'footer'=>[] ];
        
        $blogsIdsWithInsertScriptGroupped = [];
        
        $blogsData = $this->_listBlogsWherePluginActivated('header-and-footer-scripts-fixdigital', true);
        $blogsWithFixPluginActivated = $blogsData['blogs'];
        
        $blogsData = $this->_listBlogsWherePluginActivated('insert-headers-and-footers', true);
        $blogsWithInsertHFPluginActivated = $blogsData['blogs'];
        
        $blogsIds = array_intersect($blogsWithFixPluginActivated, $blogsWithInsertHFPluginActivated);
        
        foreach ($blogsIds as $blogId) {
            $inHeader = get_blog_option($blogId, 'ihaf_insert_header');
            if ($inHeader) {
                $blogsIdsWithInsertScript['header'][] = $blogId;
                $blogsIdsWithInsertScriptGroupped[$blogId][] = 'header';
            }
            
            $inFooter = get_blog_option($blogId, 'ihaf_insert_footer');
            if ($inFooter) {
                $blogsIdsWithInsertScript['footer'][] = $blogId;
                $blogsIdsWithInsertScriptGroupped[$blogId][] = 'footer';
            }
        }
        
        foreach ($blogsIdsWithInsertScript['header'] as $blogId) {
            $inHeader = get_blog_option($blogId, 'shfs_insert_header');
            if ($inHeader) {
                $blogsIdsWithFixScript['header'][] = $blogId;
                
                if (get_blog_option($blogId, 'shfs_insert_header_is_active', false)) {
                    $blogsIdsWithFixScriptIsActivated['header'][] = $blogId;
                }
                
            }
            
            $inFooter = get_blog_option($blogId, 'shfs_insert_footer');
            if ($inFooter) {
                $blogsIdsWithFixScript['footer'][] = $blogId;
                
                if (get_blog_option($blogId, 'shfs_insert_footer_is_active', false)) {
                    $blogsIdsWithFixScriptIsActivated['footer'][] = $blogId;
                }
            }
        }
        
        $res = [
            'insertScript' => $blogsIdsWithInsertScript,
            'insertScriptGroupped' => $blogsIdsWithInsertScriptGroupped,
            'fixScript'    => $blogsIdsWithFixScript,
            'intersections' => [
                'header' => array_intersect($blogsIdsWithInsertScript['header'], $blogsIdsWithFixScript['header']),
                'footer' => array_intersect($blogsIdsWithInsertScript['footer'], $blogsIdsWithFixScript['footer'])
            ],
            'blogsIdsWithFixScriptIsActivated' => $blogsIdsWithFixScriptIsActivated
        ];
        
        $result = $res; 
        
        
        die( json_encode( $result ) );
    }
    
    
    public function listBlogsWherePluginActivatedApi()
    {
        $this->checkRequiredParams(['plugin'], 'GET');
        
        $pluginName = $_GET['plugin'];
        
        $result = $this->_listBlogsWherePluginActivated($pluginName);
        
        die( json_encode( $result ) );
    }
    
    
    private function _listBlogsWherePluginActivated($pluginName, bool $forNotDeletedBlogs=false)
    {
        $result = [
            'isNetworkActivated' => false,
            'blogs' => []
        ];
        
        $opts = $forNotDeletedBlogs ? ['deleted' => 0] : [];
        
        $blogsIds = $this->getAllBlogsIds($opts);
        
        if ( $this->_checkIsPluginNetworkActivated(1, $pluginName) ) {
            $result = [
                'isNetworkActivated' => true,
                'blogs' => $blogsIds
            ];
            
        } else {
            $ids = [];
            foreach ($blogsIds as $bId) {
                $activePlugins = get_blog_option($bId, 'active_plugins', []);
                $isPresent = $this->isPluginNamePresent($pluginName, $activePlugins);
                if ($isPresent) {
                    $ids[] = $bId;
                }
            }
            
            $result['blogs'] = $ids;
        }
        
        return $result;
    }
    
    
    private function _checkIsPluginNetworkActivated($siteId, $pluginName)
    {
        $result = false;
        
        $activePlugins = get_metadata('site', $siteId, 'active_sitewide_plugins', true);
        
        if (is_array($activePlugins)) {            
            $result = $this->isPluginNamePresent($pluginName, array_keys($activePlugins));
        }
        
        return $result;
    }
    
    
    private function isPluginNamePresent($pluginName, Array $pluginsNames)
    {
        $result = false;
        
        $regex = "@^{$pluginName}/@";
        
        $matches = preg_grep($regex, $pluginsNames);
            
        if (count($matches)) $result = true;
        
        return $result;
    }
    
    
    private function _getBlogsIdsWithIsertPlugin()
    {
        $result = [];
        
//        $blogsIdsWithInsertScript = [ 'header' => [], 'footer'=>[] ];
        $blogsIdsWithInsertScript = [];
        
        $blogsData = $this->_listBlogsWherePluginActivated('header-and-footer-scripts-fixdigital', true);
        $blogsWithFixPluginActivated = $blogsData['blogs'];
        
        $blogsData = $this->_listBlogsWherePluginActivated('insert-headers-and-footers', true);
        $blogsWithInsertHFPluginActivated = $blogsData['blogs'];
        
        $blogsIds = array_intersect($blogsWithFixPluginActivated, $blogsWithInsertHFPluginActivated);
        
        foreach ($blogsIds as $blogId) {
            $inHeader = get_blog_option($blogId, 'ihaf_insert_header');
            if ($inHeader) {
                $blogsIdsWithInsertScript[] = $blogId;
                continue;
            }
            
            $inFooter = get_blog_option($blogId, 'ihaf_insert_footer');
            if ($inFooter) {
                $blogsIdsWithInsertScript[] = $blogId;
            }
        }
        
        $result = $blogsIdsWithInsertScript;
        
        return  $result;
    }
    
    
}

