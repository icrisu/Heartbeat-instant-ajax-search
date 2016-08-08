<?php

/**
* settings model
*/
class HBSettingsModel
{
	public $maxResults = 5;
	public $customCSS = '';
	public $isNativeIntegration = 'false';
	public $nativeSelector = '';

	private static $metaKey = 'heartbeat_settings_model_meta';

	function __construct()
	{
		$this->getData();
	}

	private function getData() {
		$data = get_option(self::$metaKey, array());
		$this->maxResults = isset($data['maxResults']) ? $data['maxResults'] : $this->maxResults;
		$this->customCSS = isset($data['customCSS']) ? $data['customCSS'] : $this->customCSS;
		$this->isNativeIntegration = isset($data['isNativeIntegration']) ? $data['isNativeIntegration'] : $this->isNativeIntegration;
		$this->nativeSelector = isset($data['nativeSelector']) ? $data['nativeSelector'] : $this->nativeSelector;
		return $data;
	}

	public function saveData($data) {
		update_option(self::$metaKey, array(
			'maxResults' => isset($data['maxResults']) ? $data['maxResults'] : $this->maxResults,
			'customCSS' => isset($data['customCSS']) ? $data['customCSS'] : $this->customCSS,
			'isNativeIntegration' => isset($data['isNativeIntegration']) ? $data['isNativeIntegration'] : $this->isNativeIntegration,
			'nativeSelector' => isset($data['nativeSelector']) ? $data['nativeSelector'] : $this->nativeSelector,
		));
	}

	public function destroy() {
		try {
			delete_option(self::$metaKey);
		} catch (Exception $e) {}
	}
}
?>