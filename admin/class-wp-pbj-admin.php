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
	private $lpse;

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
		$this->lpse = $this->connLPSE(array('cek' => true));

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
		wp_localize_script( $this->plugin_name, 'pbj', array(
		    'api_key' => get_option( '_crb_pbj_api_key' )
		));

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

	public function generatePage($nama_page, $tahun_anggaran = 2021, $content = false, $update = false){
		$custom_post = get_page_by_title($nama_page, OBJECT, 'page');
		if(empty($content)){
			$content = '[monitor_pbj tahun_anggaran="'.$tahun_anggaran.'"]';
		}

		$_post = array(
			'post_title'	=> $nama_page,
			'post_content'	=> $content,
			'post_type'		=> 'page',
			'post_status'	=> 'private',
			'comment_status'	=> 'closed'
		);
		if (empty($custom_post) || empty($custom_post->ID)) {
			$id = wp_insert_post($_post);
			$_post['insert'] = 1;
			$_post['ID'] = $id;
			$custom_post = get_page_by_title($nama_page, OBJECT, 'page');
			update_post_meta($custom_post->ID, 'ast-breadcrumbs-content', 'disabled');
			update_post_meta($custom_post->ID, 'ast-featured-img', 'disabled');
			update_post_meta($custom_post->ID, 'ast-main-header-display', 'disabled');
			update_post_meta($custom_post->ID, 'footer-sml-layout', 'disabled');
			update_post_meta($custom_post->ID, 'site-content-layout', 'page-builder');
			update_post_meta($custom_post->ID, 'site-post-title', 'disabled');
			update_post_meta($custom_post->ID, 'site-sidebar-layout', 'no-sidebar');
			update_post_meta($custom_post->ID, 'theme-transparent-header-meta', 'disabled');
		}else if($update){
			$_post['ID'] = $custom_post->ID;
			wp_update_post( $_post );
			$_post['update'] = 1;
		}
		return $this->get_link_post($custom_post);
	}

	public function crb_attach_sipd_options(){
		if( !is_admin() ){
        	return;
        }
        $ket_lpse = '<span style="color: red; font-weight: bold;">Belum terkoneksi</span>';
        if(!empty($this->lpse)){
        	$ket_lpse = '<span style="color: green; font-weight: bold;">Sukses terkoneksi</span>';
        }

        $url_singkronisasi_lpse = $this->generatePage('Singkronisasi data LPSE', false, '[singkronisasi_data_lpse]');
        $url_dashboard_lpse = $this->generatePage('Dashboard LPSE', false, '[dashboard_lpse]');
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
	            	->set_html( 'Status koneksi database LPSE: '.$ket_lpse ),
	            Field::make( 'html', 'crb_pbj_singkron_lpse' )
	            	->set_html( '<a target="_blank" href="'.$url_singkronisasi_lpse.'">Halaman singkronisasi data LPSE oleh admin PPE.</a>' )
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

	function gen_user_wp($user = array()){
		global $wpdb;
		if(!empty($user)){
			$username = $user['loginname'];
			$email = '';
			if(empty($user['emailteks'])){
				$email = $username.'@pbj.com';
			}else{
				$email = $user['emailteks'];
			}
			$role = get_role($user['role']);
			if(empty($role)){
				$kewenangan = array( 
					'read' => true,
					'edit_posts' => false,
					'delete_posts' => false
				);
				if($user['role'] == 'pbj-ppe'){
					$kewenangan = get_role( 'editor' )->capabilities;
					$kewenangan['edit_private_pages'] = false;
					$kewenangan['edit_private_post'] = false;
					$kewenangan['delete_private_posts'] = false;
					$kewenangan['delete_private_pages'] = false;
					$kewenangan['read_private_posts'] = false;
					$kewenangan['read_private_pages'] = false;
					$kewenangan['edit_theme_options'] = true;
					$kewenangan['list_users'] = true;
					$kewenangan['edit_users'] = true;
				}
				add_role( $user['role'], $user['role'], $kewenangan );
			}

			$user_id = username_exists($username);
			$option_user = array(
				'user_login' => $username,
				'user_pass' => $user['pass'],
				'user_email' => $email,
				'first_name' => $user['nama'],
				'display_name' => $user['nama'],
				'role' => $user['role']
			);
			if(!$user_id){
				$user_id = wp_insert_user($option_user);
			}else{
				$option_user['ID'] = $user_id;
				wp_update_user($option_user);
			}

			foreach ($user['meta'] as $key => $value) {
		      	update_user_meta( $user_id, $key, $value ); 
			}
		}
	}

	public function pbj_singkron_user(){
		global $wpdb;
		$ret = array(
			'status'	=> 'success',
			'message'	=> 'Berhasil singkronisasi user!'
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_pbj_api_key' )) {
				$type_aksi = $_POST['type_aksi'];
				if($type_aksi == 'pbj_singkron_user_ppe'){
					$sth = $this->lpse->prepare("
						SELECT
							e.*
						FROM public.pegawai e
							inner join public.usergroup g ON g.userid=e.peg_namauser
						WHERE e.peg_isactive=-1
							AND g.idgroup='ADM_PPE'
					");
					$role = 'pbj-ppe';
				}else if($type_aksi == 'pbj_singkron_user_kupbj'){
					$sth = $this->lpse->prepare("
						SELECT
							e.*
						FROM public.pegawai e
							inner join public.usergroup g ON g.userid=e.peg_namauser
						WHERE e.peg_isactive=-1
							AND g.idgroup='KUPPBJ'
					");
					$role = 'pbj-kupbj';
				}else if($type_aksi == 'pbj_singkron_user_ppk'){
					$sth = $this->lpse->prepare("
						SELECT
							p.*,
							e.*
						FROM public.ppk p
							inner join public.pegawai e ON p.peg_id=e.peg_id
							inner join public.usergroup g ON g.userid=e.peg_namauser
						WHERE e.peg_isactive=-1
							AND g.idgroup='PPK'
					");
					$role = 'pbj-ppk';
				}else if($type_aksi == 'pbj_singkron_user_pokja'){
					$sth = $this->lpse->prepare("
						SELECT
							e.*
						FROM public.pegawai e
							inner join public.usergroup g ON g.userid=e.peg_namauser
						WHERE e.peg_isactive=-1
							AND g.idgroup='PANITIA'
					");
					// $sth_anggota_panitia = $this->lpse->prepare("
					// 	SELECT
					// 		p.*,
					// 		a.*,
					// 		e.*
					// 	FROM public.anggota_panitia a
					// 		inner join public.panitia p ON a.pnt_id=p.pnt_id
					// 		inner join public.pegawai e ON a.peg_id=e.peg_id
					// 		inner join public.usergroup g ON g.userid=e.peg_namauser
					// 	WHERE e.peg_isactive=-1
					// 		AND g.idgroup='PANITIA'
					// ");
					$role = 'pbj-pokja';
				}else{
					$ret['status'] = 'error';
					$ret['message'] = $type_aksi.' tidak ditemukan!';
				}
				if($ret['status'] != 'error'){
					$pass = $_POST['pass'];
					$sth->execute();
					$users = $sth->fetchAll(PDO::FETCH_ASSOC);
					$jml = 0;
					foreach ($users as $userdb) {
						$jml++;
						$user = array();
						$user['pass'] = $pass;
						$user['loginname'] = $userdb['peg_namauser'];
						$user['emailteks'] = $userdb['peg_email'];
						$user['role'] = $role;
						$user['nama'] = $user['peg_nama'];
						$user['meta'] = $userdb;
						$this->gen_user_wp($user);
					}
					$ret['message'] = 'Berhasil singkronisasi '.$jml.' user '.$role.'!';
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'APIKEY tidak sesuai!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format Salah!';
		}
		die(json_encode($ret));
	}

	public function singkronisasi_data_lpse(){
		// untuk disable render shortcode di halaman edit page/post
		if(!empty($_GET) && !empty($_GET['post'])){
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wp-pbj-admin-display.php';
	}

	public function dashboard_lpse(){
		if(!empty($_GET) && !empty($_GET['post'])){
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wp-pbj-dashboard-lpse.php';
	}

	function gen_key($key_db = false, $options = array()){
		$now = time()*1000;
		if(empty($key_db)){
			$key_db = md5(get_option( '_crb_pbj_api_key' ));
		}
		$tambahan_url = '';
		if(!empty($options['custom_url'])){
			$custom_url = array();
			foreach ($options['custom_url'] as $k => $v) {
				$custom_url[] = $v['key'].'='.$v['value'];
			}
			$tambahan_url = $key_db.implode('&', $custom_url);
		}
		$key = base64_encode($now.$key_db.$now.$tambahan_url);
		return $key;
	}

	public function get_link_post($custom_post){
		$link = get_permalink($custom_post);
		$options = array();
		if(!empty($custom_post->custom_url)){
			$options['custom_url'] = $custom_post->custom_url;
		}
		if(strpos($link, '?') === false){
			$link .= '?key=' . $this->gen_key(false, $options);
		}else{
			$link .= '&key=' . $this->gen_key(false, $options);
		}
		return $link;
	}

    public function allow_access_private_post(){
    	if(
    		!empty($_GET) 
    		&& !empty($_GET['key'])
    	){
    		$key = base64_decode($_GET['key']);
    		$key_db = md5(get_option( '_crb_pbj_api_key' ));
    		$key = explode($key_db, $key);
    		$valid = 0;
    		if(
    			!empty($key[1]) 
    			&& $key[0] == $key[1]
    			&& is_numeric($key[1])
    		){
    			$tgl1 = new DateTime();
    			$date = substr($key[1], 0, strlen($key[1])-3);
    			$tgl2 = new DateTime(date('Y-m-d', $date));
    			$valid = $tgl2->diff($tgl1)->days+1;
    		}
    		if($valid == 1){
	    		global $wp_query;
		        // print_r($wp_query);
		        // print_r($wp_query->queried_object); die('tes');
		        if(!empty($wp_query->queried_object)){
		    		if($wp_query->queried_object->post_status == 'private'){
						wp_update_post(array(
					        'ID'    =>  $wp_query->queried_object->ID,
					        'post_status'   =>  'publish'
				        ));
				        die('<script>window.location =  window.location.href;</script>');
					}else{
						wp_update_post(array(
					        'ID'    =>  $wp_query->queried_object->ID,
					        'post_status'   =>  'private'
				        ));
					}
				}else if($wp_query->found_posts >= 1){
					global $wpdb;
					$sql = $wp_query->request;
					$post = $wpdb->get_results($sql, ARRAY_A);
					if(!empty($post)){
						if($post[0]['post_status'] == 'private'){
							wp_update_post(array(
						        'ID'    =>  $post[0]['ID'],
						        'post_status'   =>  'publish'
					        ));
					        die('<script>window.location =  window.location.href;</script>');
						}else{
							wp_update_post(array(
						        'ID'    =>  $post[0]['ID'],
						        'post_status'   =>  'private'
					        ));
						}
					}
				}
			}
    	}
    }

    public function pbj_detail_pegawai(){
		global $wpdb;
		$user_id = um_user( 'ID' );
		$user = get_userdata($user_id);
		if(empty($user->roles)){
			echo 'User ini tidak dapat akses sama sekali :)';
		}else if(
			in_array("pbj-ppe", $user->roles)
			|| in_array("pbj-kupbj", $user->roles)
			|| in_array("pbj-ppk", $user->roles)
			|| in_array("pbj-pokja", $user->roles)
		){
			$sth = $this->lpse->prepare("
				SELECT
					e.*
				FROM public.pegawai e
				WHERE e.peg_namauser='".$user->data->user_login."'
			");
			$sth->execute();
			$users = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($users as $userdb) {
				$detail = array();
				foreach ($userdb as $key => $value) {
					if($key == 'passw'){
						continue;
					}
					$detail[] = $key.'= '.$value;
				}
				echo implode('<br>', $detail);
			}
		}else{
			echo 'User ini tidak dapat akses halaman ini :)';
		}
    }
}
