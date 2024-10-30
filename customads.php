<?php
    /**
    * Plugin Name:  Custom-Ads
    * Plugin URI: http://agileinfoways.com
    * Description: This Plugin will Display Custom Ads 
    * Version: 1.0.0
    * Author: Agile Infoways
    * Author URI: http://agileinfoways.com
    * License: GPL2
    */
?>
<?php
    //This is table names which are used in Plugin
    global $table_customadsdetails;
    global $wpdb;

    $table_customadsdetails = $wpdb->prefix . "customads";

    //This are the common files which are included for Global Use
    include('common.php');
    include('function.php');

    //This are Hooks which are called when plugin is loaded
    add_action('admin_menu', 'customads_menu');   
    add_action('admin_enqueue_scripts', 'customads_adminscripts');
    register_activation_hook( __FILE__, 'customads_install' );
    register_deactivation_hook( __FILE__, 'customads_uninstall' );
    add_shortcode('Customads', 'Customads');
    add_filter('widget_text', 'do_shortcode');

?>