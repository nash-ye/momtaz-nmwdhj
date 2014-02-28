<?php
namespace Nmwdhj\Elements;

/**
 * The Checkboxes element class.
 *
 * @since 1.0
 */
class Checkboxes extends Input {

	/*** Properties ***********************************************************/

	/**
	 * Default element key.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $key = 'checkboxes';


	/*** Magic Methods ********************************************************/

	/**
	 * The Checkboxes element constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $key = '', array $properties = NULL ) {

		// Set the type attribute.
		if ( ! $this->has_attr( 'type' ) ) {
			$this->set_attr( 'type', 'checkbox' );
		}

		parent::__construct( $key, $properties );

		if ( is_array( $properties ) && isset( $properties['value_options'] ) ) {
			$this->set_value_options( $properties['value_options'] );
		}

	}


	/*** Methods **************************************************************/

	// Value Options

	/**
	 * Get the values and labels for the value options.
	 *
	 * @return array
	 * @since 1.0
	 */
	public function get_value_options() {
		return $this->value_options;
	}

	/**
	 * Ser the values and labels for the value options.
	 *
	 * @return Nmwdhj\Elements\Checkboxes
	 * @since 1.0
	 */
	public function set_value_options( array $options, $append = false ) {

		if ( $append ) {
			$options = array_merge( (array) $this->value_options, $options );
		}

		$this->value_options = $options;
		return $this;

	}

	/**
	 * Remove all/specified value options.
	 *
	 * @return Nmwdhj\Elements\Checkboxes
	 * @since 1.0
	 */
	public function remove_value_options( $options = NULL ) {

		if ( is_null( $options ) ) {

			$this->set_value_options( array() );

		} else {

			foreach( (array) $options as $option ) {
				$this->remove_value_option( $option );
			}

		}

		return $this;

	}

	/**
	 * Remove a specified value option.
	 *
	 * @return Nmwdhj\Elements\Checkboxes
	 * @since 1.0
	 */
	public function remove_value_option( $option ) {
		unset( $this->value_options[ $option ] );
		return $this;
	}

	/**
	 * Get the element output.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function get_output() {
		$view = new \Nmwdhj\Views\Checkboxes();
		return $view( $this );
	}

}