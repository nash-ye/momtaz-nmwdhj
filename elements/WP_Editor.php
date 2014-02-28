<?php
namespace Nmwdhj\Elements;

/**
 * The WP_Editor element class.
 *
 * @since 1.0
 */
class WP_Editor extends Element {

	/*** Properties ***********************************************************/

	/**
	 * Default element key.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $key = 'wp_editor';


	/*** Methods **************************************************************/

	/**
	 * Get the element output.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function get_output() {
		$view = new \Nmwdhj\Views\WP_Editor();
		return $view( $this );
	}

}