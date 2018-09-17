<?php

namespace WebGroupApi;
 
class PageStatusApi extends Api
{
    protected $apiHooksData = [
        'activatePage'   => 'activatePageApi',
        'deactivatePage' => 'deactivatePageApi'
    ];
    
    public function __construct()
    {
//        add_action( 'init', [$this, 'initUnreadPostStatus'] );
    }
    
    
    public function activatePageApi()
    {
        $this->_setPageStatus('publish');
    }
    
    public function deactivatePageApi()
    {
        $this->_setPageStatus('private');
    }
    
    
    private function _setPageStatus($status)
    {
        $this->checkAuthorizationByToken();
        $this->checkRequiredParams(['page', 'blog'], 'GET');
        $this->checkBlogAndSwitch();
        $this->checkPage();
        
        $this->page->post_status = $status;

        $pageId = wp_update_post( $this->page, true );
        
        if (is_wp_error($pageId)) {
            $pageId = wp_update_post( $this->page, true );						  
            if (is_wp_error($pageId)) {
                $errMsg = ''.
                $errors = $pageId->get_error_messages();
                foreach ($errors as $error) {
                    $errMsg .= $error . ' ';
                }
            
                $this->returnError(500, "Cannot update status for page: {$this->pageId} . $errMsg");
            }
        }
        
        $result = ['status' => 'Ok'];

        die( json_encode( $result ) );
    }
}

