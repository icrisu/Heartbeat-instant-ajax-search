<?php
require_once(dirname(__FILE__) . '/../models/index-model.php');
require_once(dirname(__FILE__) . '/../models/search-terms-model.php');
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
			'heartbeat_save_post_types' => array($this, 'heartbeat_save_post_types'),
			'heartbeat_get_post_types' => array($this, 'heartbeat_get_post_types'),
			'heartbeat_update_index_terms' => array($this, 'heartbeat_update_index_terms'),
			'heartbeat_front_get_meta' => array($this, 'heartbeat_front_get_meta'),
			'heartbeat_front_get_index_data' => array($this, 'heartbeat_front_get_index_data')
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

		$hbSearchTermsModel = new HBSearchTermsModel();
		$terms = $hbSearchTermsModel->getTerms()->processTerms();

		if (empty($terms)) {
			echo json_encode(array('status' => 'FAIL', 'msg' => 'Please choose at least one post type to index!'));
			die();
		}		

		$m->saveRawData($m->findAll(array(
			'post_type' => $terms,
			'posts_per_page' => -1
		)));
		echo json_encode(array('status' => 'OK', 'data' => array(
			'size' => $m->size,
			'timestamp' => $m->timestamp
		)));
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

	/**
	 * get available post types and selected terms
	 * @return HBSearchTermsModel 
	 */
	public function heartbeat_get_post_types() {
		if (!is_admin()) {
			echo json_encode(array('status' => 'FAIL', 'msg' => 'Something went wrong, only admin can save this data!'));
			die();
		}
		$m = new HBSearchTermsModel();
		echo json_encode(array('status' => 'OK', 'data' => $m));
		die();
	}

	/**
	 * udapte index terms
	 * @return [type] [description]
	 */
	public function heartbeat_update_index_terms() {
		if (!is_admin()) {
			echo json_encode(array('status' => 'FAIL', 'msg' => 'Something went wrong, only admin can save this data!'));
			die();
		}
		$terms = isset($_POST['terms']) ? $_POST['terms'] : [];

		$m = new HBSearchTermsModel();
		$m->saveTerms($terms);
		echo json_encode(array('status' => 'OK', 'data' => $m));
		die();
	}

	//frontend
	/**
	 * get index meta
	 * @return array
	 */
	public function heartbeat_front_get_meta() {
		$m = new IndexModel();
		$m->getMeta();
		echo json_encode(array('status' => 'OK', 'data' => array('hash' => $m->hash)));
		die();
	}

	/**
	 * get reults
	 * @return array
	 */
	public function heartbeat_front_get_index_data() {
		$m = new IndexModel();
		$m->get();
		echo json_encode(array('status' => 'OK', 'data' => array('hash' => $m->hash, 'indexes' => $m->result)));
		die();
	}
}
?>