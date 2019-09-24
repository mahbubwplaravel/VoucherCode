<?php 
/**
 * Plugin Name:        Staff Asia Course Voucher Code
 * Plugin URI:         http://staffasia.org/vouchercode
 * Description:        It is to upload data from CSV to DB & Custom insert voucher list
 * Version:            1.00
 * Requires at least:  5.2
 * Requires PHP:       5.6
 * Author:            Mahbub Staff Asia
 * Author URI:       http://stafasia.org
 * License:           GPL v2 or later

 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function voucher_lists() {
	//this is the main item for the menu
	add_menu_page('Upload Voucher Code', //page title
	'All Voucher', //menu title
	'manage_options', //capabilities
	'voucher-options', //menu slug
	'all_voucher_list', //function
    'dashicons-admin-network',99
	);
	add_submenu_page( 'voucher-options', 'Add Voucher Code', 'Add Voucher', 'manage_options', 'Voucher', 'custome_voucher_form_data_add');
	//this is a submenu
	add_submenu_page('voucher-options', //parent slug
	'Upload Voucher', //page title
	'Upload CSV Voucher', //menu title
	'manage_options', //capability
	'upload_new', //menu slug
	'upload_new'); //function
}
add_action('admin_menu','voucher_lists');


// custom table cretae
if(!function_exists('ww_create_tables')) 
{
function ww_create_tables () 
{
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
  global $wpdb;
  
  $table_name = $wpdb->prefix.'voucher_details';	
   $sql_table = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(50) NOT NULL,
  `product_title` varchar(200) NOT NULL,
  `voucher_code` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
dbDelta($sql_table);
}
}


add_action('admin_init', 'ww_create_tables');

// Delete table
function delete_voucher_table() {
global $wpdb;
$table_name = $wpdb->prefix.'voucher_details'; 
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);
delete_option("my_plugin_db_version");
}
register_deactivation_hook( __FILE__, 'delete_voucher_table' );

 function voucher_plugin_scripts($hook) {
        $plugin_url = plugin_dir_url( __FILE__ );
        if($hook!='toplevel_page_voucher-options' && $hook!='all-voucher_page_Voucher' && $hook!='all-voucher_page_upload_new'  && $hook!='all-voucher_page_Voucher'){
          return;
        }
        wp_enqueue_style( 'sty-css-demo', $plugin_url . "/assets/css/bootstrap.min.css",'all');
        wp_enqueue_style( 'style-data-table',  $plugin_url . "/assets/css/datatables.min.css");
        wp_enqueue_script('bootstrap-js', $plugin_url . "/assets/js/datatables.min.js", array( 'jquery' ), false );
        wp_enqueue_script('js-data-table', $plugin_url . "/assets/js/bootstrap.min.js", array( 'jquery' ), false );
        wp_enqueue_script('js-data-table-active', $plugin_url . "assets/js/customtable.js", array( 'jquery' ), false );

    }

 add_action( 'admin_enqueue_scripts', 'voucher_plugin_scripts' );
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'voucher_plugin_home.php');
require_once(ROOTDIR . 'upload_new.php');
require_once(ROOTDIR . 'all_voucher.php');
