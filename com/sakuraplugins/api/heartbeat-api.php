<?php
require_once(dirname(__FILE__) . '/../models/index-model.php');

/**
 * API
 */
class HeartBeatAPI
{
	private $apiActions;
	protected static $instance;

	protected function __construct()
	{
		$this->apiActions = array(
			'heartbeat_create_index' => array($this, 'heartbeat_create_index'),
			'heartbeat_get_index_meta' => array($this, 'heartbeat_get_index_meta'),
			'heartbeat_save_post_types' => array($this, 'heartbeat_save_post_types')
		);
	}

	/**
	 * [getInstance]
	 * @return [HeartBeatAPI]
	 */
	public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
	}

	/* prevent clone */
	private function __clone() {}

	/**
	 * [getApiActions return available Ajax actions]
	 * @return [array]
	 */
	public function getApiActions() {
		return $this->apiActions;
	}

	/**
	 * heartbeat_create_index create new DB index
	 * @return array
	 */
	public function heartbeat_create_index() {
		if (!is_admin()) {
			echo json_encode(array('status' => 'FAIL', 'msg' => 'Something went wrong, only admin can save this data!'));
			die();
		}
		$m = new IndexModel();
		$m->saveRawData($m->findAll(array(
			'post_type' => array('post', 'page')
		)));
		echo json_encode(array('status' => 'OK', 'data' => $m));
		die();
	}

	/**
	 * get index meta data
	 * @return array ( contains: timestamp, formated date )
	 */
	public function heartbeat_get_index_meta() {
		if (!is_admin()) {
			echo json_encode(array('status' => 'FAIL', 'msg' => 'Something went wrong, only admin can access this data!'));
			die();
		}
		$m = new IndexModel();
		$m->getMeta();
		echo json_encode(array('status' => 'OK', 'data' => $m));
		die();		
	}


	public function heartbeat_save_post_types() {
		if (!is_admin()) {
			echo json_encode(array('status' => 'FAIL', 'msg' => 'Something went wrong, only admin can save this data!'));
			die();
		}
		echo json_encode(array('status' => 'OK'));
		die();
	}	
}
?>