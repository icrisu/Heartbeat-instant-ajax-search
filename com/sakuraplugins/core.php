<?php
require_once(dirname(__FILE__) . '/admin/ui/admin-core-view.php');
require_once(dirname(__FILE__) . '/api/heartbeat-api.php');

/**
* Base plugin class
*/
class HeartBeatCore {

	private $_optionPageSlug = 'heartbeatoptspage';
	const HEARTBEAT_OPTIONS_DATA = 'HEARTBEAT_OPTIONS_DATA';


	public function initializeHandler() {
		//$scm = new ShortcodesManager();
		//$scm->registerShortcodes();
	}

	//menu event
	public function admin_menu() {
		$coreView = new HeartBeatCoreUI();
		add_menu_page('HeartBeat', 'HeartBeat', 'manage_options', $this->_optionPageSlug, array($coreView, 'render'), HEARTBEAT_ADMIN_URI . '/img/admin-menu-icon.png');
	}

	//Enqueue scripts frontend
	public function wpEnqueueScriptsHandler() {

	}

	//admin scripts
	public function adminEnqueueScriptsHandler() {
		$current_screen = get_current_screen();
		$screenID = $current_screen->id;
	
		//heartbeat options page
		if ((substr($screenID, -strlen($this->_optionPageSlug)) === $this->_optionPageSlug)) {

			wp_enqueue_script('jquery');
			wp_enqueue_script('underscore', false, array('jquery'));
			wp_enqueue_script('backbone', false, array('underscore'));

			//materialize css
			wp_register_style('materialize_css', HEARTBEAT_ADMIN_URI . '/libs/materialize/css/materialize.min.css');
			wp_enqueue_style('materialize_css');			

			//admin style
			wp_register_style('heartbeat_admin_style', HEARTBEAT_ADMIN_URI . '/css/heartbeat-admin.css');
			wp_enqueue_style('heartbeat_admin_style');			

			//materialize js
			wp_register_script('materialize_js', HEARTBEAT_ADMIN_URI.'/libs/materialize/js/materialize.min.js', array('jquery'), FALSE, TRUE);
			wp_enqueue_script('materialize_js');

			//heartbeat_admin
			wp_register_script('heartbeat_admin_views', HEARTBEAT_ADMIN_URI.'/js/heartbeat-admin-views.js', array('backbone'), FALSE, TRUE);
			wp_enqueue_script('heartbeat_admin_views');

			//heartbeat_admin
			wp_register_script('heartbeat_admin', HEARTBEAT_ADMIN_URI.'/js/heartbeat-admin.js', array('backbone'), FALSE, TRUE);
			wp_enqueue_script('heartbeat_admin');									
		}

	}

	//admin bar custom
	public function adminBarCustom() {		
		$current_screen = get_current_screen();
		$screenID = $current_screen->id;		
		if (function_exists('get_current_screen')) {
			$current_screen = get_current_screen();		
			if ((substr($screenID, -strlen($this->_optionPageSlug)) === $this->_optionPageSlug)) {	
				require_once(dirname(__FILE__) . '/admin/ui/heartbeat-admin-header.php');
				HeartBeatHeader::render();
			}
		}
	}

	//single template
	public function sk_plugin_single($single_template) {
		global $post;
		if ($post->post_type == self::APPETIT_CPT_TYPE) {			
			$single_template = dirname( __FILE__ ) . '/single/appetit_cpt-template.php';										
		}
		return $single_template;
	}

	//init listeners
	public function run($opts=NULL) {
		add_action( 'init', array($this, 'initializeHandler' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array($this, 'adminEnqueueScriptsHandler' ) );
		add_action( 'wp_before_admin_bar_render', array($this, 'adminBarCustom' ) );
		//add_action(	'wp_enqueue_scripts', array($this, 'wpEnqueueScriptsHandler'));
		//add_action( 'wp_head', array($this, 'hookCustomCSS'));
		

		$apiActions = HeartBeatAPI::getInstance()->getApiActions();
		foreach ($apiActions as $key => $value) {
			add_action('wp_ajax_' . $key, array(HeartBeatAPI::getInstance(), $key));
		}
	}
}

?>