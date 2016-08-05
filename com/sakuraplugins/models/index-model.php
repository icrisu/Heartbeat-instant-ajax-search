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
	public $size;

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
		$data = get_option(self::$optionMeta, $this->buildEmptyObject());
		if (isset($data['hash'])) {
			$this->hash = $data['hash'];
		}
		if (isset($data['result'])) {
			$this->result = $data['result'];
		}		
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
		$this->hash = $data['hash'];
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
			$thumbUrl = wp_get_attachment_image_url(get_post_thumbnail_id($dataEntry['id']));
			if ($thumbUrl && !empty($thumbUrl)) {
				$dataEntry['i'] = $thumbUrl;	
			}

			$curratedTags = array();
			$tags = wp_get_post_tags($dataEntry['id']);
			if ($tags && !empty($tags) && is_array($tags)) {
				foreach ($tags as $tag) {
					array_push($curratedTags, $tag->name);
				}				
			}

			if (sizeof($curratedTags) != 0) {
				$dataEntry['tg'] = $curratedTags;
			}
			$dataEntry['t'] = get_the_title($dataEntry['id']);
			$dataEntry['l'] = esc_url(get_permalink($dataEntry['id']));
			unset($dataEntry['id']);
			array_push($this->result, $dataEntry);		
		}

		$serializedResult = serialize($this->result);
		if (function_exists('mb_strlen')) {
		    $this->size = mb_strlen($serializedResult, '8bit');
		} else {
		    $this->size = strlen($serializedResult);
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