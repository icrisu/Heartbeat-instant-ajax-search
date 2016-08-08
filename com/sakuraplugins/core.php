<?php
require_once(dirname(__FILE__) . '/views/ui/admin-core-view.php');
require_once(dirname(__FILE__) . '/api/heartbeat-api.php');
require_once(dirname(__FILE__) . '/utils/utils.php');
require_once(dirname(__FILE__) . '/shortcodes/hb-shortcodes.php');
require_once(dirname(__FILE__) . '/models/settings-model.php');

/**
* Base plugin class
*/
class HeartBeatCore {

	private $_optionPageSlug = 'heartbeatoptspage';
	const HEARTBEAT_OPTIONS_DATA = 'HEARTBEAT_OPTIONS_DATA';


	public function initializeHandler() {
		$scm = new HBShortcodeManager();
		$scm->registerShortcodes();
	}

	//menu event
	public function admin_menu() {
		$coreView = new HeartBeatCoreUI();
		add_menu_page('HeartBeat', 'HeartBeat', 'manage_options', $this->_optionPageSlug, array($coreView, 'render'), HEARTBEAT_ADMIN_URI . '/img/admin-menu-icon.png');
	}

	//Enqueue scripts frontend
	public function wpEnqueueScriptsHandler() {

			//admin style

		wp_enqueue_script('jquery');
		wp_enqueue_script('underscore', false, array('jquery'));
		wp_enqueue_script('jquery-ui-autocomplete', false, array('jquery'));

		wp_register_script('lunr_js', HEARTBEAT_FRONT_URI.'/js/lunr.min.js', array('underscore'), FALSE, TRUE);
		wp_enqueue_script('lunr_js');

		wp_register_script('resize_sensor', HEARTBEAT_FRONT_URI.'/libs/element-queries/ResizeSensor.js', array('jquery'), FALSE, TRUE);
		wp_enqueue_script('resize_sensor');

		wp_register_script('element_queries', HEARTBEAT_FRONT_URI.'/libs/element-queries/ElementQueries.js', array('jquery'), FALSE, TRUE);
		wp_enqueue_script('element_queries');

		wp_register_script('heart_beat_core_js', HEARTBEAT_FRONT_URI.'/js/heart-beat.js', array('lunr_js'), FALSE, TRUE);
		wp_enqueue_script('heart_beat_core_js');

		HBeatUtils::enqueFontsFrom(array(
			array('key'=>'hb-roboto', 'resource'=>'://fonts.googleapis.com/css?family=Roboto:400,300,500'),
		));

		$settingsModel = new HBSettingsModel();
		wp_localize_script('heart_beat_core_js', 'HeartBeatOptions', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'md_max_results' => (int)$settingsModel->maxResults,
			'isNativeIntegration' => $settingsModel->isNativeIntegration,
			'nativeSelector' => $settingsModel->nativeSelector
		));

		wp_register_style('heartbeat_icons', HEARTBEAT_FRONT_URI . '/css/fonts/style.css');
		wp_enqueue_style('heartbeat_icons');

		wp_register_style('heartbeat_style', HEARTBEAT_FRONT_URI . '/css/heartbeat.css');
		wp_enqueue_style('heartbeat_style');			
	}

	//add custom css
	public function hookCustomCSS() {
		$settingsModel = new HBSettingsModel();
		$output = '<style type="text/css">' . $settingsModel->customCSS . '</style>';
		echo $output;
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

			//heartbeat_admin models
			wp_register_script('heartbeat_admin_models', HEARTBEAT_ADMIN_URI.'/js/heartbeat-admin-models.js', array('backbone'), FALSE, TRUE);
			wp_enqueue_script('heartbeat_admin_models');

			//heartbeat_admin views
			wp_register_script('heartbeat_admin_views', HEARTBEAT_ADMIN_URI.'/js/heartbeat-admin-views.js', array('backbone'), FALSE, TRUE);
			wp_enqueue_script('heartbeat_admin_views');

			//heartbeat_admin
			wp_register_script('heartbeat_admin', HEARTBEAT_ADMIN_URI.'/js/heartbeat-admin.js', array('backbone'), FALSE, TRUE);
			wp_enqueue_script('heartbeat_admin');

			HBeatUtils::enqueFontsFrom(array(
				array('key'=>'material-icons', 'resource'=>'://fonts.googleapis.com/icon?family=Material+Icons'),
			));									
		}

	}

	//admin bar custom
	public function adminBarCustom() {						
		if (function_exists('get_current_screen')) {			
			$current_screen = get_current_screen();	
			$screenID = $current_screen->id;	
			if ((substr($screenID, -strlen($this->_optionPageSlug)) === $this->_optionPageSlug)) {	
				require_once(dirname(__FILE__) . '/views/ui/heartbeat-admin-header.php');
				HeartBeatHeader::render();
			}
		}
	}


	//init listeners
	public function run($opts=NULL) {
		add_action( 'init', array($this, 'initializeHandler' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array($this, 'adminEnqueueScriptsHandler' ) );
		add_action( 'wp_before_admin_bar_render', array($this, 'adminBarCustom' ) );
		add_action(	'wp_enqueue_scripts', array($this, 'wpEnqueueScriptsHandler'));
		add_action( 'wp_head', array($this, 'hookCustomCSS'));
		

		$apiActions = HeartBeatAPI::getInstance()->getApiActions();
		foreach ($apiActions as $key => $value) {
			add_action('wp_ajax_' . $key, array(HeartBeatAPI::getInstance(), $key));
		}

		add_action( 'wp_ajax_nopriv_heartbeat_front_get_meta', array(HeartBeatAPI::getInstance(), 'heartbeat_front_get_meta'));
		add_action( 'wp_ajax_nopriv_heartbeat_front_get_index_data', array(HeartBeatAPI::getInstance(), 'heartbeat_front_get_index_data'));	
	}
}

?>