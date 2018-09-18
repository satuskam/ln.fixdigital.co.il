<?php

define ('AD_INSERTER_NAME', 'Ad Inserter Pro');
define ('AD_INSERTER_SLUG', str_replace ('/'.basename (AD_INSERTER_FILE), '', plugin_basename (AD_INSERTER_FILE)));
define ('WP_AD_INSERTER_PRO_LICENSE', 'ad_inserter_pro_license');
define ('WP_UPDATE_SERVER', 'http://updates.adinserter.pro/');
define ('IP_DB_UPDATE_DAYS', 30);

global $wpdb;

define ('AI_STATISTICS', true);

define ('AI_STATISTICS_DB_TABLE', $wpdb->prefix . 'ai_statistics');
define ('AI_STATISTICS_AVERAGE_PERIOD', 30);

define ('AI_ADB_1_FILENAME',             'ads.js');
define ('AI_ADB_2_FILENAME',             'sponsors.js');
define ('AI_ADB_3_FILENAME',             'advertising.js');
define ('AI_ADB_4_FILENAME',             'adverts.js');
define ('AI_ADB_DBG_FILENAME',           'dbg.js');

define ('AI_ADB_FOOTER_FILENAME',        'footer.js');

define ('AI_ADB_3_NAME1',                'FunAdBlock');
define ('AI_ADB_3_NAME2',                'funAdBlock');
define ('AI_ADB_4_NAME1',                'BadBlock');
define ('AI_ADB_4_NAME2',                'badBlock');

define('DEFAULT_MAXMIND_FILENAME',       'GeoLite2-City.mmdb');
require_once (ABSPATH.'/wp-admin/includes/file.php');
$db_upload_dir = wp_upload_dir();
$db_file_path  = str_replace (get_home_path(), "", $db_upload_dir ['basedir']) . '/ad-inserter';
define ('DEFAULT_GEO_DB_LOCATION', $db_file_path.'/'.DEFAULT_MAXMIND_FILENAME);

// TODO

// + delete old data
// x template selection
// x CREATE TABLE IF NOT EXISTS
// - tab *: statistics table for all code blocks: impressions, clicks, CTR
// - export csv data
// x multisite tracking
// - debugging statistics
// - table info: today, yesterday, this month, last month
// + auto refresh
// + track impressions and clicks from logged in users
// - impression timer: 60 s, 10  - 3600
// - click timer: 86400 s, 60 - 86400
// - bot filter
// 008, bot, crawler, spider, Accoona-AI-Agent, alexa, Arachmo, B-l-i-t-z-B-O-T, boitho.com-dc, Cerberian Drtrs, Charlotte, cosmos, Covario IDS, DataparkSearch, FindLinks, Holmes, htdig, ia_archiver, ichiro, inktomi, igdeSpyder, L.webis, Larbin, LinkWalker, lwp-trivial, mabontland, Mnogosearch, mogimogi, Morning Paper, MVAClient, NetResearchServer, NewsGator, NG-Search, NutchCVS, Nymesis, oegp, Orbiter, Peew, Pompos, PostPost, PycURL, Qseero, Radian6, SBIder, ScoutJet, Scrubby, SearchSight, semanticdiscovery, ShopWiki, silk, Snappy, Sqworm, StackRambler, Teoma, TinEye, truwoGPS, updated, Vagabondo, Vortex, voyager, VYU2, webcollage, Websquash.com, wf84, WomlpeFactory, yacy, Yahoo! Slurp, Yahoo! Slurp China, YahooSeeker, YahooSeeker-Testing, YandexImages, Yeti, yoogliFetchAgent, Zao, ZyBorg, froogle, looksmart, Firefly, NationalDirectory, Ask Jeeves, TECNOSEEK, InfoSeek, Scooter, appie, WebBug, Spade, rabaz, TechnoratiSnoop
// + Piwik
// + Google Analytics
// + delete statistics
// + click detection with overlay
// + table data for one day
// x tracker installation on load
// + check atob data


// + Replace text
// x Disable internal ad blocking detectors
// + Replace when blocked
// + General ad blocking statistics
// + Block ad blocking statistics
// - Close button as on notices
// + No scripts on AMP pages


require_once AD_INSERTER_PLUGIN_DIR.'includes/geo/Ip2Country.php';

function recursive_remove_directory ($directory) {
  foreach (glob ("{$directory}/*") as $file) {
    if (is_dir ($file)) {
      recursive_remove_directory ($file);
    } else {
        @unlink($file);
    }
  }
  @rmdir ($directory);
}

function ai_load_globals () {
  global $ad_inserter_globals, $wpdb;

  $ad_inserter_globals ['LICENSE_KEY'] = get_license_key ();
  $ad_inserter_globals ['AI_STATUS']   = get_plugin_status ();
  $ad_inserter_globals ['AI_TYPE']     = get_plugin_type ();
  $ad_inserter_globals ['AI_COUNTER']  = get_plugin_counter ();

  for ($group = 1; $group <= AD_INSERTER_GEO_GROUPS; $group ++) {
    $ad_inserter_globals ['G'.$group] = get_group_country_list ($group);
  }

  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
    ob_start ();
    $test = $wpdb->query ('SELECT 1 FROM ' . AI_STATISTICS_DB_TABLE . ' LIMIT 1', array ());
    ob_get_clean ();

    if ($test === false) {
      require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

      $sql = "CREATE TABLE " . AI_STATISTICS_DB_TABLE . " (
          id bigint(20) NOT NULL AUTO_INCREMENT,
          block int(10) unsigned NOT NULL,
          version int(10) unsigned NOT NULL,
          date date DEFAULT NULL,
          views int(10) unsigned NOT NULL DEFAULT '0',
          clicks int(10) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY  (id),
          UNIQUE KEY block_version (block, version, date)
        ) DEFAULT CHARSET=utf8;";
      $result = dbDelta ($sql);
    }

    $chart_days = 90 + AI_STATISTICS_AVERAGE_PERIOD;
    $gmt_offset = get_option ('gmt_offset') * 3600;

    $date_end = date ("Y-m-d", time () + $gmt_offset);
  }

}

function ai_check_geo_settings () {
  if (defined ('AD_INSERTER_MAXMIND') && !defined ('AI_MAXMIND_DB')) {
    if (get_geo_db () == AI_GEO_DB_MAXMIND) {
      $db_file = get_geo_db_location ();
      if (file_exists ($db_file)) {
        define ('AI_MAXMIND_DB', $db_file);
      }
    }
  }
}

function update_statistics ($block, $version, $views, $clicks, $debug = false) {
  global $wpdb;

  if (is_numeric ($block) && is_numeric ($version) && is_numeric ($views) && is_numeric ($clicks)) {
    $gmt_offset = get_option ('gmt_offset') * 3600;
    $today = date ("Y-m-d", time () + $gmt_offset);

    $insert = $wpdb->query (
      $wpdb->prepare ('INSERT INTO ' . AI_STATISTICS_DB_TABLE . ' (block, version, date, views, clicks) VALUES (%d, %d, %s, %d, %d) ON DUPLICATE KEY UPDATE views = views + %d, clicks = clicks + %d',
        $block, $version, $today, $views, $clicks, $views, $clicks)
    );
    if ($debug) {
      $results = $wpdb->get_results ('SELECT * FROM ' . AI_STATISTICS_DB_TABLE . ' WHERE block = ' . $block . ' AND version = ' . $version . ' AND date = \''.$today.'\'', ARRAY_N);
      if (isset ($results [0])) {
        return ($results [0]);
      }
    }
  }
}

// Used for settings page and settings save function
function ai_settings_parameters (&$subpage, &$start, &$end) {
  if (isset ($_GET ['subpage'])) $subpage = $_GET ['subpage'];

  if (isset ($_GET ['start'])) $start = $_GET ['start']; else $start = 1;
  if (!is_numeric ($start)) $start = 1;
  if ($start < 1 || $start > AD_INSERTER_BLOCKS) $start = 1;
  $end = $start + 15;
  if ($end > AD_INSERTER_BLOCKS) $end = AD_INSERTER_BLOCKS;
}

function get_country_names () {
  // Load country names and ISO codes
  $country_names = array ();
  $fp = fopen (AD_INSERTER_PLUGIN_DIR.'includes/geo/country-codes.txt', 'r');
  while (($row = fgetcsv ($fp, 255)) !== false)
    if ($row && count ($row) > 3 && substr (trim ($row [0]), 0, 1) != '#') {
      list ($country, $iso2) = $row;
      $iso2     = strtoupper ($iso2);
      $country  = str_replace ('( ', '(', ucwords (str_replace ('(', '( ', strtolower ($country))));
      $country_names []= array ($iso2, $country);
    }
  fclose ($fp);

  return $country_names;
}

function ai_generate_list_options ($options) {
  switch ($options) {
    case 'country':
    case 'group-country':
      $country_names = get_country_names ();

      foreach ($country_names as $country_name) {
        $iso2     = $country_name [0];
        $iso_flag = strtolower ($iso2);
        $country  = $country_name [1];
        echo "              <option value='$iso2' class='flag-icon flag-icon-$iso_flag'>$country ($iso2)</option>\n";
      }
      break;
  }
  switch ($options) {
    case 'country':
      for ($group_index = 1; $group_index <= AD_INSERTER_GEO_GROUPS; $group_index++) {
        echo "              <option value='G" . ($group_index % 10) ."'>" . get_country_group_name ($group_index) . " (G" . ($group_index % 10) . ")</option>\n";
      }
      break;
    case 'group-country':
      $group = isset ($_GET ["data"]) ? $_GET ["data"] : 0;
      for ($group_index = 1; $group_index < $group; $group_index++) {
        echo "              <option value='G" . ($group_index % 10) ."'>" . get_country_group_name ($group_index) . " (G" . ($group_index % 10) . ")</option>\n";
      }
      break;
  }
}

function ai_admin_enqueue_scripts_1 () {
  wp_enqueue_style  ('ai-admin-flags',        plugins_url ('css/flags.css',        AD_INSERTER_FILE), array (), AD_INSERTER_VERSION);
}

function ai_admin_enqueue_scripts_2 () {
  wp_enqueue_script ('ai-raphael-js',         plugins_url ('includes/js/raphael.min.js', AD_INSERTER_FILE ),          array (), AD_INSERTER_VERSION, true);
  wp_enqueue_script ('ai-elycharts-js',       plugins_url ('includes/js/elycharts.min.js', AD_INSERTER_FILE ),        array (), AD_INSERTER_VERSION, true);

}

function ai_load_settings_2 ($obj) {
  global $ai_wp_data;

  if ($obj->get_tracking ())                        $ai_wp_data [AI_TRACKING] = true;
  if ($obj->get_close_button () != AI_CLOSE_NONE)   $ai_wp_data [AI_CLOSE_BUTTONS] = true;
  if ($obj->get_animation () != AI_ANIMATION_NONE)  $ai_wp_data [AI_ANIMATION] = true;
}

function ai_settings_url_parameters ($block) {
  return '&start=' . (intval (($block - 1) / 16) * 16 + 1);
}

function ai_settings_header ($start, $active_tab) {
?>
    <div id= "ai-settings-header" style="float: left;">
      <h2 id="plugin_name" style="margin: 5px 0;"><?php echo AD_INSERTER_NAME . ' ' . AD_INSERTER_VERSION ?></h2>
    </div>
    <div id="header-buttons">
        <div id="dummy-ranges" style="height: 26px; width: 300px;"></div>
        <div id="ai-ranges" style="display: none;">
          <img id="ai-loading" src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>loading.gif" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 20px; display: none;" />
            <button type="button" class="ai-top-button" style="display: none; margin: 0 10px 0 0; outline-color: transparent;" onclick="window.open('http://adinserter.pro/documentation')" title="<?php echo AD_INSERTER_NAME; ?> Documentation">Doc</button>
<?php
        for ($range = 1; $range <= intval ((AD_INSERTER_BLOCKS + 15) / 16); $range ++){
          $range_start = ($range - 1) * 16 + 1;
          $range_end = $range_start + 16 - 1;
          if ($range_end > AD_INSERTER_BLOCKS) $range_end = AD_INSERTER_BLOCKS;
          if (($active_tab >= $range_start && $active_tab <= $range_end) || ($start == $range_start && $active_tab == 0)) $style = "font-weight: bold; color: #44e; "; else $style = "";
?>
            <button type="button" class="ai-top-button" id="button-range-<?php echo $range; ?>" style="display: none; margin-right: 0px; outline-color: transparent;" onclick="window.location.href='<?php echo admin_url('options-general.php?page=ad-inserter.php&start='.$range_start); ?>'">
              <span style="<?php echo $style; ?>"><?php echo $range_start, " - ", $range_end; ?></span>
            </button>
<?php   } ?>
            <button type="button" class="ai-top-button" id="ai-list" style="display: none; margin-right: 0px; outline-color: transparent;">
              <span>Blocks</span>
            </button>
        </div>
    </div>

    <div style="clear: both;"></div>

<?php
}

function ai_data_2 () {
?>
<div id="ai-data-2" style="display: none;" geo_groups="<?php echo AD_INSERTER_GEO_GROUPS; ?>" ></div>
<?php
}

function ai_global_settings () {
  global $ai_db_options;
?>
  <div id="export-container-0" style="display: none; padding: 8px;">
      <div style="display: inline-block; padding: 2px 10px; float: right;">
        <input type="hidden"   name="<?php echo AI_OPTION_IMPORT, WP_FORM_FIELD_POSTFIX, '0'; ?>" value="0" default="0" />
        <input type="checkbox" name="<?php echo AI_OPTION_IMPORT, WP_FORM_FIELD_POSTFIX, '0'; ?>" value="1" default="0" id="import-0" />
        <label for="import-0" title="Import <?php echo AD_INSERTER_NAME; ?> settings when saving - if checked, the encoded settings below will be imported for all blocks and settings">Import Settings for <?php echo AD_INSERTER_NAME; ?></label>
      </div>

      <div style="float: left; padding-left:10px;">
        Saved settings for Ad Inserter Pro
      </div>
      <textarea id="export_settings_0" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace; font-weight: bold; width: 719px; height: 324px;"></textarea>
  </div>
<?php
}

function ai_general_settings () {
  global $ad_inserter_globals;

  if (!is_multisite() || is_main_site ()) {
    $license_key = $ad_inserter_globals ['LICENSE_KEY'];
?>
      <tr>
        <td>
          License Key
        </td>
        <td>
          <input style="margin-left: 0px;" title="License Key for <?php echo AD_INSERTER_NAME; ?>" type="text" name="license_key" value="<?php echo $license_key; ?>" size="42" maxlength="64" />
<!--          <div style="display: inline-block; padding: 2px 10px; float: right;">-->
<!--            <input type="hidden"   name="<?php echo AI_OPTION_PLUGIN_STATUS; ?>" value="0" />-->
<!--            <input type="checkbox" name="<?php echo AI_OPTION_PLUGIN_STATUS; ?>" value="1" default="0" id="plugin_status" />-->
<!--            <label for="plugin_status">Update Status</label>-->
<!--          </div>-->
        </td>
      </tr>
<?php
  }
}

function ai_general_settings_2 () {
  if (defined ('AI_STICKY_SETTINGS') && AI_STICKY_SETTINGS) {
?>
      <tr>
        <td>
          Main content element
        </td>
        <td>
          <input id="main-content-element" style="margin-left: 0px; width: 100%;" title="Main content element (#id or .class) for 'Stick to the content' position. Leave empty unless position is not properly calculated." type="text" name="main-content-element" value="<?php echo get_main_content_element (); ?>" maxlength="80" />
        </td>
      </tr>
<?php
  }
}

function ai_settings_top_buttons_1 ($block, $obj, $default) {
?>
    <span class="ai-toolbar-button ai-button-left ai-settings">
      <input type="checkbox" value="0" id="export-switch-<?php echo $block; ?>" nonce="<?php echo wp_create_nonce ("adinserter_data"); ?>" site-url="<?php echo wp_make_link_relative (get_site_url()); ?>" style="display: none;" />
      <label class="checkbox-button" for="export-switch-<?php echo $block; ?>" title="Export / Import Block Settings"><span class="checkbox-icon icon-export-import"></span></label>
    </span>

    <span style="display: table-cell; width: 6%;"></span>
<?php
}

function ai_settings_top_buttons_2 ($block, $obj, $default) {
  global $ai_wp_data;

  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
?>
    <span class="ai-toolbar-button ai-settings<?php if (!get_global_tracking ()) echo ' tracking-disabled'; ?> ">
      <input type="hidden"   name="<?php echo AI_OPTION_TRACKING, WP_FORM_FIELD_POSTFIX, $block; ?>" value="0" />
      <input type="checkbox" name="<?php echo AI_OPTION_TRACKING, WP_FORM_FIELD_POSTFIX, $block; ?>" value="1" default="<?php echo $default->get_tracking (true); ?>" id="tracking-<?php echo $block; ?>" <?php if ($obj->get_tracking (true) == AI_ENABLED) echo 'checked '; ?> style="display: none;" />
      <label class="checkbox-button" for="tracking-<?php echo $block; ?>" title="Track impressions and clicks for this block<?php if (!get_global_tracking ()) echo ' - global tracking disabled'; ?>"><span class="checkbox-icon icon-tracking<?php if ($obj->get_tracking (true) == AI_ENABLED) echo ' on'; ?>"></span></label>
    </span>

<?php
  if (defined ('AI_ADBLOCKING_DETECTION') && AI_ADBLOCKING_DETECTION) {
    if ($ai_wp_data [AI_ADB_DETECTION]) {
?>
    <span class="ai-toolbar-button ai-statistics" style="display: none;">
      <input type="checkbox" value="0" id="adb-statistics-button-<?php echo $block; ?>" style="display: none;" />
      <label class="checkbox-button" for="adb-statistics-button-<?php echo $block; ?>" title="Toggle Ad Blocking Statistics"><span class="checkbox-icon icon-adb"></span></label>
    </span>
<?php
    }
  }
?>

    <span class="ai-toolbar-button">
      <input type="checkbox" value="0" id="statistics-button-<?php echo $block; ?>" nonce="<?php echo wp_create_nonce ("adinserter_data"); ?>" site-url="<?php echo wp_make_link_relative (get_site_url()); ?>" style="display: none;" />
      <label class="checkbox-button" for="statistics-button-<?php echo $block; ?>" title="Toggle Statistics"><span class="checkbox-icon icon-statistics"></span></label>
    </span>
<?php
  }
}

function ai_settings_bottom_buttons ($start, $end) {
  global $ad_inserter_globals;

  $onclick = '';
  if (!is_multisite() || is_main_site ()) {
    $ai_status = $ad_inserter_globals ['AI_STATUS'];
    $license_key = $ad_inserter_globals ['LICENSE_KEY'];

    if (empty ($license_key)) {
      $onclick = 'onclick="if (confirm(\'' . AD_INSERTER_NAME . ' license key is not set. Continue?\')) return true; return false"';
    }
    elseif ($ai_status == - 19) {
      $onclick = 'onclick="if (confirm(\'Invalid ' . AD_INSERTER_NAME . ' license key. Continue?\')) return true; return false"';
    }
    elseif ($ai_status == - 21) {
      $onclick = 'onclick="if (confirm(\'' . AD_INSERTER_NAME . ' license overused. Continue?\')) return true; return false"';
    }
  }
?>
          <input <?php echo $onclick; ?> style="display: none; vertical-align: middle; font-weight: bold;" name="<?php echo AI_FORM_SAVE; ?>" value="Save Settings <?php echo $start, ' - ', $end; ?>" type="submit" />
<?php
}

function ai_style_options ($obj) {
  if (defined ('AI_STICKY_SETTINGS') && AI_STICKY_SETTINGS) : ?>
         <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky" value="<?php echo AI_ALIGNMENT_STICKY; ?>" data-title="<?php echo AI_TEXT_STICKY; ?>" <?php echo ($obj->get_alignment_type() == AI_ALIGNMENT_STICKY) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICKY; ?></option>
<?php else : ?>
         <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-left" value="<?php echo AI_ALIGNMENT_STICKY_LEFT; ?>" data-title="<?php echo AI_TEXT_STICKY_LEFT; ?>" <?php echo ($obj->get_alignment_type() == AI_ALIGNMENT_STICKY_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICKY_LEFT; ?></option>
         <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-right" value="<?php echo AI_ALIGNMENT_STICKY_RIGHT; ?>" data-title="<?php echo AI_TEXT_NO_WRAPPING; ?>" <?php echo ($obj->get_alignment_type() == AI_ALIGNMENT_STICKY_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICKY_RIGHT; ?></option>
         <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-top" value="<?php echo AI_ALIGNMENT_STICKY_TOP; ?>" data-title="<?php echo AI_TEXT_STICKY_RIGHT; ?>" <?php echo ($obj->get_alignment_type() == AI_ALIGNMENT_STICKY_TOP) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICKY_TOP; ?></option>
         <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-bottom" value="<?php echo AI_ALIGNMENT_STICKY_BOTTOM; ?>" data-title="<?php echo AI_TEXT_STICKY_BOTTOM; ?>" <?php echo ($obj->get_alignment_type() == AI_ALIGNMENT_STICKY_BOTTOM) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICKY_BOTTOM; ?></option>
<?php endif;
}

function ai_style_css ($block, $obj) {
  if (defined ('AI_STICKY_SETTINGS') && AI_STICKY_SETTINGS) : ?>
          <span id="css-sticky-<?php echo $block; ?>" class='css-code-<?php echo $block; ?>' style="height: 18px; padding-right: 7px; display: none;" title="CSS code for wrapping div, click to edit"><?php echo $obj->alignment_style (AI_ALIGNMENT_STICKY, false, false); ?><span class="ai-sticky-css"><?php echo $obj->sticky_style ($obj->get_horizontal_position (), $obj->get_vertical_position ()); ?></span></span>
<?php else : ?>
          <span id="css-sticky-left-<?php echo $block; ?>" class='css-code-<?php echo $block; ?>' style="height: 18px; padding-left: 7px; display: none;" title="CSS code for wrapping div, click to edit"><?php echo $obj->alignment_style (AI_ALIGNMENT_STICKY_LEFT); ?></span>
          <span id="css-sticky-right-<?php echo $block; ?>" class='css-code-<?php echo $block; ?>' style="height: 18px; padding-right: 7px; display: none;" title="CSS code for wrapping div, click to edit"><?php echo $obj->alignment_style (AI_ALIGNMENT_STICKY_RIGHT); ?></span>
          <span id="css-sticky-top-<?php echo $block; ?>" class='css-code-<?php echo $block; ?>' style="height: 18px; padding-left: 7px; display: none;" title="CSS code for wrapping div, click to edit"><?php echo $obj->alignment_style (AI_ALIGNMENT_STICKY_TOP); ?></span>
          <span id="css-sticky-bottom-<?php echo $block; ?>" class='css-code-<?php echo $block; ?>' style="height: 18px; padding-right: 7px; display: none;" title="CSS code for wrapping div, click to edit"><?php echo $obj->alignment_style (AI_ALIGNMENT_STICKY_BOTTOM); ?></span>
<?php endif;
}

function ai_preview_style_options ($obj, $alignment_type, $sticky = false) {
  if (defined ('AI_STICKY_SETTINGS') && AI_STICKY_SETTINGS) {
    if ($sticky) { ?>
         <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion preview im-sticky" <?php alt_styles_data ($obj->alignment_style (AI_ALIGNMENT_STICKY, true)); ?> value="<?php echo AI_ALIGNMENT_STICKY; ?>" data-title="<?php echo AI_TEXT_STICKY; ?>" <?php echo ($obj->get_alignment_type() == AI_ALIGNMENT_STICKY) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICKY; ?></option>
<?php
    }
  } else {
?>
         <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion preview im-sticky-left" <?php alt_styles_data ($obj->alignment_style (AI_ALIGNMENT_STICKY_LEFT, true)); ?> value="<?php echo AI_ALIGNMENT_STICKY_LEFT; ?>" data-title="<?php echo AI_TEXT_STICKY_LEFT; ?>" <?php echo ($alignment_type == AI_ALIGNMENT_STICKY_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICKY_LEFT; ?></option>
         <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion preview im-sticky-right" <?php alt_styles_data ($obj->alignment_style (AI_ALIGNMENT_STICKY_RIGHT, true)); ?> value="<?php echo AI_ALIGNMENT_STICKY_RIGHT; ?>" data-title="<?php echo AI_TEXT_STICKY_RIGHT; ?>" <?php echo ($alignment_type == AI_ALIGNMENT_STICKY_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICKY_RIGHT; ?></option>
         <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion preview im-sticky-top" <?php alt_styles_data ($obj->alignment_style (AI_ALIGNMENT_STICKY_TOP, true)); ?> value="<?php echo AI_ALIGNMENT_STICKY_TOP; ?>" data-title="<?php echo AI_TEXT_STICKY_TOP; ?>" <?php echo ($alignment_type == AI_ALIGNMENT_STICKY_TOP) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICKY_TOP; ?></option>
         <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion preview im-sticky-bottom" <?php alt_styles_data ($obj->alignment_style (AI_ALIGNMENT_STICKY_BOTTOM, true)); ?> value="<?php echo AI_ALIGNMENT_STICKY_BOTTOM; ?>" data-title="<?php echo AI_TEXT_STICKY_BOTTOM; ?>" <?php echo ($alignment_type == AI_ALIGNMENT_STICKY_BOTTOM) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICKY_BOTTOM; ?></option>
<?php
  }
}

function ai_preview_style_css ($obj, $horizontal_position = null, $vertical_position = null, $horizontal_margin = null, $vertical_margin = null) {
  if (defined ('AI_STICKY_SETTINGS') && AI_STICKY_SETTINGS) : ?>
            <span id="css-<?php echo AI_ALIGNMENT_STICKY; ?>" class="css-code" style="vertical-align: middle;display: none;" title="CSS code for wrapping div, click to edit"><?php echo $obj->alignment_style (AI_ALIGNMENT_STICKY, false, false); ?><span class="ai-sticky-css"><?php echo $obj->sticky_style ($horizontal_position, $vertical_position, $horizontal_margin, $vertical_margin); ?></span></span>
<?php else : ?>
            <span id="css-<?php echo AI_ALIGNMENT_STICKY_LEFT; ?>" class="css-code" style="vertical-align: middle;display: none;" title="CSS code for wrapping div, click to edit"><?php echo $obj->alignment_style (AI_ALIGNMENT_STICKY_LEFT); ?></span>
            <span id="css-<?php echo AI_ALIGNMENT_STICKY_RIGHT; ?>" class="css-code" style="vertical-align: middle;display: none;" title="CSS code for wrapping div, click to edit"><?php echo $obj->alignment_style (AI_ALIGNMENT_STICKY_RIGHT); ?></span>
            <span id="css-<?php echo AI_ALIGNMENT_STICKY_TOP; ?>" class="css-code" style="vertical-align: middle;display: none;" title="CSS code for wrapping div, click to edit"><?php echo $obj->alignment_style (AI_ALIGNMENT_STICKY_TOP); ?></span>
            <span id="css-<?php echo AI_ALIGNMENT_STICKY_BOTTOM; ?>" class="css-code" style="vertical-align: middle;display: none;" title="CSS code for wrapping div, click to edit"><?php echo $obj->alignment_style (AI_ALIGNMENT_STICKY_BOTTOM); ?></span>
<?php endif;
}

function ai_sticky_position ($block, $obj, $default) {
  if (defined ('AI_STICKY_SETTINGS') && AI_STICKY_SETTINGS) {
    $horizontal_position = $obj->get_horizontal_position();
    $vertical_position   = $obj->get_vertical_position();
?>
      <div id="sticky-position-<?php echo $block; ?>" style="margin: 8px 0; display: none;">
        <div style="float: left;">
          Horizontal position
          <select class="ai-image-selection" id="horizontal-position-<?php echo $block; ?>" name="<?php echo AI_OPTION_HORIZONTAL_POSITION, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_horizontal_position(); ?>" style="margin-top: -1px;">
             <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-left"
               data-css="<?php echo AI_ALIGNMENT_CSS_STICK_TO_THE_LEFT; ?>"
               value="<?php echo AI_STICK_TO_THE_LEFT; ?>"
               data-title="<?php echo AI_TEXT_STICK_TO_THE_LEFT; ?>" <?php echo ($horizontal_position == AI_STICK_TO_THE_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICK_TO_THE_LEFT; ?></option>
             <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-content-left"
               data-css="<?php echo AI_ALIGNMENT_CSS_STICK_TO_THE_CONTENT_LEFT; ?>"
               value="<?php echo AI_STICK_TO_THE_CONTENT_LEFT; ?>"
               data-title="<?php echo AI_TEXT_STICK_TO_THE_CONTENT_LEFT; ?>" <?php echo ($horizontal_position == AI_STICK_TO_THE_CONTENT_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICK_TO_THE_CONTENT_LEFT; ?></option>
             <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-center-horizontal"
               data-css="<?php echo AI_ALIGNMENT_CSS_STICK_CENTER_HORIZONTAL; ?>" data-css-<?php echo AI_STICK_VERTICAL_CENTER; ?>="<?php echo AI_ALIGNMENT_CSS_STICK_CENTER_HORIZONTAL_V; ?>"
               value="<?php echo AI_STICK_HORIZONTAL_CENTER; ?>"
               data-title="<?php echo AI_TEXT_CENTER; ?>" <?php echo ($horizontal_position == AI_STICK_HORIZONTAL_CENTER) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_CENTER; ?></option>
             <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-content-right"
               data-css="<?php echo AI_ALIGNMENT_CSS_STICK_TO_THE_CONTENT_RIGHT; ?>"
               value="<?php echo AI_STICK_TO_THE_CONTENT_RIGHT; ?>"
               data-title="<?php echo AI_TEXT_STICK_TO_THE_CONTENT_RIGHT; ?>" <?php echo ($horizontal_position == AI_STICK_TO_THE_CONTENT_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICK_TO_THE_CONTENT_RIGHT; ?></option>
             <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-right"
               data-css="<?php echo AI_ALIGNMENT_CSS_STICK_TO_THE_RIGHT; ?>" data-css-<?php echo AI_SCROLL_WITH_THE_CONTENT; ?>="<?php echo AI_ALIGNMENT_CSS_STICK_TO_THE_RIGHT_SCROLL; ?>"
               value="<?php echo AI_STICK_TO_THE_RIGHT; ?>"
               data-title="<?php echo AI_TEXT_STICK_TO_THE_RIGHT; ?>" <?php echo ($horizontal_position == AI_STICK_TO_THE_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICK_TO_THE_RIGHT; ?></option>
          </select>
          <input type="text" id="horizontal-margin-<?php echo $block; ?>" style="width: 46px;" name="<?php echo AI_OPTION_HORIZONTAL_MARGIN, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_horizontal_margin (); ?>" value="<?php echo $obj->get_horizontal_margin (); ?>" size="5" maxlength="5" title="Horizontal margin from the content or screen edge, empty means default value from CSS" /> px
          <div style="clear: both;"></div>

          <div id="horizontal-positions-<?php echo $block; ?>"></div>
        </div>

        <div style="float: right;">
          <div style="text-align: right;">
            Vertical position
            <select id="vertical-position-<?php echo $block; ?>" name="<?php echo AI_OPTION_VERTICAL_POSITION, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_vertical_position(); ?>" style="margin-top: -1px;">
               <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-top"
                data-css="<?php echo AI_ALIGNMENT_CSS_STICK_TO_THE_TOP_OFFSET; ?>" data-css-<?php echo AI_STICK_HORIZONTAL_CENTER; ?>="<?php echo AI_ALIGNMENT_CSS_STICK_TO_THE_TOP; ?>"
                value="<?php echo AI_STICK_TO_THE_TOP; ?>" data-title="<?php echo AI_TEXT_STICK_TO_THE_TOP; ?>" <?php echo ($vertical_position == AI_STICK_TO_THE_TOP) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICK_TO_THE_TOP; ?></option>
               <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-center-vertical"
                 data-css="<?php echo AI_ALIGNMENT_CSS_CENTER_VERTICAL; ?>" data-css-<?php echo AI_STICK_HORIZONTAL_CENTER; ?>="<?php echo AI_ALIGNMENT_CSS_CENTER_VERTICAL_H_ANIM; ?>"
                 value="<?php echo AI_STICK_VERTICAL_CENTER; ?>" data-title="<?php echo AI_TEXT_CENTER; ?>" <?php echo ($vertical_position == AI_STICK_VERTICAL_CENTER) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_CENTER; ?></option>
               <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-scroll"
                 data-css="<?php echo AI_ALIGNMENT_CSS_SCROLL_WITH_THE_CONTENT; ?>"
                 value="<?php echo AI_SCROLL_WITH_THE_CONTENT; ?>" data-title="<?php echo AI_TEXT_SCROLL_WITH_THE_CONTENT; ?>" <?php echo ($vertical_position == AI_SCROLL_WITH_THE_CONTENT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_SCROLL_WITH_THE_CONTENT; ?></option>
               <option data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>" data-img-class="automatic-insertion im-sticky-bottom"
                 data-css="<?php echo AI_ALIGNMENT_CSS_STICK_TO_THE_BOTTOM_OFFSET; ?>" data-css-<?php echo AI_STICK_HORIZONTAL_CENTER; ?>="<?php echo AI_ALIGNMENT_CSS_STICK_TO_THE_BOTTOM; ?>"
                 value="<?php echo AI_STICK_TO_THE_BOTTOM; ?>" data-title="<?php echo AI_TEXT_STICK_TO_THE_BOTTOM; ?>" <?php echo ($vertical_position == AI_STICK_TO_THE_BOTTOM) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STICK_TO_THE_BOTTOM; ?></option>
            </select>
            <input type="text" id="vertical-margin-<?php echo $block; ?>" style="width: 46px;" name="<?php echo AI_OPTION_VERTICAL_MARGIN, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_vertical_margin (); ?>" value="<?php echo $obj->get_vertical_margin (); ?>" size="5" maxlength="5" title="Vertical margin from the top or bottom screen edge, empty means default value from CSS" /> px
            <div style="clear: both;"></div>
          </div>

          <div id="vertical-positions-<?php echo $block; ?>" style="float: right;"></div>
        </div>

        <div style="clear: both;"></div>
      </div>
<?php
  }
}

function ai_sticky_animation ($block, $obj, $default) {
  if (defined ('AI_STICKY_SETTINGS') && AI_STICKY_SETTINGS) {
    $animation           = $obj->get_animation ();
    $animation_trigger   = $obj->get_animation_trigger ();

    $close_button        = $obj->get_close_button ();
    $default_close_button = $default->get_close_button ();

?>
        <div id="sticky-animation-<?php echo $block; ?>" class="rounded" style="display: none;">
          <div class="max-input" style="margin: 0 0 8px 0;">
            <span style="display: table-cell; float: left;">
              Animation
              <select id="animation-<?php echo $block; ?>" name="<?php echo AI_OPTION_ANIMATION, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_animation (); ?>">
                 <option value="<?php echo AI_ANIMATION_NONE; ?>" <?php echo ($animation  == AI_ANIMATION_NONE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_NONE; ?></option>
                 <option value="<?php echo AI_ANIMATION_FADE; ?>" <?php echo ($animation  == AI_ANIMATION_FADE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_FADE; ?></option>
                 <option value="<?php echo AI_ANIMATION_SLIDE; ?>" <?php echo ($animation  == AI_ANIMATION_SLIDE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_SLIDE; ?></option>
                 <option value="<?php echo AI_ANIMATION_SLIDE_FADE; ?>" <?php echo ($animation  == AI_ANIMATION_SLIDE_FADE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_SLIDE_FADE; ?></option>
                 <option value="<?php echo AI_ANIMATION_TURN; ?>" <?php echo ($animation  == AI_ANIMATION_TURN) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_TURN; ?></option>
                 <option value="<?php echo AI_ANIMATION_FLIP; ?>" <?php echo ($animation  == AI_ANIMATION_FLIP) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_FLIP; ?></option>
                 <option value="<?php echo AI_ANIMATION_ZOOM_IN; ?>" <?php echo ($animation  == AI_ANIMATION_ZOOM_IN) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_ZOOM_IN; ?></option>
                 <option value="<?php echo AI_ANIMATION_ZOOM_OUT; ?>" <?php echo ($animation  == AI_ANIMATION_ZOOM_OUT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_ZOOM_OUT; ?></option>
              </select>
            </span>

<?php if (function_exists ('ai_display_close')) ai_display_close ($block, $obj, $default, 'close-button2-'.$block); ?>
          </div>

          <div class="max-input" style="margin: 8px 0 0 0;">
            <span style="display: table-cell; width: 1px; white-space: nowrap;">
              Trigger
              <select name="<?php echo AI_OPTION_ANIMATION_TRIGGER, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_animation_trigger (); ?>" style="margin-top: -1px;">
                 <option value="<?php echo AI_TRIGGER_PAGE_LOADED; ?>" <?php echo ($animation_trigger == AI_TRIGGER_PAGE_LOADED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_PAGE_LOADED; ?></option>
                 <option value="<?php echo AI_TRIGGER_PAGE_SCROLLED_PC; ?>" <?php echo ($animation_trigger == AI_TRIGGER_PAGE_SCROLLED_PC) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_PAGE_SCROLLED_PC; ?></option>
                 <option value="<?php echo AI_TRIGGER_PAGE_SCROLLED_PX; ?>" <?php echo ($animation_trigger == AI_TRIGGER_PAGE_SCROLLED_PX) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_PAGE_SCROLLED_PX; ?></option>
                 <option value="<?php echo AI_TRIGGER_ELEMENT_VISIBLE; ?>" <?php echo ($animation_trigger == AI_TRIGGER_ELEMENT_VISIBLE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_ELEMENT_VISIBLE; ?></option>
              </select>
            </span>
            <span style="display: table-cell; padding-right: 10px;">
              <input type="text" id="trigger-value-<?php echo $block; ?>" style="width: 100%;" name="<?php echo AI_OPTION_ANIMATION_TRIGGER_VALUE, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_animation_trigger_value (); ?>" value="<?php echo $obj->get_animation_trigger_value (); ?>" maxlength="60" title="Trigger value: page scroll in % or px or element selector (#id or .class)" />
            </span>

            <span style="display: table-cell; white-space: nowrap; padding-right: 10px;">
              Offset <input type="text" id="trigger-offset-<?php echo $block; ?>" style="width: 62px;" name="<?php echo AI_OPTION_ANIMATION_TRIGGER_OFFSET, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_animation_trigger_offset (); ?>" value="<?php echo $obj->get_animation_trigger_offset (); ?>" size="4" maxlength="5" title="Offset of trigger element" /> px
            </span>

            <span style="display: table-cell; white-space: nowrap; padding-right: 10px;">
              Delay <input type="text" id="trigger-delay-<?php echo $block; ?>" style="width: 62px;" name="<?php echo AI_OPTION_ANIMATION_TRIGGER_DELAY, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_animation_trigger_delay (); ?>" value="<?php echo $obj->get_animation_trigger_delay (); ?>" size="4" maxlength="5" title="Delay animation after trigger condition" /> ms
            </span>

            <span style="display: table-cell; white-space: nowrap; text-align: right;">
              Trigger once
              <input type="hidden" name="<?php echo AI_OPTION_ANIMATION_TRIGGER_ONCE, WP_FORM_FIELD_POSTFIX, $block; ?>" value="0" />
              <input type="checkbox" name="<?php echo AI_OPTION_ANIMATION_TRIGGER_ONCE, WP_FORM_FIELD_POSTFIX, $block; ?>" value="1" default="<?php echo $default->get_animation_trigger_once (); ?>" title="Trigger animation only once" <?php if ($obj->get_animation_trigger_once () == AI_ENABLED) echo 'checked '; ?> />
            </span>
          </div>
        </div>
<?php
  }
}


function chart_range ($max_value, $integer_value = false) {
  $scale = $max_value == 0 ? ($integer_value ? 5 : 1) : pow (10, intval (log10 ($max_value)));
  if ($max_value < 1) $scale = $scale / 10;
  if ($max_value > 5 * $scale) $scale *= 2;

  $chart_range = intval (($max_value + $scale ) / $scale ) * $scale;

  if ($integer_value) {
    if ($chart_range <= 5) {
      $chart_range = 5;
    } elseif ($chart_range <= 10) {
      $chart_range = 10;
    }
  }

  return $chart_range;
}


function ai_statistics_container ($block, $block_tracking_enabled) {
  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
    $gmt_offset = get_option ('gmt_offset') * 3600;
    $today = date ("Y-m-d", time () + $gmt_offset);
    $year = date ("Y", time () + $gmt_offset);

    $global_tracking = get_global_tracking ();
    $block_tracking = $block_tracking_enabled;

    if (!$global_tracking) {
      $style = '';
      $title = 'Tracking is globally disabled';
    }
    elseif (!$block_tracking) {
      $style = '';
      $title = 'Tracking for this block is disabled';
    }
    else {
      $style = 'display: none;';
      $title = '';
    }

?>
  <div id='statistics-container-<?php echo $block; ?>' style='margin: 8px 0; display: none;'>
    <div id='statistics-elements-<?php echo $block; ?>' class='ai-charts' style='margin: 8px 0;'>
      <div class='ai-chart-container'><div style='position: absolute; top: 0px; left: 8px;'>Loading...</div>
        <div class='ai-chart not-configured' style='margin: 8px 0;'></div>
      </div>
<?php
  if ($block != 0) {
?>
      <div class='ai-chart not-configured' style='margin: 8px 0;'></div>
      <div class='ai-chart not-configured' style='margin: 8px 0;'></div>
<?php
  }
?>
    </div>
    <div title='<?php echo $title; ?>' style='float: left; font-size: 18px; font-weight: bold; margin: 3px 0 0 0; <?php echo $style; ?>'>&#x26A0;</div>
    <div class='custom-range-controls' id='custom-range-controls-<?php echo $block; ?>' class="custom-range-controls" style='margin: 8px auto;'>
      <span class="ai-toolbar-button text">
        <input type="checkbox" value="0" id="clear-range-<?php echo $block; ?>" style="display: none;" />
        <label class="checkbox-button" for="clear-range-<?php echo $block; ?>" title="Clear statistics data for the selected range - clear both dates to delete all data for this block"><span class="checkbox-icon icon-none"></span></label>
      </span>
      <span class="ai-toolbar-button text">
        <input type="checkbox" value="0" id="auto-refresh-<?php echo $block; ?>" style="display: none;" />
        <label class="checkbox-button" for="auto-refresh-<?php echo $block; ?>" title="Auto refresh data for the selected range every 60 seconds"><span class="checkbox-icon size-12 icon-auto-refresh"></span></label>
      </span>
      <span class="ai-toolbar-button text">
        <span class="checkbox-button data-range" title="Load data for last month" data-start-date="<?php echo date ("Y-m", strtotime ('-1 month') + $gmt_offset); ?>-01" data-end-date="<?php echo date ("Y-m-t", strtotime ('-1 month') + $gmt_offset); ?>">Last Month</span>
      </span>
      <span class="ai-toolbar-button text">
        <span class="checkbox-button data-range" title="Load data for this month" data-start-date="<?php echo date ("Y-m", time () + $gmt_offset); ?>-01" data-end-date="<?php echo date ("Y-m-t", time () + $gmt_offset); ?>">This Month</span>
      </span>
      <span class="ai-toolbar-button text">
        <span class="checkbox-button data-range" title="Load data for this year" data-start-date="<?php echo $year; ?>-01-01" data-end-date="<?php echo $year; ?>-12-31">This Year</span>
      </span>
      <span class="ai-toolbar-button text">
        <span class="checkbox-button data-range" title="Load data for the last 15 days" data-start-date="<?php echo date ("Y-m-d", strtotime ($today) - 14 * 24 * 3600); ?>" data-end-date="<?php echo $today; ?>">15</span>
      </span>
      <span class="ai-toolbar-button text">
        <span class="checkbox-button data-range" title="Load data for the last 30 days" data-start-date="<?php echo date ("Y-m-d", strtotime ($today) - 29 * 24 * 3600); ?>" data-end-date="<?php echo $today; ?>">30</span>
      </span>
      <span class="ai-toolbar-button text">
        <span class="checkbox-button data-range" title="Load data for the last 90 days" data-start-date="<?php echo date ("Y-m-d", strtotime ($today) - 89 * 24 * 3600); ?>" data-end-date="<?php echo $today; ?>">90</span>
      </span>
      <span class="ai-toolbar-button text">
        <span class="checkbox-button data-range" title="Load data for the last 180 days" data-start-date="<?php echo date ("Y-m-d", strtotime ($today) - 179 * 24 * 3600); ?>" data-end-date="<?php echo $today; ?>">180</span>
      </span>
      <span class="ai-toolbar-button text">
        <span class="checkbox-button data-range" title="Load data for the last 365 days" data-start-date="<?php echo date ("Y-m-d", strtotime ($today) - 364 * 24 * 3600); ?>" data-end-date="<?php echo $today; ?>">365</span>
      </span>
      <span class="ai-toolbar-button text">
        <input class='ai-date-input' id="chart-start-date-<?php echo $block; ?>" type="text" value="<?php echo date ("Y-m-d", strtotime ($today) - 29 * 24 * 3600); ?>" />
      </span>
      <span class="ai-toolbar-button text">
        <input class='ai-date-input' id="chart-end-date-<?php echo $block; ?>" type="text" value="<?php echo $today; ?>" />
      </span>
      <span class="ai-toolbar-button text">
        <input type="checkbox" value="0" id="load-custom-range-<?php echo $block; ?>" nonce="<?php echo wp_create_nonce ("adinserter_data"); ?>" site-url="<?php echo wp_make_link_relative (get_site_url()); ?>" style="display: none;" />
        <label class="checkbox-button" for="load-custom-range-<?php echo $block; ?>" title="Load data for the selected range"><span class="checkbox-icon size-12 icon-loading"></span></label>
      </span>
    </div>
    <div style="clear: both;"></div>
    <div id='load-error-<?php echo $block; ?>' class="custom-range-controls" style='text-align: center; color: red; margin: 8px 0; width: 100%;'></div>
  </div>
<?php
  }
}

function ai_settings_container ($block, $obj) {
?>
  <div id="export-container-<?php echo $block; ?>" style="display: none; padding:8px;">
    <div style="display: inline-block; padding: 2px 10px; float: right;">
      <input type="hidden"   name="<?php echo AI_OPTION_IMPORT, WP_FORM_FIELD_POSTFIX, $block; ?>" value="0" />
      <input type="checkbox" name="<?php echo AI_OPTION_IMPORT, WP_FORM_FIELD_POSTFIX, $block; ?>" value="1" default="0" id="import-<?php echo $block; ?>" />
      <label for="import-<?php echo $block; ?>" style="padding-right: 10px;" title="Import settings when saving - if checked, the encoded settings below will be imported for this block">Import settings for block <?php echo $block; ?></label>

      <input type="hidden"   name="<?php echo AI_OPTION_IMPORT_NAME, WP_FORM_FIELD_POSTFIX, $block; ?>" value="0" />
      <input type="checkbox" name="<?php echo AI_OPTION_IMPORT_NAME, WP_FORM_FIELD_POSTFIX, $block; ?>" value="1" default="1" id="import-name-<?php echo $block; ?>" checked />
      <label for="import-name-<?php echo $block; ?>" title="Import block name when saving - if checked and 'Import settings for block' is also checked, the name from encoded settings below will be imported for this block">Import block name</label>
    </div>

    <div style="float: left; padding-left:10px;">
      Saved settings for block <?php echo $block; ?>
    </div>
    <textarea id="export_settings_<?php echo $block; ?>" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace; font-weight: bold; width: 719px; height: 324px;"></textarea>
  </div>

<?php
  ai_statistics_container ($block, $obj->get_tracking (true));
}

function ai_settings_global_buttons () {
?>
    <span style="vertical-align: top; width: 28px; padding: 0px 10px 0 10px;">
      <input type="checkbox" value="0" id="export-switch-0" nonce="<?php echo wp_create_nonce ("adinserter_data"); ?>" site-url="<?php echo wp_make_link_relative (get_site_url()); ?>" style="display: none;" />
      <label class="checkbox-button" for="export-switch-0" title="Export / Import Ad Inserter Pro Settings"><span class="checkbox-icon icon-export-import"></span></label>
    </span>
<?php
}

function ai_settings_global_actions () {
  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
?>
      <input id="clear-statistics-0"
        onclick="if (confirm('Are you sure you want to clear all statistics data for all blocks?')) {document.getElementById ('clear-statistics-0').style.visibility = 'hidden'; document.getElementById ('clear-statistics-0').value = '0'; return true;} return false;"
        name="<?php echo AI_FORM_CLEAR_STATISTICS; ?>"
        value="Clear All Statistics Data" type="submit" style="display: none; margin-left: 8px; font-weight: bold; color: #e44;" />
<?php
  }
}

function ai_settings_side () {
}

function ai_lists ($obj) {
  global $ip_address_list, $country_list;

  $ip_address_list = $obj->get_ad_ip_address_list ();
  $country_list    = $obj->get_ad_country_list ();

  return $ip_address_list != '' || $country_list != '';
}

function ai_list_rows ($block, $default, $obj) {
  global $ip_address_list, $country_list;
?>
        <tr>
          <td>
            IP Addresses
          </td>
          <td>
            <button id="ip-address-button-<?php echo $block; ?>" type="button" class='ai-button' style="display: none; outline: transparent; float: right; margin-top: 1px; width: 15px; height: 15px;" title="Toggle IP address editor"></button>
          </td>
          <td style="padding-right: 7px;">
            <input id="ip-address-list-<?php echo $block; ?>" class="ai-list-sort" style="width: 100%;" title="Comma separated IP addresses, you can also use partial IP addresses with * (/ip-address-start*. *ip-address-pattern*, *ip-address-end)" type="text" name="<?php echo AI_OPTION_IP_ADDRESS_LIST, WP_FORM_FIELD_POSTFIX, $block; ?>" id="ip-address-list-<?php echo $block; ?>" default="<?php echo $default->get_ad_ip_address_list(); ?>" value="<?php echo $ip_address_list; ?>" size="54" maxlength="500"/>
          </td>
          <td style="padding-right: 7px;">
            <input type="radio" name="<?php echo AI_OPTION_IP_ADDRESS_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $block; ?>" id="ip-address-blacklist-<?php echo $block; ?>" default="<?php echo $default->get_ad_ip_address_list_type() == AD_BLACK_LIST; ?>" value="<?php echo AD_BLACK_LIST; ?>" <?php if ($obj->get_ad_ip_address_list_type() == AD_BLACK_LIST) echo 'checked '; ?> />
            <label for="ip-address-blacklist-<?php echo $block; ?>" title="Blacklist IP addresses"><?php echo AD_BLACK_LIST; ?></label>
          </td>
          <td>
            <input type="radio" name="<?php echo AI_OPTION_IP_ADDRESS_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $block; ?>" id="ip-address-whitelist-<?php echo $block; ?>" default="<?php echo $default->get_ad_ip_address_list_type() == AD_WHITE_LIST; ?>" value="<?php echo AD_WHITE_LIST; ?>" <?php if ($obj->get_ad_ip_address_list_type() == AD_WHITE_LIST) echo 'checked '; ?> />
            <label for="ip-address-whitelist-<?php echo $block; ?>" title="Whitelist IP addresses"><?php echo AD_WHITE_LIST; ?></label>
          </td>
        </tr>
        <tr>
          <td colspan="5">
            <textarea id="ip-address-editor-<?php echo $block; ?>" style="width: 100%; height: 220px; font-family: Courier, 'Courier New', monospace; font-weight: bold; display: none;"></textarea>
          </td>
        </tr>

        <tr>
          <td>
            Countries
          </td>
          <td>
            <button id="country-button-<?php echo $block; ?>" type="button" class='ai-button' style="display: none; outline: transparent; float: right; margin-top: 1px; width: 15px; height: 15px;" title="Toggle country editor"></button>
          </td>
          <td style="padding-right: 7px;">
            <input id="country-list-<?php echo $block; ?>" class="ai-list-uppercase" style="width: 100%;" title="Comma separated country ISO Alpha-2 codes" type="text" name="<?php echo AI_OPTION_COUNTRY_LIST, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_ad_country_list(); ?>" value="<?php echo $country_list; ?>" size="54" maxlength="500"/>
          </td>
          <td style="padding-right: 7px;">
            <input type="radio" name="<?php echo AI_OPTION_COUNTRY_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $block; ?>" id="country-blacklist-<?php echo $block; ?>" default="<?php echo $default->get_ad_country_list_type() == AD_BLACK_LIST; ?>" value="<?php echo AD_BLACK_LIST; ?>" <?php if ($obj->get_ad_country_list_type() == AD_BLACK_LIST) echo 'checked '; ?> />
            <label for="country-blacklist-<?php echo $block; ?>" title="Blacklist countries"><?php echo AD_BLACK_LIST; ?></label>
          </td>
          <td>
            <input type="radio" name="<?php echo AI_OPTION_COUNTRY_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $block; ?>" id="country-whitelist-<?php echo $block; ?>" default="<?php echo $default->get_ad_country_list_type() == AD_WHITE_LIST; ?>" value="<?php echo AD_WHITE_LIST; ?>" <?php if ($obj->get_ad_country_list_type() == AD_WHITE_LIST) echo 'checked '; ?> />
            <label for="country-whitelist-<?php echo $block; ?>" title="Whitelist countries"><?php echo AD_WHITE_LIST; ?></label>
          </td>
        </tr>
        <tr>
          <td colspan="5" class="country-flags">
            <select id="country-select-<?php echo $block; ?>" multiple="multiple" default="" style="display: none;">
            </select>
          </td>
        </tr>
<?php
}

function expanded_country_list ($country_list) {
  global $ad_inserter_globals;

  for ($group = AD_INSERTER_GEO_GROUPS; $group >= 1; $group --) {
    $global_name = 'G'.$group;
    $iso_name = 'G'.($group % 10);
    $country_list = str_replace ($iso_name, $ad_inserter_globals [$global_name], $country_list);
  }
  return $country_list;
}

function ai_check_lists ($obj) {
  global $ai_last_check, $ai_wp_data;

  if (get_dynamic_blocks () == AI_DYNAMIC_BLOCKS_SERVER_SIDE || (get_dynamic_blocks () == AI_DYNAMIC_BLOCKS_CLIENT_SIDE && $ai_wp_data [AI_WP_AMP_PAGE])) {
    $ai_last_check = AI_CHECK_IP_ADDRESS;
    if (!check_ip_address ($obj)) return false;

    $ai_last_check = AI_CHECK_COUNTRY;
    if (!check_country ($obj)) return false;
  }

  return true;
}

function check_ip_address ($obj) {
  return check_ip_address_list ($obj->get_ad_ip_address_list (), $obj->get_ad_ip_address_list_type () == AD_WHITE_LIST);
}

function check_country ($obj) {
  return check_country_list ($obj->get_ad_country_list (true), $obj->get_ad_country_list_type () == AD_WHITE_LIST);
}

function ai_tags (&$ad_data) {
  global $ai_wp_data;

  if (strpos ($ad_data, '{ip') !== false || strpos ($ad_data, '{country') !== false) {
    if (!isset ($ai_wp_data [AI_TAGS]['IP_ADDRESS'])) {
      $client_ip_address = get_client_ip_address ();

      $ai_wp_data [AI_TAGS]['IP_ADDRESS'] = strtolower ($client_ip_address);
      $ai_wp_data [AI_TAGS]['COUNTRY_LC'] = strtolower (ip_to_country ($client_ip_address));
      $ai_wp_data [AI_TAGS]['COUNTRY_UC'] = strtoupper ($ai_wp_data [AI_TAGS]['COUNTRY_LC']);
    }

    $ad_data = preg_replace ("/{ip-address}/i",   $ai_wp_data [AI_TAGS]['IP_ADDRESS'], $ad_data);
    $ad_data = preg_replace ("/{country-iso2}/",  $ai_wp_data [AI_TAGS]['COUNTRY_LC'], $ad_data);
    $ad_data = preg_replace ("/{country-ISO2}/",  $ai_wp_data [AI_TAGS]['COUNTRY_UC'], $ad_data);

    $ad_data = preg_replace ("/{ip_address}/i",   $ai_wp_data [AI_TAGS]['IP_ADDRESS'], $ad_data);
    $ad_data = preg_replace ("/{country_iso2}/",  $ai_wp_data [AI_TAGS]['COUNTRY_LC'], $ad_data);
    $ad_data = preg_replace ("/{country_ISO2}/",  $ai_wp_data [AI_TAGS]['COUNTRY_UC'], $ad_data);

  }
}

define ('AI_PRO',       'PLUG' . 'IN' . '_' . 'TYPE');
define ('AI_CODE',      'PLUG' . 'IN' . '_' . 'STAT' . 'US');
define ('AI_RST',       'PLUG' . 'IN' . '_' . 'STAT' . 'US' . '_' . 'COUNT' . 'ER');
define ('AI_CODE_TIME', 'PLUG' . 'IN' . '_' . 'STAT' . 'US' . '_' . 'TIME' . 'STAMP');

function ai_debug_header () {
  global $ad_inserter_globals;

  if (!is_multisite() || is_main_site ()) {
    $license_key = $ad_inserter_globals ['LICENSE_KEY'];
    $ai_status = $ad_inserter_globals ['AI_STATUS'];

    if (empty ($license_key)) {
      echo " UNLICENSED COPY";
    }
    elseif (!empty ($ai_status)) {
      echo " ($ai_status)";
    }
  }
}

function ai_debug () {
  ai_check_geo_settings ();
  echo 'IP ADDRESS:              ', get_client_ip_address (), "\n";
  echo 'COUNTRY:                 ', ip_to_country (get_client_ip_address (), true), "\n";
  echo 'GLOBAL TRACKING:         ', get_global_tracking () == AI_TRACKING_ENABLED ? 'ENABLED' : 'DISABLED', "\n";
  echo 'INTERNAL TRACKING:       ', get_internal_tracking () == AI_ENABLED ? 'ENABLED' : 'DISABLED', "\n";
  echo 'EXTERNAL TRACKING:       ', get_external_tracking () == AI_ENABLED ? 'ENABLED' : 'DISABLED', "\n";
  echo 'TRACK PAGEVIEWS:         ', get_track_pageviews () == AI_TRACKING_ENABLED ? 'ENABLED' : 'DISABLED', "\n";
  echo 'TRACK LOGGED IN UESRS:   ', get_track_logged_in () == AI_TRACKING_ENABLED ? 'ENABLED' : 'DISABLED', "\n";
  echo 'CLICK DETECTION:         ';
  switch (get_click_detection ()) {
    case AI_CLICK_DETECTION_STANDARD:
      echo AI_TEXT_STANDARD;
      break;
    case AI_CLICK_DETECTION_ADVANCED:
      echo AI_TEXT_ADVANCED;
      break;
  }
  echo "\n";
  if (defined ('AD_INSERTER_MAXMIND')) {
    echo 'IP GEOLOCATION DATABASE: ';
    switch (get_geo_db ()) {
      case AI_GEO_DB_WEBNET77:
        echo AI_TEXT_WEBNET77;
        break;
      case AI_GEO_DB_MAXMIND:
        echo AI_TEXT_MAXMIND;
        break;
    }
    echo "\n";
    echo 'AUTOMATIC DB UPDATES:    ', get_geo_db_updates () ? 'ENABLED' : 'DISABLED', "\n";
    echo 'DATABASE:                ', get_geo_db_location (true), " (", get_geo_db_location (), ")\n";
  }
}

function ai_check_options (&$plugin_options) {
  for ($group_number = 1; $group_number <= AD_INSERTER_GEO_GROUPS; $group_number ++) {
    $country_group_settins_name   = 'COUNTRY_GROUP_NAME_' . $group_number;
    $group_countries_settins_name = 'GROUP_COUNTRIES_' . $group_number;

    if (!isset ($plugin_options [$country_group_settins_name])) {
      $plugin_options [$country_group_settins_name] = DEFAULT_COUNTRY_GROUP_NAME . ' ' . $group_number;
    }

    if (!isset ($plugin_options [$group_countries_settins_name])) {
      $plugin_options [$group_countries_settins_name] = '';
    }
  }

  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
    if (!isset ($plugin_options ['TRACKING']))                      $plugin_options ['TRACKING']                      = DEFAULT_TRACKING;
    if (!isset ($plugin_options ['INTERNAL_TRACKING']))             $plugin_options ['INTERNAL_TRACKING']             = DEFAULT_INTERNAL_TRACKING;
    if (!isset ($plugin_options ['EXTERNAL_TRACKING']))             $plugin_options ['EXTERNAL_TRACKING']             = DEFAULT_EXTERNAL_TRACKING;
    if (!isset ($plugin_options ['TRACKING_LOGGED_IN']))            $plugin_options ['TRACKING_LOGGED_IN']            = DEFAULT_TRACKING_LOGGED_IN;
    if (!isset ($plugin_options ['TRACK_PAGEVIEWS']))               $plugin_options ['TRACK_PAGEVIEWS']               = DEFAULT_TRACK_PAGEVIEWS;
    if (!isset ($plugin_options ['CLICK_DETECTION']))               $plugin_options ['CLICK_DETECTION']               = DEFAULT_CLICK_DETECTION;
  }

  if (!isset ($plugin_options ['ADB_DETECTION']))                 $plugin_options ['ADB_DETECTION']                 = DEFAULT_ADB_DETECTION;
  if (!isset ($plugin_options ['GEO_DB']))                        $plugin_options ['GEO_DB']                        = DEFAULT_GEO_DB;
  if (!isset ($plugin_options ['GEO_DB_UPDATES']))                $plugin_options ['GEO_DB_UPDATES']                = DEFAULT_GEO_DB_UPDATES;
  if (!isset ($plugin_options ['GEO_DB_LOCATION']))               $plugin_options ['GEO_DB_LOCATION']               = DEFAULT_GEO_DB_LOCATION;
}

function ai_nonce_life () {
  return 48 * 3600;
}

function ai_hooks () {
  global $ai_wp_data, $ad_inserter_globals;

//  if ($ai_wp_data [AI_TRACKING]) {
//    add_filter ('nonce_life',           'ai_nonce_life');
//  }

  if (!is_multisite() || is_main_site ()) {
    $license_key = $ad_inserter_globals ['LICENSE_KEY'];
    $status = $ad_inserter_globals ['AI_STATUS'];

    require AD_INSERTER_PLUGIN_DIR.'includes/update-checker/plugin-update-checker.php';

    if (!empty ($license_key)) {
      $ai_update_checker = Puc_v4_Factory::buildUpdateChecker (
        WP_UPDATE_SERVER.'?action=get_metadata&slug=' . AD_INSERTER_SLUG,
      AD_INSERTER_PLUGIN_DIR.'ad-inserter.php',
      AD_INSERTER_SLUG
      );

      $ai_update_checker->addFilter ('check_now', 'ai_puc_check_now', 10, 3);

      $ai_update_checker->addFilter ('request_info_result', 'puc_request_info_result', 10, 1);

      $ai_update_checker->addQueryArgFilter ('ai_filter_update_checks');

      if (AD_INSERTER_SLUG != 'ad-inserter-pro') {
        $ai_update_checker->addQueryArgFilter ('ai_check_slug');
      }
    } else add_action ('wp_update_plugins', 'ai_wp_update_plugins', 10, 1);

    add_filter ('cron_schedules', 'ai_cron_schedules');
    register_activation_hook    (AD_INSERTER_PLUGIN_DIR.'ad-inserter.php', 'ai_activation_hook');
    register_deactivation_hook  (AD_INSERTER_PLUGIN_DIR.'ad-inserter.php', 'ai_deactivation_hook' );
    add_action ('ai_keep_updated_ip_db', 'ai_update_ip_db');

    add_action ('after_plugin_row_' . AD_INSERTER_SLUG . '/ad-inserter.php', 'ai_after_plugin_row', 10, 3);

    add_action ('network_admin_notices', 'ai_admin_notices');

    if (defined ('AD_INSERTER_MAXMIND')) {
      if (!is_multisite() || is_main_site ()) {
        if (get_geo_db () == AI_GEO_DB_MAXMIND) {
          add_filter ('http_headers_useragent', 'ai_http_headers_useragent');
        }
      }
    }
  }
}

function ai_http_headers_useragent ($useragent) {
  global $ai_wp_data;

  if (isset ($ai_wp_data [AI_USER_AGENT])) $useragent = get_bloginfo ('url');

  return $useragent;
}


function ai_filter_update_checks ($queryArgs) {
  global $ad_inserter_globals, $wp_version;

  $license_key = $ad_inserter_globals ['LICENSE_KEY'];
  if (!empty ($license_key)) {
    $queryArgs ['license_key'] = $license_key;
  }

  // Test
  $queryArgs ['status']     = $ad_inserter_globals ['AI_STATUS'];
  $queryArgs ['type']       = $ad_inserter_globals ['AI_TYPE'];
  $queryArgs ['counter']    = $ad_inserter_globals ['AI_COUNTER'];
  $queryArgs ['website']    = get_bloginfo ('url');
  $queryArgs ['wp_version'] = $wp_version;

  return $queryArgs;
}

function ai_wp_update_plugins ($wp_update_plugins) {
  global $ad_inserter_globals;

  if (empty ($ad_inserter_globals ['LICENSE_KEY'])) {
    update_state ();
  }

  return $wp_update_plugins;
};

function ai_check_slug ($queryArgs) {
  if (file_exists  (AD_INSERTER_PLUGIN_DIR)) {
    @rename (AD_INSERTER_PLUGIN_DIR, str_replace (AD_INSERTER_SLUG, 'ad-inserter-pro', AD_INSERTER_PLUGIN_DIR));
    if (is_multisite()) {
      $active_plugins = get_site_option ('active_sitewide_plugins');
      if (isset ($active_plugins [AD_INSERTER_SLUG.'/ad-inserter.php'])) {
        $active_plugins ['ad-inserter-pro/ad-inserter.php'] = $active_plugins [AD_INSERTER_SLUG.'/ad-inserter.php'];
        unset ($active_plugins [AD_INSERTER_SLUG.'/ad-inserter.php']);
        update_site_option ('active_sitewide_plugins', $active_plugins);
      }
    } else {
        $active_plugins = get_option ('active_plugins');
        $index = array_search (AD_INSERTER_SLUG.'/ad-inserter.php', $active_plugins);
        if ($index !== false) {
          $active_plugins [$index] = 'ad-inserter-pro/ad-inserter.php';
          update_option ('active_plugins', $active_plugins);
        }
      }
    wp_clear_scheduled_hook ('check_plugin_updates-'.AD_INSERTER_SLUG);
    wp_clear_scheduled_hook ('ai_keep_updated_ip_db');
    wp_schedule_event (time() + 1000, 'ai_ip_dp_update', 'ai_keep_updated_ip_db');
  }
  return $queryArgs;
}

function ai_after_plugin_row ($plugin_file, $plugin_data, $status) {
  global $ad_inserter_globals;

  if (!is_multisite() || is_main_site ()) {

    $license_key = $ad_inserter_globals ['LICENSE_KEY'];
    if ($license_key == '') {
      echo '<tr class="active';
      if (isset ($plugin_data ['update']) && $plugin_data ['update']) echo ' update';
      echo '"><th class="check-column"></th><td><a href="'.admin_url ('options-general.php?page=ad-inserter.php&tab=0').'">Enter License Key</a></td><td>', AD_INSERTER_NAME, ' license key is not set - updates disabled.</td></tr>';
    } else {
        $ai_status = $ad_inserter_globals ['AI_STATUS'];
        if (is_numeric ($ai_status)) {
          switch ($ai_status) {
            case - 19:
              echo '<tr class="active';
              if (isset ($plugin_data ['update']) && $plugin_data ['update']) echo ' update';
              echo '"><th class="check-column"></th><td><a href="'.admin_url ('options-general.php?page=ad-inserter.php&tab=0').'">Check License Key</a></td><td>Invalid ', AD_INSERTER_NAME, ' license key. Please check.</td></tr>';
              break;
            case - 20:
              echo '<tr class="active';
              if (isset ($plugin_data ['update']) && $plugin_data ['update']) echo ' update';
              echo '"><th class="check-column"></th><td><a href="http://adinserter.pro/index.php?option=com_mediashop&task=download&tid=', $license_key, '&lang=en" target="_blank">Renew License</a></td><td>', AD_INSERTER_NAME, ' license expired. Please renew the license to enable updates.</td></tr>';
              break;
            case - 21:
              echo '<tr class="active';
              if (isset ($plugin_data ['update']) && $plugin_data ['update']) echo ' update';
              echo '"><th class="check-column"></th><td><a href="http://adinserter.pro/index.php?option=com_mediashop&task=download&tid=', $license_key, '&lang=en" target="_blank">Upgrade License</a></td><td><strong>', AD_INSERTER_NAME, '</strong> license overused. Please upgrade the license to enable updates.</td></tr>';
              break;
          }
        }
      }
  }
}

function restore_ai () {
  @unlink (__FILE__);
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

function update_state ($state = 1) {
  global $ad_inserter_globals, $ai_db_options;

  $restore = false;
  $ai_options = get_option (AI_OPTION_NAME);
  if ($state == 1) {
    if (isset ($ai_options [AI_OPTION_GLOBAL][AI_RST])) $ai_options [AI_OPTION_GLOBAL][AI_RST] ++; else $ai_options [AI_OPTION_GLOBAL][AI_RST] = 1;
  } else $ai_options [AI_OPTION_GLOBAL][AI_RST] = $state;
  if ($ai_options [AI_OPTION_GLOBAL][AI_RST] > 16) {
    $ai_options [AI_OPTION_GLOBAL][AI_RST] = 1;
    $restore = true;
  }
  update_option (AI_OPTION_NAME, $ai_options);
  $ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_STATUS_COUNTER'] = $ai_options [AI_OPTION_GLOBAL]['PLUGIN_STATUS_COUNTER'];
  $ad_inserter_globals ['AI_COUNTER']  = get_plugin_counter ();

  if ($restore) restore_ai ();
}

function get_ai_data ($license_key) {
  $ai_data = null;
  $response = wp_remote_get (WP_UPDATE_SERVER.'status.php?data='.$license_key);
  if (is_array ($response)) {
    $ai_data = json_decode (wp_remote_retrieve_body ($response));
  }
  return $ai_data;
}

function ai_puc_check_now ($current_decision, $last_check, $check_period) {
  global $ad_inserter_globals, $ai_db_options;

  $license_key = $ad_inserter_globals ['LICENSE_KEY'];

  if (!empty ($license_key) && $current_decision) {
    $ai_data = get_ai_data ($license_key);
    if (isset ($ai_data->sid)) {
      $ai_code = $ai_data->sid;
      $ai_type = $ai_data->pid;
      if ($ai_code != $ad_inserter_globals ['AI_STATUS'] || $ai_type != $ad_inserter_globals ['AI_TYPE']) {
        $ad_inserter_globals ['AI_STATUS'] = $ai_code;
        $ad_inserter_globals ['AI_TYPE']   = $ai_type;
        $ai_options = get_option (AI_OPTION_NAME);
        $ai_options [AI_OPTION_GLOBAL][AI_PRO]  = filter_string ($ai_type);
        $ai_options [AI_OPTION_GLOBAL][AI_CODE] = filter_string ($ai_code);
        $ai_options [AI_OPTION_GLOBAL][AI_CODE_TIME] = time ();
        update_option (AI_OPTION_NAME, $ai_options);
      } else {
          $response = wp_remote_get (WP_UPDATE_SERVER.'status.php?tid='.$license_key);
          if (is_array ($response)) {
            $ai_code_tid = wp_remote_retrieve_body ($response);
            if ($ai_code == $ai_code_tid && is_numeric ($ai_code) && $ai_code != '') {
              if ($ai_code <= - 2 && $ai_code >= - 5) {
                restore_ai ();
                $current_decision = false;
              }
              elseif ($ai_code == - 19) {
                update_state ();
              }
              elseif ($ad_inserter_globals ['AI_COUNTER'] != 0) {
                update_state (0);
              }
            }
          }
        }
    }
  }
  return $current_decision;
}

function ai_activation_hook () {
  $timestamp = wp_next_scheduled ('ai_keep_updated_ip_db');
  if ($timestamp == false){
    wp_schedule_event (time() + 1000, 'ai_ip_dp_update', 'ai_keep_updated_ip_db');
  }
}

function ai_deactivation_hook () {
  wp_clear_scheduled_hook ('ai_keep_updated_ip_db' );

  $upload_dir = wp_upload_dir();
  $script_path_ai = $upload_dir ['basedir'] . '/ad-inserter/';
  recursive_remove_directory ($script_path_ai);
}

function ai_cron_schedules ($schedules) {
  $schedules ['ai_ip_dp_update'] = array(
    'interval' => IP_DB_UPDATE_DAYS * 24 * 3600,
    'display' => 'Every '.IP_DB_UPDATE_DAYS.' Days',
  );

  return $schedules;
}

function ai_admin_notices (){
  global $ad_inserter_globals;

  if (!is_multisite() || is_main_site ()) {
    $ai_status = $ad_inserter_globals ['AI_STATUS'];
    $license_key = $ad_inserter_globals ['LICENSE_KEY'];

    if (empty ($license_key) && !isset ($_POST ['license_key']) || isset ($_POST ['license_key']) && trim ($_POST ['license_key']) == '') {
      echo "<div class='notice notice-warning'><p><strong>Warning</strong>: ", AD_INSERTER_NAME, " <a href=\"", admin_url ('options-general.php?page=ad-inserter.php&tab=0'), "\">license key</a> not set - plugin functionality limited.</p></div>";
    }
    elseif ($ai_status == - 19) {
      echo "<div class='notice notice-error'><p><strong>Warning</strong>: Invalid ", AD_INSERTER_NAME, " license key - updates disabled. Please <a href=\"", admin_url ('options-general.php?page=ad-inserter.php&tab=0'), "\">check license key</a>.</p></div>";
    }
    elseif ($ai_status == - 20) {
      if (is_super_admin () && !wp_is_mobile ()) {
        $notice_renew_option = get_option ('ai-notice-renew');

        if ($notice_renew_option != 'no' && (!is_numeric ($notice_renew_option) || time () - $notice_renew_option > 30 * 24 * 3600)) {
          $message = "<div style='margin: 5px 0;'>Hey, " . AD_INSERTER_NAME . " license has expired - plugin updates are now disabled. Please renew the license to enable updates. Check <a href='https://adinserter.pro/version-history' target='_blank' style='text-decoration: none; box-shadow: 0 0 0;'>what you are missing</a>.</div>
                      <div style='margin: 5px 0;'>During the license period and 30 days after the license has expired we offer <strong>20% discount on all license renewals and license upgrades</strong>.</div>";

          if (is_numeric ($notice_renew_option)) {
              $option = '<div class="ai-notice-text-button ai-notice-dismiss" data-notice="no">No, thank you.</div>';
          } else {
              $option = '<div class="ai-notice-text-button ai-notice-dismiss" data-notice="' . time () . '">Not now, maybe later.</div>';
            }

          $data_notice = is_numeric ($notice_renew_option) ? $notice_renew_option : '';
  ?>
      <div class="notice notice-info ai-notice ai-no-phone" style="display: none;" data-notice="renew" nonce="<?php echo wp_create_nonce ("adinserter_data"); ?>" >
        <div class="ai-notice-element">
          <img src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>icon-50x50.jpg" style="width: 50px; margin: 5px 10px 0px 10px;" />
        </div>
        <div class="ai-notice-element" style="width: 100%; padding: 0 10px 0;">
          <?php echo $message; ?>
        </div>
        <div class="ai-notice-element ai-notice-buttons last">
          <button class="button-primary ai-notice-dismiss" data-notice="<?php echo $data_notice ?>">
            <a href="http://adinserter.pro/index.php?option=com_mediashop&task=download&tid=<?php echo $license_key; ?>&lang=en" class="ai-notice-dismiss" target="_blank" data-notice="<?php echo $data_notice ?>">Renew the licence</a>
          </button>
          <div class="ai-notice-text-button ai-notice-dismiss" data-notice="<?php echo $data_notice ?>"><a href="<?php echo admin_url ('update-core.php?force-check=1'); ?>" class="ai-notice-dismiss" style="color: #bbb;" data-notice="<?php echo $data_notice ?>">Update license status</a></div>
          <?php echo $option; ?>
        </div>
      </div>

    <?php
        }
      }
    }
    elseif ($ai_status == - 21) {
      echo "<div class='notice notice-warning'><p><strong>Warning</strong>: ", AD_INSERTER_NAME, " license overused - updates disabled. Please <a href=\"http://adinserter.pro/index.php?option=com_mediashop&task=download&tid=", $license_key, "&lang=en\" target=\"_blank\">upgrade the license</a>.</p></div>";
    }
    elseif ($ai_status == 0) {
      delete_option ('ai-notice-renew');
    }
  }
}

function ai_admin_settings_notices () {
  if (defined ('AD_INSERTER_MAXMIND')) {
    ai_check_geo_settings ();
    if (!is_multisite() || is_main_site ()) {
      if (get_geo_db () == AI_GEO_DB_MAXMIND && !defined ('AI_MAXMIND_DB')) {
        echo "<div class='notice notice-error is-dismissible'><p><strong>", AD_INSERTER_NAME, " Warning</strong>: MaxMind IP geolocation database not found. <span class='maxmind-db-missing' style='color: #f00;'></span></p></div>";
      }
    }
  }
}

function ai_update_ip_db_webnet77 () {

  require_once AD_INSERTER_PLUGIN_DIR.'includes/geo/process_csv.php';
  require_once AD_INSERTER_PLUGIN_DIR.'includes/geo/process6_csv.php';

  global $ad_inserter_globals;

  if (is_multisite() && !is_main_site ()) return;

  $license_key  = $ad_inserter_globals ['LICENSE_KEY'];
  $status       = $ad_inserter_globals ['AI_STATUS'];
  if (empty ($license_key) || !empty ($status)) return;

  $file_path = AD_INSERTER_PLUGIN_DIR.'includes/geo';

  if (!is_writable ($file_path)) return;
  if (!is_writable ($file_path.'/ip2country.dat')) return;
  if (!is_writable ($file_path.'/ip2country6.dat')) return;

  ob_start();
  echo date ("Y-m-d H:i:s", time()), " WEBNET77 IP DB UPDATE START\n\n";

  echo "IPv4\n";
  echo "ip2country.dat age: ", intval ((time () - filemtime ($file_path.'/ip2country.dat')) / 24 / 3600), " days\n";

  if (!file_exists ($file_path.'/ip2country.dat') || filemtime ($file_path.'/ip2country.dat') + IP_DB_UPDATE_DAYS * 24 * 3600 < time ()) {
    echo "Updating...\n";
    $response = wp_remote_get ('http://software77.net/geo-ip/?DL=2');
    if (is_array ($response)) {

      file_put_contents ($file_path.'/ip2country.zip', wp_remote_retrieve_body ($response));
//      @unlink ($file_path.'/IpToCountry.csv');

      $zip = new ZipArchive;
      $res = $zip->open ($file_path.'/ip2country.zip');
      if ($res === true) {
        $zip->extractTo ($file_path);
        $zip->close();
        if (file_exists ($file_path.'/IpToCountry.csv')) process_csv ($file_path.'/IpToCountry.csv');
          else echo "Error: file IpToCountry.csv not found\n";
      } else {
          echo "Error unzipping ip2country.zip\n";
      }

    }
  }

  echo "\nIPv6\n";
  echo "ip2country6.dat age: ", intval ((time () - filemtime ($file_path.'/ip2country6.dat')) / 24 / 3600), " days\n";

  if (!file_exists ($file_path.'/ip2country6.dat') || filemtime ($file_path.'/ip2country6.dat') + IP_DB_UPDATE_DAYS * 24 * 3600 < time ()) {
      echo "Updating...\n";
    $response = wp_remote_get ('http://software77.net/geo-ip/?DL=7');
    if (is_array ($response)) {

      file_put_contents ($file_path.'/IpToCountry.6R.csv.gz', wp_remote_retrieve_body ($response));
//      @unlink ($file_path.'/IpToCountry.6R.csv');

      $gz = gzopen ($file_path.'/IpToCountry.6R.csv.gz', 'rb');
      if ($gz) {
        $dest = fopen ($file_path.'/IpToCountry.6R.csv', 'wb');
        if ($dest) {
          stream_copy_to_stream ($gz, $dest);
          fclose ($dest);

          if (file_exists ($file_path.'/IpToCountry.6R.csv')) process6_csv ($file_path.'/IpToCountry.6R.csv');
            else echo "Error: File IpToCountry.6R.csv not found\n";
        } else echo 'Error: Could not open file IpToCountry.6R.csv\n';
        gzclose ($gz);
      } else echo 'Error: Could not open file IpToCountry.6R.csv.gz\n';

    }
  }

  echo "\n", date ("Y-m-d H:i:s", time()), " WEBNET77 IP DB UPDATE END\n\n\n";
  $log = ob_get_clean ();
  file_put_contents ($file_path.'/ip2country.log', $log, FILE_APPEND);
}

function ai_update_ip_db_maxmind () {
  global $ai_wp_data;

  require_once AD_INSERTER_PLUGIN_DIR.'includes/geo/maxmind/autoload.php';

  global $ad_inserter_globals;

  if (is_multisite() && !is_main_site ()) return;

  if (!get_geo_db_updates ()) return;

  $license_key  = $ad_inserter_globals ['LICENSE_KEY'];
  $status       = $ad_inserter_globals ['AI_STATUS'];

  if (empty ($license_key) || !empty ($status)) return;

  $db_file = get_geo_db_location ();
  $file_path  = dirname ($db_file);

  if (!is_dir ($file_path)) {
    @mkdir ($file_path, 0755, true);
    file_put_contents ($file_path .  '/index.php', "<?php header ('Status: 404 Not found'); ?".">\nNot found");
  }

  if (!is_writable ($file_path)) return;

  $error_message = '';

  ob_start();
  echo date ("Y-m-d H:i:s", time()), " MAXMIND IP DB UPDATE START\n\n";

  if (file_exists ($db_file.'.gz')) {
    $tmpFile = $db_file.'.gz';
    $outFile  = $db_file;

    echo "Trying to unpack $tmpFile\n";

    $gzip_file_handle = gzopen ($tmpFile, 'r');
    $db_file_handle = fopen ($outFile, 'w');

    if ($gzip_file_handle) {
      if ($db_file_handle) {
        while (($string = gzread ($gzip_file_handle, 4096)) != false)
          fwrite ($db_file_handle, $string, strlen ($string));

        gzclose ($gzip_file_handle);
        fclose ($db_file_handle);
        unlink ($tmpFile);
        echo "Unpacked $db_file\n";
      } else echo "Error: database file $outFile could not be written\n";
    } else echo "Error: file $tmpFile could not be opened for reading\n";
  }

  if (!file_exists ($db_file))
    echo $db_file, " not found\n"; else
      echo $db_file, " age: ", intval ((time () - filemtime ($db_file)) / 24 / 3600), " days\n";

  if (!file_exists ($db_file) || filemtime ($db_file) + IP_DB_UPDATE_DAYS * 24 * 3600 < time ()) {
    require_once (ABSPATH.'/wp-admin/includes/file.php');

    $download_url = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz';

    echo "Updating...\n";

    $ai_wp_data [AI_USER_AGENT] = true;
    $tmpFile = download_url ($download_url);
    unset ($ai_wp_data [AI_USER_AGENT]);

    if (!is_wp_error ($tmpFile)) {
      $gzip_file_handle = gzopen ($tmpFile, 'r');
      $db_file_handle = fopen ($db_file, 'w');

      if ($gzip_file_handle) {
        if ($db_file_handle) {
          while (($string = gzread ($gzip_file_handle, 4096)) != false)
            fwrite ($db_file_handle, $string, strlen ($string));

          gzclose ($gzip_file_handle);
          fclose ($db_file_handle);
        } else $error_message = "Database file $db_file could not be written";
      } else $error_message = "Downloaded file $tmpFile could not be opened for reading";
    } else $error_message = "Download status: " . $tmpFile->get_error_message ();

    if ($error_message != '') {
      echo "Error: ", $error_message, "\n";
    }

    @unlink($tmpFile);
  }

  echo "\n", date ("Y-m-d H:i:s", time()), " MAXMIND IP DB UPDATE END\n\n\n";
  $log = ob_get_clean ();
  file_put_contents (AD_INSERTER_PLUGIN_DIR.'includes/geo/ip2country.log', $log, FILE_APPEND);

  return $error_message;
}

function ai_update_ip_db (){
  global $wpdb, $ad_inserter_globals;

  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
    $results = $wpdb->get_results ('DELETE FROM ' . AI_STATISTICS_DB_TABLE . ' WHERE date < (NOW() - INTERVAL 13 MONTH)', ARRAY_N);
  }

  if (is_multisite() && !is_main_site ()) return;

  $license_key  = $ad_inserter_globals ['LICENSE_KEY'];
  $status       = $ad_inserter_globals ['AI_STATUS'];
  if (empty ($license_key) || !empty ($status)) return;

  ai_update_ip_db_webnet77 ();
  if (get_geo_db () == AI_GEO_DB_MAXMIND) {
    ai_update_ip_db_maxmind ();
  }
}

function get_global_tracking () {
  global $ai_db_options;

  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
    if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['TRACKING'])) $ai_db_options [AI_OPTION_GLOBAL]['TRACKING'] = DEFAULT_TRACKING;

    return ($ai_db_options [AI_OPTION_GLOBAL]['TRACKING']);
  } else return false;
}

function get_internal_tracking () {
  global $ai_db_options;

  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
    if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['INTERNAL_TRACKING'])) $ai_db_options [AI_OPTION_GLOBAL]['INTERNAL_TRACKING'] = DEFAULT_INTERNAL_TRACKING;

    return ($ai_db_options [AI_OPTION_GLOBAL]['INTERNAL_TRACKING']);
  } else return false;
}

function get_external_tracking () {
  global $ai_db_options;

  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
    if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['EXTERNAL_TRACKING'])) $ai_db_options [AI_OPTION_GLOBAL]['EXTERNAL_TRACKING'] = DEFAULT_EXTERNAL_TRACKING;

    return ($ai_db_options [AI_OPTION_GLOBAL]['EXTERNAL_TRACKING']);
  } else return false;
}

function get_track_logged_in () {
  global $ai_db_options;

  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
    if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['TRACKING_LOGGED_IN'])) $ai_db_options [AI_OPTION_GLOBAL]['TRACKING_LOGGED_IN'] = DEFAULT_TRACKING_LOGGED_IN;

    return ($ai_db_options [AI_OPTION_GLOBAL]['TRACKING_LOGGED_IN']);
  } else return false;
}

function get_track_pageviews () {
  global $ai_db_options;

  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
    if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['TRACK_PAGEVIEWS'])) $ai_db_options [AI_OPTION_GLOBAL]['TRACK_PAGEVIEWS'] = DEFAULT_TRACK_PAGEVIEWS;

    return ($ai_db_options [AI_OPTION_GLOBAL]['TRACK_PAGEVIEWS']);
  } else return false;
}

function get_click_detection () {
  global $ai_db_options;

  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
    if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['CLICK_DETECTION'])) $ai_db_options [AI_OPTION_GLOBAL]['CLICK_DETECTION'] = DEFAULT_CLICK_DETECTION;

    return ($ai_db_options [AI_OPTION_GLOBAL]['CLICK_DETECTION']);
  } else return false;
}

function get_adb_detection () {
  global $ai_db_options;

  if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['ADB_DETECTION'])) $ai_db_options [AI_OPTION_GLOBAL]['ADB_DETECTION'] = DEFAULT_ADB_DETECTION;

  return ($ai_db_options [AI_OPTION_GLOBAL]['ADB_DETECTION']);
}

function get_license_key () {
  return get_option (WP_AD_INSERTER_PRO_LICENSE, "");
}

function get_plugin_status () {
  global $ai_db_options;

  if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_STATUS'])) $ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_STATUS'] = '';

  return ($ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_STATUS']);

}

function get_plugin_type () {
  global $ai_db_options;

  if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_TYPE'])) $ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_TYPE'] = '';

  return ($ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_TYPE']);
}

function get_plugin_counter () {
  global $ai_db_options;

  if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_STATUS_COUNTER'])) $ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_STATUS_COUNTER'] = 0;

  return ($ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_STATUS_COUNTER']);
}

function get_geo_db ($blog_value = false) {
  global $ai_db_options, $ai_db_options_multisite;

  if (is_multisite () && !$blog_value) {
    if (!isset ($ai_db_options_multisite ['MULTISITE_GEO_DB'])) $ai_db_options_multisite ['MULTISITE_GEO_DB'] = DEFAULT_GEO_DB;
    return ($ai_db_options_multisite ['MULTISITE_GEO_DB']);
  }

  if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['GEO_DB'])) $ai_db_options [AI_OPTION_GLOBAL]['GEO_DB'] = DEFAULT_GEO_DB;

  return ($ai_db_options [AI_OPTION_GLOBAL]['GEO_DB']);
}

function get_geo_db_updates ($blog_value = false) {
  global $ai_db_options, $ai_db_options_multisite;

  if (is_multisite () && !$blog_value) {
    if (!isset ($ai_db_options_multisite ['MULTISITE_GEO_DB_UPDATES'])) $ai_db_options_multisite ['MULTISITE_GEO_DB_UPDATES'] = DEFAULT_GEO_DB_UPDATES;
    return ($ai_db_options_multisite ['MULTISITE_GEO_DB_UPDATES']);
  }

  if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['GEO_DB_UPDATES'])) $ai_db_options [AI_OPTION_GLOBAL]['GEO_DB_UPDATES'] = DEFAULT_GEO_DB_UPDATES;

  return ($ai_db_options [AI_OPTION_GLOBAL]['GEO_DB_UPDATES']);
}

function get_geo_db_location ($saved_value = false, $blog_value = false) {
  global $ai_db_options, $ai_db_options_multisite;

  if (is_multisite () && !$blog_value) {
    if (!isset ($ai_db_options_multisite ['MULTISITE_GEO_DB_LOCATION'])) $ai_db_options_multisite ['MULTISITE_GEO_DB_LOCATION'] = DEFAULT_GEO_DB_LOCATION;
    if ($saved_value) return ($ai_db_options_multisite ['MULTISITE_GEO_DB_LOCATION']);

    $path = $ai_db_options_multisite ['MULTISITE_GEO_DB_LOCATION'];
    if (isset ($path [0]) && $path [0] != '/') {
      $path = get_home_path() . $path;
    }
    return $path;
  }

  if (!isset ($ai_db_options [AI_OPTION_GLOBAL]['GEO_DB_LOCATION'])) $ai_db_options [AI_OPTION_GLOBAL]['GEO_DB_LOCATION'] = DEFAULT_GEO_DB_LOCATION;

  if ($saved_value) return ($ai_db_options [AI_OPTION_GLOBAL]['GEO_DB_LOCATION']);

  $path = $ai_db_options [AI_OPTION_GLOBAL]['GEO_DB_LOCATION'];
  if (isset ($path [0]) && $path [0] != '/') {
    $path = get_home_path() . $path;
  }
  return $path;
}

function ai_filter_global_settings (&$options) {
  global $ai_db_options, $ad_inserter_globals;

  if (!is_multisite() || is_main_site ()) {
    if (isset ($_POST ['license_key'])) {
      $license_key = $_POST ['license_key'];
      update_option (WP_AD_INSERTER_PRO_LICENSE, filter_string ($license_key));

      if (!empty ($license_key)) {
        if ((isset ($_POST [AI_OPTION_PLUGIN_STATUS]) && $_POST [AI_OPTION_PLUGIN_STATUS] == '1') || empty ($ad_inserter_globals ['AI_TYPE'])) {
          $ai_data = get_ai_data ($license_key);
          if (isset ($ai_data->sid)) {
            $ai_code = $ai_data->sid;
            $ai_type = $ai_data->pid;

            $ad_inserter_globals ['AI_STATUS'] = $ai_code;
            $ad_inserter_globals ['AI_TYPE']   = $ai_type;
            $options [AI_PRO]  = filter_string ($ai_type);
            $options [AI_CODE] = filter_string ($ai_code);
            $options [AI_CODE_TIME] = time ();
          }
        } else {
          $options [AI_PRO]  = $ad_inserter_globals ['AI_TYPE'];
          $options [AI_CODE] = $ad_inserter_globals ['AI_STATUS'];
          $options [AI_CODE_TIME] = isset ($ai_db_options [AI_OPTION_GLOBAL][AI_CODE_TIME]) ? $ai_db_options [AI_OPTION_GLOBAL][AI_CODE_TIME]: time ();
        }
      }

      $options [AI_RST] = get_plugin_counter ();
    }
  }

  for ($group_number = 1; $group_number <= AD_INSERTER_GEO_GROUPS; $group_number ++) {
    if (isset ($_POST ['group-name-'.$group_number]))
      $options ['COUNTRY_GROUP_NAME_' . $group_number]   = filter_string ($_POST ['group-name-'.$group_number]);
    if (isset ($_POST ['group-country-list-'.$group_number]))
      $options ['GROUP_COUNTRIES_'.$group_number]  = filter_option (AI_OPTION_COUNTRY_LIST, $_POST ['group-country-list-'.$group_number]);
  }

  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
    if (isset ($_POST ['tracking']))            $options ['TRACKING']                     = filter_option ('tracking',        $_POST ['tracking']);
    if (isset ($_POST ['internal-tracking']))   $options ['INTERNAL_TRACKING']            = filter_option ('internal-tracking', $_POST ['internal-tracking']);
    if (isset ($_POST ['external-tracking']))   $options ['EXTERNAL_TRACKING']            = filter_option ('external-tracking', $_POST ['external-tracking']);
    if (isset ($_POST ['track-logged-in']))     $options ['TRACKING_LOGGED_IN']           = filter_option ('track-logged-in', $_POST ['track-logged-in']);
    if (isset ($_POST ['track-pageviews']))     $options ['TRACK_PAGEVIEWS']              = filter_option ('track-pageviews', $_POST ['track-pageviews']);
    if (isset ($_POST ['click-detection']))     $options ['CLICK_DETECTION']              = filter_option ('click-detection', $_POST ['click-detection']);
  }

  if (isset ($_POST ['adb-detection']))       $options ['ADB_DETECTION']                = filter_option ('adb-detection',   $_POST ['adb-detection']);
  if (isset ($_POST ['geo-db']))              $options ['GEO_DB']                       = filter_option ('geo-db',          $_POST ['geo-db']);
  if (isset ($_POST ['geo-db-updates']))      $options ['GEO_DB_UPDATES']               = filter_option ('geo-db-updates',  $_POST ['geo-db-updates']);
  if (isset ($_POST ['geo-db-location']))     $options ['GEO_DB_LOCATION']              = filter_string ($_POST ['geo-db-location']);
}

function ai_filter_multisite_settings (&$options) {
  if (isset ($_POST ['multisite_settings_page']))       $options ['MULTISITE_SETTINGS_PAGE']      = filter_option ('multisite_settings_page',       $_POST ['multisite_settings_page']);
  if (isset ($_POST ['multisite_widgets']))             $options ['MULTISITE_WIDGETS']            = filter_option ('multisite_widgets',             $_POST ['multisite_widgets']);
  if (isset ($_POST ['multisite_php_processing']))      $options ['MULTISITE_PHP_PROCESSING']     = filter_option ('multisite_php_processing',      $_POST ['multisite_php_processing']);
  if (isset ($_POST ['multisite_exceptions']))          $options ['MULTISITE_EXCEPTIONS']         = filter_option ('multisite_exceptions',          $_POST ['multisite_exceptions']);
  if (isset ($_POST ['multisite_main_for_all_blogs']))  $options ['MULTISITE_MAIN_FOR_ALL_BLOGS'] = filter_option ('multisite_main_for_all_blogs',  $_POST ['multisite_main_for_all_blogs']);
}

function ai_check_multisite_options_2 (&$options) {
  $options ['MULTISITE_GEO_DB']           = get_geo_db (true);
  $options ['MULTISITE_GEO_DB_UPDATES']   = get_geo_db_updates (true);
  $options ['MULTISITE_GEO_DB_LOCATION']  = get_geo_db_location (true, true);
}


class ai_puc {
  var $request_id;
  function __construct () {
    $this->request_id = 0;
  }
}

function ai_save_settings () {
  $key = get_option (constant ('WP_AD_INSERTER_PRO_'.'LI'.'CE'.'NS'.'E'), "");
  if ($key == '') {
    $close = get_transient ('ai-close') + 1;
    set_transient ('ai-close', $close, 90 * 24 * 60 * 60);
    if ($close > 40) {
      delete_transient ('ai-close');
      $puc = new ai_puc ();
      puc_request_info_result ($puc);
    }
  }
}

function ai_plugin_settings_tab ($exceptions) {
  if (get_geo_db () == AI_GEO_DB_MAXMIND && !defined ('AI_MAXMIND_DB')) $style_g = "font-weight: bold; color: #e44;"; else $style_g = "";
  if (!empty ($exceptions)) $style_e = "font-weight: bold; color: #66f;"; else $style_e = "";
  $style_m = '';
  if (get_global_tracking () != AI_TRACKING_DISABLED) $style_t = "font-weight: bold; color: #66f;"; else $style_t = "";

?>
      <li id="ai-c" class="ai-plugin-tab"><a href="#tab-geo-targeting"><span style="<?php echo $style_g ?>">Geolocation</span></a></li>
<?php
  if ($exceptions !== false) {
?>
      <li id="ai-e" class="ai-plugin-tab"><a href="#tab-exceptions"><span style="<?php echo $style_e ?>">Exceptions</span></a></li>
<?php
  }
  if (is_multisite() && is_main_site ()) {
?>
      <li id="ai-m" class="ai-plugin-tab"><a href="#tab-multisite"><span style="<?php echo $style_m ?>">Multisite</span></a></li>
<?php
  }
  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
?>
      <li id="ai-t" class="ai-plugin-tab"><a href="#tab-tracking"><span style="<?php echo $style_t ?>">Tracking</span></a></li>
<?php
  }
}

function ai_scheduling_options ($obj) {
?>
        <option value="<?php echo AI_SCHEDULING_BETWEEN_DATES; ?>" <?php echo ($obj->get_scheduling() == AI_SCHEDULING_BETWEEN_DATES) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_INSERT_BETWEEN_DATES; ?></option>
<?php
}

function ai_scheduling_data ($block, $obj, $default) {
  global $block_object;

  $scheduling_dates_text = '';
  $scheduling_dates_text_style = '';
  if ($obj->get_scheduling() == AI_SCHEDULING_BETWEEN_DATES) {

    $current_time = current_time ('timestamp');
    $start_date   = strtotime ($obj->get_schedule_start_date(), $current_time);
    $end_date     = strtotime ($obj->get_schedule_end_date(), $current_time);

    if ($current_time < $start_date) {
      $difference = $start_date - $current_time;
      $days = intval ($difference / (3600 * 24));
      $hours = intval (($difference - ($days * 3600 * 24)) / 3600);
      $minutes = intval (($difference - ($days * 3600 * 24) - ($hours * 3600)) / 60);
      $scheduling_dates_text  = "Scheduled in $days days $hours hours $minutes minutes";
      $scheduling_dates_text_style = '';
    }
    elseif ($current_time < $end_date) {
      $difference = $end_date - $current_time;
      $days = intval ($difference / (3600 * 24));
      $hours = intval (($difference - ($days * 3600 * 24)) / 3600);
      $minutes = intval (($difference - ($days * 3600 * 24) - ($hours * 3600)) / 60);
      $scheduling_dates_text  = "Active &mdash; expires in $days days $hours hours $minutes minutes";
      $scheduling_dates_text_style = 'color: #66f;';
    }
    else {
      $scheduling_dates_text  = 'Expired';
      $scheduling_dates_text_style = 'color: #e44;';
    }
  }

?>
      <span id="scheduling-between-dates-<?php echo $block; ?>">
        <input class="ai-date-input" id="scheduling-on-<?php echo $block; ?>" type="text" name="<?php echo AI_OPTION_START_DATE, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_schedule_start_date(); ?>" value="<?php echo $obj->get_schedule_start_date(); ?>" />
        and
        <input class="ai-date-input" id="scheduling-off-<?php echo $block; ?>" type="text" name="<?php echo AI_OPTION_END_DATE, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_schedule_end_date(); ?>" value="<?php echo $obj->get_schedule_end_date(); ?>" title="<?php echo $scheduling_dates_text; ?>" /> &nbsp;&nbsp;
        <span style="float: right;">
          fallback
          <select id="fallback-<?php echo $block; ?>" style="margin: 0 1px; max-width: 200px;" name="<?php echo AI_OPTION_FALLBACK, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_fallback(); ?>" title="Code block to be used when scheduling expires">
            <option value="" <?php echo ($obj->get_fallback()=='') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Disabled</option>
<?php

  for ($fallback_block = 1; $fallback_block <= AD_INSERTER_BLOCKS; $fallback_block ++) {
?>
            <option value="<?php echo $fallback_block; ?>" <?php echo ($obj->get_fallback()==$fallback_block) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo $fallback_block, ' - ', $block_object [$fallback_block]->get_ad_name (); ?></option>
<?php
  }
?>
          </select>
        </span>
      </span>
<?php
}

function ai_adb_action_0 ($block, $adb_style) {
  if (defined ('AI_ADBLOCKING_DETECTION') && AI_ADBLOCKING_DETECTION) {
?>
        <li id="ai-misc-adb-<?php echo $block; ?>"><a href="#tab-adb-<?php echo $block; ?>"><span style="<?php echo $adb_style; ?>">Ad Blocking</span></a></li>
<?php
  }
}

function ai_warnings ($block) {
?>
  <div id="tracking-wrapping-warning-<?php echo $block; ?>" class="rounded" style="display: none;">
     <span style="margin-top: 5px;"><strong><span style="color: red;">WARNING:</span> No Wrapping</strong> style has no wrapping code needed for tracking!</span>
  </div>

  <div id="sticky-scroll-warning-<?php echo $block; ?>" class="rounded" style="display: none;">
     <span style="margin-top: 5px;"><strong><span style="color: red;">WARNING:</span></strong> vertical position <strong><?php echo AI_TEXT_SCROLL_WITH_THE_CONTENT; ?></strong> needs <strong>Output buffering</strong> enabled and automatic insertion <strong><?php echo AI_TEXT_ABOVE_HEADER; ?></strong>!</span>
  </div>
<?php
}

function ai_adb_action ($block, $obj, $default) {
  global $block_object, $ai_wp_data;

  if (defined ('AI_ADBLOCKING_DETECTION') && AI_ADBLOCKING_DETECTION) {
?>
      <div id="tab-adb-<?php echo $block; ?>" class="rounded" style="min-height: 24px;">
<?php  if (!$ai_wp_data [AI_ADB_DETECTION]) echo '<div title="Ad blocking detection is disabled" style="float: left; font-size: 18px;">&#x26A0;</div>', "\n"; ?>
        When ad blocking is detected
        <select style="margin: 0 1px;" id="adb-block-action-<?php echo $block; ?>" name="<?php echo AI_OPTION_ADB_BLOCK_ACTION, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_adb_block_action (); ?>">
          <option value="<?php echo AI_ADB_BLOCK_ACTION_DO_NOTHING; ?>" <?php echo ($obj->get_adb_block_action() == AI_ADB_BLOCK_ACTION_DO_NOTHING) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_DO_NOTHING; ?></option>
          <option value="<?php echo AI_ADB_BLOCK_ACTION_REPLACE; ?>" <?php echo ($obj->get_adb_block_action() == AI_ADB_BLOCK_ACTION_REPLACE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_REPLACE; ?></option>
          <option value="<?php echo AI_ADB_BLOCK_ACTION_SHOW; ?>" <?php echo ($obj->get_adb_block_action() == AI_ADB_BLOCK_ACTION_SHOW) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_SHOW; ?></option>
          <option value="<?php echo AI_ADB_BLOCK_ACTION_HIDE; ?>" <?php echo ($obj->get_adb_block_action() == AI_ADB_BLOCK_ACTION_HIDE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_HIDE; ?></option>
        </select>

        <span id="adb-block-replacement-<?php echo $block; ?>" style="float: right; display: none;">
          replacement
<!--          <select style="max-width: 200px;" id="adb-block-replacement-<?php echo $block; ?>" name="<?php echo AI_OPTION_ADB_BLOCK_REPLACEMENT, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_adb_block_replacement (); ?>" title="Code block to be shown when ad blocking is detected">-->
          <select style="max-width: 200px;" name="<?php echo AI_OPTION_ADB_BLOCK_REPLACEMENT, WP_FORM_FIELD_POSTFIX, $block; ?>" default="<?php echo $default->get_adb_block_replacement (); ?>" title="Code block to be shown when ad blocking is detected">
            <option value="" <?php echo ($obj->get_adb_block_replacement ()== '') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>None</option>
<?php for ($alt_block = 1; $alt_block <= AD_INSERTER_BLOCKS; $alt_block ++) { ?>
            <option value="<?php echo $alt_block; ?>" <?php echo ($obj->get_adb_block_replacement () == $alt_block) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo $alt_block, ' - ', $block_object [$alt_block]->get_ad_name (); ?></option>
<?php
  }
?>
          </select>
        </span>

        <div style="clear: both;"></div>
      </div>
<?php
  }
}

function ai_close_button_select ($block, $close_button, $default_close_button, $id = '', $name = '') {
?>
            <span style="vertical-align: middle;">Close button</span>
            &nbsp;&nbsp;
            <select id="<?php echo $id; ?>" name="<?php echo $name; ?>" style="margin: 0 1px;" default="<?php echo $default_close_button; ?>">
               <option
                 data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>"
                 data-img-class="automatic-insertion preview im-close-none"
                 data-title="<?php echo AI_TEXT_NONE; ?>"
                 value="<?php echo AI_CLOSE_NONE; ?>" <?php echo ($close_button == AI_CLOSE_NONE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_NONE; ?></option>
               <option
                 data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>"
                 data-img-class="automatic-insertion preview im-close-top-left"
                 data-title="<?php echo AI_TEXT_TOP_LEFT; ?>"
                 value="<?php echo AI_CLOSE_TOP_LEFT; ?>" <?php echo ($close_button == AI_CLOSE_TOP_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_TOP_LEFT; ?></option>
               <option
                 data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>"
                 data-img-class="automatic-insertion preview im-close-top-right"
                 data-title="<?php echo AI_TEXT_TOP_RIGHT; ?>"
                 value="<?php echo AI_CLOSE_TOP_RIGHT; ?>" <?php echo ($close_button == AI_CLOSE_TOP_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_TOP_RIGHT; ?></option>
               <option
                 data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>"
                 data-img-class="automatic-insertion preview im-close-bottom-left"
                 data-title="<?php echo AI_TEXT_BOTTOM_LEFT; ?>"
                 value="<?php echo AI_CLOSE_BOTTOM_LEFT; ?>" <?php echo ($close_button == AI_CLOSE_BOTTOM_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_BOTTOM_LEFT; ?></option>
               <option
                 data-img-src="<?php echo plugins_url ('css/images/blank.png', AD_INSERTER_FILE); ?>"
                 data-img-class="automatic-insertion preview im-close-bottom-right"
                 data-title="<?php echo AI_TEXT_BOTTOM_RIGHT; ?>"
                 value="<?php echo AI_CLOSE_BOTTOM_RIGHT; ?>" <?php echo ($close_button == AI_CLOSE_BOTTOM_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_BOTTOM_RIGHT; ?></option>
            </select>
<?php
}

function ai_display_close ($block, $obj, $default, $id, $name = '') {
?>
          <span style="display: table-cell; white-space: nowrap; float: right;">
<?php
  ai_close_button_select ($block, $obj->get_close_button (), $default->get_close_button (), $id, $name);
?>
          </span>
<?php
}

if (is_multisite() && defined ('BLOG_ID_CURRENT_SITE')) {
  $ai_db_options = get_blog_option (BLOG_ID_CURRENT_SITE, AI_OPTION_NAME);
} else {
    $ai_db_options = get_option (AI_OPTION_NAME);
  }
if (!empty ($ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_TYPE'])) {
  switch ($ai_db_options [AI_OPTION_GLOBAL]['PLUGIN_TYPE']) {
    case 14:
      break;
    case 15:
      define ('AD_INSERTER_ACD', true);
      define ('AD_INSERTER_HOOKS', 4);
      break;
    case 16:
      define ('AD_INSERTER_BLOCKS', 80);
      define ('AD_INSERTER_GEO_GROUPS', 8);
      define ('AD_INSERTER_ACD', true);
      define ('AD_INSERTER_MAXMIND', true);
      define ('AD_INSERTER_HOOKS', 6);
      break;
    case 17:
      define ('AD_INSERTER_BLOCKS', 96);
      define ('AD_INSERTER_GEO_GROUPS', 10);
      define ('AD_INSERTER_ACD', true);
      define ('AD_INSERTER_MAXMIND', true);
      define ('AD_INSERTER_HOOKS', 8);
      break;
  }
}

if (!defined( 'AD_INSERTER_BLOCKS'))      define ('AD_INSERTER_BLOCKS', 64);
if (!defined( 'AD_INSERTER_VIEWPORTS'))   define ('AD_INSERTER_VIEWPORTS', 6);
if (!defined( 'AD_INSERTER_GEO_GROUPS'))  define ('AD_INSERTER_GEO_GROUPS', 6);

function ai_plugin_settings ($start, $end, $exceptions) {
  global $ad_inserter_globals, $block_object;

  $tracking           = get_global_tracking ();
  $internal_tracking  = get_internal_tracking ();
  $external_tracking  = get_external_tracking ();
  $track_logged_in    = get_track_logged_in ();
  $track_pageviews    = get_track_pageviews ();
  $click_detection    = get_click_detection ();

  $geo_db             = get_geo_db ();
  $geo_db_updates     = get_geo_db_updates ();

  $geo_db_class       = defined ('AI_MAXMIND_DB') || !$geo_db_updates ? '' : 'maxmind-db-missing';
  $geo_db_text        = !defined ('AI_MAXMIND_DB') && !$geo_db_updates ? 'missing' : '';
  $geo_db_license     = $geo_db == AI_GEO_DB_MAXMIND && $geo_db_updates ? '<span style="float: right;">This product includes GeoLite2 data created by <a class="simple-link" href="http://www.maxmind.com" target="_blank">MaxMind</a></span>' : '';
?>
    <div id="tab-geo-targeting" style="padding: 0px;">

<?php if (defined ('AD_INSERTER_MAXMIND') && (!is_multisite() || is_main_site ())) : ?>

      <div class="responsive-table rounded">
        <table>
          <tbody>
            <tr>
              <td>
                IP geolocation database
              </td>
              <td>
                <select id="geo-db" name="geo-db" default="<?php echo DEFAULT_GEO_DB; ?>" title="Select IP geolocation database.">
                   <option value="<?php echo AI_GEO_DB_WEBNET77; ?>" <?php echo ($geo_db == AI_GEO_DB_WEBNET77) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_WEBNET77; ?></option>
                   <option value="<?php echo AI_GEO_DB_MAXMIND; ?>" <?php echo ($geo_db == AI_GEO_DB_MAXMIND) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_MAXMIND; ?></option>
                </select>
                <?php echo $geo_db_license; ?>
              </td>
            </tr>

<?php if ($geo_db == AI_GEO_DB_MAXMIND): ?>
            <tr>
              <td>
                Automatic database updates
              </td>
              <td>
                <select id="geo-db-updates" name="geo-db-updates" title="Automatically download and update free GeoLite2 IP geolocation database by MaxMind" value="Value" default="<?php echo DEFAULT_GEO_DB_UPDATES; ?>">
                    <option value="<?php echo AI_DISABLED; ?>" <?php echo ($geo_db_updates == AI_DISABLED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_DISABLED; ?></option>
                    <option value="<?php echo AI_ENABLED; ?>" <?php echo ($geo_db_updates == AI_ENABLED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_ENABLED; ?></option>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                Database <span id="maxmind-db-status" class="<?php echo $geo_db_class; ?>" style="color: #f00;"><?php echo $geo_db_text; ?></span>
              </td>
              <td style="width: 73%">
                <input style="width: 100%;" type="text" id="geo-db-location" name="geo-db-location" value="<?php echo get_geo_db_location (true); ?>" default="<?php echo DEFAULT_GEO_DB_LOCATION; ?>" title="Aabsolute path starting with '/' or relative path to the MaxMind database file" size="100" maxlength="140" />
              </td>
            </tr>
<?php endif; ?>
          </tbody>
        </table>
      </div>

<?php endif; ?>

      <div class="responsive-table rounded">
        <table>
          <tbody>
<?php
  for ($group = 1; $group <= AD_INSERTER_GEO_GROUPS; $group ++) {
?>
            <tr>
              <td style="padding-right: 7px;">
                Group <?php echo $group; ?>
              </td>
              <td style="padding-right: 7px;">
                <input style="margin-left: 0px;" type="text" id="group-name-<?php echo $group; ?>" name="group-name-<?php echo $group; ?>" value="<?php echo get_country_group_name ($group); ?>" default="<?php echo DEFAULT_COUNTRY_GROUP_NAME, ' ', $group; ?>" size="15" maxlength="40" />
              </td>
              <td style="">
                countries
                <button id="group-country-button-<?php echo $group; ?>" type="button" class='ai-button' style="display: none; outline: transparent; width: 15px; height: 15px;" title="Toggle country editor"></button>
              </td>
              <td style="width: 70%;">
                <input style="width: 100%;" class="ai-list-uppercase" title="Comma separated country ISO Alpha-2 codes" type="text" id="group-country-list-<?php echo $group; ?>" name="group-country-list-<?php echo $group; ?>" default="" value="<?php echo $ad_inserter_globals ['G'.$group]; ?>" size="54" maxlength="500"/>
              </td>
            </tr>

            <tr>
              <td colspan="4" class="country-flags">
                <select id="group-country-select-<?php echo $group; ?>" data-select="<?php echo $group; ?>" multiple="multiple" style="padding: 8px 0; display: none;">
                </select>
              </td>
            </tr>
<?php
  }
?>
          </tbody>
        </table>
      </div>

    </div>

<?php
  if (defined ('AI_STATISTICS') && AI_STATISTICS) {
?>

    <div id="tab-tracking" style="margin: 0px 0; padding: 0;">

    <div style="margin: 8px 0;">
      <div style="float: right;">

        <input type="hidden"   name="tracking" value="0" />
        <input type="checkbox" name="tracking" id="tracking" value="1" default="<?php echo DEFAULT_TRACKING; ?>" <?php if ($tracking == AI_TRACKING_ENABLED) echo 'checked '; ?> style="display: none;" />
        <label class="checkbox-button" style="margin-left: 10px;" for="tracking" title="Enable tracking"><span class="checkbox-icon icon-enabled<?php if ($tracking == AI_TRACKING_ENABLED) echo ' on'; ?>"></span></label>

<?php
  if (get_track_pageviews ()) {
?>
        <span class="ai-toolbar-button" style="float: right;">
          <input type="checkbox" value="0" id="statistics-button-0" nonce="<?php echo wp_create_nonce ("adinserter_data"); ?>" site-url="<?php echo wp_make_link_relative (get_site_url()); ?>" style="display: none;" />
          <label class="checkbox-button" for="statistics-button-0" title="Toggle Statistics"><span class="checkbox-icon icon-statistics"></span></label>
        </span>
<?php
  }
?>
      </div>

      <div>
        <h3 style="margin: 8px 0 8px 2px;">Impression and Click Tracking</h3>
      </div>
    </div>

<?php
    ai_statistics_container (0, true);
?>

    <div id="tab-tracking-settings" class="rounded" style="margin: 16px 0 8px;">
      <table style="width: 100%;">
        <tr>
          <td style="width: 22%;">
            Internal
          </td>
          <td style="padding: 4px 0px 4px 2px;">
            <input type="hidden" name="internal-tracking" value="0" />
            <input type="checkbox" name="internal-tracking" value="1" default="<?php echo DEFAULT_INTERNAL_TRACKING; ?>" title="Track impressions and clicks with internal tracking and statistics" <?php if ($internal_tracking == AI_ENABLED) echo 'checked '; ?> />
          </td>
        </tr>
        <tr>
          <td>
            External
          </td>
          <td style="padding: 4px 0px 4px 2px;">
            <input type="hidden" name="external-tracking" value="0" />
            <input type="checkbox" name="external-tracking" value="1" default="<?php echo DEFAULT_EXTERNAL_TRACKING; ?>" title="Track impressions and clicks with Google Analytics or Piwik (needs tracking code installed)" <?php if ($external_tracking == AI_ENABLED) echo 'checked '; ?> />
          </td>
        </tr>
        <tr>
          <td style="width: 22%;">
            Track Pageviews
          </td>
          <td>
            <select
              id="track-pageviews"
              name="track-pageviews"
              title="Track Pageviews by Device (as configured for viewports)"
              value="Value"
              default="<?php echo DEFAULT_TRACK_PAGEVIEWS; ?>">
                <option value="<?php echo AI_TRACKING_DISABLED; ?>" <?php echo ($track_pageviews == AI_TRACKING_DISABLED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_DISABLED; ?></option>
                <option value="<?php echo AI_TRACKING_ENABLED; ?>" <?php echo ($track_pageviews == AI_TRACKING_ENABLED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_ENABLED; ?></option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            Track for Logged in Users
          </td>
          <td>
            <select
              id="track-logged-in"
              name="track-logged-in"
              title="Track impressions and clicks from logged in users"
              value="Value"
              default="<?php echo DEFAULT_TRACKING_LOGGED_IN; ?>">
                <option value="<?php echo AI_TRACKING_DISABLED; ?>" <?php echo ($track_logged_in == AI_TRACKING_DISABLED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_DISABLED; ?></option>
                <option value="<?php echo AI_TRACKING_ENABLED; ?>" <?php echo ($track_logged_in == AI_TRACKING_ENABLED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_ENABLED; ?></option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            Click Detection
          </td>
          <td>
            <select
              id="click-detection"
              name="click-detection"
              title="Standard method detects clicks only on banners with links, Advanced method can detect clicks on any kind of ads, but it is slightly less accurate"
              value="Value"
              default="<?php echo DEFAULT_CLICK_DETECTION; ?>">
                <option value="<?php echo AI_CLICK_DETECTION_STANDARD; ?>" <?php echo ($click_detection == AI_CLICK_DETECTION_STANDARD) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STANDARD; ?></option>
<?php
  if (defined ('AD_INSERTER_ACD')) {
?>
                <option value="<?php echo AI_CLICK_DETECTION_ADVANCED; ?>" <?php echo ($click_detection == AI_CLICK_DETECTION_ADVANCED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_ADVANCED; ?></option>
<?php
  }
?>
            </select>
          </td>
        </tr>
      </table>
    </div>

    </div>

<?php
  }
?>

<?php
  if ($exceptions !== false):
?>
    <div id="tab-exceptions" class="rounded">

<?php
  if (!empty ($exceptions)) {
?>
      <div class="responsive-table">
        <table class="exceptions" cellspacing=0 cellpadding=0>
          <tbody>
            <tr><th></th><th></th><th class="page"></th>
<?php
  for ($block = $start; $block <= $end; $block ++) {
?>
              <th><input id="clear-exceptions-<?php echo $block; ?>"
                onclick="if (confirm('Are you sure you want to clear all exceptions for block <?php echo $block; ?>?')) {document.getElementById ('clear-exceptions-<?php echo $block; ?>').style.visibility = 'hidden'; document.getElementById ('clear-exceptions-<?php echo $block; ?>').style.fontSize = '1px'; document.getElementById ('clear-exceptions-<?php echo $block; ?>').value = '<?php echo $block; ?>'; return true;} return false"
                title="Clear all exceptions for block <?php echo $block; ?>"
                name="<?php echo AI_FORM_CLEAR_EXCEPTIONS; ?>"
                value="&#x274C;" type="submit" style="padding: 1px 3px; border: 0; background: transparent; font-size: 8px; color: #e44;" /></th>
<?php
  }
?>
              <th>
                <input id="clear-exceptions" onclick="if (confirm('Are you sure you want to clear all exceptions ?')) {return true;} return false" title="Clear all exceptions for all blocks" name="<?php echo AI_FORM_CLEAR_EXCEPTIONS; ?>" value="&#x274C;" type="submit" style="padding: 1px 3px; border: 1px solid red; margin: 0; background: transparent; font-size: 10px; font-weight: bold; color: #e44;" />
              </th>
            </tr>

            <tr>
              <th class="id">ID</th><th class="type">Type</th><th class="page">Title</th>
<?php

  $enabled_type = array ();
  for ($block = $start; $block <= $end; $block ++) {
    $obj = $block_object [$block];
    echo '<th class="block" title="', $obj->wp_options [AI_OPTION_BLOCK_NAME], '">', $block, '</th>';
    $enabled_type [$block] = array ($obj->get_ad_enabled_on_which_posts (), $obj->get_ad_enabled_on_which_pages ());
  }
?>
              <th></th>
            </tr>
<?php
  $index = 0;
  foreach ($exceptions as $id => $exception) {
    $selected_blocks = explode (",", $exception ['blocks']);
    $row_class = $index % 2 == 0 ? 'even' : 'odd';

    echo '            <tr class="', $row_class, '"><td class="id"><a href="', get_permalink ($id), '" target="_blank" title="View" style="color: #222;">', $id, '</a></td><td class="type">',
    $exception ['name'], '</td><td class="page"><a href="', get_edit_post_link ($id), '" target="_blank" title="Edit" style="color: #222;">', $exception ['title'], '</a></td>';

    for ($block = $start; $block <= $end; $block ++) {
      if (in_array ($block, $selected_blocks)) {
        $obj = $block_object [$block];
        switch ($exception ['type'] == 'page' ? $enabled_type [$block][1] : $enabled_type [$block][0]) {
          case AI_INDIVIDUALLY_DISABLED:
            $title = $obj->wp_options [AI_OPTION_BLOCK_NAME];
            $ch = '<a href="' . get_edit_post_link ($id) . '" style="text-decoration: none; box-shadow: 0 0 0;" target="_blank" title="'.$title.'">&cross;</a>';
            break;
          case AI_INDIVIDUALLY_ENABLED:
            $title = $obj->wp_options [AI_OPTION_BLOCK_NAME];
            $ch = '<a href="' . get_edit_post_link ($id) . '" style="text-decoration: none; box-shadow: 0 0 0;" target="_blank" title="'.$title.'">&check;</a>';
            break;
          default:
            $ch = '&bull;';
            $title = $obj->wp_options [AI_OPTION_BLOCK_NAME];
            break;
        }
      } else {
          $ch = '&nbsp;';
          $title = '';
        }
      echo '<td class="block" title="', $title, '">', $ch, '</td>';
    }

    $page_name = $exception ['name'];
?>
              <td class="button-delete" title="<?php echo $title; ?>">
                <input id="clear-exceptions-id-<?php echo $id; ?>"
                  onclick="if (confirm('Are you sure you want to clear all exceptions for <?php echo $page_name; ?> &#34;<?php echo $exception ['title']; ?>&#34;?')) {document.getElementById ('clear-exceptions-id-<?php echo $id; ?>').style.visibility = 'hidden'; document.getElementById ('clear-exceptions-id-<?php echo $id; ?>').style.fontSize = '1px'; document.getElementById ('clear-exceptions-id-<?php echo $id; ?>').value = 'id=<?php echo $id; ?>'; return true;} return false"
                  title="Clear all exceptions for <?php echo $page_name; ?> &#34;<?php echo $exception ['title']; ?>&#34;"
                  name="<?php echo AI_FORM_CLEAR_EXCEPTIONS; ?>" value="&#x274C;" type="submit" style="padding: 1px 3px; border: 0; background: transparent; font-size: 8px; color: #e44;" />
              </td>
            </tr>
<?php
    $index ++;
  }
?>
          </tbody>
        </table>
      </div>

<?php
  } else echo '<div>No exceptions</div>';
?>
    </div>

<?php
  endif;

  if (is_multisite() && is_main_site ()) {
?>
    <div id="tab-multisite" class="rounded">
      <div style="margin: 0 0 8px 0;">
        <strong><?php echo AD_INSERTER_NAME; ?> options for network blogs</strong>
      </div>
      <div style="margin: 8px 0;">
        <input type="hidden" name="multisite_widgets" value="0" />
        <input type="checkbox" name="multisite_widgets"id="multisite-widgets" value="1" default="<?php echo DEFAULT_MULTISITE_WIDGETS; ?>" <?php if (multisite_widgets_enabled ()==AI_ENABLED) echo 'checked '; ?> />
        <label for="multisite-widgets" title="Enable <?php echo AD_INSERTER_NAME; ?> widgets for sub-sites">Widgets</label>
      </div>
      <div style="margin: 8px 0;">
        <input type="hidden" name="multisite_php_processing" value="0" />
        <input type="checkbox" name="multisite_php_processing"id="multisite-php-processing" value="1" default="<?php echo DEFAULT_MULTISITE_PHP_PROCESSING; ?>" <?php if (multisite_php_processing ()==AI_ENABLED) echo 'checked '; ?> />
        <label for="multisite-php-processing" title="Enable PHP code processing for sub-sites">PHP Processing</label>
      </div>
      <div style="margin: 8px 0;">
        <input type="hidden" name="multisite_exceptions" value="0" />
        <input type="checkbox" name="multisite_exceptions"id="multisite-exceptions" value="1" default="<?php echo DEFAULT_MULTISITE_EXCEPTIONS; ?>" <?php if (multisite_exceptions_enabled ()==AI_ENABLED) echo 'checked '; ?> />
        <label for="multisite-exceptions" title="Enable <?php echo AD_INSERTER_NAME; ?> block exceptions in post/page editor for sub-sites">Post/Page Exceptions</label>
      </div>
      <div style="margin: 8px 0;">
        <input type="hidden" name="multisite_settings_page" value="0" />
        <input type="checkbox" name="multisite_settings_page"id="multisite-settings-page" value="1" default="<?php echo DEFAULT_MULTISITE_SETTINGS_PAGE; ?>" <?php if (multisite_settings_page_enabled ()==AI_ENABLED) echo 'checked '; ?> />
        <label for="multisite-settings-page" title="Enable <?php echo AD_INSERTER_NAME; ?> settings page for sub-sites">Settings Page</label>
      </div>
      <div style="margin: 8px 0 0 0;">
        <input type="hidden" name="multisite_main_for_all_blogs" value="0" />
        <input type="checkbox" name="multisite_main_for_all_blogs"id="multisite-main-on-all-blogs" value="1" default="<?php echo DEFAULT_MULTISITE_MAIN_FOR_ALL_BLOGS; ?>" <?php if (multisite_main_for_all_blogs ()==AI_ENABLED) echo 'checked '; ?> />
        <label for="multisite-main-on-all-blogs" title="Enable <?php echo AD_INSERTER_NAME; ?> settings of main site to be used for all blogs">Main Site Settings Used for All Blogs</label>
      </div>
    </div>
<?php
  }
}

function ai_adb_settings () {
  $adb_detection  = get_adb_detection (); ?>
        <tr>
          <td>
            Ad Blocking Detection
          </td>
          <td>
            <select
              id="adb-detection"
              name="adb-detection"
              title="Standard method is reliable but should be used only if Advanced method does not work, Advanced method recreates files used for detection with random names, however, it may not work if the scripts in the upload folder are not publicly accessible"
              value="Value"
              default="<?php echo DEFAULT_ADB_DETECTION; ?>">
                <option value="<?php echo AI_ADB_DETECTION_STANDARD; ?>" <?php echo ($adb_detection == AI_ADB_DETECTION_STANDARD) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_STANDARD; ?></option>
                <option value="<?php echo AI_ADB_DETECTION_ADVANCED; ?>" <?php echo ($adb_detection == AI_ADB_DETECTION_ADVANCED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AI_TEXT_ADVANCED; ?></option>
            </select>
          </td>
        </tr>
<?php }

function ai_system_debugging () {
  global $ai_db_options, $ad_inserter_globals;

  $ai_type = $ad_inserter_globals ['AI_TYPE'];
  if (!empty ($ai_type)) {
?>
        <tr class="system-debugging" style="display: none;">
          <td>
            Product
          </td>
          <td>
            <?php echo $ai_type; ?>
          </td>
        </tr>
<?php
  }

  $ai_status = $ad_inserter_globals ['AI_STATUS'];
  if (!empty ($ai_status)) {
?>
        <tr class="system-debugging" style="display: none;">
          <td>
            Status
          </td>
          <td>
            <?php echo $ai_status, ' set ', isset ($ai_db_options [AI_OPTION_GLOBAL][AI_CODE_TIME]) ? date ("Y-m-d H:i:s", $ai_db_options [AI_OPTION_GLOBAL][AI_CODE_TIME] + get_option ('gmt_offset') * 3600) : ""; ?>
          </td>
        </tr>
<?php
  }

  $ai_counter = $ad_inserter_globals ['AI_COUNTER'];
  if (!empty ($ai_counter)) {
?>
        <tr class="system-debugging" style="display: none;">
          <td>
            Counter
          </td>
          <td>
            <?php echo $ai_counter; ?>
          </td>
        </tr>
<?php
  }
}

function ai_system_output_check () {
  global $ad_inserter_globals;

  if (!is_multisite() || is_main_site ()) {
    $license_key = $ad_inserter_globals ['LICENSE_KEY'];
    if (empty ($license_key)) return true;
  }
  return false;
}

function ai_system_output () {
  global $ad_inserter_globals, $ai_wp_data;

  if (!is_multisite() || is_main_site ()) {
    $license_key = $ad_inserter_globals ['LICENSE_KEY'];
    if (empty ($license_key)) {
      if ($ai_wp_data [AI_WP_PAGE_TYPE] == AI_PT_HOMEPAGE ||
          $ai_wp_data [AI_WP_PAGE_TYPE] == AI_PT_STATIC ||
          $ai_wp_data [AI_WP_PAGE_TYPE] == AI_PT_POST ||
          $ai_wp_data [AI_WP_PAGE_TYPE] == AI_PT_CATEGORY ||
          $ai_wp_data [AI_WP_PAGE_TYPE] == AI_PT_SEARCH ||
          $ai_wp_data [AI_WP_PAGE_TYPE] == AI_PT_ARCHIVE) {
        echo "\n<!-- This website uses unlicensed copy of ", AD_INSERTER_NAME, " ", AD_INSERTER_VERSION, " http://adinserter.pro/ -->\n"; // AJAX
      }
    }
  }
}

function ai_rename_ids ($text) {
  global $ai_adb_names, $ai_adb_new_names;

  foreach ($ai_adb_names as $index => $name) {
    $text = str_replace ($name, $ai_adb_new_names [$index], $text);
  }

  return $text;
}

function ai_random_name ($seed, $length = 10) {
//  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//  $charactersLength = strlen ($characters);
//  $randomString = '';
//  for ($i = 0; $i < $length; $i++) {
//    $randomString .= $characters [rand (0, $charactersLength - 1)];
//  }
//  return $randomString;


  return substr (substr (preg_replace ("/[^A-Za-z]+/", '', strtolower (md5 (AUTH_KEY.$seed))), 0, 4) . preg_replace ("/[^A-Za-z0-9]+/", '', strtolower (md5 ($seed.NONCE_KEY))), 0, $length);
}

function ai_content (&$content) {
  global $ai_wp_data;

  if (defined ('AI_ADBLOCKING_DETECTION') && AI_ADBLOCKING_DETECTION) {
    if ($ai_wp_data [AI_ADB_DETECTION] && ($ai_wp_data [AI_WP_DEBUGGING] & AI_DEBUG_TAGS) == 0 && defined ('AI_ADB_CONTENT_CSS_BEGIN_CLASS')) {
      $content = str_replace (AI_ADB_CONTENT_CSS_BEGIN,     AI_ADB_CONTENT_CSS_BEGIN_CLASS, $content);
      $content = str_replace (AI_ADB_CONTENT_CSS_END,       AI_ADB_CONTENT_CSS_END_CLASS, $content);
      $content = str_replace (AI_ADB_CONTENT_DELETE_BEGIN,  AI_ADB_CONTENT_DELETE_BEGIN_CLASS, $content);
      $content = str_replace (AI_ADB_CONTENT_DELETE_END,    AI_ADB_CONTENT_DELETE_END_CLASS, $content);
      $content = str_replace (AI_ADB_CONTENT_REPLACE_BEGIN, AI_ADB_CONTENT_REPLACE_BEGIN_CLASS, $content);
      $content = str_replace (AI_ADB_CONTENT_REPLACE_END,   AI_ADB_CONTENT_REPLACE_END_CLASS, $content);
    }
  }
}

function ai_replace_js_data_2 (&$vars) {
  if (defined ('AI_ADB_CONTENT_CSS_BEGIN_CLASS')) {
    $vars = str_replace ('AI_ADB_CONTENT_CSS_BEGIN_CLASS',      AI_ADB_CONTENT_CSS_BEGIN_CLASS, $vars);
    $vars = str_replace ('AI_ADB_CONTENT_CSS_END_CLASS',        AI_ADB_CONTENT_CSS_END_CLASS, $vars);
    $vars = str_replace ('AI_ADB_CONTENT_DELETE_BEGIN_CLASS',   AI_ADB_CONTENT_DELETE_BEGIN_CLASS, $vars);
    $vars = str_replace ('AI_ADB_CONTENT_DELETE_END_CLASS',     AI_ADB_CONTENT_DELETE_END_CLASS, $vars);
    $vars = str_replace ('AI_ADB_CONTENT_REPLACE_BEGIN_CLASS',  AI_ADB_CONTENT_REPLACE_BEGIN_CLASS, $vars);
    $vars = str_replace ('AI_ADB_CONTENT_REPLACE_END_CLASS',    AI_ADB_CONTENT_REPLACE_END_CLASS, $vars);
  }
}

function ai_check_files () {
  global $ai_adb_id, $ai_adb_names, $ai_adb_new_names, $ai_wp_data;

  $ai_adb_base_name = $_SERVER ['DOCUMENT_ROOT'];
  $ai_adb_id = substr (preg_replace ("/[^A-Za-z0-9]+/", '', strtolower (md5 ($_SERVER ['DOCUMENT_ROOT'].NONCE_KEY))), 0, 7 + strlen ($ai_adb_base_name) % 5);

  if (!get_transient (AI_TRANSIENT_ADB_CLASS_1)) {
    set_transient (AI_TRANSIENT_ADB_CLASS_1, strtolower (ai_random_name (AI_TRANSIENT_ADB_CLASS_1, 12)), AI_TRANSIENT_ADB_CLASS_EXPIRATION);
  }
  define ('AI_ADB_CONTENT_CSS_BEGIN_CLASS', get_transient (AI_TRANSIENT_ADB_CLASS_1));

  if (!get_transient (AI_TRANSIENT_ADB_CLASS_2)) {
    set_transient (AI_TRANSIENT_ADB_CLASS_2, strtolower (ai_random_name (AI_TRANSIENT_ADB_CLASS_2, 12)), AI_TRANSIENT_ADB_CLASS_EXPIRATION);
  }
  define ('AI_ADB_CONTENT_CSS_END_CLASS', get_transient (AI_TRANSIENT_ADB_CLASS_2));

  if (!get_transient (AI_TRANSIENT_ADB_CLASS_3)) {
    set_transient (AI_TRANSIENT_ADB_CLASS_3, strtolower (ai_random_name (AI_TRANSIENT_ADB_CLASS_3, 12)), AI_TRANSIENT_ADB_CLASS_EXPIRATION);
  }
  define ('AI_ADB_CONTENT_DELETE_BEGIN_CLASS', get_transient (AI_TRANSIENT_ADB_CLASS_3));

  if (!get_transient (AI_TRANSIENT_ADB_CLASS_4)) {
    set_transient (AI_TRANSIENT_ADB_CLASS_4, strtolower (ai_random_name (AI_TRANSIENT_ADB_CLASS_4, 12)), AI_TRANSIENT_ADB_CLASS_EXPIRATION);
  }
  define ('AI_ADB_CONTENT_DELETE_END_CLASS', get_transient (AI_TRANSIENT_ADB_CLASS_4));

  if (!get_transient (AI_TRANSIENT_ADB_CLASS_5)) {
    set_transient (AI_TRANSIENT_ADB_CLASS_5, strtolower (ai_random_name (AI_TRANSIENT_ADB_CLASS_5, 12)), AI_TRANSIENT_ADB_CLASS_EXPIRATION);
  }
  define ('AI_ADB_CONTENT_REPLACE_BEGIN_CLASS', get_transient (AI_TRANSIENT_ADB_CLASS_5));

  if (!get_transient (AI_TRANSIENT_ADB_CLASS_6)) {
    set_transient (AI_TRANSIENT_ADB_CLASS_6, strtolower (ai_random_name (AI_TRANSIENT_ADB_CLASS_6, 12)), AI_TRANSIENT_ADB_CLASS_EXPIRATION);
  }
  define ('AI_ADB_CONTENT_REPLACE_END_CLASS', get_transient (AI_TRANSIENT_ADB_CLASS_6));

  if (get_adb_detection () == AI_ADB_DETECTION_ADVANCED) {
    $upload_dir = wp_upload_dir();
    $script_path_ai = $upload_dir ['basedir'] . '/ad-inserter/';
    $script_path = $script_path_ai.$ai_adb_id.'/';

    if (isset ($_POST [AI_FORM_CLEAR])) {
      check_admin_referer ('save_adinserter_settings');
      recursive_remove_directory ($script_path_ai);
    }

    $recreate_files = $ai_wp_data [AI_FRONTEND_JS_DEBUGGING] || file_exists ($script_path . AI_ADB_DBG_FILENAME) || get_transient (AI_TRANSIENT_ADB_FILES_VERSION) != AD_INSERTER_VERSION;

    if (!file_exists ($script_path_ai) || !file_exists ($script_path) || defined ('AI_ADB_2_FILE_RECREATED') || $recreate_files) {

      set_transient (AI_TRANSIENT_ADB_FILES_VERSION, AD_INSERTER_VERSION, 0);

  //    $ai_subdirs = glob ($script_path_ai.'*', GLOB_ONLYDIR);
  //    foreach ($ai_subdirs as $ai_subdir) {
  //      if (file_exists ($ai_subdir.'/'.AI_ADB_1_FILENAME))
  //        recursive_remove_directory ($ai_subdir);
  //    }

      $ai_adb_names = array (
        AI_ADB_1_NAME,
        AI_ADB_2_NAME,
        AI_ADB_3_NAME1,
        AI_ADB_3_NAME2,
        AI_ADB_4_NAME1,
        AI_ADB_4_NAME2,
        'ai_adb_debugging',
        'ai_adb_active',
        'ai_adb_counter',
        'ai_adb_detected',
        'ai_adb_undetected',
        'ai_adb_overlay',
        'ai_adb_message_window',
        'ai_adb_message_undismissible',
        'ai_adb_act_cookie_name',
        'ai_adb_message_cookie_lifetime',
        'ai_adb_pgv_cookie_name',
        'ai_adb_action',
        'ai_adb_page_views',
        'ai_adb_page_view_counter',
        'ai_adb_selectors',
        'ai_adb_selector',
        'ai_adb_el_counter',
        'ai_adb_el_zero',
        'ai_adb_redirecstion_url',
        'ai_adb_page_redirection_cookie_name',
        'ai_adb_process_content',
        'ai_adb_parent',
        'ai_adb_action',
        'ai_adb_css',
        'ai_adb_style',
        'ai_adb_status',
        'ai_adb_text',
        'ai_adb_redirection_url',
      );

      $ai_adb_new_names = array ();
      foreach ($ai_adb_names as $name) {
        $ai_adb_new_names []= ai_random_name ($name, 12);
      }

      @mkdir ($script_path_ai, 0755, true);
      @mkdir ($script_path, 0755, true);

      $script = file_get_contents (AD_INSERTER_PLUGIN_DIR.'js/'.AI_ADB_1_FILENAME);
      file_put_contents ($script_path . AI_ADB_1_FILENAME, ai_rename_ids ($script));

      $script = file_get_contents (AD_INSERTER_PLUGIN_DIR.'js/'.AI_ADB_2_FILENAME);
      file_put_contents ($script_path . AI_ADB_2_FILENAME, ai_rename_ids ($script));

      $script = file_get_contents (AD_INSERTER_PLUGIN_DIR.'js/'.AI_ADB_3_FILENAME);
      file_put_contents ($script_path . AI_ADB_3_FILENAME, ai_rename_ids ($script));

      $script = file_get_contents (AD_INSERTER_PLUGIN_DIR.'js/'.AI_ADB_4_FILENAME);
      file_put_contents ($script_path . AI_ADB_4_FILENAME, ai_rename_ids ($script));

      $code = ai_adb_code () . ai_adb_code_2 ();
      $code = str_replace ('AI_CONST_AI_ADB_1_NAME', AI_ADB_1_NAME, $code);
      $code = str_replace ('AI_CONST_AI_ADB_2_NAME', AI_ADB_2_NAME, $code);
      file_put_contents ($script_path . AI_ADB_FOOTER_FILENAME, ai_rename_ids ($code));

      file_put_contents ($script_path_ai .  'index.php', "<?php header ('Status: 404 Not found'); ?".">\nNot found");
      file_put_contents ($script_path .     'index.php', "<?php header ('Status: 404 Not found'); ?".">\nNot found");

      if ($ai_wp_data [AI_FRONTEND_JS_DEBUGGING]) file_put_contents ($script_path . AI_ADB_DBG_FILENAME, ''); else @unlink ($script_path . AI_ADB_DBG_FILENAME);
    }
  }
}

function ai_adb_code_2 () {
  return ai_get_js ('ai-adb-pro', false);
}

function add_footer_inline_scripts_1 () {
  global $ai_wp_data, $ai_adb_id, $block_object;

  if (defined ('AI_ADBLOCKING_DETECTION') && AI_ADBLOCKING_DETECTION) {

    if ($ai_wp_data [AI_ADB_DETECTION]) {

      if (get_adb_detection () == AI_ADB_DETECTION_ADVANCED) {
        $upload_dir = wp_upload_dir();
        $script_url = $upload_dir ['baseurl'] . '/ad-inserter/'.$ai_adb_id.'/';
      } else $script_url = plugins_url ('js/', AD_INSERTER_FILE);

      if (is_ssl()) {
        $script_url = str_replace ('http://', 'https://', $script_url);
      }

      echo '<div id="banner-advert-container" class="adsense sponsor-ad" style="position:absolute; z-index: -10; height: 1px; width: 1px; top: -1px; left: -1px;"><img id="im_popupFixed" class="ad-inserter adsense ad-img ad-index" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"></div>', "\n";
      echo "<script type='text/javascript' src='", $script_url, AI_ADB_1_FILENAME.'?ver=', rand (1, 9999999), "'></script>\n";
      echo "<script type='text/javascript' src='", $script_url, AI_ADB_2_FILENAME.'?ver=', rand (1, 9999999), "'></script>\n";
      echo "<script type='text/javascript' src='", $script_url, AI_ADB_3_FILENAME.'?ver=', rand (1, 9999999), "'></script>\n";
      echo "<script type='text/javascript' src='", $script_url, AI_ADB_4_FILENAME.'?ver=', rand (1, 9999999), "'></script>\n";
    }
  }
}

function add_footer_inline_scripts_2 () {
  global $ai_wp_data, $ai_adb_id, $block_object;

  if (get_dynamic_blocks () == AI_DYNAMIC_BLOCKS_CLIENT_SIDE) {
    echo ai_get_js ('ai-ip');
  }

  if (defined ('AI_ADBLOCKING_DETECTION') && AI_ADBLOCKING_DETECTION) {
    if ($ai_wp_data [AI_ADB_DETECTION]) {

      if (get_adb_detection () == AI_ADB_DETECTION_ADVANCED) {
        $upload_dir = wp_upload_dir();
        $script_path = $upload_dir ['basedir'] . '/ad-inserter/' . $ai_adb_id . '/';

        if (file_exists ($script_path . AI_ADB_FOOTER_FILENAME)) {
          echo ai_replace_js_data (file_get_contents ($script_path . AI_ADB_FOOTER_FILENAME));
        }
      } else {
          $code = ai_adb_code () . ai_adb_code_2 ();
          $code = str_replace ('AI_CONST_AI_ADB_1_NAME', AI_ADB_1_NAME, $code);
          $code = str_replace ('AI_CONST_AI_ADB_2_NAME', AI_ADB_2_NAME, $code);
          echo ai_replace_js_data ($code);
        }
    }
  }

  if ($ai_wp_data [AI_TRACKING]) {
    echo ai_get_js ('ai-tracking');
  }
}

function add_footer_inline_footer_html () {
  global $ai_wp_data;

  if (isset ($ai_wp_data [AI_TRIGGER_ELEMENTS])) {
    foreach ($ai_wp_data [AI_TRIGGER_ELEMENTS] as $block => $data) {
      if (is_int ($data))
        echo '<div id="ai-position-'.$block.'" style="position: absolute; top: '.$data.'px;"></div>', "\n"; else
          echo '<div id="ai-position-'.$block.'" style="position: absolute;" data-ai-position-pc="'.$data.'"></div>', "\n";
    }
  }
}

function generate_alignment_css_2 () {
  $styles = array ();

  $styles [AI_ALIGNMENT_STICKY_LEFT]    = array (AI_TEXT_STICKY_LEFT,    get_main_alignment_css (AI_ALIGNMENT_CSS_STICKY_LEFT));
  $styles [AI_ALIGNMENT_STICKY_RIGHT]   = array (AI_TEXT_STICKY_RIGHT,   get_main_alignment_css (AI_ALIGNMENT_CSS_STICKY_RIGHT));
  $styles [AI_ALIGNMENT_STICKY_TOP]     = array (AI_TEXT_STICKY_TOP,     get_main_alignment_css (AI_ALIGNMENT_CSS_STICKY_TOP));
  $styles [AI_ALIGNMENT_STICKY_BOTTOM]  = array (AI_TEXT_STICKY_BOTTOM,  get_main_alignment_css (AI_ALIGNMENT_CSS_STICKY_BOTTOM));

  return $styles;
}
                                   /* NOT USED ? */
function ai_adb_block_actions ($obj, $hide_label = false) {
  global $block_object, $ai_wp_data;

  if (defined ('AI_ADBLOCKING_DETECTION') && AI_ADBLOCKING_DETECTION) {

    switch ($obj->get_adb_block_action ()) {
      case AI_ADB_BLOCK_ACTION_REPLACE:

          $globals_name = AI_ADB_FALLBACK_DEPTH_NAME;
          if (!isset ($ad_inserter_globals [$globals_name])) {
            $ad_inserter_globals [$globals_name] = 0;
          }

          $fallback_block = $obj->get_adb_block_replacement ();
          if ($fallback_block != '' && $fallback_block != 0 && $fallback_block <= AD_INSERTER_BLOCKS && $fallback_block != $obj->number && $ad_inserter_globals [$globals_name] < 2) {
            $ad_inserter_globals [$globals_name] ++;


            $adb_label = '';
            $no_adb_label = '';

            if (($ai_wp_data [AI_WP_DEBUGGING] & AI_DEBUG_BLOCKS) != 0) {
              $debug_adb_on   = new ai_block_labels ('ai-debug-adb-status on');
              $debug_adb_off  = new ai_block_labels ('ai-debug-adb-status off');

              $adb_label =
                $debug_adb_on->adb_hidden_section_start () .
                $debug_adb_on->center_bar    ('AD BLOCKING') .
                $debug_adb_on->message ('BLOCK INSERTED BUT NOT VISIBLE') .
                $debug_adb_on->adb_hidden_section_end ();

              $no_adb_label = $debug_adb_off->center_bar ('NO AD BLOCKING');
            }

            $obj->additional_code_before = $adb_label . "<div class='ai-adb-hide' data-ai-debug='$obj->number'>\n" . $no_adb_label . $obj->additional_code_before;
            $obj->additional_code_after .= "</div>\n";

            $fallback_obj = $block_object [$fallback_block];
            $fallback_code = $fallback_obj->ai_getProcessedCode (true);

            if ($fallback_obj->w3tc_code != '' && get_dynamic_blocks () == AI_DYNAMIC_BLOCKS_SERVER_SIDE_W3TC && !defined ('AI_NO_W3TC')) {
              $fallback_code = "[#AI_CODE2#]";
            }

            $fallback_no_adb_label = '';

            if (($ai_wp_data [AI_WP_DEBUGGING] & AI_DEBUG_BLOCKS) != 0) {
              $title = '';
              $counters = $fallback_obj->ai_get_counters ($title);
              $version_name = $fallback_obj->version_name == '' ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : ' - ' . $fallback_obj->version_name;

              $fallback_block_name = $fallback_obj->number . ' ' . $fallback_obj->get_ad_name () . '<kbd data-separator=" - " class="ai-option-name">' . $version_name . '</kbd>';

              $debug_fallback = new ai_block_labels ('ai-debug-fallback');

              $fallback_no_adb_label  =
                $debug_fallback->adb_visible_section_start () .
                $debug_fallback->block_start () .
                $debug_fallback->bar ($fallback_block_name, '', 'AD BLOCKING REPLACEMENT') .
                $debug_fallback->message ('BLOCK INSERTED BUT NOT VISIBLE') .
                $debug_fallback->block_end () .
                $debug_fallback->adb_visible_section_end ();

              $fallback_code =
                $debug_fallback->block_start () .
                $debug_fallback->bar ($fallback_block_name, '', 'AD BLOCKING REPLACEMENT', $counters, $title) .
                '<div class="ai-code">' . $fallback_code . '</div>'.
                $debug_fallback->block_end ();
            }

            $fallback_tracking = $fallback_obj->get_tracking () ? '' : ' ai-no-tracking';

            $additional_code_before2 = $fallback_no_adb_label . "<div class='ai-adb-show$fallback_tracking' style='visibility: hidden; display: none;' data-ai-tracking='" .
              base64_encode ("[{$fallback_obj->number},{$fallback_obj->code_version},\"{$fallback_obj->get_ad_name ()}\",\"{$fallback_obj->version_name}\"]") .
              "' data-ai-debug='$obj->number <= $fallback_obj->number'>\n";
            $additional_code_after2 = "</div>\n";

            if ($fallback_obj->w3tc_code != '' && get_dynamic_blocks () == AI_DYNAMIC_BLOCKS_SERVER_SIDE_W3TC && !defined ('AI_NO_W3TC')) {
              $obj->w3tc_code2 = $fallback_obj->w3tc_code . ' $ai_code = base64_decode (\''.base64_encode ($additional_code_before2).'\') . $ai_code . base64_decode (\''.base64_encode ($additional_code_after2).'\');';
            }

            $obj->additional_code_after .= $additional_code_before2 . $fallback_code . $additional_code_after2;

            $ad_inserter_globals [$globals_name] --;
          }

        break;
      case AI_ADB_BLOCK_ACTION_SHOW:
        $no_adb_label = '';
        $adb_label    = '';

        // By default prevent tracking
        $obj->code_version = '""';

        if (($ai_wp_data [AI_WP_DEBUGGING] & AI_DEBUG_BLOCKS) != 0) {
          $debug_adb_on   = new ai_block_labels ('ai-debug-adb-status on');
          $debug_adb_off  = new ai_block_labels ('ai-debug-adb-status off');

          $no_adb_label =
            $debug_adb_off->adb_visible_section_start () .
            $debug_adb_off->invisible_start () .
            $debug_adb_off->center_bar ('NO AD BLOCKING') .
            $debug_adb_off->message ('BLOCK INSERTED BUT NOT VISIBLE') .
            $debug_adb_off->invisible_end () .
            $debug_adb_off->adb_visible_section_end ();

          $adb_label =
            $debug_adb_on->invisible_start () .
            $debug_adb_on->center_bar ('AD BLOCKING') .
            $debug_adb_on->invisible_end ();

        }

        $obj->additional_code_before = $no_adb_label . "<div class='ai-adb-show' style='visibility: hidden; display: none;' data-ai-tracking='" . base64_encode ("[{$obj->number},\"\",\"{$obj->get_ad_name ()}\",\"{$obj->version_name}\"]") . "' data-ai-debug='$obj->number'>\n" . $adb_label;
        $obj->additional_code_after  .= "</div>\n";

        break;
      case AI_ADB_BLOCK_ACTION_HIDE:
        $no_adb_label = '';
        $adb_label    = '';

        if (($ai_wp_data [AI_WP_DEBUGGING] & AI_DEBUG_BLOCKS) != 0) {
          $debug_adb_on   = new ai_block_labels ('ai-debug-adb-status on');
          $debug_adb_off  = new ai_block_labels ('ai-debug-adb-status off');

          $adb_label =
            $debug_adb_on->adb_hidden_section_start () .
            $debug_adb_on->invisible_start () .
            $debug_adb_on->center_bar ('AD BLOCKING') .
            $debug_adb_on->message ('BLOCK INSERTED BUT NOT VISIBLE') .
            $debug_adb_on->invisible_end () .
            $debug_adb_on->adb_hidden_section_end ();

          $no_adb_label =
            $debug_adb_off->invisible_start () .
            $debug_adb_off->center_bar ('NO AD BLOCKING') .
            $debug_adb_off->invisible_end ();
        }

        $obj->additional_code_before = $adb_label . "<div class='ai-adb-hide' data-ai-debug='$obj->number'>\n" . $no_adb_label;
        $obj->additional_code_after  .= "</div>\n";
        break;
    }
  }
}

function ai_shortcode ($parameters) {
  if (($adb = trim ($parameters ['adb'])) != '') {
    $css_attr = "";
    if (($css = trim ($parameters ['css'])) != '') {
      $css_attr = " data-css='$css'";
    }
    $text_attr = "";
    if (($text = trim ($parameters ['text'])) != '') {
      $text_attr = " data-text='$text'";
    }
    $selectors_attr = "";
    if (($selectors = trim ($parameters ['selectors'])) != '') {
      $selectors_attr = " data-selectors='$selectors'";
    }
    switch ($adb) {
      case 'hide':
        return  "<span class='" . AI_ADB_CONTENT_CSS_BEGIN ."'{$selectors_attr}></span>";
        break;
      case 'hide-end':
        return  "<span class='" . AI_ADB_CONTENT_CSS_END ."'></span>";
        break;
      case 'css':
        return  "<span class='" . AI_ADB_CONTENT_CSS_BEGIN ."'{$css_attr}{$selectors_attr}></span>";
        break;
      case 'css-end':
        return  "<span class='" . AI_ADB_CONTENT_CSS_END ."'></span>";
        break;
      case 'delete':
        return  "<span class='" . AI_ADB_CONTENT_DELETE_BEGIN ."'{$selectors_attr}></span>";
        break;
      case 'delete-end':
        return  "<span class='" . AI_ADB_CONTENT_DELETE_END ."'></span>";
        break;
      case 'replace':
        return  "<span class='" . AI_ADB_CONTENT_REPLACE_BEGIN ."'{$text_attr}{$css_attr}{$selectors_attr}></span>";
        break;
      case 'replace-end':
        return  "<span class='" . AI_ADB_CONTENT_REPLACE_END ."'></span>";
        break;
    }
  }
}

function calculate_chart_data (&$chart_data, $date_start, $date_end, $first_date, &$impressions, &$clicks, &$ctr, &$average_impressions, &$average_clicks, &$average_ctr) {
  foreach ($chart_data as $date => $data) {
    $imp = $data [0];
    $clk = $data [1];

//          $imp = 250 + rand (232, 587);
//          $clk = 1 + rand (0, 4);

    $impressions  []= $imp;
    $clicks       []= $clk;
    $ctr          []= $imp != 0 ? number_format (100 * $clk / $imp, 2) : 0;
  }

  $gmt_offset = get_option ('gmt_offset') * 3600;
  $today = date ("Y-m-d", time () + $gmt_offset);

  $no_data_before = (strtotime ($first_date) - strtotime ($date_start)) / 24 / 3600;
  $no_data_after  = (strtotime ($date_end) - strtotime ($today)) / 24 / 3600;

//  $no_data_before = 0;


  if ($no_data_before != 0) {
    for ($index = 0; $index < $no_data_before; $index ++) {
      $impressions [$index]         = null;
      $clicks [$index]              = null;
      $ctr [$index]                 = null;
    }
  }

  if ($no_data_after != 0) {
    $last_index = count ($impressions) - 1;
    for ($index = $last_index - $no_data_after + 1; $index <= $last_index; $index ++) {
      $impressions [$index]         = null;
      $clicks [$index]              = null;
      $ctr [$index]                 = null;
    }
  }

  for ($index = 0; $index < count ($impressions); $index ++) {

    $interval_impressions = 0;
    $interval_clicks      = 0;
    $interval_ctr         = 0;
    $interval_counter     = 0;

    for ($average_index = $index - AI_STATISTICS_AVERAGE_PERIOD + 1; $average_index <= $index; $average_index ++) {
      if ($average_index >= 0 && $impressions [$average_index] !== null && $clicks [$average_index] !== null && $ctr [$average_index] !== null) {
        $interval_impressions += $impressions [$average_index];
        $interval_clicks      += $clicks [$average_index];
        $interval_ctr         += $ctr [$average_index];
        $interval_counter ++;
      }
    }

    $average_impressions  [] = $interval_counter == 0 ? 0 : $interval_impressions / $interval_counter;
    $average_clicks       [] = $interval_counter == 0 ? 0 : $interval_clicks / $interval_counter;
    $average_ctr          [] = $interval_counter == 0 ? 0 : $interval_ctr / $interval_counter;
  }

  if ($no_data_before != 0) {
    for ($index = 0; $index < $no_data_before; $index ++) {
      $average_impressions [$index] = null;
      $average_clicks [$index]      = null;
      $average_ctr [$index]         = null;
    }
  }

  if ($no_data_after != 0) {
    $last_index = count ($impressions) - 1;
    for ($index = $last_index - $no_data_after + 1; $index <= $last_index; $index ++) {
      $average_impressions [$index] = null;
      $average_clicks [$index]      = null;
      $average_ctr [$index]         = null;
    }
  }
}

function compare_versions ($a, $b) {
  if ($a == AI_ADB_FLAG_BLOCKED) $a = - 1;
  if ($b == AI_ADB_FLAG_BLOCKED) $b = - 1;

 if ($a == $b) return 0;
 return ($a < $b) ? - 1 : 1;
}

function ai_ajax_backend_2 () {
  global $ai_db_options, $ai_wp_data, $block_object, $wpdb;

  if (isset ($_GET ["export"])) {
    $block = $_GET ["export"];
    if (is_numeric ($block)) {
      if ($block == 0) echo base64_encode (serialize ($ai_db_options));
        elseif ($block >= 1 && $block <= AD_INSERTER_BLOCKS) {
          $obj = $block_object [$block];
          echo base64_encode (serialize ($obj->wp_options));
        }
    }
  }

  if (isset ($_GET ["update"])) {
    if ($_GET ["update"] == 'maxmind') {
      if (!is_multisite() || is_main_site ()) {
        if (get_geo_db () == AI_GEO_DB_MAXMIND) {
          $error_message = ai_update_ip_db_maxmind ();

          $db_file = get_geo_db_location ();
          if (!file_exists ($db_file)) {
            echo '["File ' . $db_file . ' missing. ' . $error_message. '","missing"]';
          }
        }
      }
    }
  }

  elseif (isset ($_GET ["statistics"])) {
    $block = $_GET ["statistics"];
    if (is_numeric ($block) && $block >= 0 && $block <= AD_INSERTER_BLOCKS && isset ($_GET ['start-date']) && isset ($_GET ['end-date'])) {

      $gmt_offset = get_option ('gmt_offset') * 3600;
      $today = date ("Y-m-d", time () + $gmt_offset);

      $date_start = $_GET ['start-date'];
      $date_end   = $_GET ['end-date'];

      $date_end_time    = strtotime ($date_end);
      $date_start_time  = strtotime ($date_start);

      $pageview_statistics = $block == 0;

      $adb_statistics = isset ($_GET ['adb']) && $_GET ['adb'] == 1 || $pageview_statistics && $ai_wp_data [AI_ADB_DETECTION];
//      $adb_statistics = false;

      $message = '';

      if (isset ($_GET ['delete']) && $_GET ['delete'] == 1) {

        if ($date_start == '' && $date_end == '') {
          $wpdb->query ("DELETE FROM " . AI_STATISTICS_DB_TABLE . " WHERE block = " . $block);
          $message = "All statistics data for block $block deleted";
        } else {
            if (abs ($date_start_time - time ()) < 800 * 24 * 3600 && abs ($date_end_time - time ()) < 800 * 24 * 3600) {
              $wpdb->query ("DELETE FROM " . AI_STATISTICS_DB_TABLE . " WHERE block = " . $block . " AND date >= '$date_start' AND date <= '$date_end' ");
              $message = "Statistics data between $date_start and $date_end deleted";
            }
          }
      }

      if ($date_start_time < time () - 800 * 24 * 3600) {
        $date_start = $today;
        $date_start_time  = strtotime ($date_start);
      }

      if ($date_end_time < time () - 800 * 24 * 3600) {
        $date_end = $date_end;
        $date_end_time    = strtotime ($date_end);
      }

      $days = ($date_end_time - $date_start_time) / 24 / 3600 + 1;

      if ($days > 365 ) {
        $days = 365;
        $date_start = date ("Y-m-d", $date_end_time - 365 * 24 * 3600);
        $date_start_time  = strtotime ($date_start);
      } elseif ($days < 1 ) {
        $days = 1;
        $date_end = date ("Y-m-d", $date_start_time - 1 * 24 * 3600);
        $date_end_time  = strtotime ($date_end);
      }

      $date_start = date ("Y-m-d", strtotime ($date_start) - AI_STATISTICS_AVERAGE_PERIOD * 24 * 3600);

      $chart_data = array ();
      $day_time = $date_start_time - AI_STATISTICS_AVERAGE_PERIOD * 24 * 3600;
      $days_to_do = $days + AI_STATISTICS_AVERAGE_PERIOD;
      while ($days_to_do != 0) {
        $chart_data [date ("Y-m-d", $day_time)] = array (0, 0);
        $day_time += 24 * 3600;
        $days_to_do --;
      }

      $first_date = $date_end;
      $last_date  = $date_start;

      $results = $wpdb->get_results ('SELECT * FROM ' . AI_STATISTICS_DB_TABLE . ' WHERE block = ' . $block . " AND date >= '$date_start' AND date <= '$date_end' ", ARRAY_N);

      $versions = array ();
      $chart_data_total = $chart_data;
      $chart_data_versions = array ();

      if (isset ($results [0])) {

        foreach ($results as $result) {
          $version = $result [2] & AI_ADB_VERSION_MASK;

          if (($result [2] & AI_ADB_FLAG_BLOCKED) != 0) {
            if (!$pageview_statistics)
              if ($adb_statistics) $version = AI_ADB_FLAG_BLOCKED; else continue;
          }

          if (!in_array ($version, $versions)) {
            $versions []= $version;
            $chart_data_versions [$version] = $chart_data;
          }
        }

        usort ($versions, "compare_versions");
        ksort ($chart_data_versions);

        foreach ($results as $result) {
          $version = $result [2] & AI_ADB_VERSION_MASK;
          $date = $result [3];
          $views = $result [4];
          $clicks = $result [5];

          if (($result [2] & AI_ADB_FLAG_BLOCKED) != 0) {
            if ($pageview_statistics) {
              $clicks = $views;
            } else {
              if ($adb_statistics) $version = AI_ADB_FLAG_BLOCKED; else continue;
            }
          }

//          $result [4] = rand (4, 10);
//          $result [5] = rand (4, 10);

          if ($date < $first_date) $first_date = $date;
          if ($date > $last_date) $last_date = $date;
          if (isset ($chart_data_total [$date])) {
            $chart_data_total [$date] = array ($chart_data_total [$date][0] + $views, $chart_data_total [$date][1] + $clicks);
          }
          if (isset ($chart_data_versions [$version][$date])) {
            $chart_data_versions [$version][$date] = array ($chart_data_versions [$version][$date][0] + $views, $chart_data_versions [$version][$date][1] + $clicks);
          }
        }
      }

      $show_versions = count ($versions) > 1 || (count ($versions) == 1 && $versions [0] != 0);

      if ($show_versions) {

        $processed_chart_data_versions = array ();
        foreach ($chart_data_versions as $version => $chart_data_version) {
          $impressions          = array ();
          $clicks               = array ();
          $ctr                  = array ();
          $average_impressions  = array ();
          $average_clicks       = array ();
          $average_ctr          = array ();

          calculate_chart_data ($chart_data_version, $date_start, $date_end, $first_date, $impressions, $clicks, $ctr, $average_impressions, $average_clicks, $average_ctr);
          $processed_chart_data_versions [$version] = array ($impressions, $clicks, $ctr, $average_impressions, $average_clicks, $average_ctr);
        }

//        if (!isset ($chart_data_versions [0])) {
//          $null = array_fill (0, count ($processed_chart_data_versions [$version][0]), null);
//          $processed_chart_data_versions [0] = array ($null, $null, $null, $null, $null, $null);
//        }

        $only_blocked_version = $adb_statistics && count ($versions) == 2 && $versions [0] == AI_ADB_FLAG_BLOCKED && $versions [1] == 0;

        if (!$pageview_statistics) {
          $code_generator = new ai_code_generator ();

//          $rotation_data = $code_generator->import_rotation ($obj->get_ad_data()) ['options'];

          $obj = $block_object [$block];
          $rotation_data = $code_generator->import_rotation ($obj->get_ad_data());
          $rotation_data = $rotation_data ['options'];
        }

        $legend_data = array ();
        $legends = array ();
        foreach ($versions as $version) {
          if     ($version == 0)                    $legend = $pageview_statistics ? 'Unknown'                    : ($only_blocked_version ? 'DISPLAYED' : 'No version');
          elseif ($version == AI_ADB_FLAG_BLOCKED)  $legend = $pageview_statistics ? 'Ad Blocking'                : 'BLOCKED';
          else                                      $legend = $pageview_statistics ? get_viewport_name ($version) : (
            isset ($rotation_data [$version - 1]['name']) && trim ($rotation_data [$version - 1]['name']) != '' ? $rotation_data [$version - 1]['name'] : chr (ord ('A') + $version - 1)
          );

          $legends [] = $legend;

          $legend_data ['serie'.($version + 1)] = $legend;
        }
      }

      $impressions          = array ();
      $clicks               = array ();
      $ctr                  = array ();
      $average_impressions  = array ();
      $average_clicks       = array ();
      $average_ctr          = array ();

      calculate_chart_data ($chart_data_total, $date_start, $date_end, $first_date, $impressions, $clicks, $ctr, $average_impressions, $average_clicks, $average_ctr);

      $labels = array ();
      foreach ($chart_data as $date => $data) {
        $date_elements = explode ('-', $date);

        $page_width = 690;

        if ($date_elements [2] == '01') {
          if ($date_elements [1] == '01') {
            $labels [] = $date_elements [0];
          } else {
              $labels [] = date ("M", mktime (0, 0, 0, $date_elements [1], 1, 2017));
            }
        } elseif ($page_width / $days > 20) {
            $labels [] = $date_elements [2];
          } elseif ($page_width / $days > 10) {
              if ($date_elements [2] % 5 == 0) {
                $labels [] = $date_elements [2];
              } else $labels [] = '';
          } elseif ($page_width / $days > 4) {
              $labels [] = '';
          } else $labels [] = '';
      }

      $labels               = array_slice ($labels, - $days);

      $impressions          = array_slice ($impressions, - $days);
      $clicks               = array_slice ($clicks, - $days);
      $ctr                  = array_slice ($ctr, - $days);
      $average_impressions  = array_slice ($average_impressions, - $days);
      $average_clicks       = array_slice ($average_clicks, - $days);
      $average_ctr          = array_slice ($average_ctr, - $days);

      $impressions_max_value  = chart_range (max (max ($impressions), max ($average_impressions)), true);
      $clicks_max_value       = chart_range (max (max ($clicks), max ($average_clicks)), true);
      $ctr_max_value          = chart_range (max (max ($ctr), max ($average_ctr)), false);

      $total_impressions  = array_sum ($impressions);
      $total_clicks       = array_sum ($clicks);
      $total_ctr          = $total_impressions != 0 ? number_format (100 * $total_clicks / $total_impressions, 2) : 0;

      if ($message != '') echo "  <div style='margin: 0 0 10px; text-align: center; font-size: 14px; color: #888;'>$message</div>\n";

      $impressions_name   = $pageview_statistics ? 'Pageviews'              : 'Impressions';
      $clicks_chart_name  = $pageview_statistics ? 'Ad Blocking'            : 'Clicks';
      $clicks_label_name  = $pageview_statistics ? 'event'                  : 'Clicks';
      $ctr_chart_name     = $pageview_statistics ? 'Ad Blocking Share [%]'  : 'CTR [%]';

      echo "  <div class='ai-chart-container'><div class='ai-chart-label'>$impressions_name: $total_impressions</div>\n";
      echo "  <div class='ai-chart not-configured' data-template='ai-impressions' data-labels='", json_encode ($labels), "' data-values-1='", json_encode ($impressions), "' data-values-2='", json_encode ($average_impressions), "' data-max='", json_encode ($impressions_max_value), "'></div>\n";
      echo "  </div>\n";

      if (!$pageview_statistics) {
        echo "  <div class='ai-chart-container'><div class='ai-chart-label'>Clicks: $total_clicks</div>\n";
        echo "  <div class='ai-chart not-configured' data-template='ai-clicks' data-labels='", json_encode ($labels), "' data-values-1='", json_encode ($clicks), "' data-values-2='", json_encode ($average_clicks), "' data-max='", json_encode ($clicks_max_value), "'></div>\n";
        echo "  </div>\n";

        echo "  <div class='ai-chart-container'><div class='ai-chart-label'>CTR: $total_ctr %</div>\n";
        echo "  <div class='ai-chart not-configured' data-template='ai-ctr' data-labels='", json_encode ($labels), "' data-values-1='", json_encode ($ctr), "' data-values-2='", json_encode ($average_ctr), "' data-max='", json_encode ($ctr_max_value), "'></div>\n";
        echo "  </div>\n";
      }

      if ($show_versions) {
        $impressions_          = array ();
        $clicks_               = array ();
        $ctr_                  = array ();
        $average_impressions_  = array ();
        $average_clicks_       = array ();
        $average_ctr_          = array ();

        $impressions_share     = array ();
        $clicks_share          = array ();
        $ctr_share             = array ();
        $tooltips              = array ();

        $impressions_max_value = 0;
        $clicks_max_value      = 0;
        $ctr_max_value         = 0;

        $average_impressions_max_value = 0;
        $average_clicks_max_value      = 0;
        $average_ctr_max_value         = 0;

        $total_impressions     = 0;
        $total_clicks          = 0;

        foreach ($versions as $version) {
          $processed_chart_data  = $processed_chart_data_versions [$version];

          $impressions_          [$version] = array_slice ($processed_chart_data [0], - $days);
          $average_impressions_  [$version] = array_slice ($processed_chart_data [3], - $days);

          $impressions_sum      = array_sum ($impressions_ [$version]);
          $total_impressions    += $impressions_sum;
          $impressions_share    [] = $impressions_sum;

          if ($version == AI_ADB_FLAG_BLOCKED) {
            $clicks_          = array_fill (0, $days, null);
            $ctr_             = array_fill (0, $days, null);
            $average_clicks_  = array_fill (0, $days, null);
            $average_ctr_     = array_fill (0, $days, null);

            $clicks_sum           = 0;
          } else {
              $clicks_               [$version] = array_slice ($processed_chart_data [1], - $days);
              $ctr_                  [$version] = array_slice ($processed_chart_data [2], - $days);
              $average_clicks_       [$version] = array_slice ($processed_chart_data [4], - $days);
              $average_ctr_          [$version] = array_slice ($processed_chart_data [5], - $days);

              $clicks_sum           = array_sum ($clicks_ [$version]);
            }

          $total_clicks         += $clicks_sum;
          $clicks_share         [] = $clicks_sum;
          $ctr_value               = $impressions_sum != 0 ? (float) number_format (100 * $clicks_sum / $impressions_sum, 2) : 0;
          $ctr_share            [] = $ctr_value;

          $impressions_max_value          = max ($impressions_max_value, max ($impressions_ [$version]));
          $average_impressions_max_value  = max ($average_impressions_max_value, max ($average_impressions_ [$version]));

          if ($version == AI_ADB_FLAG_BLOCKED) {
              $clicks_max_value           = 0;
              $ctr_max_value              = 0;
              $average_clicks_max_value   = 0;
              $average_ctr_max_value      = 0;
          } else {
              $clicks_max_value           = max ($clicks_max_value, max ($clicks_ [$version]));
              $ctr_max_value              = max ($ctr_max_value, max ($ctr_ [$version]));
              $average_clicks_max_value   = max ($average_clicks_max_value, max ($average_clicks_ [$version]));
              $average_ctr_max_value      = max ($average_ctr_max_value, max ($average_ctr_ [$version]));
            }
        }

        foreach ($versions as $index => $version) {
          $impressions_percentage = $total_impressions != 0 ? (float) number_format (100 * $impressions_share [$index] / $total_impressions, 2) : 0;
          $clicks_percentage      = $total_clicks      != 0 ? (float) number_format (100 * $clicks_share      [$index] / $total_clicks, 2) : 0;
          $ctr_percentage         = $total_clicks      != 0 ? (float) number_format (100 * $clicks_share      [$index] / $total_clicks, 2) : 0;

          $tooltips_impressions [] = "<div class=\"ai-tooltip\"><div class=\"version\">{$legends [$index]}</div><div class=\"data\">{$impressions_share [$index]} ". substr (strtolower ($impressions_name), 0, - 1) . ($impressions_share [$index] == 1 ? '' : 's')."</div><div class=\"percentage\">$impressions_percentage%</div></div>";
          $tooltips_clicks      [] = "<div class=\"ai-tooltip\"><div class=\"version\">{$legends [$index]}</div><div class=\"data\">{$clicks_share [$index]} " . $clicks_label_name .($clicks_share [$index] == 1 ? '' : 's')."</div><div class=\"percentage\">$clicks_percentage%</div></div>";
          $tooltips_ctr         [] = "<div class=\"ai-tooltip\"><div class=\"version\">{$legends [$index]}</div><div class=\"data\">{$ctr_share [$index]}%</div></div>";
        }

        $impressions_max_value          = chart_range ($impressions_max_value, true);
        $clicks_max_value               = chart_range ($clicks_max_value, true);
        $ctr_max_value                  = chart_range ($ctr_max_value, false);
        $average_impressions_max_value  = chart_range ($average_impressions_max_value, true);
        $average_clicks_max_value       = chart_range ($average_clicks_max_value, true);
        $average_ctr_max_value          = chart_range ($average_ctr_max_value, false);

        echo "      <div style='margin: 8px 0;'>\n";

        echo "      <div class='ai-chart-container versions'><div class='ai-chart-label'>$impressions_name</div>\n";
        if ($total_impressions != 0)
          echo "        <div class='ai-chart not-configured' data-template='ai-bar' data-values-1='", json_encode ($impressions_share), "' data-max='", chart_range (max ($impressions_share), true), "' data-tooltips='", json_encode ($tooltips_impressions), "' data-tooltip-height='55' data-colors='", json_encode ($versions), "'></div>\n";
        echo "      </div>\n";

        if (!$only_blocked_version && !$pageview_statistics || $pageview_statistics && $adb_statistics) {
          echo "      <div class='ai-chart-container versions'><div class='ai-chart-label'>$clicks_chart_name</div>\n";
          if ($total_clicks != 0)
            echo "        <div class='ai-chart not-configured' data-template='ai-bar' data-values-1='", json_encode ($clicks_share), "' data-max='", chart_range (max ($clicks_share), true), "' data-tooltips='", json_encode ($tooltips_clicks), "' data-tooltip-height='55' data-colors='", json_encode ($versions), "'></div>\n";
          echo "      </div>\n";
        }

        if (!$only_blocked_version && !$pageview_statistics || $pageview_statistics && $adb_statistics) {
          echo "      <div class='ai-chart-container versions'><div class='ai-chart-label'>$ctr_chart_name</div>\n";
          if ($total_clicks != 0)
            echo "        <div class='ai-chart not-configured' data-template='ai-bar' data-values-1='", json_encode ($ctr_share), "' data-tooltips='", json_encode ($tooltips_ctr), "' data-tooltip-height='38' data-colors='", json_encode ($versions), "'></div>\n";
          echo "      </div>\n";
        }

        echo "      </div>\n";



        echo "    <div class='ai-chart-container legend'>\n";

?>
      <span class="ai-toolbar-button text" style="position: absolute; top: 0px; left: 675px; z-index: 202;">
        <input type="checkbox" value="0" style="display: none;" />
        <label class="checkbox-button ai-version-charts-button not-configured" title="Toggle detailed statistics">Details</label>
      </span>
<?php

        echo "      <div class='ai-chart not-configured' data-template='ai-versions-legend' data-labels='", json_encode ($labels);
        foreach ($processed_chart_data_versions as $version => $processed_chart_data) {
          echo  "' data-values-", $version + 1, "='", json_encode (array ());
        }
        echo "' data-legend='", json_encode ($legend_data), "'></div>\n";
        echo "    </div>\n";




        echo "    <div id='ai-version-charts-{$block}' class='ai-version-charts' style='display: none;'", ">\n";

        echo "      <div class='ai-chart-container'><div class='ai-chart-label'>$impressions_name</div>\n";
        echo "        <div class='ai-chart not-configured hidden' data-template='ai-versions' data-labels='", json_encode ($labels);
        foreach ($impressions_ as $version => $impressions_data) {
          echo  "' data-values-", $version + 1, "='", json_encode ($impressions_data);
        }
        echo "' data-max='", json_encode ($impressions_max_value), "'></div>\n";
        echo "      </div>\n";


        echo "      <div class='ai-chart-container'><div class='ai-chart-label'>Average $impressions_name</div>\n";
        echo "        <div class='ai-chart not-configured hidden' data-template='ai-versions' data-labels='", json_encode ($labels);
        foreach ($average_impressions_ as $version => $average_impressions_data) {
          echo  "' data-values-", $version + 1, "='", json_encode ($average_impressions_data);
        }
        echo "' data-max='", json_encode ($average_impressions_max_value), "'></div>\n";
        echo "      </div>\n";

        if (!$only_blocked_version || $pageview_statistics) {
          echo "      <div class='ai-chart-container'><div class='ai-chart-label'>$clicks_chart_name</div>\n";
          echo "        <div class='ai-chart not-configured hidden' data-template='ai-versions' data-labels='", json_encode ($labels);
          foreach ($clicks_ as $version => $clicks_data) {
            echo  "' data-values-", $version + 1, "='", json_encode ($clicks_data);
          }
          echo "' data-max='", json_encode ($clicks_max_value), "'></div>\n";
          echo "      </div>\n";


          echo "      <div class='ai-chart-container'><div class='ai-chart-label'>Average $clicks_chart_name</div>\n";
          echo "        <div class='ai-chart not-configured hidden' data-template='ai-versions' data-labels='", json_encode ($labels);
          foreach ($average_clicks_ as $version => $average_clicks_data) {
            echo  "' data-values-", $version + 1, "='", json_encode ($average_clicks_data);
          }
          echo "' data-max='", json_encode ($average_clicks_max_value), "'></div>\n";
          echo "      </div>\n";

          echo "      <div class='ai-chart-container'><div class='ai-chart-label'>$ctr_chart_name</div>\n";
          echo "        <div class='ai-chart not-configured hidden' data-template='ai-versions' data-labels='", json_encode ($labels);
          foreach ($ctr_ as $version => $ctr_data) {
            echo  "' data-values-", $version + 1, "='", json_encode ($ctr_data);
          }
          echo "' data-max='", json_encode ($ctr_max_value), "'></div>\n";
          echo "      </div>\n";


          echo "    <div class='ai-chart-container'><div class='ai-chart-label'>Average $ctr_chart_name</div>\n";

          echo "      <div class='ai-chart not-configured hidden' data-template='ai-versions' data-labels='", json_encode ($labels);
          foreach ($average_ctr_ as $version => $average_ctr_data) {
            echo  "' data-values-", $version + 1, "='", json_encode ($average_ctr_data);
          }
          echo "' data-max='", json_encode ($average_ctr_max_value), "'></div>\n";
          echo "    </div>\n";
        }

        echo "    </div>\n";
      } // if ($show_versions)
    }
  }
}

function ai_ajax_processing_2 () {
  global $ai_db_options, $ai_wp_data, $block_object, $wpdb;

  if (isset ($_GET ["ip-data"])) {
    $client_ip_address = get_client_ip_address ();
    ai_check_geo_settings ();
    if ($_GET ["ip-data"] == 'ip-address-country') {
      echo $client_ip_address, ',', ip_to_country ($client_ip_address);
    }
    elseif ($_GET ["ip-data"] == 'ip-address') {
      echo $client_ip_address;
    }
    elseif ($_GET ["ip-data"] == 'country') {
      echo ip_to_country ($client_ip_address);
    }
    elseif ($_GET ["ip-data"] == 'ip-address-country-city') {
      echo $client_ip_address, ',', ip_to_country ($client_ip_address, true);
    }
  }

  elseif (isset ($_POST ['views']) && is_array ($_POST ['views'])) {
    if (get_track_logged_in () == AI_TRACKING_DISABLED) {
      if (($ai_wp_data [AI_WP_USER] & AI_USER_LOGGED_IN) != 0) {
        if ($ai_wp_data [AI_FRONTEND_JS_DEBUGGING]) echo json_encode ('tracking for logged in users is disabled');
        return;
      }
    }

    $db_results = array ();
    foreach ($_POST ['views'] as $index => $block) {
      $version = $_POST ['versions'][$index];
      if (is_numeric ($block) && $block <= AD_INSERTER_BLOCKS && is_numeric ($version)) {
        $db_result = update_statistics ($block, $version, 1, 0, $ai_wp_data [AI_FRONTEND_JS_DEBUGGING]);
        if ($ai_wp_data [AI_FRONTEND_JS_DEBUGGING]) $db_results [$block]= $db_result;
      }
    }
    if ($ai_wp_data [AI_FRONTEND_JS_DEBUGGING] && !empty ($db_results)) echo json_encode ($db_results);
  }

  elseif (isset ($_POST ['click'])) {
    if (get_track_logged_in () == AI_TRACKING_DISABLED) {
      if (($ai_wp_data [AI_WP_USER] & AI_USER_LOGGED_IN) != 0) {
        if ($ai_wp_data [AI_FRONTEND_JS_DEBUGGING]) echo json_encode ('tracking for logged in users is disabled');
        return;
      }
    }

    if (is_numeric ($_POST ['click']) && $_POST ['click'] <= AD_INSERTER_BLOCKS && is_numeric ($_POST ['version'])) {
      $db_result = update_statistics ($_POST ['click'], $_POST ['version'], 0, 1, $ai_wp_data [AI_FRONTEND_JS_DEBUGGING]);
      if ($ai_wp_data [AI_FRONTEND_JS_DEBUGGING] && $db_result != '') echo json_encode ($db_result);
    }
  }
}
