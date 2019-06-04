<?php

return blockCategoriesDeletionPlugin::bootstrap(array(CAT_NOT_DELETE)); // pass IDs of categories to block as array

class blockCategoriesDeletionPlugin {
	/**
	 * @var blockCategoriesDeletionPlugin
	 */
	static $instance;
	private $categoryIDs = array();
	public static function bootstrap(array $categoryIDs) {
		if (null===self::$instance) {
			self::$instance = new self($categoryIDs);
		} else {
			throw new BadFunctionCallException(sprintf('Plugin %s already instantiated', __CLASS__));
		}
		return self::$instance;
	}
	private function isCategoryDeleteRequest() {
		$notAnCategoryDeleteRequest = 
			empty($_REQUEST['taxonomy'])
			|| empty($_REQUEST['action'])
			|| $_REQUEST['taxonomy'] !== 'category'
			|| !( $_REQUEST['action'] === 'delete' || $_REQUEST['action'] === 'delete-tag');

		$isCategoryDeleteRequest = !$notAnCategoryDeleteRequest;
		
		return $isCategoryDeleteRequest;
	}
	public function __construct(array $categoryIDs) {
		$this->categoryIDs = $categoryIDs;
		if ($this->isCategoryDeleteRequest()) {
			add_filter('check_admin_referer', array($this, 'check_referrer'), 10, 2);
			add_filter('check_ajax_referer', array($this, 'check_referrer'), 10, 2);
		}
	}
	private function blockCategoryID($categoryID) {
		return in_array($categoryID, $this->categoryIDs);
	}
	/**
	 * @-wp-hook check_admin_referer
	 * @-wp-hook check_ajax_referer
	 */
	public function check_referrer($action, $result) {
		if (!$this->isCategoryDeleteRequest()) {
			return;
		}
		$prefix = 'delete-tag_';
		if (strpos($action, $prefix) !== 0)
			return;
		$actionID = substr($action, strlen($prefix));
		$categoryID = max(0, (int) $actionID);
		if ($this->blockCategoryID($categoryID)) {
			wp_die(__('This category is blocked for deletion.'));
		}
	}
}
