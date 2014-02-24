<?php
namespace Nmwdhj\Elements;

/**
 * The Checkbox element class.
 *
 * @since 1.0
 */
class Checkbox extends Element {

	/*** Properties ***********************************************************/

	/**
	 * Default element key.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $key = 'checkbox';


	/*** Magic Methods ********************************************************/

	/**
	 * The Checkbox element constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $key = '', array $properties = NULL ) {

		// Set the type attribute.
		if ( ! $this->has_attr( 'type' ) ) {
			$this->set_attr( 'type', 'checkbox' );
		}

		parent::__construct( $key, $properties );

	}


	/*** Methods **************************************************************/

	// Value

	/**
	 * Checks if this checkbox is checked.
	 *
	 * @return bool
	 * @since 1.0
	 */
	public function is_checked() {
		return (bool) $this->get_value();
	}

	/**
	 * Checks or unchecks the checkbox.
	 *
	 * @return Nmwdhj\Elements\Checkbox
	 * @since 1.0
	 */
	public function set_checked( $value ) {
		return $this->set_value( (bool) $value );
	}

	/**
	 * Set the element value.
	 *
	 * @return Nmwdhj\Elements\Checkbox
	 * @since 1.0
	 */
	public function set_value( $value ) {

		if ( ! is_bool( $value ) ) {
			$value = ( $value === $this->get_checked_value() );
		}

		return parent::set_value( $value );

	}

	// Checked Value

	/**
	 * Get the value to use when checkbox is checked
	 *
	 * @return scalar
	 * @since 1.0
	 */
	public function get_checked_value( $def = 1 ) {

		if ( ! is_scalar( $def ) ) {
			$def = 1;
		}

		return $this->get_option( 'checked_value', $def );

	}

	/**
	 * Set the value to use when checkbox is checked
	 *
	 * @return Nmwdhj\Elements\Checkbox
	 * @since 1.0
	 */
	public function set_checked_value( $value ) {

		if ( ! is_scalar( $value ) ) {
			return $this;
		}

		return $this->set_option( 'checked_value', $value );

	}

	// Unchecked Value

	/**
	 * Get the value to use when checkbox is unchecked.
	 *
	 * @return scalar
	 * @since 1.0
	 */
	public function get_unchecked_value( $def = 0 ) {

		if ( ! is_scalar( $def ) ) {
			$def = 0;
		}

		return $this->get_option( 'unchecked_value', $def );

	}

	/**
	 * Set the value to use when checkbox is unchecked.
	 *
	 * @return Nmwdhj\Elements\Checkbox
	 * @since 1.0
	 */
	public function set_unchecked_value( $value ) {

		if ( ! is_scalar( $value ) ) {
			return $this;
		}

		return $this->set_option( 'unchecked_value', $value );

	}

}