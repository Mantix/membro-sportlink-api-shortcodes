<?php

/**
 * Plugin Name: Membro Sportlink API Shortcodes
 * Description: Verenigingen en clubs in het bezit van een API sleutel voor de Sportlink Dataservice kunnen deze plugin gebruiken om API data te tonen in een Wordpress website.
 * Version: 1.14.3.10
 * Author: Membro BV
 * Author URI: https://membro.nl/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * */

// PHP error reporting, should be turned off in production
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

// include the MembroSportlinkClient class
include 'membro-sportlink-api-shortcodes-client.php';

/***********************************************************************
 Registreer [membro-sportlink ...]
 */
function membro_sportlink_shortcode($atts) {
  $client = new MembroSportlinkClient(
    get_option('membro_sportlink_api_key'),
    get_option('membro_sportlink_api_pathname'),
    get_option('membro_sportlink_api_clubname')
  );

  extract(shortcode_atts(array(
    'uri' => 'uri',
    'extra' => 'extra',
    'template' => 'template',
    'fields' => null,
  ), $atts));

  return '<div class="membro-sportlink">' . $client->getData($uri, $extra, $template, $fields) . '</div>';
}

add_shortcode("membro-sportlink", "membro_sportlink_shortcode");

/***********************************************************************
 Registreer [membro-sportlink-team ...]
 */
function membro_sportlink_team_shortcode($atts) {
  $client = new MembroSportlinkClient(
    get_option('membro_sportlink_api_key'),
    get_option('membro_sportlink_api_pathname'),
    get_option('membro_sportlink_api_clubname')
  );

  extract(shortcode_atts(array(
    'list' => 'list',
  ), $atts));

  $output = '';
  if (isset($list) && count(explode(';', $list)) > 0) {
    foreach (explode(';', $list) as $teamId) {
      $output = $output . '<div class="team">';
      $output = $output . '<div class="team-results">' . $client->getData('/teams/' . $teamId . '/results', 'weeknummer=A') . '</div>';
      $output = $output . '<div class="team-ranking">' . $client->getData('/teams/' . $teamId . '/ranking') . '</div>';
      $output = $output . '<div class="team-schedule">' . $client->getData('/teams/' . $teamId . '/schedule', 'weeknummer=A') . '</div>';
      $output = $output . '</div>';
    }
  }

  return '<div class="membro-sportlink-team">' . $output . '</div>';
}

add_shortcode("membro-sportlink-team", "membro_sportlink_team_shortcode");

/***********************************************************************
 Registreer [membro-sportlink-team-slider ...]
 */
function membro_sportlink_team_slider_shortcode($atts) {
  $client = new MembroSportlinkClient(
    get_option('membro_sportlink_api_key'),
    get_option('membro_sportlink_api_pathname'),
    get_option('membro_sportlink_api_clubname')
  );

  extract(shortcode_atts(array(
    'id' => 'id',
    'extra' => 'extra',
  ), $atts));

  $output = '';
  if (isset($id)) {
    $output = $output . $client->getData('/teams/' . $id . '/schedule', 'weeknummer=C&slider=1&' . $extra);
    $output = $output . $client->getData('/teams/' . $id . '/results', 'weeknummer=A&slider=1&' . $extra);
  }

  return '<div class="membro-sportlink-team-slider">' . $output . '</div>';
}

add_shortcode("membro-sportlink-team-slider", "membro_sportlink_team_slider_shortcode");

/***********************************************************************
 Admin init functie
 */
if (is_admin()) {
  add_action('admin_menu', 'membro_sportlink_api_menu');
  add_action('admin_init', 'membro_sportlink_api_register_settings');
}

/***********************************************************************
 Define wordpress options menu
 */
function membro_sportlink_api_menu() {
  add_options_page(
    'Sportlink API Opties',   // Title in browser tab
    'Sportlink API',          // Title in settings menu
    'manage_options',    // Capability needed to see this menu
    'membro-sportlink-api-menu',     // Slug
    'membro_sportlink_api_options'
  ); // Function to call when rendering this menu
}

/***********************************************************************
 Rendering options page
 */
function membro_sportlink_api_options() {

  // create a client and receive data
  $client = new MembroSportlinkClient(get_option('membro_sportlink_api_key'), get_option('membro_sportlink_api_pathname'), get_option('membro_sportlink_api_clubname'));

  $test_call = $client->getStatus();
  $api_status = $test_call['apiStatus'];
  $connected_to_api = $test_call['connectedStatus'];

  wp_enqueue_style('membro-sportlink-api-style', plugins_url('shortcode-membro-sportlink-api-style.css', __FILE__));

  /* raintpl templating stores a compiled template in a folder. We need to be
  ** sure that this folder is writable otherwise the user has to create and set permissions
  ** manually. This is why we use the uploads folder since in all wordpress installations
  ** the uploads folder already has 777 permissions. */
  $cache_folder = ABSPATH . 'wp-content/uploads/shortcode-membro-sportlink-api/cache';

  // create cache folder
  if (!file_exists($cache_folder)) {
    mkdir($cache_folder, 0777, true);
  }

  // Start rendering page
?>
  <div class="wrap">

    <h2>Sportlink API Opties</h2>

    <?php
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'settings';
    ?>

    <h2 class="nav-tab-wrapper">
      <a href="?page=membro-sportlink-api-menu&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">Instellingen</a>
      <?php
      if ($api_status && $connected_to_api) {
      ?>
        <a href="?page=membro-sportlink-api-menu&tab=general-shortcodes" class="nav-tab <?php echo $active_tab == 'general-shortcodes' ? 'nav-tab-active' : ''; ?>">Algemene shortcodes</a>
        <a href="?page=membro-sportlink-api-menu&tab=team-shortcodes" class="nav-tab <?php echo $active_tab == 'team-shortcodes' ? 'nav-tab-active' : ''; ?>">Shortcodes per team</a>
        <a href="?page=membro-sportlink-api-menu&tab=match-shortcodes" class="nav-tab <?php echo $active_tab == 'match-shortcodes' ? 'nav-tab-active' : ''; ?>">Shortcodes per wedstrijd</a>
        <a href="?page=membro-sportlink-api-menu&tab=parameter-shortcodes" class="nav-tab <?php echo $active_tab == 'parameter-shortcodes' ? 'nav-tab-active' : ''; ?>">Shortcode parameters</a>
        <!-- <a href="?page=membro-sportlink-api-menu&tab=example-shortcodes" class="nav-tab <?php // echo $active_tab == 'example-shortcodes' ? 'nav-tab-active' : ''; 
                                                                                              ?>">Voorbeeld shortcodes</a> -->
      <?php
      }
      ?>
    </h2>

    <form method="post" action="options.php">
      <?php
      if ($active_tab == 'settings') {
        settings_fields('membro-sportlink-api-settings-group');
        do_settings_sections('membro-sportlink-api-settings-group');
        if (!$connected_to_api) {
      ?>
          <h2>Geen shortcodes</h2>
          <p>Het lijkt erop dat u niet bent verbonden met de API. Er zijn daarom geen beschikbare shortcodes.</p>
        <?php
        }
        ?>
        <h2>Voor meer uitleg over de verschillende shortcodes, raadpleeg onze <a href="https://membro.nl/uitleg/sportlink-gegevens-op-je-wordpress-website">handleiding</a></h2>

        <table class="form-table">
          <tr valign="top">
            <th>API status</th>
            <td>
              <div class="api-status">
                <?php
                if ($api_status) {
                  echo "<div class='api-status' id='api-status-green'></div>";
                } else {
                  echo "<div class='api-status' id='api-status-red'></div>";
                }
                ?>
              </div>
            </td>
          </tr>

          <tr valign="top">
            <th>Verbonden met API</th>
            <td>
              <div class="api-status">
                <?php
                if ($connected_to_api) {
                  echo "<div class='api-status' id='api-status-green'></div>";
                } else {
                  echo "<div class='api-status' id='api-status-red'></div>";
                }
                ?>
              </div>
            </td>
          </tr>

          <tr valign="top">
            <th scope="row">Clubnaam</th>
            <td>
              <?php $membro_sportlink_api_clubname_set = esc_attr(get_option('membro_sportlink_api_clubname')); ?>
              <input type="text" name="membro_sportlink_api_clubname" value="<?php echo (!empty($membro_sportlink_api_clubname_set) ? esc_attr(get_option('membro_sportlink_api_clubname')) : $client->clubName); ?>" />
            </td>
          </tr>

          <tr valign="top">
            <th scope="row">API sleutel</th>
            <td>
              <input type="text" name="membro_sportlink_api_key" value="<?php echo esc_attr(get_option('membro_sportlink_api_key')); ?>" />
            </td>
          </tr>

          <tr valign="top">
            <th scope="row">Pathnaam</th>
            <td>
              <input type="text" name="membro_sportlink_api_pathname" value="<?php echo esc_attr(get_option('membro_sportlink_api_pathname')); ?>" />
            </td>
          </tr>

          <tr valign="top">
            <th scope="row">Cache (in minuten)</th>
            <td>
              <input type="text" name="membro_sportlink_api_cachetime" value="<?php echo esc_attr(get_option('membro_sportlink_api_cachetime')); ?>" />
            </td>
          </tr>
        </table>
        <?php
        submit_button();
        ?>
    </form>

    <?php if ($api_status && $connected_to_api) { ?>

      <form action="" method="post">
        <div class="wrap">
          <input class="hidden" type="text" name="clear_cache" value="clear-cache" disabled="disabled">
          <input type="submit" name="clear_cache" value="Cache legen" class="button button-primary" />
        </div>
      </form>

  <?php
          $empty_cache = isset($_POST['clear_cache']) ? true : false;
          if ($empty_cache) {
            $dt = new DateTime('now');
            $dt->setTimezone(new DateTimeZone('Europe/Amsterdam'));
            echo '<p><em>Cache geleegd op ' . $dt->format('d-m-Y \o\m H:i:s T') . '</em></p>';
            echo membro_sportlink_api_clear_cache($cache_folder);
          }
        }
      } elseif ($active_tab == 'general-shortcodes' && $api_status && $connected_to_api) {
        $template = trim($client->getData('/settings_screen_general_shortcodes', 'settings_screen', NULL, true));
        echo $template;
      } elseif ($active_tab == 'team-shortcodes' && $api_status && $connected_to_api) {
        $template = trim($client->getData('/settings_screen_team_shortcodes', 'settings_screen'));
        echo $template;
      } elseif ($active_tab == 'match-shortcodes' && $api_status && $connected_to_api) {
        $template = trim($client->getData('/settings_screen_match_shortcodes', 'settings_screen'));
        echo $template;
      } elseif ($active_tab == 'parameter-shortcodes' && $api_status && $connected_to_api) {
        $template = trim($client->getData('/settings_screen_parameter_shortcodes', 'settings_screen'));
        echo $template;
      } elseif ($active_tab == 'example-shortcodes' && $api_status && $connected_to_api) {
        $template = trim($client->getData('/settings_screen_example_shortcodes', 'settings_screen'));
        echo $template;
      }
  ?>
  </div>
<?php
}

/***********************************************************************
 Leeg de cache map
 */
function membro_sportlink_api_clear_cache($cache_folder) {
  $result = '';
  // emtpy cache folder
  $files = glob($cache_folder . '/*.rtpl.php'); // get all file names
  foreach ($files as $file) { // iterate files
    if (is_file($file)) {
      unlink($file); // delete file
      $result .= '<p>Cache removed: <code>' . $file . '</code></p>';
    }
  }
  return $result;
}

/***********************************************************************
 Registreerd de API-settings
 */
function membro_sportlink_api_register_settings() { // whitelist options
  register_setting('membro-sportlink-api-settings-group', 'membro_sportlink_api_key');
  register_setting('membro-sportlink-api-settings-group', 'membro_sportlink_api_pathname');
  register_setting('membro-sportlink-api-settings-group', 'membro_sportlink_api_clubname');
  register_setting('membro-sportlink-api-settings-group', 'membro_sportlink_api_cachetime');
}

?>