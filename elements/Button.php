<?php
namespace Nmwdhj\Elements;

/**
 * The Button element class.
 *
 * @since 1.0
 */
class Button extends Element {

	/**
	 * Button content.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $content;

	/**
	 * Default element key.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $key = 'button';


	/*** Magic Methods ********************************************************/

	/**
	 * The Input element constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $key = '', array $properties = NULL ) {

		if ( $key && ! $this->has_attr( 'type' ) ) {

			switch( $key ) {

				case 'button';
					$this->set_attr( 'type', 'button' );
					break;

				case 'button_submit';
					$this->set_attr( 'type', 'submit' );
					break;

				case 'button_reset';
					$this->set_attr( 'type', 'reset' );
					break;

			}

		}

		parent::__construct( $key, $properties );

	}


	/*** Methods **************************************************************/

	/**
	 * Set the button content.
	 *
	 * @return Nmwdhj\Elements\Button
	 * @since 1.0
	 */
	public function set_content( $content ) {
		$this->content = $content;
		return $this;
	}

	/**
	 * Get the button content.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_content() {
		return $this->content;
	}

}