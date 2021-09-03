<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/agusnurwanto/
 * @since      1.0.0
 *
 * @package    Wp_Pbj
 * @subpackage Wp_Pbj/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Pbj
 * @subpackage Wp_Pbj/admin
 * @author     Agus Nurwanto <agusnurwantomuslim@gmail.com>
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Wp_Pbj_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Pbj_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Pbj_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-pbj-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Pbj_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Pbj_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-pbj-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function crb_attach_sipd_options(){
		if( !is_admin() ){
        	return;
        }
        $ket_lpse = '<span style="color: red; font-weight: bold;">Belum terkoneksi</span>';
        $cek_koneksi = $this->connLPSE(array('cek' => true));
        if(!empty($cek_koneksi)){
        	$ket_lpse = '<span style="color: green; font-weight: bold;">Sukses terkoneksi</span>';
        }
		$basic_options_container = Container::make( 'theme_options', __( 'PBJ Options' ) )
			->set_page_menu_position( 4 )
	        ->add_fields( array(
	            Field::make( 'text', 'crb_pbj_api_key', 'API Key' )
	            	->set_default_value($this->generateRandomString()),
	            Field::make( 'text', 'crb_pbj_tahun_anggaran', 'Tahun Anggaran' )
	            	->set_default_value('2021'),
	            Field::make( 'text', 'crb_pbj_lpse_host', 'IP atau host dari database backup LPSE' ),
	            Field::make( 'text', 'crb_pbj_lpse_host_port', 'Port Database' )
	            	->set_default_value('5432'),
	            Field::make( 'text', 'crb_pbj_lpse_dbname', 'Nama Database LPSE' )
	            	->set_help_text('Nama database backup dari aplikasi LPSE.'),
	            Field::make( 'text', 'crb_pbj_lpse_username', 'User Database' ),
	            Field::make( 'text', 'crb_pbj_lpse_password', 'Password Database' ),
	            Field::make( 'html', 'crb_pbj_status_lpse' )
	            	->set_html( 'Status koneksi database LPSE: '.$ket_lpse )
            ) );
	}

	public function connLPSE($options = array()){
		$host = get_option('_crb_pbj_lpse_host');
		$port = get_option('_crb_pbj_lpse_host_port');
		$db = get_option('_crb_pbj_lpse_dbname');
		$user = get_option('_crb_pbj_lpse_username');
		$password = get_option('_crb_pbj_lpse_password');
		try {
			$dsn = "pgsql:host=$host;port=$port;dbname=$db;";
			$pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
		} catch (PDOException $e) {
			if(!empty($options['debug'])){
				die($e->getMessage());
			}else{
				$pdo = false;
			}
		}
		return $pdo;
	}

	public function get_ajax_field($options = array('type' => 'pbj')){
		$ret = array();
		$hide_sidebar = Field::make( 'html', 'crb_hide_sidebar' )
        	->set_html( '
        		<style>
        			.postbox-container { display: none; }
        			#poststuff #post-body.columns-2 { margin: 0 !important; }
        		</style>
        		<div id="load_ajax_carbon" data-type="'.$options['type'].'"></div>
        	' );
		$ret[] = $hide_sidebar;
		return $ret;
	}

	public function load_ajax_carbon(){
		global $wpdb;
		$ret = array(
			'status'	=> 'success',
			'message'	=> ''
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_pbj_api_key' )) {

			}
		}
		die(json_encode($ret));
	}
}
