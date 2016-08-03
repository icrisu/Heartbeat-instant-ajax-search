<?php
require_once(dirname(__FILE__) . '/../db/heartbeat-db.php');
/**
* create index db model
*/
class CreateIndexModel
{

	public $test = 'hello';
	public $result;
	
	function __construct()
	{
		$this->result = array();
	}

	public function findAll($args) {
		$this->result = HeartBeatDBInterface::findAll($args);
		return $this;
	}
}
?>