<?php
require_once(dirname(__FILE__) . '/../db/heartbeat-db.php');
/**
* create index db model
*/
class IndexModel
{
	public $result;
	public $timestamp;
	public $formatedDate;
	public $hash;

	private static $optionMeta = 'heartbeat_index_model_meta';
	private static $optionMetaInfo = 'heartbeat_index_model_meta_info';

	function __construct() {
		$this->result = array();
	}

	/**
	 * saveRawData
	 * @return [IndexModel]
	 */
	public function saveRawData($rawData) {
		$this->beforeSaveRawData($rawData);

		$this->hash = uniqid('_heart_beat_index');
		$this->timestamp = time();
		$this->formatedDate = date('l jS \of F Y h:i:s A', time());

		return;
		if (!empty($rawData)) {
			update_option(self::$optionMeta, array(
				'timestamp' => $this->timestamp,
				'hash' => $this->hash,
				'result' => $this->result,
				'formatedDate' => $this->formatedDate
			));
			update_option(self::$optionMetaInfo, array(
				'timestamp' => $this->timestamp,
				'hash' => $this->hash,
				'formatedDate' => $this->formatedDate
			));

		} else {
			update_option(self::$optionMeta, $this->buildEmptyObject());
			update_option(self::$optionMetaInfo, $this->buildEmptyObject());
		}
		return $this;
	}

	/**
	 * populate model
	 * @return [IndexModel] [return IndexModel all data]
	 */
	public function get() {
		//date('l jS \of F Y h:i:s A', time());
		$data = get_option(self::$optionMeta, $this->buildEmptyObject());
		return $data;
	}

	/**
	 * [getHash return only hash & date formated]
	 * @return [IndexModel] 
	 */
	public function getMeta() {
		$data = get_option(self::$optionMetaInfo, $this->buildEmptyObject());
		$this->timestamp = $data['timestamp'];
		$this->formatedDate = $data['formatedDate'];
		return $data;
	}

	public function findAll($args) {
		return HeartBeatDBInterface::findAll($args);
	}

	/**
	 * [beforeSaveRawData process raw data before save]
	 * @param  [array] $rawData [description]
	 * @return [null]          [description]
	 */
	protected function beforeSaveRawData($rawData) {
		$this->result = array();
		if (empty($rawData)) {
			return;
		}		
		foreach ($rawData as $dataEntry) {
			$dataEntry['i'] = wp_get_attachment_image_url($dataEntry['id']);
			$dataEntry['t'] = get_the_title($dataEntry['id']);
			$dataEntry['l'] = esc_url(get_permalink($dataEntry['id']));
			unset($dataEntry['id']);
			array_push($this->result, $dataEntry);		
		}	
		return $this;
	}

	//TBD
	protected function afterSave() {}

	/**
	 * build empty objec
	 * @return array
	 */
	private function buildEmptyObject() {
		return array(
			'timestamp' => 'no_time',
			'hash' => 'no_hash',
			'result' => array()
		);
	}

}
?>