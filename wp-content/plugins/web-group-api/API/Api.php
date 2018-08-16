<?php

namespace WebGroupApi;

class Api
{
    protected $blogId = null;
    protected $pageId = null;
    protected $page = null;
    
    protected $apiHooksData = [];  // this field should be redefined in the descendants
    
    private $_subClasses = [
        'HeaderFooterScriptsApi' => 'HeaderFooterScriptsApi.php',
        'PageStatusApi' => 'PageStatusApi.php',
        'PluginsApi' => 'PluginsApi.php'
    ];
    
    public function __construct()
    {
        foreach ($this->_subClasses as $className => $classFile) {
            require_once ($classFile);
            $className = 'WebGroupApi\\' . $className;
            $api = new $className();
            $api->initApiHooks();
        }
    }
    
    
    protected function initApiHooks()
    {
        foreach ($this->apiHooksData as $apiName => $apiCallback) {
            add_action( "wp_ajax_{$apiName}", [$this, $apiCallback] );
            add_action( "wp_ajax_nopriv_{$apiName}", [$this, $apiCallback] );
        }
    }

    
    public function checkBlogAndSwitch()
    {
        $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
        
        if (!$blogId) {
            $blogId = isset( $_POST['blogId'] ) ? (int) $_POST['blogId'] : null;
        }
        
        if (!$blogId) {
            $this->returnError(400, "Parameter 'blogId' is required");
        }

        $blogData = get_blog_details( $blogId );

        if (!$blogData) {
            $this->returnError(461, "Blog with id: $blogId not found");
        }
       
        $this->blogId = $blogId;
        
        switch_to_blog($blogId);
    }
    
    
    public function checkPage($requestType='GET')
    {
        $pageId = isset( $_GET['page'] ) ? (int) $_GET['page'] : null;
        
        if (!isset($pageId)) {
            $pageId = isset( $_POST['pageId'] ) ? (int) $_POST['pageId'] : null;
        }
        
        if (!isset($pageId)) {
            $this->returnError(400, "Parameter 'pageId' is required");
        }

        if ($pageId) {
            $page = get_post( $pageId );

            if (!($page && $page->post_type === 'page')) {
                $this->returnError(462, "Page with id: $pageId not found");
            }
        }
        
        $this->pageId = $pageId;
        $this->page = $page;
    }
    
    
    public function checkRequiredParams($params, $type)
    {
        foreach ($params as $param) {
            if ($type === 'POST') {
                $arr = $_POST;
            } else if ($type === 'GET') {
                $arr = $_GET;
            }
            
            if (!isset($arr[$param])) {
                $this->returnError(400, "Param: '$param' is required.");
            }
        }
    }


    protected function checkAuthorizationByToken()
    {
        $token = trim(getWebGroupApiToken());

        if (!$token || $token !== $_GET['token']) {
            $this->returnError(401, 'Wrong token');
        }
    }
    
    
    public function returnError($code, $msg, $json=null)
    {
        status_header($code, $msg);

        if ($json) {
            die(json_encode($json));
        }

        exit;
    }
    
    
    public function getAllBlogsIds(Array $opts=[])
    {
        $blogs = [];
        
        $opts = array_merge(['number' => 100000000], $opts);
        
        $data = get_sites($opts);
        foreach ($data as $blog) {
            $blogs[] = (int) $blog->blog_id;
        }
        
        return $blogs;
    }
}

