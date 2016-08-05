<?php


class HBSearchTermsModel
{
	public $terms;
	public $availableTerms = array();

	private static $termsMeta = 'heartbeat_terms_model_meta';

	function __construct() {
		$this->getTerms();
		$postTypes = get_post_types(array(), 'objects');
		foreach ($postTypes as $type) {
			array_push($this->availableTerms, array('key' => $type->name, 'label' => $type->labels->name));
		}
	}

	/**
	 * save terms
	 * @param  array
	 * @return array 
	 */
	public function saveTerms($terms) {
		$this->terms = $terms;	
		update_option(self::$termsMeta, $this->terms);
		return $this;
	}

	/**
	 * get terms
	 * @return HBSearchTermsModel
	 */
	public function getTerms() {
		$this->terms = get_option(self::$termsMeta, array());
		return $this;
	}

	public function processTerms() {
		$out = array();
		foreach ($this->terms as $term) {
			array_push($out, $term['key']);
		}
		return $out;
	}
}
?>