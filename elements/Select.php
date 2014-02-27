<?php
namespace Nmwdhj\Elements;

/**
 * The Select element class.
 *
 * @since 1.0
 */
class Select extends Element {

	/*** Properties ***********************************************************/

	/**
	 * Default element key.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $key = 'select';

	/**
	 * Default value options.
	 *
	 * @var array
	 * @since 1.0
	 */
	protected $value_options = array();


	/*** Magic Methods ********************************************************/

	/**
	 * The Select element constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $key = '', array $properties = NULL ) {

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
	 * @return Nmwdhj\Elements\Select
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
	 * @return Nmwdhj\Elements\Select
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
	 * @return Nmwdhj\Elements\Select
	 * @since 1.0
	 */
	public function remove_value_option( $option ) {
		unset( $this->value_options[ $option ] );
		return $this;
	}

}