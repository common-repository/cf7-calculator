<?php
/**
*Plugin Name: Calculate Contact Form 7
*Description: This plugin allows create calculator for contact form 7.
* Version: 2.0
* Author: Ocean Infotech
* Author URI: https://www.xeeshop.com
* Copyright: 2019 
*/

if (!defined('ABSPATH')) {
  die('-1');
}
if (!defined('OCCF7CAL_PLUGIN_NAME')) {
  define('OCCF7CAL_PLUGIN_NAME', 'Calculate Contact Form 7');
}
if (!defined('OCCF7CAL_PLUGIN_VERSION')) {
  define('OCCF7CAL_PLUGIN_VERSION', '2.0.0');
}
if (!defined('OCCF7CAL_PLUGIN_FILE')) {
  define('OCCF7CAL_PLUGIN_FILE', __FILE__);
}
if (!defined('OCCF7CAL_PLUGIN_DIR')) {
  define('OCCF7CAL_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('OCCF7CAL_DOMAIN')) {
  define('OCCF7CAL_DOMAIN', 'occf7cal');
}

if (!class_exists('OCCF7CAL')) {

  class OCCF7CAL {

    protected static $OCCF7CAL_instance;

    //Load all includes files
    function includes() {
      include_once('admin/calculator.php');
      include_once('admin/rangeslider.php');
    }

    function init() {
      add_action( 'admin_init', array($this, 'OCCF7CAL_load_plugin'), 11 );
      add_action( 'wp_enqueue_scripts',  array($this, 'OCCF7CAL_load_script_style'));
    }

	  function OCCF7CAL_load_plugin() {
      if ( ! ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) ) {
        add_action( 'admin_notices', array($this,'OCCF7CAL_install_error') );
      }
    }

    function OCCF7CAL_install_error() {
      deactivate_plugins( plugin_basename( __FILE__ ) );
      ?>
        <div class="error">
          <p>
            <?php _e( ' cf7 calculator plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=contact+form+7">Contact Form 7</a> plugin installed and activated.', OCCF7CAL_DOMAIN ); ?>
          </p>
        </div>
      <?php
    }

    //Add JS and CSS on Frontend
    function OCCF7CAL_load_script_style() {
      wp_enqueue_script( 'OCCF7CAL-front-js', OCCF7CAL_PLUGIN_DIR . '/includes/js/front.js', false, '2.0.0' );
      wp_enqueue_style( 'OCCF7CAL-front-jquery-ui-css', OCCF7CAL_PLUGIN_DIR . '/includes/js/jquery-ui.css', false, '2.0.0' );
      wp_enqueue_script( 'OCCF7CAL-front-jquery-ui-js', OCCF7CAL_PLUGIN_DIR . '/includes/js/jquery-ui.js', false, '2.0.0' );
      wp_enqueue_script( 'OCCF7CAL-front-touch-pubnch-js', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', false, '2.0.0' );
      wp_enqueue_style( 'OCCF7CAL-front-css', OCCF7CAL_PLUGIN_DIR . '/includes/css/front-style.css', false, '2.0.0' );
    }

    //Plugin Rating
    public static function do_activation() {
      set_transient('ocinsta-first-rating', true, MONTH_IN_SECONDS);
    }

    public static function OCCF7CAL_instance() {
      if (!isset(self::$OCCF7CAL_instance)) {
        self::$OCCF7CAL_instance = new self();
        self::$OCCF7CAL_instance->init();
        self::$OCCF7CAL_instance->includes();
      }
      return self::$OCCF7CAL_instance;
    }

  }

  add_action('plugins_loaded', array('OCCF7CAL', 'OCCF7CAL_instance'));
  register_activation_hook(OCCF7CAL_PLUGIN_FILE, array('OCCF7CAL', 'do_activation'));
}
