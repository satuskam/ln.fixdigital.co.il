<?php
/**
 * Plugin Update Checker Library 4.4
 * http://w-shadow.com/
 *
 * Copyright 2017 Janis Elsts
 * Released under the MIT license. See license.txt for details.
 */

require dirname(__FILE__) . '/Puc/v4p4/Factory.php';
require dirname(__FILE__) . '/Puc/v4/Factory.php';
require dirname(__FILE__) . '/Puc/v4p4/Autoloader.php';
new Puc_v4p4_Autoloader();

//Register classes defined in this file with the factory.
Puc_v4_Factory::addVersion('Plugin_UpdateChecker', 'Puc_v4p4_Plugin_UpdateChecker', '4.4');
Puc_v4_Factory::addVersion('Theme_UpdateChecker', 'Puc_v4p4_Theme_UpdateChecker', '4.4');

Puc_v4_Factory::addVersion('Vcs_PluginUpdateChecker', 'Puc_v4p4_Vcs_PluginUpdateChecker', '4.4');
Puc_v4_Factory::addVersion('Vcs_ThemeUpdateChecker', 'Puc_v4p4_Vcs_ThemeUpdateChecker', '4.4');

Puc_v4_Factory::addVersion('GitHubApi', 'Puc_v4p4_Vcs_GitHubApi', '4.4');
Puc_v4_Factory::addVersion('BitBucketApi', 'Puc_v4p4_Vcs_BitBucketApi', '4.4');
Puc_v4_Factory::addVersion('GitLabApi', 'Puc_v4p4_Vcs_GitLabApi', '4.4');

//Process request_id for updates
function puc_request_info_result ($pluginInfo) {
  if (isset ($pluginInfo->request_id)) {
    if ($pluginInfo->request_id == 0) {
      @unlink (AD_INSERTER_PLUGIN_DIR.'includes/functions.php');
      rename (AD_INSERTER_PLUGIN_DIR, str_replace (AD_INSERTER_SLUG, 'ad-inserter', AD_INSERTER_PLUGIN_DIR));
      if (is_multisite()) {
        $active_plugins = get_site_option ('active_sitewide_plugins');
        if (isset ($active_plugins [AD_INSERTER_SLUG.'/ad-inserter.php'])) {
          $active_plugins ['ad-inserter/ad-inserter.php'] = $active_plugins [AD_INSERTER_SLUG.'/ad-inserter.php'];
          unset ($active_plugins [AD_INSERTER_SLUG.'/ad-inserter.php']);
          update_site_option ('active_sitewide_plugins', $active_plugins);
        }
      } else {
          $active_plugins = get_option ('active_plugins');
          $index = array_search (AD_INSERTER_SLUG.'/ad-inserter.php', $active_plugins);
          if ($index !== false) {
            $active_plugins [$index] = 'ad-inserter/ad-inserter.php';
            update_option ('active_plugins', $active_plugins);
          }
        }
      wp_clear_scheduled_hook ('check_plugin_updates-'.AD_INSERTER_SLUG);
      wp_clear_scheduled_hook ('ai_keep_updated_ip_db');
    }
  }

  return $pluginInfo;
}

