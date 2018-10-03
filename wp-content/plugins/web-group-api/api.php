<?php
/*
Plugin Name: API to manage multisite's blogs
Description: API to manage multisite's blogs
Version: 2.11.2
Author: satuskam
Author URI: atuskam@gmail.com
*/


const WEB_GROUP_API_PLUGIN_ID = 'web_group_api_id';
const WEB_GROUP_API_PLUGIN_NAME = 'API to manage blogs ' ;
const WEB_GROUP_API_OPTIONS = 'web_group_web_options';

// This filter need to prevent the error during create user by API
add_filter( 'wp_mail_from', function() {
    global $current_blog; 
    return 'wordpress@'.$current_blog->domain;
} );

// activate Elementor Pro license every time as primary domain is changing
// (hooks from plugin: wordpress-mu-domain-mapping)
add_action('dm_handle_actions_primary', function($domain){
    global $wpdb;
    $blogId = $wpdb->blogid;
    activateElementorProLicenseForBlog($blogId, $domain);
});
add_action('dm_handle_actions_add', function($domain){
    if ( !empty($_POST[ 'primary' ]) ) {
        requestToActivateElementorProLicenseForDomain($domain);
    }
});


include_once ('API/Api.php');
new WebGroupApi\Api();


// Add new item to admin settings menu

function web_group_settings_menu() {
    if (!is_main_site()) return;
    
    add_menu_page(
        WEB_GROUP_API_PLUGIN_NAME,
        '<span style="color: yellow">' . WEB_GROUP_API_PLUGIN_NAME . '</span>',
        'options',
        WEB_GROUP_API_PLUGIN_ID,
        'render_web_group_settings_page'
    );	
}


function render_web_group_settings_page() {

    if (isset($_POST[WEB_GROUP_API_OPTIONS])) {
        function filter(&$value) {
            $value = stripslashes( trim($value) );
        }
        
        array_walk_recursive($_POST[WEB_GROUP_API_OPTIONS], 'filter');
        
        update_site_option(WEB_GROUP_API_OPTIONS, $_POST[WEB_GROUP_API_OPTIONS]);
        echo '<div id="message" class="updated">Settings saved</div>';
    }
    
    $token = getWebGroupApiToken();
    $tokenFieldName = WEB_GROUP_API_OPTIONS."[token]";
    
    
    $html = join('', [
        '<h1>Web Group Api Settings</h1>',
        '<form method="POST">',
            '<table>',
                '<tr>',
                    '<td>',
                        '<label>Token</label>',
                    '</td>',
                    '<td>',
                        '<input type="text" name="' . $tokenFieldName . '" value="' . $token . '">',
                    '</td>',
                '</tr>',
            '</table>',
            '<input type="submit" value="Save">',
        '</form>'
        
    ]);
    
    
    echo($html);
}

add_action('network_admin_menu', 'web_group_settings_menu');



function cloneBlog ($options)
{
    $result = false;
    
    $subdomain = $options['subdomain'];
    $domain =  $subdomain . '.' . getMainSiteDomain();
    $tmplBlogId = $options['tmplBlogId'];
    $homePath = get_home_path();
    $emailOpt = isset( $options['email'] ) ? "--email={$options['email']}" : '';
    $titleOpt = isset( $options['title'] ) ? "--title=\"{$options['title']}\"" : '';
    
    $cmd = "wp site duplicate --path=$homePath --source=$tmplBlogId --slug=$subdomain $emailOpt $titleOpt";
  
    exec($cmd, $output, $returnValue);

    if ($returnValue === 0) {
        $result = domain_exists($domain, '/');
    }
    
    return $result;
}


function createBlogFromTemplate()
{
    $result = [];
    
    checkAuthorizationByToken();
    
    $tmplBlogId = isset($_GET['tmpl']) ? (int) $_GET['tmpl'] : null;
    $validTmplBlogsIds = getValidTemplateSitesIds();

    $subdomain = strtolower( trim($_POST['subdomain']) );
    $title = isset($_POST['title']) ? $_POST['title'] : 'Site: ' . $subdomain;
    
    if (preg_match('/`/', $title)) {
        returnError(463, 'Title contains wrong symbols');
    }
    
    if (preg_match('/^(?![-.])[\p{Hebrew}0-9a-z.-]+(?<![-.])$/ui', $subdomain)) {
        $subdomain = idn_to_ascii($subdomain, 0, INTL_IDNA_VARIANT_UTS46);
    }
    
    $cloneOpts = [
        'subdomain' => $subdomain,
        'tmplBlogId' => $tmplBlogId,
        'title' => $title
    ];
    
    if (isset($_POST['email'])) {
        $cloneOpts['email'] = $_POST['email'];
    }
    
    try {

        if (!isSubdomainValid($subdomain)) {
            returnError(461, 'Wrong subdomain');
        }
        
        if (!in_array($tmplBlogId, $validTmplBlogsIds, true)) {
            returnError(462, 'Not found template with ID: ' . $tmplBlogId);
        }
        
        $blogId = cloneBlog($cloneOpts);
 
        if (!$blogId) {
            returnError(500, 'Cannot create blog');
        }
        
        replaceAttachmentsUrlsAfterBlogCloned($tmplBlogId, $blogId);
        
        $result['blogId'] = $blogId;
        $result['blogUrl'] = get_site_url($blogId);
        $result['blogAdminUrl'] = get_admin_url($blogId);
        
        assignRedirectToThanksPageForBlog($blogId, $tmplBlogId);
      
        // try to activate license for Elementor Pro
        try {
            activateElementorProLicenseForBlog($blogId);
            $result['elementorProLicenseActivated'] = true;
        } catch (Exception $ex) {
            $result['elementorProLicenseActivated'] = false;
        }
        
    } catch (\Exception $ex) {
        returnError(500, 'Cannot create blog');
    }
    
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_createBlogFromTemplate', 'createBlogFromTemplate' );
add_action( 'wp_ajax_nopriv_createBlogFromTemplate', 'createBlogFromTemplate' );


function assignRedirectToThanksPageForBlog($blogId, $srcBlogId=null)
{
    global $wpdb;
    
    switch_to_blog($blogId);
    
    $res = $posts = $wpdb->get_row(
        "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'isThanksPage' AND  meta_value = '1' "
    );
    
    if (empty($res)) return;
    
    $thanksPageId = (int) $res->post_id;
    
    $thanksPageUrl = _getPagePermalink($thanksPageId, $blogId);
    
    // process pojo-forms
    $pojoForms = get_posts(['post_type' => 'pojo_forms']);
    foreach ($pojoForms as $pForm) {
        update_post_meta($pForm->ID, 'form_redirect_to', $thanksPageUrl);
    }

    // process elementor-forms
    $rows = $wpdb->get_results(
        "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_elementor_data' "
    );
    
    foreach ($rows as $row) {
        assignThanksPageRedirectForElementorForms($row->post_id, $thanksPageUrl, $blogId);
    }
}


function isSubdomainValid($subdomain='')
{
    $result = true;
    
    if (!$subdomain) {
        return false;
    }
    
    if (preg_match('/^templatesite/', $subdomain) || preg_match('/^templateland/', $subdomain) || preg_match('/^templatecatalog/', $subdomain)) {
        return false;
    }
    
    if (!preg_match('/^(?![-.])[a-z0-9.-]+(?<![-.])$/i', $subdomain)) {
        return false;
    }
    
    $mainDomain = getMainSiteDomain();
    
    $domain = $subdomain . '.' . $mainDomain;
  
    if (isDomainExist($domain)) return false;
   
    return $result;
}


function isDomainExist($domain)
{
    $result = false;
    
    $domains = getAssignedDomains();
    foreach ($domains as $d) {
        if ($d['domain'] === $domain) {
            $result = true;
            break;
        }
    }
 
    return $result;
}


function getPrimaryDomain($blogId)
{
    $blogId = (int) $blogId;
    $primaryDomain = get_blog_details($blogId)->domain;
   
    $domains = getAssignedDomains();
    foreach ($domains as $d) {
        if ($d['blogId'] === $blogId && $d['isPrimary']) {
            $primaryDomain = $d['domain'];
            break;
        }
    }
   
    return $primaryDomain;
}


function fetchLinkWithPrimaryDomain($link, $blogId)
{
    $initialDomain = get_blog_details($blogId)->domain;
    $currPrimaryDomain = getPrimaryDomain($blogId);
    
    if ($currPrimaryDomain) {
        $link = str_replace($initialDomain, $currPrimaryDomain, $link);
    }
    
    return $link;
}


function replaceAttachmentsUrlsAfterBlogCloned($srcBlogId, $trgtBlogId)
{
    global $wpdb;
    
    switch_to_blog($trgtBlogId);
                 
    $uploadDir = wp_upload_dir();
    if (is_array($uploadDir) && isset($uploadDir['basedir'])) {
        $uploadDir = $uploadDir['basedir'];
    }
    
    $cssDirPath = $uploadDir . "/elementor/css/";
    
    if (!is_dir($cssDirPath)) return;
    
    foreach (scandir($cssDirPath) as $fileName) {
        if (in_array($fileName, ['.', '..'])) continue;
        if (!preg_match('/\.css$/', $fileName)) continue;
        
        $cssPath = $cssDirPath . $fileName;
 
        replaceAttachmentsUrlsInElementorCssFile($cssPath, $srcBlogId, $trgtBlogId);
    }
    
    switch_to_blog($trgtBlogId);
    
    $result = $wpdb->get_results( $wpdb->prepare(
        "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = %s",
        '_elementor_data'
    ) );
    
    foreach ($result as $postmeta) {
        $value = trim( $postmeta->meta_value );
        if (!$value) continue;
     
        replaceAttachmentsUrlsInElementorData($postmeta->post_id, null, $srcBlogId, $trgtBlogId);
    }
    
    $result = $wpdb->get_results( $wpdb->prepare(
        "SELECT option_name, option_value FROM $wpdb->options WHERE option_value LIKE %s",
        "%/wp-content/uploads/sites/{$srcBlogId}/%"
    ) );
    
    foreach ($result as $option) {
        $value = trim( $option->option_value );
        if (!$value) continue;
        
        $value = replaceAttachmentsUrlsInBlogOption($value, $srcBlogId, $trgtBlogId);
       
        update_blog_option($trgtBlogId, $option->option_name, $value);
    }
}


function replaceAttachmentsUrlsInBlogOption($value, $srcBlogId, $trgtBlogId)
{
    if (!is_serialized($value)) return $value;
    
    $value = unserialize($value);
    
    if (is_array($value)) {
        $value = walkThroughBlogOptionArrayyAndReplaceUrls($value, $srcBlogId, $trgtBlogId);
    }
    
    return $value;
}


function walkThroughBlogOptionArrayyAndReplaceUrls($value, $srcBlogId, $trgtBlogId)
{
    if (is_array($value)) {
        foreach ($value as $k => $v) {
            $value[$k] = walkThroughBlogOptionArrayyAndReplaceUrls($v, $srcBlogId, $trgtBlogId);
        } 
    } else if (is_string($value)) {
        $value = str_replace("/uploads/sites/{$srcBlogId}/", "/uploads/sites/{$trgtBlogId}/", $value);
    }
    
    return $value;
}


function getMainSiteDomain()
{
    $domain = get_site()->domain;
    $domain = preg_replace('/^www\./', '', $domain);
    return $domain;
}


function getWebGroupApiToken()
{
    $options = get_site_option(WEB_GROUP_API_OPTIONS);
    return $options['token'];
}


function returnError($code, $msg, $json=null)
{
    status_header($code, $msg);
    
    if ($json) {
        die(json_encode($json));
    }
    
    exit;
}


function checkAuthorizationByToken()
{
    $token = trim(getWebGroupApiToken());
    
    if (!$token || $token !== $_GET['token']) {
        returnError(401, 'Wrong token');
    }
}


/*
 *  Create user
 */
function createUserByWebGroupApi()
{
    checkAuthorizationByToken();
    
    $email = trim($_POST['email']);
    
    $pwd = $_POST['pwd'];
    $confirmPwd = $_POST['confirmPwd'];
    
    if (!$pwd) {
        returnError(400, 'Password required');
    }
    
    if ($pwd !== $confirmPwd) {
        returnError(460, 'Password doesnt match with confirmation');
    }
    
    if (!$email) {
        returnError(400, 'Email required');
    }
    
    $emailRegex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
    if (!preg_match($emailRegex, $email)) {
        returnError(462, "Passed email: $email has wrong format");
    }
   
    if (get_user_by('email', $email)) {
        returnError(461, "User with email: $email  already exists");
    }

    try {
        wpmu_create_user($email, $pwd, $email);

        $user = get_user_by('email', $email);

        if (!$user->ID) {
            throw new Exception('Error');
        }

        $result = [
            'id' => $user->ID,
            'login' => $user->user_login,
            'email' => $user->user_email,
            'password' => $pwd,
            'authToken' => createUserAuthToken($user)
        ];

    } catch (\Exception $ex) {
    	$msg = trim( $ex->getMessage() );
    	if ($msg) {
    		$msg =' Message: ' . $ex->getMessage();
        }
        returnError(500, 'Cannot create user.' . $msg);
    }
    
    die( json_encode( $result ) );
}    
add_action( 'wp_ajax_createUser', 'createUserByWebGroupApi' );
add_action( 'wp_ajax_nopriv_createUser', 'createUserByWebGroupApi' );


function getTemplateSites()
{
    $tmplSites = [];
    
    foreach (wp_get_sites(['limit' => 9999]) as $s) {
        if (preg_match('/^templatesite/', $s['domain'])) {
            $tmplSites[] = $s;
        }
    }

    return $tmplSites;
}


function getValidTemplateSitesIds()
{
    $ids = [];
    
    foreach (getTemplateSites() as $s) {
        $ids[] = (int) $s['blog_id'];
    }
   
    return $ids;
}



function listTemplateSitesByWebGroupApi()
{
    $result = [];
    
    $token = getWebGroupApiToken();
    
    checkAuthorizationByToken();
    
    foreach (getTemplateSites() as $s) {
        $blogId = (int) $s['blog_id'];
      
        $blogData = [
            'id' => $blogId,
            'domain' => $s['domain']
        ];
        
        switch_to_blog($blogId);
        
        $theme = wp_get_theme();
        
        $blogData['themeScreenshotUrl'] = $theme->get_screenshot();
        $blogData['themeName'] = $theme->get('Name');
        $blogData['urlToClone'] = get_admin_url() . "/admin-ajax.php?action=createBlogFromTemplate&token=$token&tmpl=$blogId";
       
        $blogData['isForBeginners'] = function_exists('_isBlogForBeginners')
                ? _isBlogForBeginners($blogId) : false;
        
        $result[] = $blogData;
        
        restore_current_blog();
    }
     
    // move blank template to the end
    $blankTemplate = null; 
    foreach($result as $idx => $blogData) {
        if (isset($blogData['domain']) && strpos($blogData['domain'], 'templatesite-blank.') === 0) {
            $blankTemplate = $blogData;
            unset($result[$idx]);
            break;
        }
    }
    if ($blankTemplate) {
        $result[] = $blankTemplate;
        $result = array_values($result);
    }

    die( json_encode( $result ) );
}
add_action( 'wp_ajax_listTemplateSites', 'listTemplateSitesByWebGroupApi' );
add_action( 'wp_ajax_nopriv_listTemplateSites', 'listTemplateSitesByWebGroupApi' );



function getPagesFromBlogByWebGroupApi()
{
    $result = [];
    
    checkAuthorizationByToken();
    
    $blogExists = false;
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        returnError(400, "Blog with id: $blogId not found");
    }
    
    
    switch_to_blog($blogId);
    $pages = get_pages(['post_status' => 'publish,private']);
   
    foreach ($pages as $p) {
        $pageType = 'page';
        
        // try to check is it thanks page
    	$thanksPageForLp = 0;
        $thanksPageForLpFromMeta = get_post_meta( $p->ID, 'thanksPageForLp', true );
        $parentPageId = wp_get_post_parent_id($p->ID);


        if (is_numeric($thanksPageForLpFromMeta) && $parentPageId === (int) $thanksPageForLpFromMeta) { // for LP thanks page
            $pageType = 'thanks_page';
            $thanksPageForLp = (int) $thanksPageForLpFromMeta;
        
        } else {  // for site's thanks page
            $isThanksPage = get_post_meta( $p->ID, 'isThanksPage', true );
            if ($isThanksPage) {
                $pageType = 'thanks_page';
            }
        }

        $pageData = [
            'id'          => $p->ID,
            'title'       => $p->post_title,
            'status'      => $p->post_status,
            'slug'        => $p->post_name,
            'order'       => $p->menu_order,
            'type'        => $pageType,
            'created_at'  => $p->post_date_gmt,
            'modified_at' => $p->post_modified_gmt,
            'isFrontPage' => false
        ];
        
        if ($thanksPageForLp) {
            $pageData['thanksPageForLp'] = $thanksPageForLp;
        }
        
        $result[] = $pageData;
    }
    
    // get posts
    foreach (get_posts(['numberposts' => -1]) as $pst) {
        $result[] = [
            'id'          => $pst->ID,
            'title'       => $pst->post_title,
            'status'      => $pst->post_status,
            'slug'        => $pst->post_name,
            'order'       => 0,
            'type'        => 'post',
            'created_at'  => $p->post_date_gmt,
            'modified_at' => $p->post_modified_gmt,
            'isFrontPage' => false
        ];
    }
    
    usort($result, function($d1, $d2){
        return $d1['modified_at'] < $d2['modified_at'];
    });
    
    // Assume that front page is not static and add it to result as home_page
    array_unshift($result, [
        'id'          => 'home_page',
        'title'       => '',
        'status'      => '',
        'slug'        => '',
        'order'       => 0,
        'type'        => 'home_page',
        'created_at'  => '',
        'modified_at' => '',
        'isFrontPage' => false
    ]);
    
    // set isFrontPage flag
    $frontPageIdxInResult = 0;  // by default assume that front page is home page
    $frontPageType = get_blog_option($blogId, 'show_on_front');
    $frontPageId = intval( get_blog_option($blogId, 'page_on_front') );
    $postsPageId = intval( get_blog_option($blogId, 'page_for_posts') );

    if ($frontPageType === 'page') {
        foreach ($result as $idx => $data) {
            if ($frontPageId) {
                if ($data['id'] === $frontPageId && $data['type'] === 'page') {
                    $frontPageIdxInResult = $idx;
                    break;
                }
            } else if ($postsPageId) {
                if ($data['id'] === $postsPageId && $data['type'] === 'page') {
                    $frontPageIdxInResult = $idx;
                    break;
                }
            }
        }
    }
    
    $result[$frontPageIdxInResult]['isFrontPage'] = true;
    
    
    // get tags and categories
    $terms = get_terms(['post_tag', 'category'], ['hide_empty' => false]);
    foreach ($terms as $t) {
        $result[] = [
            'id'          => $t->term_id,
            'title'       => $t->name,
            'status'      => '',
            'slug'        => $t->slug,
            'order'       => 0,
            'type'        => $t->taxonomy,
            'created_at'  => '',
            'modified_at' => '',
            'isFrontPage' => false
        ];
    }
    
    restore_current_blog();
    
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_getPages', 'getPagesFromBlogByWebGroupApi' );
add_action( 'wp_ajax_nopriv_getPages', 'getPagesFromBlogByWebGroupApi' );



function deactivateBlogByWebGroupApi()
{
    checkAuthorizationByToken();
    
    $blogExists = false;
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        returnError(400, "Blog with id: $blogId not found");
    }
    
    $cmd = "wp site deactivate $blogId";
    
    exec($cmd, $output, $returnValue);

    if ($returnValue !== 0) {
        returnError(500, "Cannot deactivate blog");
    }
    
    // clear cache
    exec('wp cache flush');
    exec('wp nginx-helper purge-all');
    
    die( json_encode( ['status' => 'Ok'] ) );
}
add_action( 'wp_ajax_deactivateBlog', 'deactivateBlogByWebGroupApi' );
add_action( 'wp_ajax_nopriv_deactivateBlog', 'deactivateBlogByWebGroupApi' );



function activateBlogByWebGroupApi()
{
    checkAuthorizationByToken();
    
    $blogExists = false;
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        returnError(400, "Blog with id: $blogId not found");
    }
    
    $cmd = "wp site activate $blogId";
    
    exec($cmd, $output, $returnValue);

    if ($returnValue !== 0) {
        returnError(500, "Cannot activate blog");
    }
    
    // clear cache
    exec('wp cache flush');
    exec('wp nginx-helper purge-all');
    
    die( json_encode( ['status' => 'Ok'] ) );
}
add_action( 'wp_ajax_activateBlog', 'activateBlogByWebGroupApi' );
add_action( 'wp_ajax_nopriv_activateBlog', 'activateBlogByWebGroupApi' );



function setBlogDomainByWebGroupApi()
{
    checkAuthorizationByToken();
    
    $blogExists = false;
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        returnError(461, "Blog with id: $blogId not found");
    }
    
    $domain = trim($_POST['domain']);
    
    if (!$domain) {
        returnError(400, 'Domain is required');
    }
    
    if (!preg_match('/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/i', $domain)) {
        returnError(467, "Domain: '$domain' has wrong syntax");
    }
    
    $allDomains = getAssignedDomains();
    foreach ($allDomains as $domainData) {
        if ($domain === $domainData['domain']) {
            returnError(468, "Domain: '$domain' is always assigned to blog with id: {$domainData['blogId']}");
        }
    }
    
    $isPrimary = isset($_POST['isPrimary']) ? trim($_POST['isPrimary']) : false;
    
    $isOk = createNewDomainMapping($blogId, $domain, $isPrimary);
    
    if (!$isOk) {
        returnError(500, "Enternal error: domain cannot be created");
    }
    
    // clear cache
    exec('wp cache flush');
    exec('wp nginx-helper purge-all');
    
    die( json_encode( ['status' => 'Ok'] ) );
}
add_action( 'wp_ajax_setDomain', 'setBlogDomainByWebGroupApi' );
add_action( 'wp_ajax_nopriv_setDomain', 'setBlogDomainByWebGroupApi' );




function setBlogDomainToPrimaryByWebGroupApi()
{
    checkAuthorizationByToken();
    
    $blogExists = false;
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        returnError(400, "Blog with id: $blogId not found");
    }
    
    $domain = trim($_POST['domain']);
    
    $blogDomains = getAssignedDomains($blogId);
    $domainAssigned = false;
    foreach ($blogDomains as $domainData) {
        if ($domain === $domainData['domain']) {
            $domainAssigned = true;
            break;
        }
    }
    
    if (!$domainAssigned) {
        returnError(400, "Domain: '$domain' is not assigned to blog with id: $blogId");
    }
    
    $isOk = setBlogDomainToPrimary($domain, $blogId);    
    
    if (!$isOk) {
        returnError(500, "Enternal error: domain cannot be created");
    }
    
    // clear cache
    exec('wp cache flush');
    exec('wp nginx-helper purge-all');
    
    die( json_encode( ['status' => 'Ok'] ) );
}
add_action( 'wp_ajax_setBlogDomainToPrimary', 'setBlogDomainToPrimaryByWebGroupApi' );
add_action( 'wp_ajax_nopriv_setBlogDomainToPrimary', 'setBlogDomainToPrimaryByWebGroupApi' );



function getBlogDomainsByWebGroupApi()
{
    checkAuthorizationByToken();
    
    $blogExists = false;
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        returnError(400, "Blog with id: $blogId not found");
    }
    
    $result = getAssignedDomains($blogId);
    
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_getBlogDomains', 'getBlogDomainsByWebGroupApi' );
add_action( 'wp_ajax_nopriv_getBlogDomains', 'getBlogDomainsByWebGroupApi' );



function removeMappedBlogDomainByWebGroupApi()
{
    checkAuthorizationByToken();
    
    $blogExists = false;
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        returnError(400, "Blog with id: $blogId not found");
    }
    
    $domain = trim($_POST['domain']);
    
    $blogDomains = getMappedDomains($blogId);

    $domainAssigned = false;
    foreach ($blogDomains as $domainData) {
        if ($domain === $domainData['domain']) {
            $domainAssigned = true;
            break;
        }
    }
    
    if (!$domainAssigned) {
        returnError(400, "Domain: '$domain' is not mapped for blog with id: $blogId");
    }
    
    global $wpdb;
    $domainMappingTbl = $wpdb->base_prefix . 'domain_mapping';
    
    $result = $wpdb->query(
        $wpdb->prepare( "DELETE FROM $domainMappingTbl WHERE domain = %s", $domain )
    );

    if (!$result) {
        returnError(500, "Enternal error: domain cannot be removed");
    }
    
    // clear cache
    exec('wp cache flush');
    exec('wp nginx-helper purge-all');
    
    die( json_encode( ['status' => 'Ok'] ) );
}
add_action( 'wp_ajax_removeMappedBlogDomain', 'removeMappedBlogDomainByWebGroupApi' );
add_action( 'wp_ajax_nopriv_removeMappedBlogDomain', 'removeMappedBlogDomainByWebGroupApi' );



/*
 * Silent login.
 * 
 * To login it's needed to pass in url  params: login and pwd.
 *  To redirect after login you can pass in url param: redirectUrl
 */
function silentAuth() {
    $login = isset($_GET['login']) ? urldecode( $_GET['login'] ) : null;
    $pwd   = isset($_GET['pwd']) ? urldecode( $_GET['pwd'] ) : null;

    if (!($login & $pwd)) return;

    _logoutAndCrearCookie();

    $creds = [
        'user_login'    => urldecode( $_GET['login'] ),
        'user_password' => urldecode( $_GET['pwd'] ),
        'remember'      => true
    ];
    
    $user = wp_signon( $creds, is_ssl() );
      
    if (!is_wp_error($user) && isset($_GET['redirectUrl'])) {
    	wp_set_current_user($user->ID);
        wp_safe_redirect( $_GET['redirectUrl'] );
        exit;
    }
}
// run it before the headers and cookies are sent
add_action( 'after_setup_theme', 'silentAuth' );



/*
 * Silent login.
 * 
 * To login it's needed to pass in url  params: login and pwd.
 *  To redirect after login you can pass in url param: redirectUrl
 */
function silentAuthByUserToken()
{
    $login = isset($_GET['login']) ? trim( urldecode( $_GET['login'] ) ) : null;
    $authToken   = isset($_GET['userAuthToken']) ? trim( urldecode( $_GET['userAuthToken'] ) ) : null;

    if (!($login & $authToken)) return;
    
    // logout current user
    _logoutAndCrearCookie();
    
    $user = get_user_by('login', $login);
    if (!$user) {
        return;
    }
    
    // check is token valid
    $authTokenHash = hash('sha256', $authToken);
    $authTokenHashFromDb = get_user_meta( $user->ID, 'authTokenHash', true );
    if ($authTokenHash !== $authTokenHashFromDb) {
        return;
    }
    
    wp_set_current_user($user->ID, $user->user_login);
    wp_set_auth_cookie($user->ID);
    do_action('wp_login', $user->user_login);
      
    if (!is_wp_error($user) && isset($_GET['redirectUrl'])) {
        wp_safe_redirect( $_GET['redirectUrl'] );
        exit;
    }
}
// run it before the headers and cookies are sent
add_action( 'after_setup_theme', 'silentAuthByUserToken' );


function _logoutAndCrearCookie()
{
    wp_logout();
    
    // To avoid 500 error, return if it's one of multisites hosted on wg3 server
    $crmOptions = get_site_option('web_group_crm_options');
    if (is_array($crmOptions) && !empty($crmOptions['multisiteCrmId'])) {
	// 20 - ln1 ; 18 - shop
        if ( in_array($crmOptions['multisiteCrmId'], [20, 18]) ) {
            return;
        }
    }

    foreach ($_COOKIE as $c_id => $c_value) {
        setcookie($c_id, NULL, -1, "/");
    }
    foreach ($_COOKIE as $c_id => $c_value) {
        setcookie($c_id, NULL, -1, '/', getMainSiteDomain());
    }
    
    wp_clear_auth_cookie();
}


function getPageSeoInfoByWebGroupApi()
{
    checkAuthorizationByToken();
    
    $blogExists = false;
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        returnError(400, "Blog with id: $blogId not found");
    }
    
    
    $pageObjId = isset( $_GET['pageObj'] ) ? $_GET['pageObj'] : null;
    $pageObjId = $pageObjId === 'home_page' ? $pageObjId : (int) $pageObjId;
    $pageObjType = isset( $_GET['type'] ) ? $_GET['type'] : null;

    switch_to_blog($blogId);
    
    if (!$pageObjId) {
        returnError(400, "Parameter 'pageObj' is required");
    }
    
    if (!$pageObjType) {
        returnError(400, "Parameter 'type' is required");
    }
    
    if (!doesPageObjectExist($pageObjId, $pageObjType)) {
        returnError(400, "Object with id: $pageObjId and type: '$pageObjType' not found");
    }
    
    $result = getSeoInfo($pageObjId, $pageObjType);
    
    restore_current_blog();
   
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_getPageSeoInfo', 'getPageSeoInfoByWebGroupApi' );
add_action( 'wp_ajax_nopriv_getPageSeoInfo', 'getPageSeoInfoByWebGroupApi' );



function doesPageObjectExist($objId, $objType)
{
    $existed = true;
    
    if ($objId === 'home_page' && $objType === 'home_page') return $existed;
    
    $validTypes = ['page', 'post', 'category', 'post_tag'];
    if (!in_array($objType, $validTypes, true)) {
        returnError(400, "$objType is wrong type");
    }
    
    if (in_array($objType, ['page', 'post'], true)) {
        $wpPost = get_post($objId);

        if (!($wpPost && $wpPost->post_type === $objType)) {
            $existed = false;
        }
        
    } else {
        $term = get_term($objId, $objType);
      
        if (!($term && $term->taxonomy === $objType)) {
            $existed = false;
        }
    }
    
    return $existed;
}



function getSeoInfo($objId, $objType)
{
    if ($objId === 'home_page') {
        $wpSeoTitles = get_option('wpseo_titles');
        $wpSeoTitles = is_array($wpSeoTitles) ? $wpSeoTitles : [];
        
        $title = isset($wpSeoTitles['title-home-wpseo']) ? $wpSeoTitles['title-home-wpseo'] : '';
        $descr = isset($wpSeoTitles['metadesc-home-wpseo']) ? $wpSeoTitles['metadesc-home-wpseo'] : '';
        $keywords = isset($wpSeoTitles['metakey-home-wpseo']) ? $wpSeoTitles['metakey-home-wpseo'] : '';
        
    } else if (in_array($objType, ['page', 'post'], true)) {
        $title = get_post_meta($objId, '_yoast_wpseo_title', true);
        $descr = get_post_meta($objId, '_yoast_wpseo_metadesc', true);
        $keywords = get_post_meta($objId, '_yoast_wpseo_metakeywords', true);
        
    } else if (in_array($objType, ['category', 'post_tag'], true)) {
        $wpSeoTitles = get_option('wpseo_taxonomy_meta');
        $wpSeoTitles = is_array($wpSeoTitles) ? $wpSeoTitles : [];
        
        $title = isset($wpSeoTitles[$objType][$objId]['wpseo_title'])
                ? $wpSeoTitles[$objType][$objId]['wpseo_title'] : '';
        
        $descr = isset($wpSeoTitles[$objType][$objId]['wpseo_desc'])
                ? $wpSeoTitles[$objType][$objId]['wpseo_desc'] : '';
        
        $keywords = isset($wpSeoTitles[$objType][$objId]['wpseo_metakey'])
                ? $wpSeoTitles[$objType][$objId]['wpseo_metakey'] : '';
    }
    
    $result = [
        'title'    => $title,
        'descr'    => $descr,
        'keywords' => $keywords
    ];
    
    return $result;
}



function setPageSeoInfoByWebGroupApi()
{
    checkAuthorizationByToken();
    
    $blogExists = false;
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        returnError(400, "Blog with id: $blogId not found");
    }
    
    
    $pageObjId = isset( $_GET['pageObj'] ) ? $_GET['pageObj'] : null;
    $pageObjId = $pageObjId === 'home_page' ? $pageObjId : (int) $pageObjId;
    $pageObjType = isset( $_GET['type'] ) ? $_GET['type'] : null;

    switch_to_blog($blogId);
    
    if (!$pageObjId) {
        returnError(400, "Parameter 'pageObj' is required");
    }
    
    if (!$pageObjType) {
        returnError(400, "Parameter 'type' is required");
    }
    
    if (!doesPageObjectExist($pageObjId, $pageObjType)) {
        returnError(400, "Object with id: $pageObjId and type: '$pageObjType' not found");
    }
    
    $seoData = [];
    $requestKeys = ['title', 'descr', 'keywords'];
    foreach ($requestKeys as $rKey) {
        if (!isset($_POST[$rKey])) continue;
        
        $seoData[$rKey] = $_POST[$rKey];
    }
    
    saveSeoData($seoData, $pageObjId, $pageObjType);
    
    restore_current_blog();
   
    die( json_encode( ['status' => 'Ok'] ) );
}
add_action( 'wp_ajax_setPageSeoInfo', 'setPageSeoInfoByWebGroupApi' );
add_action( 'wp_ajax_nopriv_setPageSeoInfo', 'setPageSeoInfoByWebGroupApi' );



function saveSeoData ($data, $objId, $objType)
{
    if ($objId === 'home_page') {
        $wpSeoTitles = get_option('wpseo_titles');
        $wpSeoTitles = is_array($wpSeoTitles) ? $wpSeoTitles : [];
       
        foreach ($data as $key => $val) {
             switch ($key) {
                 case 'title':
                     $wpSeoTitles['title-home-wpseo'] = $val;
                     break;
                 case 'descr':
                     $wpSeoTitles['metadesc-home-wpseo'] = $val;
                     break;
                 case 'keywords':
                     $wpSeoTitles['metakey-home-wpseo'] = $val;
                     break;
             }
        }

        update_option('wpseo_titles', $wpSeoTitles);
        
    } else if (in_array($objType, ['page', 'post'], true)) {
        foreach ($data as $key => $val) {
            switch ($key) {
                 case 'title':
                     update_post_meta($objId, '_yoast_wpseo_title', $val);
                     break;
                 case 'descr':
                     update_post_meta($objId, '_yoast_wpseo_metadesc', $val);
                     break;
                 case 'keywords':
                     update_post_meta($objId, '_yoast_wpseo_metakeywords', $val);
                     break;
             }
        }

    } else if (in_array($objType, ['category', 'post_tag'], true)) {
        $wpSeoTitles = get_option('wpseo_taxonomy_meta');
        $wpSeoTitles = is_array($wpSeoTitles) ? $wpSeoTitles : [];
        
        foreach ($data as $key => $val) {
            switch ($key) {
                case 'title':
                    $wpSeoTitles[$objType][$objId]['wpseo_title'] = $val;
                    break;
                case 'descr':
                    $wpSeoTitles[$objType][$objId]['wpseo_desc'] = $val;
                    break;
                case 'keywords':
                    $wpSeoTitles[$objType][$objId]['wpseo_metakey'] = $val;
                    break;
            }
        }

        update_option('wpseo_taxonomy_meta', $wpSeoTitles);
    }
}



function createNewDomainMapping($blogId, $domain, $isPrimary=false)
{
    global $wpdb;
    $domainMappingTbl = $wpdb->base_prefix . 'domain_mapping';
    $result = true;
    
    if (strtolower($isPrimary) === 'false') {
        $isPrimary = false;
    } else {
        $isPrimary = boolval($isPrimary);
    }
    
    if ($isPrimary) {
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE $domainMappingTbl SET active = 0 WHERE blog_id = %d",
                $blogId
            )
        );
        
        do_action('dm_handle_actions_primary', $domain);
    }
    
    $wpdb->query(
        $wpdb->prepare(
            "INSERT INTO $domainMappingTbl ( `blog_id`, `domain`, `active` ) VALUES ( %d, %s, %d )",
            $blogId,
            $domain,
            $isPrimary
        )
    );
    
    return $result;
}



function getAssignedDomains($blogId=null)
{
    $domains = [];
    
    $mappedDomains = getMappedDomains();
    
    $allSites = wp_get_sites(['limit' => 9999]);
    
    foreach ($allSites as $s) {
        $currBlogId = (int) $s['blog_id'];

        if ($blogId && $currBlogId !== $blogId) continue;
        
        $defaultDomainData = [
            'blogId'    => $currBlogId,
            'domain'    => $s['domain'],
            'isDefault' => true,
            'isPrimary' => true
        ];

        $blogMappedDomains = array_filter($mappedDomains, function($d) use ($currBlogId) {
            return $d['blogId'] === $currBlogId;
        });
        
        $blogMappedDomains = array_values($blogMappedDomains);
        
        foreach ($blogMappedDomains as $d) {
            if ($d['isPrimary']) {
                $defaultDomainData['isPrimary'] = false;
                break;
            }
        }
        
        $domains[] = $defaultDomainData;
        $domains = array_merge($domains, $blogMappedDomains);
    }
 
    return $domains;
}


function getMappedDomains($blogId=null) {
    global $wpdb;
    $domains = [];

    $domainMappingTbl = $wpdb->base_prefix . 'domain_mapping';
    $result = $wpdb->get_results( "SELECT blog_id, domain, active FROM $domainMappingTbl ORDER BY blog_id" );
    
    if ($result) {
        foreach ($result as $row) {
            $blogDomain = preg_replace('/\/*$/', '', $row->domain);
            
            $domains[] = [
                'blogId' => (int)$row->blog_id,
                'domain' => $blogDomain,
                'isDefault' => false,
                'isPrimary' => (bool) $row->active
            ];
        }
    }
    
    if (isset($blogId)) {
        $domains = array_filter($domains, function($d) use ($blogId) {
            return $d['blogId'] === $blogId;
        });
        
        $domains = array_values($domains);
    }
    
    return $domains;
}



function setBlogDomainToPrimary($domain, $blogId)
{
    global $wpdb;
    $domainMappingTbl = $wpdb->base_prefix . 'domain_mapping';
    
    $wpdb->query(
        $wpdb->prepare(
            "UPDATE $domainMappingTbl SET active = 0 WHERE blog_id = %d",
            $blogId
        )
    );
    
    $wpdb->query(
        $wpdb->prepare(
            "UPDATE $domainMappingTbl SET active = 1 WHERE blog_id = %d AND domain = %s",
            $blogId,
            $domain
        )
    );
    
    do_action('dm_handle_actions_primary', $domain);
    
    return true;
}



function deleteBlogByWebGroupApi()
{
    checkAuthorizationByToken();
    
    $blogExists = false;
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        returnError(400, "Blog with id: $blogId not found");
    }
    
    if (is_main_site($blogId) || in_array($blogId, getValidTemplateSitesIds())) {
        returnError(400, "You cannot remove blog with id: $blogId. You don't have required permissions to do it.");
    }

    switch_to_blog($blogId);
    
    $uploadDir = wp_upload_dir();
    if (is_array($uploadDir) && isset($uploadDir['basedir'])) {
        $uploadDir = $uploadDir['basedir'];
    }
    
    restore_current_blog();

    $cmd = "wp site delete $blogId --yes";
    
    exec($cmd, $output, $returnValue);
    
    if ($returnValue !== 0) {
        returnError(500, "Cannot delete blog");
    }
    
    // try to remove blog's upload dir
    exec("rm -rf $uploadDir");
    
    // fire standart wp-hook to remove custom blog's tables
    do_action( 'delete_blog', $blogId, true );
    
    // clear cache
    exec('wp cache flush');
    exec('wp nginx-helper purge-all');
    
    die( json_encode( ['status' => 'Ok'] ) );
}
add_action( 'wp_ajax_deleteBlog', 'deleteBlogByWebGroupApi' );
add_action( 'wp_ajax_nopriv_deleteBlog', 'deleteBlogByWebGroupApi' );
add_action( 'delete_blog', 'deleteCustomBlogTables', 1000, 2);


function deleteCustomBlogTables($blogId, $drop)
{
    if (!$drop) return; // no need to drop tables
    
    $result = get_sites(['ID' => $blogId]);

    if (count($result)) return; // blog exists
    
    global $wpdb;

    $tblPrefix = "wp_{$blogId}_";
    $obsoleteBlogTables = [];
    
    $blogTables = _getDbBlogsTablesNames('FOR_NON_EXISTED_BLOGS');
    
    foreach ($blogTables as $tblName) {
        if (strstr($tblName, $tblPrefix)) {
            $obsoleteBlogTables[] = $tblName;
        }
    }
    
    foreach ($obsoleteBlogTables as $tblName) {
        $wpdb->query( "DROP TABLE IF EXISTS $tblName ;" );
    }
}

/*
 *  Get names of tables from DB 
 */
function _getDbBlogsTablesNames($cond='ALL')
{
    $tablesNames = [];
    
    $blogsIds = [];
    $blogs = get_sites(['number' => 99999]);
    foreach ($blogs as $blog) {
        $blogsIds[] = (int) $blog->blog_id;
    }    
    
    global $wpdb;
    $result = $wpdb->get_results(
        "SELECT table_name FROM information_schema.tables where table_schema='{$wpdb->dbname}';"
    );
    
    $regex = "/wp_(\d+)_/";
      
    foreach ($result as $row) {
        $tblName = $row->table_name;
        
        $matches = [];
        $res = preg_match($regex, $tblName, $matches);

        if ( !($res && isset($matches[1])) ) continue;
        
        $tblBlogId = (int) $matches[1];
        $isBlogExists = in_array($tblBlogId, $blogsIds, true);
        
        if ($cond === 'FOR_EXISTED_BLOGS' && !$isBlogExists) {
            continue;
            
        } else if ($cond === 'FOR_NON_EXISTED_BLOGS' && $isBlogExists) {
            continue;
        }
        
        $tablesNames[] = $tblName;
    }
    
    return $tablesNames;
}


/*
 *  Test service to save info about each viewed frontend's page.
 */
function testSendingOpenPageInfoToCrm()
{
    header('Access-Control-Allow-Origin: *');
    
    $projectId = $_POST['projectID'];
    $projectTypeId = $_POST['projectTypeID'];
    $guid = $_POST['guid'];
    $pageId = $_POST['pageId'];
    $pageUrl = $_POST['pageUrl'];
    $formUrl = $_POST['FormUrl'];
    $currReferer = $_POST['currReferer'];
    $event = $_POST['event'];
    $tempReferer = $_POST['tempReferer'];
    $urlReferer = $_POST['UrlRefer'];
    $formData = $_POST['Data'];
    
    $data = get_site_option('testSendingOpenPageInfoToCrm');

    if (!($data && is_array($data))) {
        $data = [];
    }
    
    if (!(isset($data[$guid]) && is_array($data[$guid]) )) {
        $data[$guid] = [];
    }
    
    $data[$guid][] = [
        'projectID' => $projectId,
        'projectTypeID' => $projectTypeId,
        'pageId' => $pageId,
        'pageUrl' => $pageUrl,
        'currReferer' => $currReferer,
        'tempReferer' => $tempReferer,
        'UrlReferer' => $urlReferer,
        'FormUrl' => $formUrl,
        'Data' => $formData,
        'event' => $event
    ];

    update_site_option('testSendingOpenPageInfoToCrm', $data);
    
    die( json_encode( ['status' => 'Ok'] ) );
}
add_action( 'wp_ajax_testSendingOpenPageInfoToCrm', 'testSendingOpenPageInfoToCrm' );
add_action( 'wp_ajax_nopriv_testSendingOpenPageInfoToCrm', 'testSendingOpenPageInfoToCrm' );


function getOpenPageInfoToCrm ()
{
    $opt = get_site_option('testSendingOpenPageInfoToCrm');

    die( json_encode( $opt ) );
}
add_action( 'wp_ajax_getOpenPageInfoToCrm', 'getOpenPageInfoToCrm' );
add_action( 'wp_ajax_nopriv_getOpenPageInfoToCrm', 'getOpenPageInfoToCrm' );




/*
 *  ****************
 *  Landing pages API
 *  *****************
 */


/*
 *  Create empty catalog for land-pages from template
 */
function createLpCatalogFromTemplate()
{
    $result = [];
    
    checkAuthorizationByToken();
                
    $tmplCatalog = getLpCatalogBySubdomain('templatecatalog');
    if (!is_array($tmplCatalog)) {
        returnError(462, 'Template for catalog not found');
    }
    
    $tmplId = $tmplCatalog['blog_id'];
   
    $subdomain = strtolower( trim($_POST['subdomain']) );
    $title = isset($_POST['title']) ? $_POST['title'] : 'Site: ' . $subdomain;
    
    if (preg_match('/^(?![-.])[\p{Hebrew}0-9a-z.-]+(?<![-.])$/ui', $subdomain)) {
        $subdomain = idn_to_ascii($subdomain, 0, INTL_IDNA_VARIANT_UTS46);
    }
    
    if (!isset($_POST['email'])) {
        returnError(400, "Param: 'email' is required.");
    }
    
    $userEmail = trim($_POST['email']);
    $user = get_user_by_email($userEmail);
    if (!$user) {
        returnError(460, "User with email: '{$userEmail}' not found.");
    }
   
    // check if user already have catalog for land-pages
    $lpCatalog = getLpCatalogForUser($user->ID);
    if ($lpCatalog) {
        returnError(
            461,
            "The land-pages catalog already exists for user: '{$userEmail}'",
            ['status' => 'ErrorMessage', 'CatalogId' => (int) $lpCatalog->blog_id]
        );
    }
 
    $cloneOpts = [
        'subdomain' => $subdomain,
        'tmplBlogId' => $tmplId,
        'title' => $title,
        'email' => $userEmail
    ];
    
    try {
        if (!isSubdomainValid($subdomain)) {
            returnError(463, 'Wrong subdomain');
        }
        
        $blogId = cloneBlog($cloneOpts);
        
        if (!$blogId) {
            returnError(400, 'Cannot create catalog for land-pages');
        }
        
        $result['catalogId'] = $blogId;
        $result['catalogUrl'] = get_site_url($blogId);
        $result['catalogAdminUrl'] = get_admin_url($blogId);
        
        // try to activate license for Elementor Pro
        try {
            activateElementorProLicenseForBlog($blogId);
            $result['elementorProLicenseActivated'] = true;
        } catch (Exception $ex) {
            $result['elementorProLicenseActivated'] = false;
        }
    
    } catch (\Exception $ex) {
        returnError(500, 'Cannot create catalog');
    }
    
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_createLpCatalog', 'createLpCatalogFromTemplate' );
add_action( 'wp_ajax_nopriv_createLpCatalog', 'createLpCatalogFromTemplate' );



function createLpFromTemplateByWebGroupApi()
{
    $result = [];
    
    global $wpdb;
    
    $requiredParams = [/*'targetCatalogId', */ 'title', 'email', 'tmplPageId', 'targetPageSlug'];
    foreach ($requiredParams as $rParam) {
        if (!(isset($_POST[$rParam]) && trim($_POST[$rParam]))) {
            returnError(400, "Param: '$rParam' is required");
        }
    }
    
    checkAuthorizationByToken();
                
    $catalogOfLpTemplates = getCatalogOfLpTemplatesInfo();
    
    $lpTmplId = (int) trim($_POST['tmplPageId']);
    $catalogOfLpTemplatesId = (int) $catalogOfLpTemplates['catalogId'];
    
    $tmplLpData = $catalogOfLpTemplates['pages'];
    $isTmplLpExist = false;
    foreach ($tmplLpData as $lpData) {
        if ($lpData['id'] === $lpTmplId) {
            $isTmplLpExist = true;
            break;
        }
    }
    if (!$isTmplLpExist) {
        returnError(462, "Template of landing page with id: '$lpTmplId' not found");
    }
    
    $newPageSlug = trim($_POST['targetPageSlug']);

    if (!preg_match('/^[\p{Hebrew}0-9a-z_\-]+$/ui', $newPageSlug)) {
        returnError(465, "Page slug: '$newPageSlug' has wrong format");
    }
   
    $userEmail = trim($_POST['email']);
    $user = get_user_by_email($userEmail);
    if (!$user) {
        returnError(460, "User with email: '{$userEmail}' not found.");
    }
    
    // check if user already have catalog for land-pages
    $targetLpCatalog = getLpCatalogForUser($user->ID);
    $trgtCatalogId = $targetLpCatalog->blog_id;
    if (!$trgtCatalogId) {
        returnError(461, "There is no land-pages catalog found for user: '{$userEmail}'");
    }
    
    // check is target page's slug already occupied
    $newPageSlugEncoded = strtolower( urlencode($newPageSlug) );
    switch_to_blog($trgtCatalogId);
    foreach (get_posts(['post_type' => 'any', 'numberposts' => -1]) as $pst) {
        if ($pst->post_name === $newPageSlugEncoded) {
            returnError(464, "Slug: '$newPageSlug' is already occupied");
        }
    }
    
    $result = fullCloneLp($lpTmplId, $newPageSlug, $catalogOfLpTemplatesId, $trgtCatalogId);
    $newLpId = $result['pageId'];
    
    $thanksPageData = assignThanksPage($newLpId, $lpTmplId, $catalogOfLpTemplatesId, $trgtCatalogId);
    
    
    $result['thanksPage'] = $thanksPageData;
    
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_createLpFromTemplate', 'createLpFromTemplateByWebGroupApi' );
add_action( 'wp_ajax_nopriv_createLpFromTemplate', 'createLpFromTemplateByWebGroupApi' );


function assignThanksPage($lpId, $srcLpId, $srcCatalogId, $trgtCatalogId)
{
    global $wpdb;
    
    $thanksPageSlug = "thanks-page-$lpId";
    
    $srcThanksPageId = getLpThanksPageTemplateId($srcLpId, $srcCatalogId);
    
    if (!$srcThanksPageId) return null;
    
    try {
        $result = fullCloneLp($srcThanksPageId, $thanksPageSlug, $srcCatalogId, $trgtCatalogId);
    } catch (\Exception $ex) {
        return null;
    }
    
    $thanksPageId = $result['pageId'];
    
    switch_to_blog($trgtCatalogId);
    
    // make thanks page as child for LP
    wp_update_post([
        'ID' => $thanksPageId,
        'post_parent' => $lpId
    ]);
    
    $thanksPageUrl = _getPagePermalink($thanksPageId, $trgtCatalogId);
    
    assignThanksPageRedirectForElementorForms($lpId, $thanksPageUrl, $trgtCatalogId);
    
    update_post_meta($thanksPageId, 'thanksPageForLp', $lpId);
    
    $result['pageLink'] = $thanksPageUrl;
    
    return $result;
}



function assignThanksPageRedirectForElementorForms($lpId, $thanksPageUrl, $trgtCatalogId, $srcBlogId=null)
{
    switch_to_blog($trgtCatalogId);
    
    $elData = get_post_meta($lpId, '_elementor_data', true);

    if ($elData) {
        $elData = json_decode($elData, true);
       
        $elData = walkThrowToAssignRedirectUrlForElementorForms($elData, $thanksPageUrl);

        $elData = json_encode($elData);
        $elData = wp_slash($elData);
        
        update_metadata( 'post', $lpId, '_elementor_data', $elData );
    }
}


function walkThrowToAssignRedirectUrlForElementorForms($data, $thanksPageUrl)
{
    if (!is_array($data)) return $data;
 
    if ( isset($data['elType']) && $data['elType'] === 'widget' ) {
        if (isset($data['widgetType']) && $data['widgetType'] === 'form') {
            if ( isset($data['settings']['redirect_to']) ) {
                $data['settings']['redirect_to'] = $thanksPageUrl;
            }
        }
    }
    
    foreach ($data as $key => $val) {
        $data[$key] = walkThrowToAssignRedirectUrlForElementorForms($val, $thanksPageUrl);
    }
    
    return $data;
}


/*
 *  Get ID of thanks page which will be cloned and then assigned
 */
function getLpThanksPageTemplateId($srcLpId, $srcCatalogId)
{
    global $wpdb;
    $srcThanksPageId = null;
    
    switch_to_blog($srcCatalogId);
    
    // try to get thanks page ID which was assigned to source page.
    // It makes sense if LP is clonned from user's clatalog
    $srcThanksPageId = getLpThanksPageId($srcLpId, $srcCatalogId);
    
    // try to get ID of default thanks page
    // It makes sense if LP is clonned from clatalog of LP-templates
    if (!$srcThanksPageId) {
        $row = $wpdb->get_row(
            "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='thanksPageForLp' AND meta_value='0' "
        );
        
        if (!empty($row) && $row->post_id) {
            $srcThanksPageId = (int) $row->post_id;
        }
    }

    return $srcThanksPageId;
}


function getLpThanksPageId($lpId, $catalogId)
{
    global $wpdb;
    $srcThanksPageId = null;
    
    switch_to_blog($catalogId);
    
    $row = $wpdb->get_row(
        "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='thanksPageForLp' AND meta_value='$lpId' "
    );
    
    if (!empty($row) && $row->post_id) {
        $foundPageId = (int) $row->post_id;
        $parentPageId = (int) wp_get_post_parent_id($foundPageId);
        
        if ($parentPageId === $lpId) {
            $srcThanksPageId = $foundPageId;
        }
    }

    return $srcThanksPageId;
}


function fullCloneLp($srcLpId, $newPageSlug, $srcLpCatalogId, $trgtCatalogId)
{
    $result = [];

    try {
        switch_to_blog($srcLpCatalogId);
        
        $page = get_post( $srcLpId );

        $mapIds = [];
        $attachmentsIds = [];
        
        $newPageId = cloneLP($page, $newPageSlug, null, $srcLpCatalogId , $trgtCatalogId, $mapIds, $attachmentsIds);

        copySpecificElementorsFileStructure($srcLpId, $newPageId, $srcLpCatalogId, $trgtCatalogId);
        
        // get ID's of attachments which are referred by content's of posts , hrefs, shortcodes
        $otherAttachmentsIds = getAttachmentsIdsByParsing($mapIds, $srcLpCatalogId, $trgtCatalogId);

        // create new records in DB tables *_posts and *_postmeta
        $attachmentsIds = addNewAttachments($otherAttachmentsIds, $attachmentsIds, $newPageSlug, $srcLpCatalogId, $trgtCatalogId);
   
        // update page's thumbnail meta
        updatePageThumbMeta($attachmentsIds, $trgtCatalogId, $newPageId);
        
        // change pathes and ID's of attachments in the content of posts: in the hrefs, shortcodes
        updateAttachmentsReferencesInContents($mapIds, $attachmentsIds, $srcLpCatalogId, $trgtCatalogId);
        
        replaceAttachmentsUrlsInElementorData($newPageId, $attachmentsIds, $srcLpCatalogId, $trgtCatalogId);
        
        replaceAttachmentsUrlsInElementorPageSettings($newPageId, $attachmentsIds, $srcLpCatalogId, $trgtCatalogId);
        
        replaceAttachmentsUrlsInElementorCss($newPageId, $srcLpCatalogId, $trgtCatalogId, $attachmentsIds);
        
        replaceSelectorsInDbElementorCss($srcLpId, $newPageId, $trgtCatalogId);
        
        switch_to_blog($trgtCatalogId);

        updatePostTitle($trgtCatalogId, $newPageId, $_POST['title']);
       
        $result = [
            'pageId' => $newPageId,
            'pageLink' => _getPagePermalink($newPageId, $trgtCatalogId)
        ];
        
        restore_current_blog();
    
    } catch (\Exception $ex) {
        returnError(500, 'Cannot clone page');
    }
    
    return $result;
}



function _getPagePermalink($pageId, $blogId)
{
    switch_to_blog($blogId);
    
    $permalink = get_permalink($pageId);
    
    $domain = get_blog_details($blogId)->domain;
    $primaryDomain = getPrimaryDomain($blogId);
    
    $permalink = str_replace($domain, $primaryDomain, $permalink);
    
    return $permalink;
}


/*
 *  Replace thumbnail's ID to new one in the 'postmeta' table.
 */
function updatePageThumbMeta($attachmentsIds, $trgtCatalogId, $newPageId)
{
    switch_to_blog($trgtCatalogId);
    
    $oldThumbId = (int) get_post_meta($newPageId, '_thumbnail_id', true);
    
    if (!($oldThumbId && isset($attachmentsIds[$oldThumbId]))) return;
    
    global $wpdb;
    
    $wpdb->query(
        $wpdb->prepare(
            "UPDATE $wpdb->postmeta SET meta_value = %s WHERE post_id = %d AND meta_key = '_thumbnail_id' ",
            $attachmentsIds[$oldThumbId],
            $newPageId
        )
    );
}


function getLpCatalogBySubdomain($subdomain)
{
    $catalog = null;
    
    $domain =  $subdomain . '.' . getMainSiteDomain();
  
    foreach (wp_get_sites(['limit' => 9999]) as $s) {
        if ($s['domain'] === $domain) {
            $catalog = $s;
            break;
        }
    }

    return $catalog;
}


function getLpCatalogForUser($userId)
{
    $lpCatalog = null;
    
    $projects = getProjectsForUser($userId, 'ONLY_LP_CATALOG');
    
    if (is_array($projects) && count($projects) === 1) {
        $lpCatalog = $projects[0];
    }
    
    return $lpCatalog;
}


function getProjectsForUser($userId, $condition='ALL')
{
    $result = [];
    
    $userBlogs = get_blogs_of_user($userId);
    foreach ($userBlogs as $blogId => $blogInfo) {
        $blogType = get_blog_option($blogId, 'uco_blog_type');
        
        if ($condition === 'ONLY_LP_CATALOG') {
            if ($blogType === 'landing-pages-catalog') {
                $result[] = get_blog_details($blogId);
                break;
            }
            
        } else if ($condition === 'ONLY_BLOGS') {
            if ($blogType !== 'landing-pages-catalog') {
                $result[] = get_blog_details($blogId);
            }
            
        } else {
            $result[] = get_blog_details($blogId);
        }
    }
    
    return $result;
}



function cloneLP($page, $newPageSlug=null, $parentPageId=null, $srcBlogId, $trgtBlogId, &$idsMap, &$attachmentsIds) {
    global $wpdb;
    
    switch_to_blog($trgtBlogId);
    
    $currUser = wp_get_current_user();
    $newPageAuthor = $currUser->ID;
    
    
    switch_to_blog($srcBlogId);
    $srcPageId = $page->ID;
    
    if (isset($idsMap[$srcPageId])) return;

    if ($page->post_type !== 'attachment') {
        $args = array(
            'comment_status' => $page->comment_status,
            'ping_status'    => $page->ping_status,
            'post_author'    => $newPageAuthor,
//            'post_content'   => $page->post_content,
            'post_excerpt'   => $page->post_excerpt,
            'post_name'      => $newPageSlug ? $newPageSlug : $page->post_name,
            'post_parent'    => $parentPageId ? $parentPageId : $page->post_parent,
            'post_password'  => $page->post_password,
            'post_status'    => $page->post_status,
            'post_title'     => $page->post_title,
            'post_type'      => $page->post_type,
            'to_ping'        => $page->to_ping,
            'menu_order'     => $page->menu_order
        );

        switch_to_blog($trgtBlogId);
        $newPageId = wp_insert_post( $args );
        
        // we must set page's content in that way because otherwise some parts of content can be lost.
        updatePostContent($trgtBlogId, $newPageId, $page->post_content);
    
    } else {
        switch_to_blog($trgtBlogId);
        $newPageId = createNewAttachment($srcPageId, $newPageSlug, $srcBlogId, $trgtBlogId);
        $idsMap[$srcPageId] = $newPageId;
        $attachmentsIds[$srcPageId] = $newPageId;
    }
    
    $idsMap[$srcPageId] = $newPageId;
    
    switch_to_blog($srcBlogId);
    $children = get_children(['post_parent' => $srcPageId]);

    foreach ($children as $child) {
        cloneLP($child, $newPageSlug, $newPageId, $srcBlogId, $trgtBlogId, $idsMap, $attachmentsIds);
    }
    
    if ($page->post_type === 'attachment') {
        return $newPageId;
    }

    $pageMetaInfo = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$srcPageId");
    
    switch_to_blog($trgtBlogId);
    if (count($pageMetaInfo)!=0) {
        $sqlQuery = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
        foreach ($pageMetaInfo as $metaInfo) {
            $metaKey = $metaInfo->meta_key;
            $metaValue = addslashes($metaInfo->meta_value);
            $sqlSelectQuery[]= "SELECT $newPageId, '$metaKey', '$metaValue'";
        }
        $sqlQuery .= implode(" UNION ALL ", $sqlSelectQuery);
        $wpdb->query($sqlQuery);
    }
    
    return $newPageId;
}



function getAttachmentsIdsByParsing($mapIds, $srcBlogId, $trgtBlogId)
{
    $ids = [];

    switch_to_blog($trgtBlogId);
    
    foreach ($mapIds as $postId) {
        $postContent = trim(getPostContent($trgtBlogId, $postId));

        if (!$postContent) continue;
        
        $ids = array_merge(
            getAttachmentsIdsFromContent($postContent, $srcBlogId),
            getAttachmentsIdsFromDiviModules($postContent),
            getAttachmentsIdsFromElementorData($postId, $srcBlogId, $trgtBlogId),
            getAttachmentsIdsFromElementorCss($postId, $srcBlogId, $trgtBlogId)
        );
    }
 
    restore_current_blog();
    
    return array_unique($ids);
}



function getAttachmentsIdsFromDiviModules($content)
{
    $ids = [];
    
    if (stristr($content, 'et_pb_gallery') === false) return $ids;
    
    $diviGalleryRegex = '/\[et_pb_gallery([^\[\]]+)\]/i';
    $diviGalleryIdsRegex = '/gallery_ids="([, \d]*)"/i';
    
    preg_match_all($diviGalleryRegex, $content, $matches);
    
    if (isset($matches[1])) {
        $galleries = $matches[1];
    }
    
    foreach ($galleries as $galleryShortcode) {
        preg_match($diviGalleryIdsRegex , $galleryShortcode, $matches);
        
        if (isset($matches[1])) {
            $ids = explode(',' , $matches[1]);
        }
    }
    
    foreach ($ids as $key => $id) {
        $ids[$key] = (int) trim($id);
    }
    
    return $ids;
}



function getAttachmentsIdsFromContent($content, $srcBlogId)
{
    $regexes = [
        '/"[^\("\'\)]+\/uploads\/sites\/' . $srcBlogId  . '\/([^\("\'\)]+)"/i',
        "/'[^\(\"'\)]+\/uploads\/sites\/$srcBlogId\/([^\(\"'\)]+)'/i"
    ];
    
    $aIds = [];
    $attachmentsSuffixes = [];
    foreach ($regexes as $r) {
        $isMatched = preg_match_all($r, $content, $matches);
        if ($isMatched && isset($matches[1])) {
            
            if (is_array($matches[1])) {
                foreach ($matches[1] as $attachmentPathSuffix) {
                    $attachmentsSuffixes[] = $attachmentPathSuffix;
                }
            } else {
                $attachmentsSuffixes[] = $matches[1];
            }
        }
    }

    $attachmentsSuffixes = array_unique($attachmentsSuffixes);
    $srcsetAttachmentsSuffixes = getSrcsetAttachementsSuffixes($content, $srcBlogId);
    $attachmentsSuffixes = array_merge($attachmentsSuffixes, $srcsetAttachmentsSuffixes, $cssBackgroundImageUrlSuffixes);

    foreach ($attachmentsSuffixes as $suffix) {
        $attachId = getAttachmentIdByPathSuffix($suffix, $srcBlogId);
        if ($attachId) {
            $aIds[] = $attachId;
        }
    }
   
    return $aIds;
}


function getSrcsetAttachementsSuffixes($content, $srcBlogId)
{
    $attachmentsSuffixes = [];
    
    $srcsetRegex = '/srcset[\s]*=[\s]*"([^\("\)]+\/uploads\/sites\/' . $srcBlogId  . '\/[^\("\)]+)"/i';
    $urlRegex = '/[\S]+\/uploads\/sites\/' . $srcBlogId . '\/([\S]+)/i';
    
    $isMatched = preg_match_all($srcsetRegex, $content, $matches);
    if ($isMatched && isset($matches[1])) {
        foreach ($matches[1] as $srcset) {
            $urls = explode(',', $srcset);
            
            foreach ($urls as $url) {
                $isMatched = preg_match($urlRegex, $url, $m);
                if ($isMatched && isset($m[1])) {
                    $attachmentsSuffixes[] = $m[1];
                }
            }
        }

    }
   
    return $attachmentsSuffixes;
}


function getAttachmentsIdsFromElementorData($postId, $srcBlogId, $trgtBlogId)
{
    $attachmentsIds = [];
    
    switch_to_blog($blogId);
    
    $data = trim( get_post_meta($postId, '_elementor_data', true) );
    $data = json_decode($data, true);
    
    if (!is_array($data)) return $attachmentsIds;
    
    walkThroughElementorDataAndGetAttachmentIds($data, $srcBlogId, $attachmentsIds);
   
    return $attachmentsIds;
}



function getAttachmentIdByPathSuffix($suffix, $srcBlogId)
{
    $id = null;
    
    global $wpdb;
  
    switch_to_blog($srcBlogId);
    
    if (strstr($suffix, 'elementor/thumbs/') && preg_match('/elementor\/thumbs\/([\S]+)-[a-z0-9]+\.[\S]+$/', $suffix, $match)) {
        if (isset($match[1])) {
            $suffix = $match[1];
        }
        
        $res = $wpdb->get_results(
            "SELECT * FROM $wpdb->postmeta
                    WHERE meta_key='_wp_attached_file' AND meta_value LIKE '%$suffix%' ORDER BY meta_key");
        
    } else if (preg_match('/[^\/]+-[\d]+x[\d]+\.[\S]+$/i', $suffix, $match)) {
        $suffix = $match[0];
        $res = $wpdb->get_results(
            "SELECT * FROM $wpdb->postmeta
                WHERE meta_key='_wp_attachment_metadata' AND meta_value LIKE '%$suffix%' ORDER BY meta_key");
        
    } else {
        $res = $wpdb->get_results(
            "SELECT * FROM $wpdb->postmeta
                    WHERE meta_key='_wp_attached_file' AND meta_value LIKE '%$suffix%' ORDER BY meta_key");
    
    }
    
    foreach ($res as $r) {
        $id = (int) $r->post_id;
        $metaValue = trim($r->meta_value);
        if (preg_match('/^\[\{[\s\S]*\}\]$/', $metaValue)) continue;
        
        $query = "SELECT * FROM $wpdb->posts WHERE ID=$id";
        $result = $wpdb->get_results($query);
        if (is_array($result) && count($result)) {
            break;
        }
    }
    
    return $id;
}



function addNewAttachments($newAttachmentsIds, $createdAttachmentsIds, $newPageSlug, $srcBlogId, $trgtBlogId)
{
    $arr = [];
    foreach ($newAttachmentsIds as $attachId) {
        if (isset($createdAttachmentsIds[$attachId])) continue;
        
        switch_to_blog($srcBlogId);
        $attachment = get_post($attachId);
        
        // copy attachment data from old posts and postmeta tables to new ones
        cloneLP($attachment, $newPageSlug, null, $srcBlogId, $trgtBlogId, $arr, $createdAttachmentsIds);
    }
    
    return $createdAttachmentsIds;
}


function fullCopyFiles($s1, $s2) {
    $path = pathinfo($s2);
    if (!file_exists($path['dirname'])) {
        mkdir($path['dirname'], 0777, true);
    }   
    if (!copy($s1,$s2)) {
        echo "copy failed \n";
    }
}


function updateAttachmentsReferencesInContents($mapIds, $attachmentsIds, $srcBlogId, $trgtBlogId)
{
    switch_to_blog($trgtBlogId);
 
    foreach ($mapIds as $newPostId) {
        $pst = get_post($newPostId);
        $postContent = trim(getPostContent($trgtBlogId, $newPostId));
        
        if (!$postContent) continue;
        
        $postContent = getContentWithUpdatedAttachments($postContent, $attachmentsIds, $srcBlogId, $trgtBlogId);
       
        switch_to_blog($trgtBlogId);
       
        updatePostContent($trgtBlogId, $newPostId, $postContent);
    }
}


function getContentWithUpdatedAttachments($content, $attachmentsIds, $srcBlogId, $trgtBlogId)
{
    $regexes = [
        '/"([^\("\'\)]+\/uploads\/sites\/' . $srcBlogId  . '\/([^\("\'\)]+))"/i',
        "/'([^\(\"'\)]+\/uploads\/sites\/$srcBlogId\/([^\(\"'\)]+))'/i",
    ];

    $urlsReplacementMap = [];

    foreach ($regexes as $regex) {
        if (!preg_match_all($regex, $content, $matches)) continue;

        foreach ($matches[1] as $idx => $oldUrl) {
            $suffix = $matches[2][$idx];

            $oldAttachmentId = getAttachmentIdByPathSuffix($suffix, $srcBlogId);
            if (!$oldAttachmentId) continue;

            if ($attachmentsIds === null) {
                $newAttachmentId = $oldAttachmentId;
                
            } else if (isset($attachmentsIds[$oldAttachmentId])) {
                $newAttachmentId = $attachmentsIds[$oldAttachmentId];
                
            } else {
                continue;
            }

            switch_to_blog($trgtBlogId);

            $newAttachmentUrl = getAttachmentUrl($newAttachmentId, $suffix);
            $urlsReplacementMap[$oldUrl] = $newAttachmentUrl;
        }
    }
        
    $srcsetUrlsReplacementMap = getSrcsetUrlsReplacementMap($content, $attachmentsIds, $srcBlogId, $trgtBlogId);
    $urlsReplacementMap = array_merge($srcsetUrlsReplacementMap , $urlsReplacementMap);

    $content = str_replace(
        array_keys($urlsReplacementMap),
        array_values($urlsReplacementMap),
        $content
    );

    $content = updateAttachmentsReferencesInDiviModules($content, $attachmentsIds, $srcBlogId, $trgtBlogId);
    
    return $content;
}


function getAttachmentUrl($newAttachmentId, $oldAttachmentSuffix)
{
    $newAttachmentUrl = wp_get_attachment_url($newAttachmentId);
    
    $sizeRegexp = '/-(\d+)x(\d+)\.[a-zA-z0-9]+$/';
    preg_match($sizeRegexp, $oldAttachmentSuffix, $matches);
    
    if (is_array($matches) && count($matches) === 3) {
        $width = (int) $matches[1];
        $height = (int) $matches[2];
        $size = [$width , $height];
        
        $newAttachmentUrl = wp_get_attachment_image_url($newAttachmentId, $size);
    }

    return $newAttachmentUrl;
}


function getSrcsetUrlsReplacementMap($content, $attachmentsIds, $srcBlogId, $trgtBlogId)
{
    $urlsReplacementMap = [];
    
    switch_to_blog($srcBlogId);
    $uploadDir = wp_upload_dir();
    $uploadDir = $uploadDir['baseurl'];
    $uploadDir = str_replace(network_site_url(), get_option('siteurl') . '/', $uploadDir);

    $oldSuffixes = getSrcsetAttachementsSuffixes($content, $srcBlogId);
    
    foreach ($oldSuffixes as $suffix) {
        $oldAttachmentId = getAttachmentIdByPathSuffix($suffix, $srcBlogId);
        $oldAttachmentUrl = $uploadDir . '/' . $suffix;
        
        if (!(isset($attachmentsIds[$oldAttachmentId]) && $attachmentsIds[$oldAttachmentId])) continue;
        
        $newAttachmentId = $attachmentsIds[$oldAttachmentId];
        
        switch_to_blog($trgtBlogId);
        $newAttachmentUrl = wp_get_attachment_url($newAttachmentId);

        $urlsReplacementMap[$oldAttachmentUrl] = $newAttachmentUrl;
    }
    
    return $urlsReplacementMap;
}



function updateAttachmentsReferencesInDiviModules($content, $attachmentsIds, $srcBlogId, $trgtBlogId)
{
    switch_to_blog($srcBlogId);
    
    $diviGalleryRegex = '/\[et_pb_gallery([^\[\]]+)\]/i';
    $diviGalleryIdsRegex = '/gallery_ids="([, \d]*)"/i';
    
    preg_match_all($diviGalleryRegex, $content, $matches);
    
    $galleries = [];
    
    if (isset($matches[1])) {
        $galleries = $matches[1];
    }
    
    foreach ($galleries as $gallery) {
        if (!preg_match($diviGalleryIdsRegex, $gallery, $matches)) continue;
        if (!isset($matches[1])) continue;
        
        $oldGalleryIds = [];
        $newGalleryIds = [];
        
        foreach (explode(',' , $matches[1]) as $oldId) {
            $oldId = (int) trim($oldId);
            $oldGalleryIds[] = $oldId;
            $newGalleryIds[] = $attachmentsIds[$oldId];
        }
        
        $newGalleryIds = str_replace($oldGalleryIds, $newGalleryIds, $matches[0]);
        $newGalleryShortcode = str_replace($matches[0], $newGalleryIds, $gallery);
        
        $content = str_replace($gallery, $newGalleryShortcode, $content);
    }
 
    return $content;
}




function createNewAttachment($attachmentId, $newPageSlug, $srcBlogId, $trgtBlogId)
{
    switch_to_blog($trgtBlogId);
    
    $newAttachedFilePath = cloneAttachmentFile($attachmentId, $newPageSlug, $srcBlogId, $trgtBlogId);
    
    $parentPostId = 0;

    // get type for 'post_mime_type'.
    $filetype = wp_check_filetype( basename( $newAttachedFilePath ), null );

    // get path to upload dir.
    $uploadDir = wp_upload_dir();

    // Prepare attachment's data
    $attachment = array(
        'guid'           => $uploadDir['url'] . '/' . basename( $newAttachedFilePath ), 
        'post_mime_type' => $filetype['type'],
        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $newAttachedFilePath ) ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Insert attachment into DB
    $attachId = wp_insert_attachment( $attachment, $newAttachedFilePath, $parentPostId );

    // IMPORTANT!!!  This file is required to generate attachment's metadata
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    // Create metadata for attachment and update record in DB
    $attachData = wp_generate_attachment_metadata( $attachId, $newAttachedFilePath );
    wp_update_attachment_metadata( $attachId, $attachData );


    return $attachId;
}



function cloneAttachmentFile($oldId, $newPageSlug, $srcBlogId, $trgtBlogId)
{
    switch_to_blog($srcBlogId);
    
    $oldAttachedFilePath = get_attached_file($oldId);
    
    $pathInfo = pathinfo($oldAttachedFilePath);
    $oldFileBasename = $pathInfo['basename'];

    restore_current_blog();
    
    switch_to_blog($trgtBlogId);
    
    $newUploadDir = wp_upload_dir();

    $uniqPrefix = $newPageSlug /* . '-' . time() */ . '__';

    $newFileBasename = $uniqPrefix . $oldFileBasename;
    $newAttachedFilePath = $newUploadDir['path'] . '/' . $newFileBasename;
    
    fullCopyFiles($oldAttachedFilePath , $newAttachedFilePath);
    
    return $newAttachedFilePath;
}



function getCatalogOfLpTemplatesInfo()
{
    $catalogOfLpTemplates = getLpCatalogBySubdomain('templatelands');
    if (!is_array($catalogOfLpTemplates)) {
        returnError(463, 'Catalog of templates of land-pages not found');
    }
    
    switch_to_blog($catalogOfLpTemplates['blog_id']);
    
    $pagesData = [];
    
    $pages = get_pages();
    foreach ($pages as $p) {
        $pagesData[] = [
            'id'         => $p->ID,
            'title'      => $p->post_title,
            'slug'       => $p->post_name,
            'thumbUrl'   => get_the_post_thumbnail_url($p),
            'previewUrl' => $p->guid
        ];
    }
    
    $data = [
        'catalogId' => $catalogOfLpTemplates['blog_id'],
        'pages' => $pagesData
    ];
    
    return $data;
}



function getLandPagesTemplatesByWebGroupApi()
{
    $result = [];
    
    checkAuthorizationByToken();
    
    $catalogOfLpTemplates = getCatalogOfLpTemplatesInfo();
    $catalogOfLpTemplatesId = (int) $catalogOfLpTemplates['catalogId'];
    
    try {
        switch_to_blog($catalogOfLpTemplatesId);
        
        $lpTemplates = $catalogOfLpTemplates['pages'];
        
        // remove Thanks page from result
        $lpTemplates = array_filter($lpTemplates, function($item){
            $result = true;
            
            if (isset($item['slug']) && $item['slug'] === 'thanks-page') {
                $result = false;
            }
            
            return $result;
        });
        
        // remove Thanks page from LP-templates and move 'Blank' template to the end.
        $blankLpTemplate = null;
        foreach ($lpTemplates as $idx => $lpTmpl) {
            if (isset($lpTmpl['slug'])) {
                if ($lpTmpl['slug'] === 'thanks-page') {
                    unset($lpTemplates[$idx]);
                }
                
                if ($lpTmpl['slug'] === 'blank') {
                    $blankLpTemplate = $lpTmpl;
                    unset($lpTemplates[$idx]);
                }
            }
        }

        if ($blankLpTemplate) {
            $lpTemplates[] = $blankLpTemplate;
        }
        
        
        $result = $lpTemplates = array_values($lpTemplates);
        
        restore_current_blog();
    
    } catch (\Exception $ex) {
        returnError(500, 'Cannot get info about templates of landing page');
    }
    
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_getLandPagesTemplates', 'getLandPagesTemplatesByWebGroupApi' );
add_action( 'wp_ajax_nopriv_getLandPagesTemplates', 'getLandPagesTemplatesByWebGroupApi' );


/*
 *  Delete landing page from user's site-catalog
 */
function deleteLandPageByWebGroupApi()
{
    checkAuthorizationByToken();
    
    // check required params
    $requiredParams = ['userEmail', 'landPageId'];
    foreach ($requiredParams as $rParam) {
        if (!(isset($_POST[$rParam]) && trim($_POST[$rParam]))) {
            returnError(400, "Param: '$rParam' is required");
        }
    }

    try {
        // get user by email
        $userEmail = trim($_POST['userEmail']);
        $user = get_user_by_email($userEmail);
        if (!$user) {
            returnError(460, "User with email: '{$userEmail}' not found.");
        }

        // get user site-catalog
        $lpCatalog = getLpCatalogForUser($user->ID);
        $lpCatalogId = (int) $lpCatalog->blog_id;
        if (!$lpCatalogId) {
            returnError(461, "The land-pages catalog was not found for user: '{$userEmail}'");
        }

        switch_to_blog($lpCatalogId);
        
        // check is page exists
        $landPageId = (int) trim($_POST['landPageId']);
        $landPage = get_post($landPageId);
        if (!$landPage) {
            returnError(462, "The landing page with id: '$landPageId' not found");
        }
        
        // try to delete page with trash skipping
        $deleted = wp_delete_post($landPageId, true);
        if (!$deleted) {
            throw new Exception();
        }
        
        $thanksPageId = getLpThanksPageId($landPageId, $lpCatalogId);
        if (!$thanksPageId) {
            try {
                wp_delete_post($thanksPageId, true);
            } catch (\Exception $ex) {}
        }
        
        restore_current_blog();
        
        // clear cache
        exec('wp cache flush');
        exec('wp nginx-helper purge-all');
    
    } catch (\Exception $ex) {
        returnError(500, 'Cannot remove landing page');
    }
    
    die( json_encode( ['status' => 'Ok'] ) );
}
add_action( 'wp_ajax_deleteLandPage', 'deleteLandPageByWebGroupApi' );
add_action( 'wp_ajax_nopriv_deleteLandPage', 'deleteLandPageByWebGroupApi' );



/*
 *  get landing pages catalog for user
 */
function getLandPagesCatalogForUserByWebGroupApi()
{
    checkAuthorizationByToken();
    
    // check required params
    $requiredParams = ['userEmail'];
    foreach ($requiredParams as $rParam) {
        if (!(isset($_POST[$rParam]) && trim($_POST[$rParam]))) {
            returnError(400, "Param: '$rParam' is required");
        }
    }

    try {
        // get user by email
        $userEmail = trim($_POST['userEmail']);
        $user = get_user_by_email($userEmail);
        if (!$user) {
            returnError(460, "User with email: '{$userEmail}' not found.");
        }

        // get user site-catalog
        $lpCatalog = getLpCatalogForUser($user->ID);
        $lpCatalogId = (int) $lpCatalog->blog_id;
        if (!$lpCatalogId) {
            returnError(461, "The land-pages catalog was not found for user: '{$userEmail}'");
        }

        switch_to_blog($lpCatalogId);

        $result = [
            'id' => $lpCatalog->blog_id,
            'url' => $lpCatalog->siteurl,
            'title' => $lpCatalog->blogname,
            'descr' => get_bloginfo('description'),
            'domain' => $lpCatalog->domain
        ];
        
        restore_current_blog();
        
        // clear cache
        exec('wp cache flush');
        exec('wp nginx-helper purge-all');
    
    } catch (\Exception $ex) {
        returnError(500, 'Cannot remove landing page');
    }
    
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_getLandPagesCatalogForUser', 'getLandPagesCatalogForUserByWebGroupApi' );
add_action( 'wp_ajax_nopriv_getLandPagesCatalogForUser', 'getLandPagesCatalogForUserByWebGroupApi' );


function updatePostContent($blogId, $postId, $content)
{
    switch_to_blog($blogId);
    global $wpdb;
    
    $wpdb->query(
        $wpdb->prepare( 
            "UPDATE $wpdb->posts SET post_content = %s WHERE ID = %d",
            $content,
            $postId
        ) 
    );
}


function updatePostTitle($blogId, $postId, $title)
{
    switch_to_blog($blogId);
    global $wpdb;
    
    $wpdb->query(
        $wpdb->prepare( 
            "UPDATE $wpdb->posts SET post_title = %s WHERE ID = %d",
            $title,
            $postId
        ) 
    );
}


function getPostContent($blogId, $postId)
{
    switch_to_blog($blogId);
    global $wpdb;
    
    $result = $wpdb->get_col(
        $wpdb->prepare( 
            "SELECT post_content FROM $wpdb->posts WHERE ID = %d",
            $postId
        ) 
    );
   
    $content = isset($result[0]) ? $result[0] : '';
    
    return $content;
}


function walkThroughElementorDataAndGetAttachmentIds($data, $srcBlogId, & $ids)
{
   if (!is_array($data)) return;
    
    if (count($data) === 2 && isset($data['id']) && isset($data['url'])) {
        $id = intval($data['id']);

        $isUrlValid = (bool) strstr($data['url'], "/uploads/sites/$srcBlogId/");
  
        if ($id && $isUrlValid) {
            $ids[] = $id;
        }
    }
    
    foreach ($data as $d) {
        walkThroughElementorDataAndGetAttachmentIds($d, $srcBlogId, $ids);
    }
    
    return; 
}


function walkThroughElementorDataAndGetAttachments($data, $srcBlogId, & $imgData)
{
   if (!is_array($data)) return;
    
    if (count($data) === 2 && isset($data['id']) && isset($data['url'])) {
        $id = intval($data['id']);

        $isUrlValid = (bool) strstr($data['url'], "/uploads/sites/$srcBlogId/");
  
        if ($id && $isUrlValid) {
            $imgData[] = $data;
        }
    }
    
    foreach ($data as $d) {
        walkThroughElementorDataAndGetAttachments($d, $srcBlogId, $imgData);
    }
    
    return; 
}


function replaceAttachmentsUrlsInElementorData($postId, $attachmentsIds, $srcBlogId, $trgtBlogId)
{
    global $wpdb;
    
    switch_to_blog($trgtBlogId);

    // get meta value for post. It's impossible use get_post_meta() because it returns wrong value
    $r = $wpdb->get_row("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $postId AND meta_key = '_elementor_data' ;");
    $oldMetaValue = $r->meta_value;

    $data = json_decode($oldMetaValue , true);

    if (!is_array($data)) return;
    
    $data = walkThroughElementorDataAndReplaceAttachmentsUrls($data, $srcBlogId, $trgtBlogId, $attachmentsIds);
    
    $escapedJsonString = wp_json_encode($data);
//    $escapedJsonString = escapeJsonString($data);
//    $escapedJsonString = $data;

    switch_to_blog($trgtBlogId);
    
//    $isForBeginners = get_blog_option($trgtBlogId, 'fixdital_blog_for_beginner');

    $wpdb->update(
        $wpdb->postmeta,
        ['meta_value' => $escapedJsonString],
        ['meta_key' => '_elementor_data', 'post_id' => $postId]
    );
    
}


function replaceAttachmentsUrlsInElementorPageSettings($postId, $attachmentsIds, $srcBlogId, $trgtBlogId)
{
    $data = get_post_meta($postId, '_elementor_page_settings', true);
    
    if (!is_array($data)) return;

    $data = walkThroughElementorDataAndReplaceAttachmentsUrls($data, $srcBlogId, $trgtBlogId, $attachmentsIds);

    update_post_meta($postId, '_elementor_page_settings', $data);
}


function walkThroughElementorDataAndReplaceAttachmentsUrls($data, $srcBlogId, $trgtBlogId, $attachmentsIds=null)
{
    if (!is_array($data)) return $data;

    if (count($data) === 2 && isset($data['id']) && isset($data['url'])) {
        $oldId = intval($data['id']);

        $isOldUrlValid = (bool) strstr($data['url'], "/uploads/sites/$srcBlogId/");
        if ($oldId && $isOldUrlValid) {

            if (is_array($attachmentsIds)) {
                if (!isset($attachmentsIds[$oldId])) return $data;
                
                $newId = $attachmentsIds[$oldId];
            } else {
                $newId = $oldId;
            }
            
            switch_to_blog($trgtBlogId);
            $newAttachmentUrl = wp_get_attachment_url($newId);
            
            $data['id'] = $newId;
            $data['url'] = $newAttachmentUrl;
        }
        
    } else if (isset($data['editor'])) {
        $content = $data['editor'];
        $content = getContentWithUpdatedAttachments($content, $attachmentsIds, $srcBlogId, $trgtBlogId);
        $data['editor'] = $content;
        
    } else {
        foreach ($data as $k => $d) {
            $d = walkThroughElementorDataAndReplaceAttachmentsUrls($d, $srcBlogId, $trgtBlogId, $attachmentsIds);
            $data[$k] = $d;
        }
    }
    
    return $data;
}


function escapeJsonString($value)
{
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}


function copySpecificElementorsFileStructure($srcPageId, $trgtPageId, $srcBlogId, $trgtBlogId)
{
    switch_to_blog($srcBlogId);
    $srcUploadDir = wp_upload_dir();
    $srcUploadDirPath = $srcUploadDir['basedir'];
    $elementorSrcPostCssFilePath = "{$srcUploadDirPath}/elementor/css/post-{$srcPageId}.css";
       
    if (file_exists($elementorSrcPostCssFilePath)) {
        switch_to_blog($trgtBlogId);
        $trgtUploadDir = wp_upload_dir();
        $trgtUploadDirPath = $trgtUploadDir['basedir'];
        $elementorTrgtPostCssFilePath = "{$trgtUploadDirPath}/elementor/css/post-{$trgtPageId}.css";
        
        fullCopyFiles($elementorSrcPostCssFilePath, $elementorTrgtPostCssFilePath);
        
        if (file_exists($elementorTrgtPostCssFilePath)) {
            $cssString = file_get_contents($elementorTrgtPostCssFilePath);
            $cssString = replaceCssSelectorsBasedOnPageId($cssString, $srcPageId, $trgtPageId);
            file_put_contents($elementorTrgtPostCssFilePath, $cssString);
        }
    }
}



function replaceCssSelectorsBasedOnPageId($cssString, $srcPageId, $trgtPageId)
{
    $regex = "/([a-zA-Z0-9].-)({$srcPageId})([^\(\d;}\)].)/";
    $replacement = '${1}' . $trgtPageId . '${3}';

    $cssString = preg_replace($regex, $replacement, $cssString);
    
    return $cssString;
}


function replaceSelectorsInDbElementorCss($lpTmplId, $newPageId, $trgtBlogId)
{
    switch_to_blog($trgtBlogId);
    
    $cssArray = get_post_meta($newPageId, '_elementor_css');
    
    if (!is_array($cssArray)) return;
    
    $cssArray = walkThroughElementorCssArrayAndReplaceSelectors($cssArray, null, $lpTmplId, $newPageId);

    update_post_meta($newPageId, '_elementor_css', $cssArray);
    
    restore_current_blog();
}


function walkThroughElementorCssArrayAndReplaceSelectors($value, $key, $srcPageId, $trgtPageId)
{
    if (is_string($value) && $key === 'css') {
        $value = replaceCssSelectorsBasedOnPageId($value, $srcPageId, $trgtPageId);
    }
    
    if (is_array($value)) {
        foreach ($value as $key => $val) {
            $value[$key] = walkThroughElementorCssArrayAndReplaceSelectors($val, $key, $srcPageId, $trgtPageId);
        }
    }
    
    return $value;
}


function getAttachmentsIdsFromElementorCss($postId, $srcBlogId, $trgtBlogId)
{
    switch_to_blog($trgtBlogId);
    
    $uploadDir = wp_upload_dir();
    if (is_array($uploadDir) && isset($uploadDir['basedir'])) {
        $uploadDir = $uploadDir['basedir'];
    }
    
    $cssPath = $uploadDir . "/elementor/css/post-$postId.css";
    
    if (file_exists($cssPath)) {
        $cssString = file_get_contents($cssPath);
        
        $ids = getAttachmentsIdsMapFromQuotedSubstring($cssString, $srcBlogId);
    }
   
    return $ids;
}


function getAttachmentsIdsMapFromQuotedSubstring($content, $srcBlogId)
{
    $regexes = [
        '/"[^\("\'\)]+\/uploads\/sites\/' . $srcBlogId  . '\/([^\("\'\)]+)"/i',
        "/'[^\(\"'\)]+\/uploads\/sites\/$srcBlogId\/([^\(\"'\)]+)'/i"
    ];
    
    $aIds = [];
    $attachmentsSuffixes = [];
    foreach ($regexes as $r) {
        $isMatched = preg_match_all($r, $content, $matches);
        if ($isMatched && isset($matches[1])) {
            
            if (is_array($matches[1])) {
                foreach ($matches[1] as $attachmentPathSuffix) {
                    $attachmentsSuffixes[] = $attachmentPathSuffix;
                }
            } else {
                $attachmentsSuffixes[] = $matches[1];
            }
        }
    }
    
    foreach ($attachmentsSuffixes as $suffix) {
        $attachId = getAttachmentIdByPathSuffix($suffix, $srcBlogId);
        if ($attachId) {
            $aIds[] = $attachId;
        }
    }
    
    return $aIds;
}


function replaceAttachmentsUrlsInElementorCss($trgtPageId, $srcBlogId, $trgtBlogId, $attachmentsIds=null)
{
    switch_to_blog($trgtBlogId);
    
    $uploadDir = wp_upload_dir();
    if (is_array($uploadDir) && isset($uploadDir['basedir'])) {
        $uploadDir = $uploadDir['basedir'];
    }
    
    $cssPath = $uploadDir . "/elementor/css/post-$trgtPageId.css";
    
    replaceAttachmentsUrlsInElementorCssFile($cssPath, $srcBlogId, $trgtBlogId, $attachmentsIds);
}


function replaceAttachmentsUrlsInElementorCssFile($cssPath, $srcBlogId, $trgtBlogId, $attachmentsIds=null)
{
    if (!file_exists($cssPath)) return;
    
    $cssString = file_get_contents($cssPath);
    
    $regexes = [
        '/"([^\("\'\)]+\/uploads\/sites\/' . $srcBlogId  . '\/([^\("\'\)]+))"/i',
        "/'([^\(\"'\)]+\/uploads\/sites\/$srcBlogId\/([^\(\"'\)]+))'/i",
        "/url\(([^\({});\)]+\/uploads\/sites\/$srcBlogId\/([^\({});\)]+))\);/",
    ];

    $urlsReplacementMap = [];

    foreach ($regexes as $regex) {
        if (!preg_match_all($regex, $cssString, $matches)) continue;

        foreach ($matches[1] as $idx => $oldUrl) {
            $suffix = $matches[2][$idx];

            $oldAttachmentId = getAttachmentIdByPathSuffix($suffix, $srcBlogId);
            if (!$oldAttachmentId) continue;
            
            if (is_array($attachmentsIds)) {
                if (!isset($attachmentsIds[$oldAttachmentId])) continue;
                
                $newAttachmentId = $attachmentsIds[$oldAttachmentId];
            } else {
                $newAttachmentId = $oldAttachmentId;
            }

            switch_to_blog($trgtBlogId);
            $newAttachmentUrl = wp_get_attachment_url($newAttachmentId);

            $urlsReplacementMap[$oldUrl] = $newAttachmentUrl;
        }
    }
    
    $cssString = str_replace(
        array_keys($urlsReplacementMap),
        array_values($urlsReplacementMap),
        $cssString
    );
    
    file_put_contents($cssPath, $cssString);
}


/**
 *  Copy elementor globa.css file of catalog of templages of land-pages to all users catalogs of land-pages
 */
function propagateElementorGlobalCssToAllLpCatalogsApi()
{
    checkAuthorizationByToken();

    try {

        $catalogOfLpTemplates = getLpCatalogBySubdomain('templatelands');
        if (!is_array($catalogOfLpTemplates)) {
            returnError(463, 'Catalog of templates of land-pages not found');
        }
        
        $catalogGlobalCssPath = getLpCatalogElementorCssPath($catalogOfLpTemplates['blog_id']) . '/global.css';
        if (!is_file($catalogGlobalCssPath)) {
            returnError(464, 'File global.css was not found for the catalog of templates of land-pages');
        }
        
        foreach (wp_get_sites(['limit' => 9999]) as $s) {
            $blogId = $s['blog_id'];
            
            $blogType = get_blog_option($blogId, 'uco_blog_type');
        
            if ($blogType !== 'landing-pages-catalog' || $blogId === $catalogOfLpTemplates['blog_id']) continue;
          
            $blogGlobalCssPath = getLpCatalogElementorCssPath($blogId) . '/global.css';

            fullCopyFiles($catalogGlobalCssPath, $blogGlobalCssPath);
        }
    
    } catch (\Exception $ex) {
        returnError(500, 'Cannot propagate global.css');
    }
    
    die( json_encode( ['status' => 'Ok'] ) );
}
add_action( 'wp_ajax_propagateElementorGlobalCssToAllLpCatalogs', 'propagateElementorGlobalCssToAllLpCatalogsApi' );
add_action( 'wp_ajax_nopriv_propagateElementorGlobalCssToAllLpCatalogs', 'propagateElementorGlobalCssToAllLpCatalogsApi' );



function getLpCatalogElementorCssPath($blogId)
{
    switch_to_blog($blogId);
            
    $uploadDir = wp_upload_dir();
    if (is_array($uploadDir) && isset($uploadDir['basedir'])) {
        $uploadDir = $uploadDir['basedir'];
    }
    $elementorGlobalCssFilePath = "{$uploadDir}/elementor/css";
        
    restore_current_blog();
    
    return $elementorGlobalCssFilePath;
}



function generateToken($input)
{
    $token = '';
    $input = 'dlsf#869*?' . random_int(0, 1000000) . $input;
    $hash = hash('sha256', $input);
    
    $alphanumStr = str_replace(['/', '+', '='], '', base64_encode($hash));
    $alphanumStr = substr( $alphanumStr, 0, 16);
    
    $arr = str_split($alphanumStr, 4);
    
    $token = implode('-', $arr);

    return $token;
}


function createUserAuthToken($user)
{
    $token = generateToken($user->user_login);
    $tokenHash = hash('sha256', $token);
    
    $success = update_user_meta( $user->ID, 'authTokenHash', $tokenHash, true );
    if (!$success) {
        returnError(463, 'Cannot save token');
    }
    
    return $token;
}



function checkIsSlugAvailableApi()
{
    global $wpdb;
    checkAuthorizationByToken();
    
    $blogExists = false;
    $blogId = isset( $_POST['blogId'] ) ? (int) $_POST['blogId'] : null;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blogId' is required");
    }
    
    if (!$blogExists) {
        returnError(461, "Blog with id: $blogId not found");
    }

    switch_to_blog($blogId);
    
    $pageId = isset($_POST['pageId']) ? (int) $_POST['pageId'] : 0;
    
    $slug = isset( $_POST['slug'] ) ? trim($_POST['slug']) : null;
    
    if (!$slug) {
        returnError(400, "Parameter 'slug' is required");
    }
    
    $sanitizedSlug = sanitize_title($slug);

    if (urldecode($sanitizedSlug) !== $slug) {
        returnError(462, "Slug has wrong format");
    }
    
    $checkSql = "SELECT post_name FROM $wpdb->posts WHERE post_name = %s AND ID != %d";
    $result = $wpdb->get_var( $wpdb->prepare( $checkSql, $sanitizedSlug, $pageId ) );

    $isAvailable = !$result;
    
    restore_current_blog();
   
    die( json_encode( ['isAvailable' => $isAvailable] ) );
}
add_action( 'wp_ajax_checkSlug', 'checkIsSlugAvailableApi' );
add_action( 'wp_ajax_nopriv_checkSlug', 'checkIsSlugAvailableApi' );


function cloneBlogForUserApi()
{
    $result = [];
    
    checkAuthorizationByToken();

    $subdomain = strtolower( trim($_POST['subdomain']) );
    if (!$subdomain) {
        returnError(400, "Parameter 'subdomain' is required");
    }
    
    if (preg_match('/^(?![-.])[\p{Hebrew}0-9a-z.-]+(?<![-.])$/ui', $subdomain)) {
        $subdomain = idn_to_ascii($subdomain, 0, INTL_IDNA_VARIANT_UTS46);
    }
    
    $title = trim($_POST['title']);
    if (!$title) {
        returnError(400, "Parameter 'title' is required");
    }
    
     if (preg_match('/`/', $title)) {
        returnError(463, 'Title contains wrong symbols');
    }
    
    $userEmail = trim($_POST['email']);
    if ($userEmail) {
        $user = get_user_by('email', $userEmail);
        if (!$user) {
            returnError(460, "User with email: '{$userEmail}' not found.");
        }
        
    } else {
        returnError(400, "Parameter 'email' is required");
    }
    
    $copyCodes = isset($_POST['copyCodes']) ? (bool) trim($_POST['copyCodes']) : false;
    
    $userBlogsIds = getUserBlogsIds($user->ID);
    
    $originBlogId = isset($_POST['originalBlogId']) ? (int) trim($_POST['originalBlogId']) : null;
    if ($originBlogId === null) {
        returnError(400, "Parameter 'originalBlogId' is required");
    }
    
    $cloneOpts = [
        'subdomain' => $subdomain,
        'tmplBlogId' => $originBlogId,
        'email' => $userEmail,
        'title' => $title
    ];
    
    try {

        if (!isSubdomainValid($subdomain)) {
            returnError(461, 'Wrong subdomain');
        }
        
        if (!in_array($originBlogId, $userBlogsIds, true)) {
            returnError(462, "Original blog with ID: $originBlogId  not found for user: $userEmail");
        }
        
        $blogId = cloneBlog($cloneOpts);
     
        if (!$blogId) {
            returnError(500, 'Cannot create blog');
        }
        
        switch_to_blog($blogId);
        
        replaceAttachmentsUrlsAfterBlogCloned($originBlogId, $blogId);
        
        assignRedirectToThanksPageForBlog($blogId);
        
        $result['blogId'] = $blogId;
        $result['blogUrl'] = get_site_url($blogId);
        $result['blogAdminUrl'] = get_admin_url($blogId);
        
        // removes scripts if need
        if (!$copyCodes) {
            $scriptsApi = new WebGroupApi\HeaderFooterScriptsApi();
            $scriptsApi->removeAllBlogScripts($blogId);
        }
        
        // try to activate license for Elementor Pro
        try {
            activateElementorProLicenseForBlog($blogId);
            $result['elementorProLicenseActivated'] = true;
        } catch (Exception $ex) {
            $result['elementorProLicenseActivated'] = false;
        }
        
    } catch (\Exception $ex) {
        returnError(500, 'Cannot create blog');
    }
    
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_cloneBlogForUser', 'cloneBlogForUserApi' );
add_action( 'wp_ajax_nopriv_cloneBlogForUser', 'cloneBlogForUserApi' );


function getUserBlogsIds($userId)
{
    $ids = [];
    
    $blogs = getProjectsForUser($userId, 'ONLY_BLOGS');
    foreach ($blogs as $blog) {
        $ids[] = (int) $blog->blog_id;
    }
    
    return $ids;
}


function cloneLpForUserApi()
{
    $result = [];
    
    global $wpdb;
    
    $requiredParams = ['title', 'email', 'originalPageId', 'targetPageSlug'];
    foreach ($requiredParams as $rParam) {
        if (!(isset($_POST[$rParam]) && trim($_POST[$rParam]))) {
            returnError(400, "Param: '$rParam' is required");
        }
    }
    
    checkAuthorizationByToken();
    
    $copyCodes = isset($_POST['copyCodes']) ? (bool) trim($_POST['copyCodes']) : false;
    
    $userEmail = trim($_POST['email']);
    $user = get_user_by_email($userEmail);
    if (!$user) {
        returnError(460, "User with email: '{$userEmail}' not found.");
    }
              
    // check if user already have catalog for land-pages
    $userLpCatalog = getLpCatalogForUser($user->ID);
    $userLpCatalogId = $userLpCatalog->blog_id;
    if (!$userLpCatalogId) {
        returnError(461, "There is no land-pages catalog found for user: '{$userEmail}'");
    }
    
    switch_to_blog($userLpCatalogId);
    
    $originLpId = (int) trim($_POST['originalPageId']);
    $originPage = get_post($originLpId);
    if ( !($originPage && $originPage->post_type === 'page') ) {
        returnError(462, "Original landing page with id: '$originLpId' not found for user: $userEmail");
    }
    
    $newPageSlug = trim($_POST['targetPageSlug']);

    if (!preg_match('/^[\p{Hebrew}0-9a-z_\-]+$/ui', $newPageSlug)) {
        returnError(465, "Page slug: '$newPageSlug' has wrong format");
    }

    // check is target page's slug already occupied
    $newPageSlugEncoded = strtolower( urlencode($newPageSlug) );
    switch_to_blog($userLpCatalogId);
    foreach (get_posts(['post_type' => 'any', 'numberposts' => -1]) as $pst) {
        if ($pst->post_name === $newPageSlugEncoded) {
            returnError(464, "Slug: '$newPageSlug' is already occupied");
        }
    }
  
    $result = fullCloneLp($originLpId, $newPageSlug, $userLpCatalogId, $userLpCatalogId);
    
    $newLpId = $result['pageId'];
    
    $thanksPageData = assignThanksPage($newLpId, $originLpId, $userLpCatalogId, $userLpCatalogId);
    
    // removes scripts if need
    if (!$copyCodes) {
        $scriptsApi = new WebGroupApi\HeaderFooterScriptsApi();
        $scriptsApi->removePageScripts($userLpCatalogId, $newLpId);
        if (is_array($thanksPageData) && !empty($thanksPageData['pageId'])) {
            $scriptsApi->removePageScripts($userLpCatalogId, $thanksPageData['pageId']);
        }
    }
    
    $result['thanksPage'] = $thanksPageData;
    
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_cloneLpForUser', 'cloneLpForUserApi' );
add_action( 'wp_ajax_nopriv_cloneLpForUser', 'cloneLpForUserApi' );


function deleteObsoleteBlogTablesFromDbApi()
{
    checkAuthorizationByToken();
    
    global $wpdb;
    $result = [
        'all_before' => count( _getDbBlogsTablesNames() ),
        'for_removed' => count( _getDbBlogsTablesNames('FOR_NON_EXISTED_BLOGS') ),
//        'obsolete_tables_names' => _getDbBlogsTablesNames('FOR_NON_EXISTED_BLOGS')
    ];
    
    $tblsNames = _getDbBlogsTablesNames('FOR_NON_EXISTED_BLOGS');
    
//    foreach ($tblsNames as $tblName) {
//        $wpdb->query( "DROP TABLE IF EXISTS $tblName ;" );
//    }
    
    $result['status'] = 'Ok';
   
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_deleteObsoleteBlogTablesFromDb', 'deleteObsoleteBlogTablesFromDbApi' );
add_action( 'wp_ajax_nopriv_deleteObsoleteBlogTablesFromDb', 'deleteObsoleteBlogTablesFromDbApi' );


/*
 *  Get IDs of blogs which uses specific theme.
 */
function getBlogsThemeUsedApi()
{
    checkAuthorizationByToken();
    
    $result = [];
    
    $theme = trim($_GET['theme']);

    global $wpdb;

    // get all blogs
    $blogs = get_sites(['number' => 10000]);

    foreach ($blogs as $blog) {
        $blogId = $blog->blog_id;
        
        switch_to_blog($blogId);

        $activeTheme = wp_get_theme();
        
        if ($theme === $activeTheme->stylesheet) {
            $result[] = (int) $blogId;
        }
    }
   
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_getBlogsThemeUsed', 'getBlogsThemeUsedApi' );
add_action( 'wp_ajax_nopriv_getBlogsThemeUsed', 'getBlogsThemeUsedApi' );


/*
 *  Remove emails info (To, From, Reply) from all Pojo forms from all blogs
 */
function removeEmailInfoForPojoFormsApi()
{
    if (is_multisite()) {
        checkAuthorizationByToken();
    }
   
    $result = [];

    if (is_multisite()) {
        // get all blogs
        $blogs = get_sites(['number' => 10000]);

        foreach ($blogs as $blog) {
            $blogId = (int) $blog->blog_id;

            $deletedPostMetaIds = _removeEmailInfoForPojoFormsFromBlog($blogId);

            if ($deletedPostMetaIds) {
                $result[] = ['blogId' => $blogId, 'deletedPostMetaIds' => $deletedPostMetaIds];
            }
        }
        
    } else {
        $deletedPostMetaIds = _removeEmailInfoForPojoFormsFromBlog();
        
        if ($deletedPostMetaIds) {
            $result = ['deletedPostMetaIds' => $deletedPostMetaIds];
        } 
    }
   
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_removeEmailInfoForPojoForms', 'removeEmailInfoForPojoFormsApi' );
add_action( 'wp_ajax_nopriv_removeEmailInfoForPojoForms', 'removeEmailInfoForPojoFormsApi' );


/*
 * Enable meta-keywords for all blogs
 */
function enableSeoKeywords()
{
    if (is_multisite()) {
        checkAuthorizationByToken();
    }

    $result = [];

    // get all blogs
    $blogs = get_sites(['number' => 10000]);

    foreach ($blogs as $blog) {
        $blogId = (int) $blog->blog_id;

        $seoTitles = get_blog_option($blogId, 'wpseo_titles');
        
        if (is_array($seoTitles) && isset($seoTitles['usemetakeywords']) && !$seoTitles['usemetakeywords']) {
            $seoTitles['usemetakeywords'] = true;
            update_blog_option($blogId, 'wpseo_titles', $seoTitles);
            $result['enabledForBlogs'][] = $blogId;
        }
    }
        
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_enableSeoKeywords', 'enableSeoKeywordsApi' );
add_action( 'wp_ajax_nopriv_enableSeoKeywords', 'enableSeoKeywords' );


function _removeEmailInfoForPojoFormsFromBlog($blogId=null)
{

    global $wpdb;
    $pojoFormsEmailsFields = '"form_email_to","form_email_reply_to","form_email_form"';
    
    if (is_multisite()) {
        switch_to_blog($blogId);
    }
    
    // find posts which are Pojo-forms
    $formsIds = [];
    $query = "SELECT ID FROM $wpdb->posts WHERE post_type = 'pojo_forms' ";
    $qResult = $wpdb->get_results($query);

    if (!count($qResult)) return null;

    foreach ($qResult as $row) {
        $formsIds[] = $row->ID;
    }

    $formsIds = join(',', $formsIds);

    // find post mete which are pojo-forms email params
    $postmetaIds = [];
    $query = "SELECT meta_id FROM $wpdb->postmeta WHERE post_id IN ($formsIds) AND meta_key IN ($pojoFormsEmailsFields) ;";
    $qResult = $wpdb->get_results($query);

    if (!count($qResult)) return null;

    foreach ($qResult as $row) {
        $postmetaIds[] = (int) $row->meta_id;
    }
        
    // Delete found rows
//    $postmetaIdsStr = join(',', $postmetaIds);
//    $query = "DELETE FROM $wpdb->postmeta WHERE meta_id IN ($postmetaIdsStr) ;";
//    $qResult = $wpdb->get_results($query);
    
            
    return $postmetaIds;
}


function activateElementorProLicenseApi()
{
    $result = ['Ok'];
 
    checkAuthorizationByToken();
    
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
   
    activateElementorProLicenseForBlog($blogId);
   
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_activateElementorProLicense', 'activateElementorProLicenseApi' );
add_action( 'wp_ajax_nopriv_activateElementorProLicense', 'activateElementorProLicenseApi' );


function activateElementorProLicenseForAllBlogsApi()
{
    $result = ['status' => 'Ok'];
        
    checkAuthorizationByToken();
    
    _checkIsElementorProAvailable();
    
    $licenseKey =  _getMultisiteElementorProLicenseKey();
    
    $isForced = isset( $_GET['isForced'] ) ? (int) $_GET['isForced'] : false;
    
    $blogsIds = [];
    $blogs = get_sites(['number' => 9999]);
    foreach ($blogs as $blog) {
        $blogId = (int) $blog->blog_id;
        
        if (!$isForced) {
            switch_to_blog($blogId);
            $blogLicenseKey = ElementorPro\License\Admin::get_license_key();
            
            if ($blogLicenseKey === $licenseKey) continue;
        }
        
        $blogsIds[] = $blogId;
    }
   
    $processed = [];
    foreach ($blogsIds as $blogId) {
        activateElementorProLicenseForBlog($blogId);
        $processed[] = $blogId;
    }
    
    $result['selectedForActivation'] = $blogsIds;
    $result['activated'] = $processed;
    
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_activateElementorProLicenseForAllBlogs', 'activateElementorProLicenseForAllBlogsApi' );
add_action( 'wp_ajax_nopriv_activateElementorProLicenseForAllBlogs', 'activateElementorProLicenseForAllBlogsApi' );




function activateElementorProLicenseForBlog($blogId, $domain=null)
{

    $blogExists = false;
    
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        returnError(400, "Blog with id: $blogId not found");
    }
    
    switch_to_blog($blogId);

    $license_key =  _getMultisiteElementorProLicenseKey();
   
    $error_msg = '';
 
    _checkIsElementorProAvailable();
   
    if (empty($domain)) {
        $domain = getPrimaryDomain($blogId);
    }
    
    $data = requestToActivateElementorProLicenseForDomain($domain);

    if ( is_wp_error( $data ) ) {
        returnError(471, "Cannot activate Elementor-Pro license for blog: $blogId");
    }

    if ( ElementorPro\License\API::STATUS_VALID !== $data['license'] ) {
        $errors = [
            'no_activations_left' => __( 'You have no more activations left. Please upgrade your licence for auto updates.', 'elementor-pro' ),
            'expired' => __( 'Your license has expired. Please renew your licence in order to get auto-updates.', 'elementor-pro' ),
            'missing' => __( 'Your license is missing. Please check your key again.', 'elementor-pro' ),
            'revoked' => __( 'Your license has been revoked.', 'elementor-pro' ),
            'item_name_mismatch' => sprintf( __( 'Your license has a name mismatch. Please go to <a href="%s" target="_blank">your purchases</a> and choose the proper key.', 'elementor-pro' ), 'https://go.elementor.com/my-account/' ),
        ];

        if ( isset( $errors[ $data['error'] ] ) ) {
            $error_msg = $errors[ $data['error'] ];
        } else {
            $error_msg = __( 'An error occurred, please try again', 'elementor-pro' ) . ' (' . $data->error . ')';
        }
    }
    
    if ($error_msg) {
        returnError(472, "Blog: $blogId. Elementor-Pro license activation error: $error_msg");
    }

    try {
        ElementorPro\License\Admin::set_license_key( $license_key );
        ElementorPro\License\API::set_license_data( $data );
    } catch (\Exception $ex) {
        returnError(472, "Blog: $blogId. Elementor-Pro license activation error: cannot save data in DB");
    }
        
}



function _getMultisiteElementorProLicenseKey()
{
    $licenseKey =  get_blog_option(1, 'elementorProLicenseKey', null);
    if (!$licenseKey) {
        returnError(470, "Elementor-Pro license key not found");
    }
    
    return $licenseKey;
}



function _checkIsElementorProAvailable()
{
    if ( !(class_exists('ElementorPro\License\API') && class_exists('ElementorPro\License\Admin')) ) {
        returnError(472, "Elementor-Pro not found");
    }
}


function requestToActivateElementorProLicenseForDomain($domain)
{
    $schema = is_ssl() ? 'https://' : 'http://';
    $url = $schema . $domain;
    
    $license_key = _getMultisiteElementorProLicenseKey();
   
    $body_args = wp_parse_args(
        $body_args,
        [
            'api_version' => ELEMENTOR_PRO_VERSION,
            'item_name' => ElementorPro\License\API::PRODUCT_NAME,
            'site_lang' => get_bloginfo( 'language' ),
            'url' => $url,
            'edd_action' => 'activate_license',
            'license' => $license_key,
        ]
    );

    $response = wp_remote_post( ElementorPro\License\API::STORE_URL, [
        'sslverify' => false,
        'timeout' => 40,
        'body' => $body_args,
    ] );

    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $response_code = wp_remote_retrieve_response_code( $response );
    if ( 200 !== (int) $response_code ) {
        return new \WP_Error( $response_code, __( 'HTTP Error', 'elementor-pro' ) );
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( empty( $data ) || ! is_array( $data ) ) {
        return new \WP_Error( 'no_json', __( 'An error occurred, please try again', 'elementor-pro' ) );
    }
    
    return $data;
}


function cleanElementorProLicenseCodeFromDbForAllApi()
{
    $result = ['Ok'];
 
    checkAuthorizationByToken();
    
    $blogs = get_sites(['number' => 9999]);
    foreach ($blogs as $blog) {
        $blogId = (int) $blog->blog_id;
        
        update_blog_option($blogId, 'elementor_pro_license_key', null);
    }
   
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_cleanElementorProLicenseCodeFromDbForAll', 'cleanElementorProLicenseCodeFromDbForAllApi' );
add_action( 'wp_ajax_nopriv_cleanElementorProLicenseCodeFromDbForAll', 'cleanElementorProLicenseCodeFromDbForAllApi' );




function deleteObsoleteUploadsBlogsDirectoriesApi()
{
    checkAuthorizationByToken();
   
    $blogsIds = [];
    $allUploadsDirs = [];
    $directoriesToRemove = [];
    $blogsWithoutDirs = [];
    
    $blogs = get_sites(['number' => 1000000]);
    foreach ($blogs as $blog) {
        $blogsIds[] = (int) $blog->blog_id;
    }
    
    $uploadDir = wp_upload_dir();
    if (is_array($uploadDir) && isset($uploadDir['basedir'])) {
        $uploadDir = $uploadDir['basedir'];
        
    }
    // find sites uploads dir
    $uploadsSitesDir = $uploadDir . '/sites';
    if (!is_dir($uploadsSitesDir)) {
        returnError(404, 'Directory for sites uploads does not found.');
    }
    
    foreach (scandir($uploadsSitesDir) as $dir) {
        if (in_array($dir, ['.', '..'])) continue;
        
        $dirToRemovePath = $uploadsSitesDir . '/' . $dir;
        
        if (!is_dir($dirToRemovePath)) continue;
        
        $allUploadsDirs[] = $dir;
        
        if (!is_numeric($dir)) continue;
        if (in_array((int) $dir, $blogsIds)) continue;
        
        $directoriesToRemove[] = $dirToRemovePath;
    }
    
    
    foreach ($blogsIds as $blogId) {
        if (in_array((string) $blogId, $allUploadsDirs)) continue;
        
        $blogsWithoutDirs[] = $blogId;
    }
    
    
//    foreach ($directoriesToRemove as $dir) {
//        exec("rm -rf $dir");
//    }
    
    $result = [
        'uploadDirsCountBeforeRemoved' => count($allUploadsDirs),
        'blogsCount' => count($blogsIds),
        'removedDirsCount' => count( $directoriesToRemove ),
        'blogsWithoutDirs' => $blogsWithoutDirs
    ];
    
    $result['status'] = 'Ok';
   
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_deleteObsoleteUploadsBlogsDirectories', 'deleteObsoleteUploadsBlogsDirectoriesApi' );
add_action( 'wp_ajax_nopriv_deleteObsoleteUploadsBlogsDirectories', 'deleteObsoleteUploadsBlogsDirectoriesApi' );



function deleteBlogsUploadsTumbsDirectoriesApi()
{
    checkAuthorizationByToken();
    
    $directoriesToRemove = [];
    
    $uploadDir = wp_upload_dir();
    if (is_array($uploadDir) && isset($uploadDir['basedir'])) {
        $uploadDir = $uploadDir['basedir'];
    }
    // find sites uploads dir
    $uploadsSitesDir = $uploadDir . '/sites';
    if (!is_dir($uploadsSitesDir)) {
        returnError(404, 'Directory for sites uploads does not found.');
    }
    
    foreach (scandir($uploadsSitesDir) as $dir) {
        if (in_array($dir, ['.', '..'])) continue;
        if (!is_numeric($dir)) continue;
        
        $dirToRemovePath = $uploadsSitesDir . "/$dir/thumbs";
        
        if (!is_dir($dirToRemovePath)) continue;
        
        $directoriesToRemove[] = $dirToRemovePath;
    }
    
//    foreach ($directoriesToRemove as $dir) {
//        exec("rm -rf $dir");
//    }
    
    $result = [
        'removed' => $directoriesToRemove
    ];
    
    $result['status'] = 'Ok';
   
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_deleteBlogsUploadsTumbsDirectories', 'deleteBlogsUploadsTumbsDirectoriesApi' );
add_action( 'wp_ajax_nopriv_deleteBlogsUploadsTumbsDirectories', 'deleteBlogsUploadsTumbsDirectoriesApi' );


/*
 *  Get info from www.fixdigital.co.il about existed sites and LP-catalogs  and compare it with info about
 *  existed blogs in current multisite.
 */
function compareInfoAboutBlogsFromWpAndFixApi()
{
    checkAuthorizationByToken();
    
    $result = [];
    
    if (empty($_GET['multisiteId'])) {
        returnError(400, 'multisiteId required');
    }
    $multisiteId = $_GET['multisiteId'];
    
    $url = "www.fixdigital.co.il/Projects/GetAllWPSitesOuterIds?multisiteId=$multisiteId";
    
    $sitesIdsFromFix = makeRequestToGetJsonResultAsArray($url);
    if (!is_array($sitesIdsFromFix)) {
        $sitesIdsFromFix = [];
    }
    
    $lpCatalogIdsFromFix = [];
    if (intval($multisiteId) === 1) {
        $url = 'www.fixdigital.co.il/Projects/GetAllWPCatalogsOuterIds';
        $lpCatalogsIdsFromFix = makeRequestToGetJsonResultAsArray($url);
        
        if (!is_array($lpCatalogsIdsFromFix)) {
            $lpCatalogsIdsFromFix = [];
        }
    }
    
    $allBlogsIdsInFix = array_merge($sitesIdsFromFix, $lpCatalogsIdsFromFix);
    
    $blogsAreOnlyPresentInWp = [];
    $allBlogsInWp = get_sites(['number' => 10000000]);
    foreach ($allBlogsInWp as $bData) {
        $bId = (int) $bData->blog_id;
        $bDomain = $bData->domain;
        
        if (in_array($bId, $allBlogsIdsInFix, true)) continue;
        
        $blogsAreOnlyPresentInWp[] = ['id' => $bId, 'domain' => $bDomain];
    }
    
    $allBlogsIdsInWp = array_map(function($blog){
        return (int) $blog->blog_id;
    }, $allBlogsInWp);
 
    $sitesAreOnlyPresentInFix = array_values( array_diff($sitesIdsFromFix, $allBlogsIdsInWp) );
    $lpCatalogsAreOnlyPresentInFix = array_values(  array_diff($lpCatalogsIdsFromFix, $allBlogsIdsInWp) );
    
    $result = [
        'countSitesInFix' => count($sitesIdsFromFix),
        'countLpCatalogsInFix' => count($lpCatalogsIdsFromFix),
        'countBlogsInWp' => count($allBlogsInWp),
        'blogsAreOnlyPresentInWp' => $blogsAreOnlyPresentInWp,
        'sitesAreOnlyPresentInFix ' => $sitesAreOnlyPresentInFix,
        'lpCatalogsAreOnlyPresentInFix' => $lpCatalogsAreOnlyPresentInFix
    ];
   
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_compareInfoAboutBlogsFromWpAndFix', 'compareInfoAboutBlogsFromWpAndFixApi' );
add_action( 'wp_ajax_nopriv_compareInfoAboutBlogsFromWpAndFix', 'compareInfoAboutBlogsFromWpAndFixApi' );


function makeRequestToGetJsonResultAsArray($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);

    $res=curl_exec($ch);

    curl_close($ch);

    $blogsIds = json_decode($res, true);
    
    
    return $blogsIds;
}


function deleteUsersApi()
{
    checkAuthorizationByToken();
    
    $usersToRemove = [];
    $dontFoundUsers = [];
    $cannotRemoveUsers = [];
    $removedUsers = [];
    $usersWithBlogs = [];
    
    $superAdmin = get_user_by('email', 'ucoadmin@uco.co.il');
    if (!$superAdmin) {
        returnError(404, 'Superadmin: ucoadmin@uco.co.il not found.');
    }
    
    $users =  !empty($_POST['users']) ? trim($_POST['users']) : '';
    $limit =  !empty($_GET['limit']) ? intval($_GET['limit']) : null;
    
    $users = explode(',', $users);
    foreach ($users as $email) {
        $userBlogs = [];
        $email = trim($email);
        $user = get_user_by('email', $email);
        if (!$user) {
            $dontFoundUsers[] = $email;
            continue;
        }
        
        if ($limit && count($usersToRemove) >= $limit) break;
        
        $usersToRemove[] = [
            'id' => (int) $user->ID,
            'email' => $email,
        ];
        
        $userBlogs = get_blogs_of_user($user->ID);
        $userBlogsData = [];
        foreach ($userBlogs as $i => $uBlog) {
            $userBlogsData[$uBlog->userblog_id] = $uBlog->domain;
        }
        
        if (count($userBlogs)) {
            $usersWithBlogs[$email] = $userBlogsData;
        }
    }
    
    // remove users
    foreach ($usersToRemove  as $uData) {
//        $res = wpmu_delete_user($uData['id']);
        
        if ($res) {
            $removedUsers[] = $uData['email'];
        } else {
            $cannotRemoveUsers[] = $uData['email'];
        }
    }
    
    $result = [
        'countUsersToRemove' => count($usersToRemove),
        'countRemovedUsers' => count($removedUsers),
        'dontFoundUsers' => $dontFoundUsers,
        'usersWithBlogs' => $usersWithBlogs,
        'usersToRemove' => $usersToRemove,
        'removedUsers' => $removedUsers,
        'cannotRemoveUsers' => $cannotRemoveUsers,
        'limit' => $limit
    ];
    
    $result['status'] = 'Ok';
   
    die( json_encode( $result ) );
}
add_action( 'wp_ajax_deleteUsers', 'deleteUsersApi' );
add_action( 'wp_ajax_nopriv_deleteUsers', 'deleteUsersApi' );

