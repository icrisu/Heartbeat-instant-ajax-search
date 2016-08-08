<?php

/**
* settings model
*/
class HBSettingsModel
{
	public $maxResults = 5;
	public $customCSS = '';

	public static $metaKey = 'heartbeat_settings_model_meta';

	function __construct()
	{
		$data = get_option(self::$metaKey, array());
		$this->maxResults = isset($data['maxResults']) ? $data['maxResults'] : $this->maxResults;
		$this->customCSS = isset($data['customCSS']) ? $data['customCSS'] : $this->customCSS;
	}

	public function saveData($data) {
		update_option(self::$metaKey, array(
			'maxResults' => isset($data['maxResults']) ? $data['maxResults'] : $this->maxResults,
			'customCSS' => isset($data['customCSS']) ? $data['customCSS'] : $this->customCSS
		));
	}
}
?>