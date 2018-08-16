<?php
/*
Plugin Name: Fixdigital for Beginners API
Description: API to manage Fixdigital blogs for beginners
Version: 0.2.9
Author: satuskam
Author URI: atuskam@gmail.com
*/


// init support of meta field to set types for Elementor templates
require_once ('Api/ElementorTemplates/ElementorTemplates.php');


function _checkIsBlogForBeginners($blogId)
{
    if (!_isBlogForBeginners($blogId)) {
        _returnError(480, "Blog with id: '$blogId' is not for beginner");
    }
}


function _isBlogForBeginners($blogId)
{
    $result = get_blog_option($blogId, 'fixdital_blog_for_beginner');
    $result = $result ? $result : get_blog_option($blogId, 'fixdigital_blog_for_beginners');
    
    return (bool) $result;
}


function _checkAuthorizationByToken()
{
    $token = trim(getWebGroupApiToken());
    
    if (!$token || $token !== $_GET['token']) {
        _returnError(401, 'Wrong token');
    }
}


function _returnError($code, $msg, $json=null)
{
    status_header($code, $msg);
    
    if ($json) {
        die(json_encode($json));
    }
    
    exit;
}



function getElementorTemplatesApi()
{
    try {
        $result = [];
        _checkAuthorizationByToken();

        $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
        if ($blogId) {
            $blogData = get_blog_details( $blogId );

            $blogExists = (bool) $blogData;
        } else {
            _returnError(400, "Parameter 'blog' is required");
        }

        if (!$blogExists) {
            _returnError(461, "Blog with id: $blogId not found");
        }
        
        _checkIsBlogForBeginners($blogId);
        
        switch_to_blog($blogId);
        
        $templates = get_posts(['post_type' => 'elementor_library', 'numberposts' => -1]);
        foreach ($templates as $tmpl) {
            $tmplId = $tmpl->ID;
            $type = get_post_meta($tmplId,'_elementor_template_custom_type',TRUE);
            
            if (!in_array($type, ['footer', 'header', 'sidebar'])) continue;
            
            $link = get_post_permalink( $tmplId );
            
            if ($link) {
                $suffix =  strstr($link, '?') ?  '&elementor' : '?elementor';
                $link .= $suffix;
                
                if (function_exists('fetchLinkWithPrimaryDomain')) {
                    $link = fetchLinkWithPrimaryDomain($link, $blogId);
                }
            }
            
            $result[] = [
                'id' => (int) $tmplId,
                'title' => $tmpl->post_title,
                'type' => $type,
                'link' => $link
            ];
        }
        
        die( json_encode($result) );
        
    } catch (Exception $ex) {
        _returnError(500, 'Cannot get Elementor templates');
    }
}
add_action( 'wp_ajax_getElementorTemplates', 'getElementorTemplatesApi' );
add_action( 'wp_ajax_nopriv_getElementorTemplates', 'getElementorTemplatesApi' );



function getPagesDataApi()
{
    $result = [];
    _checkAuthorizationByToken();
   
    $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        _returnError(400, "Parameter 'blog' is required");
    }
    
    if (!$blogExists) {
        _returnError(461, "Blog with id: $blogId not found");
    }
    
    _checkIsBlogForBeginners($blogId);
    
    $result = getPagesData($blogId);
  
    die( json_encode($result) );
}
add_action( 'wp_ajax_getPagesData', 'getPagesDataApi' );
add_action( 'wp_ajax_nopriv_getPagesData', 'getPagesDataApi' );



function getPagesData($blogId)
{
    $result = [];
    
    switch_to_blog($blogId);
 
    $menuPagesIds = getPagesIdsInMenu($blogId);
    
    $pages = get_pages();
    foreach ($pages as $page) {
        $result[] = getPageData($page, $blogId, $menuPagesIds);
    }
    
    return $result;
}


function getPageData($page, $blogId, $menuPagesIds)
{
    switch_to_blog($blogId);
    
    $pageId = (int) $page->ID;
    $template = get_post_meta( $pageId, '_wp_page_template', true );
    $template = $template ? $template : 'default';

    $result = [
        'id'         => $pageId,
        'title'      => $page->post_title,
        'slug'       => $page->post_name,
        'template'   => $template,
        'menuOrder'  => (int) $page->menu_order,
        'parent'     => (int) $page->post_parent,
        'showInMenu' => in_array($pageId, $menuPagesIds) ? true : false,
        'isHomePage' => _isPageHomepage($blogId, $pageId)
    ];
    
    return $result;
}


function getPageTemplatesApi()
{
    try {
        _checkAuthorizationByToken();

        $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
        if ($blogId) {
            $blogData = get_blog_details( $blogId );

            $blogExists = (bool) $blogData;
        } else {
            _returnError(400, "Parameter 'blog' is required");
        }

        if (!$blogExists) {
            _returnError(461, "Blog with id: $blogId not found");
        }
        
        _checkIsBlogForBeginners($blogId);

        switch_to_blog($blogId);

        $templates = ['Default Template' => 'default'];
        $templates = array_merge( $templates, get_page_templates() );
        
        $templates = array_diff($templates, ['elementor_canvas',  '../public/views/revslider-page-template.php']);
        // remove unwanted templates
        $unwantedTemplates = ['/elementor_canvas$/', '/revslider-page-template.php$/'];
        foreach ($templates as $key => $tmpl) {
            if (!preg_match($unwantedTemplates, $tmpl)) continue;
            unset($templates[$key]);
        }

        die( json_encode($templates) );
        
    } catch (Exception $ex) {
        _returnError(500, "Cannot get page templates");
    }
}
add_action( 'wp_ajax_getPageTemplates', 'getPageTemplatesApi' );
add_action( 'wp_ajax_nopriv_getPageTemplates', 'getPageTemplatesApi' );



function _updatePageByData($pageData, $blogId)
{
    switch_to_blog($blogId);
    
    $data = [
        'ID'          => isset($pageData['pageId']) ? $pageData['pageId'] : $pageData['id'],
        'post_title'  => $pageData['title'],
        'post_parent' => $pageData['parent']
    ];
    
    if (isset($pageData['menuOrder'])) {
        $data['menu_order']  = $pageData['menuOrder'];
    }
    
    if (isset($pageData['slug'])) {
        $data['post_name'] = $pageData['slug'];
    }

    $result = wp_update_post($data, true);

    if (is_wp_error($result)) {
//        $errors = $result->get_error_messages();
//	foreach ($errors as $error) {
//            var_dump( $error );
//	}
        throw new Exception();
    }

}



function updatePageDataApi()
{
    try {
        _checkAuthorizationByToken();

        $params = _preparePageDataRequestParams();
        
        $blogId = $_POST['blogId'];
        $pageId = $params['pageId'];
        
        _checkIsBlogForBeginners($blogId);
       
        switch_to_blog($blogId);
        
        _updatePageByData($params, $blogId);
       
        update_post_meta( $pageId, '_wp_page_template', $params['template'] );
        
        // set page as blog's home page (it it's need)
        if (isset($params['isHomePage']) && $params['isHomePage']) {
            _setPageAsHomepage($blogId, $pageId);
        }
       
        $pagesData = getPagesData($blogId);
        foreach ($pagesData as $idx => $pData) {
            if ($pData['id'] === $pageId) {
                $pagesData[$idx]['showInMenu'] = $params['showInMenu'];
            }
        }
        
        updatePagesMenu($blogId, $pagesData);

        die( json_encode(['status' => 'Ok']) );
    
    } catch (Exception $ex) {
        _returnError(500, "Cannot update page");
    }
}
add_action( 'wp_ajax_updatePageData', 'updatePageDataApi' );
add_action( 'wp_ajax_nopriv_updatePageData', 'updatePageDataApi' );


/*
 *  Get IDs of all child pages for page.
 *  Recursive function (!)
 */
function _getChildPagesIds($pageId, $pagesData, $iterations=0)
{
    $ids = [];
    
    if ($iterations > 1000) {
        _returnError(500, "Too much recursive iterations in _getChildPagesIds()");
    }
    
    foreach ($pagesData as $pData) {
        if ($pData['parent'] === $pageId) {
            $ids[] = $pData['id'];
        }
    }
    
    if (count($ids)) {
        foreach ($ids as $id) {
            $ids = array_merge($ids, _getChildPagesIds($id, $pagesData, $iterations++));
        }
    }
    
    return $ids;
}


function createPageFromDataApi()
{
    try {
        _checkAuthorizationByToken();

        $params = _preparePageDataRequestParams(true);

        $blogId = $params['blogId'];
        
        _checkIsBlogForBeginners($blogId);

        $data = [
            'post_title'  => $params['title'],
//            'post_name'   => $params['slug'],
            'post_parent' => $params['parent'],
            'menu_order'  => $params['menuOrder'],
            'post_type' => 'page',
            'post_content' => '',
            'post_status' => 'publish',
    //	    'post_author' => 1,
    //	    'post_slug' => 'blog'
        ];
        
        if (isset($params['slug'])) {
            $data['post_name'] = $params['slug'];
        }
    
        switch_to_blog($blogId);

        // create new page
        $pageId = wp_insert_post($data, true);
        if (is_wp_error($pageId)) {
            _returnError(500, 'Cannot create page');
        }

        update_post_meta( $pageId, '_wp_page_template', $params['template'] );
        
        // set page as blog's home page (it it's need)
        if (isset($params['isHomePage']) && $params['isHomePage']) {
            _setPageAsHomepage($blogId, $pageId);
        }
        
        // update showInMenu param and regenereate menu
        $pagesData = getPagesData($blogId);
        foreach ($pagesData as $idx => $pData) {
            if ($pData['id'] === $pageId) {
                $pagesData[$idx]['showInMenu'] = $params['showInMenu'];
            }
        }
        updatePagesMenu($blogId, $pagesData);
        
        // get final page data
        $menuPagesIds = getPagesIdsInMenu($blogId);
        $pageData = getPageData(get_page($pageId), $blogId, $menuPagesIds);

        die( json_encode($pageData) );
    
    } catch (Exception $ex) {
        _returnError(500, 'Cannot create page');
    }
    
}
add_action( 'wp_ajax_createPageFromData', 'createPageFromDataApi' );
add_action( 'wp_ajax_nopriv_createPageFromData', 'createPageFromDataApi' );



function updatePagesMenuApi()
{
    try {
        _checkAuthorizationByToken();

        $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
        if ($blogId) {
            $blogData = get_blog_details( $blogId );

            $blogExists = (bool) $blogData;
        } else {
            _returnError(400, "Parameter 'blog' is required");
        }

        if (!$blogExists) {
            _returnError(461, "Blog with id: $blogId not found");
        }
        
        _checkIsBlogForBeginners($blogId);

        switch_to_blog($blogId);

        $menuPagesData = json_decode(file_get_contents("php://input", true));

        $pagesData = _prepareDataToUpdatePagesMenu($menuPagesData, $blogId);
        
        if (checkIsPagesInheritanceValid($pagesData)) {
            _returnError(471, 'Pages structure inheritance is not valid.');
        }

        foreach ($pagesData as $pData) {
            if ($pData['needToUpdate']) {
                _updatePageByData($pData, $blogId);
            }
        }

        updatePagesMenu($blogId, $pagesData);

        $result = getPagesData($blogId);

        die( json_encode($result) );
        
    } catch (Exception $ex) {
        _returnError(500, 'The error');
    }
}
add_action( 'wp_ajax_updatePagesMenu', 'updatePagesMenuApi' );
add_action( 'wp_ajax_nopriv_updatePagesMenu', 'updatePagesMenuApi' );



function _prepareDataToUpdatePagesMenu($menuPagesData, $blogId)
{
    $pagesData = getPagesData($blogId);
    
    foreach ($menuPagesData as $i => $mpData) {
        $mPageId = $mpData->id;
        $mpData->menuOrder = $i;
        $needToUpdate = false;
        
        $pDataIdx = null;
        foreach ($pagesData as $idx => $pData) {
            if ($pData['id'] === $mPageId) {
                $pDataIdx = $idx;
                break;
            }
        }
        
        if ($pDataIdx === null) {
            _returnError(462, "Page with id: $mPageId not found");
        }
        
        // is page data need to be updated in DB. (need for performance)
        if (
            $pagesData[$pDataIdx]['parent'] != $mpData->parent
            || $pagesData[$pDataIdx]['menuOrder'] != $mpData->menuOrder
            || $pagesData[$pDataIdx]['showInMenu'] != $mpData->showInMenu
                
        ) {
            $needToUpdate = true;
        }
        
        $pagesData[$pDataIdx]['parent'] = $mpData->parent;
        $pagesData[$pDataIdx]['menuOrder'] = $mpData->menuOrder;
        $pagesData[$pDataIdx]['showInMenu'] = $mpData->showInMenu;
        $pagesData[$pDataIdx]['needToUpdate'] = $needToUpdate;
    }

    return $pagesData;
}


function deletePageApi()
{
    try {
        _checkAuthorizationByToken();

        $blogId = isset( $_GET['blog'] ) ? (int) $_GET['blog'] : null;
        if ($blogId) {
            $blogData = get_blog_details( $blogId );

            $blogExists = (bool) $blogData;
        } else {
            _returnError(400, "Parameter 'blog' is required");
        }

        if (!$blogExists) {
            _returnError(461, "Blog with id: $blogId not found");
        }
        
        _checkIsBlogForBeginners($blogId);

        switch_to_blog($blogId);

        $pageId = isset( $_GET['page'] ) ? (int) $_GET['page'] : null;
        if ($pageId) {
            $page = get_page( $pageId );

            $pageExists = $page && $page->post_type === 'page';
        } else {
            _returnError(400, "Parameter 'page' is required");
        }

        if (!$pageExists) {
            _returnError(462, "Page with id: $pageId not found");
        }

        $deletedPagesIds = deletePagesCascade($page, $blogId);
        if (!count($deletedPagesIds)) {
            _returnError(500, 'Cannot delete page');
        }
        
    } catch (Exception $ex) {
        _returnError(500, "Cannot delete page");
    }

    die( json_encode(['status' => 'Ok', 'deletedPagesIds' => $deletedPagesIds]) );
}
add_action( 'wp_ajax_deletePage', 'deletePageApi' );
add_action( 'wp_ajax_nopriv_deletePage', 'deletePageApi' );
    


function deletePagesCascade($page, $blogId)
{
    $deletedPagesIds = [];
    
    switch_to_blog($blogId);
    
    $pageId = $page->ID;
   
    $childPages = get_pages(['parent' => $pageId]);

    $result = wp_delete_post($pageId, true);
    if ($result) {
        $deletedPagesIds[] = $pageId;
    }
    
    foreach ($childPages as $chPage) {
        $r = deletePagesCascade($chPage, $blogId);
        $deletedPagesIds = array_merge($deletedPagesIds, $r);
    }
    
    return $deletedPagesIds;
}

   

function getPagesIdsInMenu($blogId)
{
    $result = [];
    
    switch_to_blog($blogId);
    
    $menus = get_nav_menu_locations();

    if (!( is_array($menus) && count($menus) )) return $result;
    
    $menuId = array_shift($menus);
 
    $menuObj = wp_get_nav_menu_object( $menuId );
    $menuItems = wp_get_nav_menu_items($menuObj);
    
    foreach ($menuItems as $mItem) {
        if ($mItem->object !== 'page') continue;
        
        $result[] = (int) $mItem->object_id;
    }

    return $result;
}



function _preparePageDataRequestParams($isCreate=false)
{
    global $wpdb;
    $params = [];
    
    // check blogId param
    $blogId = isset( $_POST['blogId'] ) ? (int) $_POST['blogId'] : null;
    if ($blogId) {
        $blogData = get_blog_details( $blogId );
        
        $blogExists = (bool) $blogData;
    } else {
        _returnError(400, "Parameter 'blogId' is required");
    }
    
    if (!$blogExists) {
        _returnError(461, "Blog with id: $blogId not found");
    }
    
    switch_to_blog($blogId);
    
    // check pageId param
    if (!$isCreate) {
        $pageId = isset( $_POST['pageId'] ) ? (int) $_POST['pageId'] : null;
        if ($pageId) {
            $page = get_page( $pageId );

            $pageExists = $page && $page->post_type === 'page';
        } else {
            _returnError(400, "Parameter 'pageId' is required");
        }

        if (!$pageExists) {
            _returnError(462, "Page with id: $pageId not found");
        }
        
         $params['pageId'] = $pageId;
    }
    
    // check required params
    foreach (['title', 'parent', 'template', 'showInMenu'] as $pName) {
        if (!isset($_POST[$pName])) {
            _returnError(400, "Parameter '$pName' is required");
        }
    }
    
    // check 'parent' param
    $parent = (int) $_POST['parent'];
    if ($parent) {
        $parentPage = get_page( $parent );
        $pageExists = $parentPage && $parentPage->post_type === 'page';
        
        if (!$pageExists) {
            _returnError(463, "Parent page with id: $parent not found");
        }
        
        if (!$isCreate) {
            checkPagesInheritance($blogId, $pageId, $parent);
        }
    }
    
    // check param: 'slug'
    if (isset($_POST['slug'])) {
        $slug = trim( $_POST['slug'] );
        if (!$slug) {
            _returnError(464, 'Slug is empty');
        }

        $sanitizedSlug = sanitize_title($slug);

        if (urldecode($sanitizedSlug) !== $slug) {
            _returnError(465, "Slug has wrong format");
        }

        if ($isCreate) {
            $checkSql = "SELECT post_name FROM $wpdb->posts WHERE post_name = %s";
            $result = $wpdb->get_var( $wpdb->prepare( $checkSql, $sanitizedSlug ) );

        } else {
            $checkSql = "SELECT post_name FROM $wpdb->posts WHERE post_name = %s AND post_type = %s AND ID != %d";
            $result = $wpdb->get_var( $wpdb->prepare( $checkSql, $sanitizedSlug, 'page', $pageId ) );
        }

        if ($result) {
            _returnError(466, "Slug: '$slug' is occupied");
        }
        
        $params['slug'] = $sanitizedSlug;
    }
    
    // check template
    $template = $_POST['template'];
    $templates = ['Default Template' => 'default'];
    $templates = array_merge( $templates, get_page_templates() );

    if (!in_array($template, $templates)) {
        _returnError(467, "Template: '$template' not found");
    }
    
    $params = array_merge($params, [
        'blogId'     => $blogId,
        'parent'     => $parent,
        'title'      => $_POST['title'],
        'template'   => $template,
        'showInMenu' => (bool) $_POST['showInMenu'],
        'isHomePage' => (bool) $_POST['isHomePage']
    ]);
    
    if (isset($_POST['menuOrder'])) {
        $params['menuOrder']  = (int) $_POST['menuOrder'];
    }
    
    
    return $params;
}


function checkIsPagesInheritanceValid($structure)
{
    $isValid = true;
    
    $parentsIds = [0];

    $wrongItems = walkThrowStructureItemsAndDeleteDescendantItems($parentsIds, $structure, 0);
    if (!count($wrongItems)) {
        $isValid = false;
    }
    
    return $isValid;
}


function walkThrowStructureItemsAndDeleteDescendantItems($parentsIds, $structure, $iterations)
{
    $iterations++;
    if ($iterations > 100) {
        _returnError(500, 'Pages structure checking. Too many iteration of recursive function');
    }
    
    $removedIds = [];
    
    foreach ($structure as $idx => $item) {
        if (in_array($item['parent'], $parentsIds)) {
            $removedIds[] = $item['id'];
            unset($structure[$idx]);
        }
    }
    
    if (count($removedIds)) {
        $structure = walkThrowStructureItemsAndDeleteDescendantItems($removedIds, $structure, $iterations);
    }
    
    return $structure;
}


function checkPagesInheritance($blogId, $pageId=null, $parent=0)
{
    $structure = getPagesData($blogId);
    
    if ($pageId) {
        foreach ($structure as $idx => $pageData) {
            if ($pageId === $pageData['id']) {
                $structure[$idx]['parent'] = $parent;
            }
        }
    }
    
    if (checkIsPagesInheritanceValid($structure)) {
        _returnError(471, 'Pages structure inheritance is not valid.');
    }
}



function updatePagesMenu($blogId, $pagesData)
{
    switch_to_blog($blogId);
    
    $pagesData = _correctChildPagesVisibility($blogId, $pagesData);

    $menus = get_nav_menu_locations();

    if (!( is_array($menus) && count($menus) )) {
        _returnError(468, 'No menu found');
    }
   
    $menuId = array_shift($menus);
 
    $menuObj = wp_get_nav_menu_object( $menuId );
    $menuItems = wp_get_nav_menu_items($menuObj);
    
    foreach ($menuItems as $mItem) {
        $mItemId = $mItem->ID;
        
        wp_delete_post($mItemId);
    }
    
    // add items to menu recursively
    usort($pagesData, function($a, $b){
        return $a['menuOrder'] > $b['menuOrder'];
    });

    addItemsToMenuRecursive($menuId, 0, 0, $pagesData, 0);
}


/*
 * Correct child pages visibility by next way:
 * 1. If parent page is hidden in menu then all its child pages is hidden in menu
 * 2. If parrent page was hidden in menu and became visible then all its child pages should be visible
 */
function _correctChildPagesVisibility($blogId, $pagesData)
{
    switch_to_blog($blogId);
  
    foreach ($pagesData as $idx => $pData) {
        $pageId = $pData['id'];
        $isPagePresentInMenuNow = _isPagePresentInMenuCurrently($blogId, $pageId);
        
        if ($pData['showInMenu'] === $isPagePresentInMenuNow) continue;
       
        if (!$pData['showInMenu']) {
             $pagesData = _changePresenceOfAllChildPagesInMenuFor($pageId, $pagesData, false);
            
        } else if ($pData['showInMenu'] && !$isPagePresentInMenuNow) {
             $pagesData = _changePresenceOfAllChildPagesInMenuFor($pageId, $pagesData, true);
        }
    }
  
    return $pagesData;
}


function _changePresenceOfAllChildPagesInMenuFor($pageId, $pagesData, $shownInMenu)
{
    $childPagesIds = _getChildPagesIds($pageId, $pagesData);
          
    foreach ($pagesData as $idx => $pData) {
        if (in_array($pData['id'], $childPagesIds)) {
            $pagesData[$idx]['showInMenu'] = $shownInMenu;
        }
    }
    
    return $pagesData;
}


function _isPagePresentInMenuCurrently($blogId, $pageId)
{
    $showInMenu = false;
    
    switch_to_blog($blogId);
        
    $currPagesData = getPagesData($blogId);
    
    foreach ($currPagesData as $pData) {
        if ($pData['id'] !== $pageId) continue;
        
        $showInMenu = $pData['showInMenu'];
    }
    
    return $showInMenu;
}



function addItemsToMenuRecursive($menuId, $parentPageId, $parentMenuItemId, $pagesData, $iterations)
{
    $iterations++;
    if ($iterations > 100) {
        _returnError(500, 'Pages structure checking. Too many iteration of recursive function');
    }
    
    $addedIds = 0;
    
    foreach ($pagesData as $pData) {
        if (!$pData['showInMenu']) continue;
        if ($parentPageId !==  $pData['parent']) continue;
        
        $iterations++;
       
        $pageId = $pData['id'];
      
        $mItemData = [
            'menu-item-object' => 'page',
            'menu-item-object-id' => $pageId,
            'menu-item-parent-id' => $parentMenuItemId ,
            'menu-item-type' => 'post_type',
            'menu-item-url' => get_permalink($pageId),
            'menu-item-status' => 'publish',
        ];
   
        $menuItemId = wp_update_nav_menu_item($menuId, null, $mItemData);
        
        if (is_wp_error($menuItemId)) continue;
 
        addItemsToMenuRecursive($menuId, $pageId, $menuItemId, $pagesData, $iterations);
        $addedIds++;
    }
    
    return $addedIds;
}


/*
 *  Set page with passed ID as homepage of blog
 */
function _setPageAsHomepage($blogId, $pageId)
{
    switch_to_blog($blodId);
    
    update_option( 'page_on_front', $pageId );
    update_option( 'show_on_front', 'page' );
}


/*
 *  Check is page the blog's homepage
 */
function _isPageHomepage($blogId, $pageId)
{
    $result = false;
    
    switch_to_blog($blodId);
    
    $pageOnFront = (int) get_option( 'page_on_front');
    
    if (get_option( 'show_on_front') === 'page' &&  $pageOnFront === $pageId) {
        $result = true;
    }
    
    return $result;
}
