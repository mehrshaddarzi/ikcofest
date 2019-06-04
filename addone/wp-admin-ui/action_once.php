<?php
if (!function_exists('add_action_once'))
{
	
	function add_action_once( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		global $_gambitFiltersRan;

		if ( ! isset( $_gambitFiltersRan ) ) {
			$_gambitFiltersRan = array();
		}

		// Since references to $this produces a unique id, just use the class for identification purposes
		$idxFunc = $function_to_add;
		if ( is_array( $function_to_add ) ) {
			$idxFunc[0] = get_class( $function_to_add[0] );
		}
		$idx = _wp_filter_build_unique_id( $tag, $idxFunc, $priority );

		if ( ! in_array( $idx, $_gambitFiltersRan ) ) {
			add_filter( $tag, $function_to_add, $priority, $accepted_args );
		}

		$_gambitFiltersRan[] = $idx;

		return true;
	}

}