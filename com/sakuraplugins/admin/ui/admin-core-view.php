<?php
/**
* Main class for admin UI
*/
class HeartBeatCoreUI
{
	private $dataModel;
	/**
	 * @param [type] $dataModel [description]
	 */
	function __construct($dataModel = null)
	{
		$this->dataModel = $dataModel;
	}

	/**
	 * render admin ui
	 * @return CoreUI 
	 */
	public function render() {
		require_once(dirname(__FILE__) . '/admin-header-view.php');
		require_once(dirname(__FILE__) . '/admin-content-view.php');
		require_once(dirname(__FILE__) . '/admin-footer-view.php');
		return $this;
	}
}
?>