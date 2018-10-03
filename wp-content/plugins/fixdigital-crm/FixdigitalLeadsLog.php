<?php

require_once 'FixdigitalCrmUtils.php';

/**
 * Log leads which are sent to Fixdigital CRM
 *
 * @author satuskam
 */
class FixdigitalLeadsLog
{
    private $_utils;
    private $_tableName;
    
    public function __construct()
    {
        global $wpdb;
        
        $this->_utils = new FixdigitalCrmUtils();
        $this->_tableName = $wpdb->base_prefix . 'fxdl_leads_log';
        
        add_action('fixdigitalLeadsLogDaily', array($this, 'removeObsoleteRecordsFromLog'));
    }
    
    
    public function log($response, $formInfoData, $url, $recordId=null)
    {
//        if ($this->_utils->isCustomBlog()) return;
        
        global $wpdb;
        
        $responseCode = $response['code'];
        
        if ($recordId === null) {
            $res = $wpdb->insert(
                $this->_tableName,
                array(
                    'blog_id'                   => $formInfoData['blogId'],
                    'page_id'                   => $formInfoData['pageId'],
                    'channel_id'                => $formInfoData['channelID'],
                    'original_response_code'    => $responseCode,
                    'original_response_message' => $response['message'],
                    'last_response_error'       => $response['error'],
                    'last_response_code'        => $responseCode,
                    'last_response_message'     => $response['message'],
                    'original_response_error'   => $response['error'],
                    'lead'                      => is_array($formInfoData) ? serialize($formInfoData) : null,
                    'url'                       => $url,
                ),
                array( '%d', '%d', '%d', '%d', '%s', '%s', '%d', '%s', '%s','%s', '%s')
            );
         
            if ($res) {
                $recordId = $wpdb->insert_id;
            }
            
        } else {
            // Get attempts count and DB's current datetime
            $row = $wpdb->get_row(
                "SELECT
                    ( SELECT attempts FROM $this->_tableName WHERE id=$recordId ) AS attempts,
                    ( SELECT NOW() ) AS dbNow ;"
            );
            
            $wpdb->update(
                $this->_tableName,
                array(
                    'last_response_code'    => $responseCode,
                    'last_response_message' => $response['message'],
                    'last_response_error'   => $response['error'],
                    'attempts'              => $row->attempts + 1,
                    'updated_at'            => $row->dbNow
                ),
                array('id' => $recordId)
            );
        }
        
//        $this->removeObsoleteRecordsFromLog();
        
        return $recordId;
    }
    
    
    /*
     *   Used for debug
     */
    public function logTestInfo($mailer)
    {
        global $wpdb;
        
        $res = $wpdb->insert(
            $this->_tableName,
            array(
                'blog_id'                   => 22222222,
                'page_id'                   => 22222222,
                'channel_id'                => 22222222,
                'original_response_code'    => 200,
                'original_response_message' => 'it is test',
                'last_response_error'       =>  serialize($mailer),
                'last_response_code'        => 200,
                'last_response_message'     => 'it is test',
                'original_response_error'   => '',
                'lead'                      => is_array($_POST) ? serialize($_POST) : null,
                'url'                       => '',
            ),
            array( '%d', '%d', '%d', '%d', '%s', '%s', '%d', '%s', '%s','%s', '%s')
        );
    }
    
    
    public function getFailedLeads($limit=5)
    {
        global $wpdb;
    
        $rows = $wpdb->get_results("
            SELECT * FROM $this->_tableName 
                WHERE last_response_code != 200 AND attempts < 100
                ORDER BY RAND()
                LIMIT $limit;
        ");
        
        return $rows;
    }
    
    
    public function removeObsoleteRecordsFromLog()
    {
        global $wpdb;
        
        // remove all records older then 1 month
        $wpdb->query( 
            " DELETE FROM  $this->_tableName WHERE created_at < DATE_SUB(NOW(),INTERVAL 1 MONTH); "
        );
    }
    
   
    public function onActivate()
    {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
        
        // create table
        $sql = "CREATE TABLE $this->_tableName (
            `id` bigint(20)  unsigned NOT NULL  AUTO_INCREMENT,
            `blog_id` bigint(20) UNSIGNED DEFAULT NULL,
            `page_id` bigint(20) UNSIGNED DEFAULT NULL,
            `channel_id` bigint(20) UNSIGNED DEFAULT NULL,
            `original_response_code` int(2) DEFAULT NULL,
            `original_response_message` text,
            `original_response_error` text,
            `lead` text COLLATE utf8mb4_unicode_ci,
            `url` text COLLATE utf8mb4_unicode_ci,
            `last_response_code` int(2) DEFAULT NULL,
            `last_response_message` text,
            `last_response_error` text,
            `attempts` tinyint(1) UNSIGNED DEFAULT '1',
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
       
        // register cron-hook
        wp_clear_scheduled_hook( 'fixdigitalLeadsLogDaily' );
        wp_schedule_event( time(), 'daily', 'fixdigitalLeadsLogDaily');
	
    }
    
    
    public function onDeactivate()
    {
        // remove cron-hook
        wp_clear_scheduled_hook('fixdigitalLeadsLogDaily');
    }
    
}