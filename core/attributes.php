<?php
namespace Nmwdhj\Attributes;

/**
 * The attributes class.
 *
 * @since 1.0
 */
class Attributes {

	/*** Properties ***********************************************************/

	/**
	 * Attributes list.
	 *
	 * @since 1.0
	 * @var Nmwdhj\Attributes\Attribute[]
	 */
	protected $attributes;


	/*** Magic Methods ********************************************************/

	/**
	 * The Attributes class constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $atts = null ) {

		// Reset the attributes.
		$this->reset_atts();

		// Set the attributes.
		$this->set_atts( $atts );

	}


	/*** Methods **************************************************************/

	// Getters

	/**
	 * Get all the attributes array.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Attributes\Attribute[]
	 */
	public function get_atts() {
		return $this->attributes;
	}

	/**
	 * Get an attribute object.
	 *
	 * @since 1.1
	 * @return Nmwdhj\Attributes\Attribute
	 */
	public function get_attr_obj( $key ) {

		$key = strtolower( $key );

		if ( isset( $this->attributes[ $key ] ) ) {
			return $this->attributes[ $key ];
		}

	}

	/**
	 * Get an attribute value.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_attr( $key, $def = '' ) {

		$obj = $this->get_attr_obj( $key );

		if ( ! $obj && is_scalar( $def ) ) {
			return (string) $def;
		}

		return $obj->get_value();

	}

	// Checks

	/**
	 * Check for an attribute existence.
	 *
	 * @since 1.0
	 * @return bool
	 */
	public function has_attr( $key ) {

		if ( $key instanceof Attribute ) {
			$key = $key->get_key();
		}

		if ( ! $this->get_attr_obj( $key ) ) {
			return false;
		}

		return true;

	}

	// Setters

	/**
	 * Set many attributes at once.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Attributes\Attributes
	 */
	public function set_atts( $atts, $override = true ) {

		if ( $atts instanceof Attributes ) {
			$atts = $atts->get_atts();
		}

		if ( is_array( $atts ) ) {

			foreach( $atts as $key => $value ) {
				$this->set_attr( $key, $value, $override );
			}

		}

		return $this;

	}

	/**
	 * Set an attribute value.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Attributes\Attributes
	 */
	public function set_attr( $key, $value, $override = true ) {

		$key = strtolower( $key );

		if ( $override || ! $this->has_attr( $key ) ) {
			$this->attributes[$key] = \Nmwdhj\create_attr_obj( $key, $value );
		}

		return $this;

	}

	// Remove

	/**
	 * Remove many attributes at once.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Attributes\Attributes
	 */
	public function remove_atts( $keys ) {

		if ( $keys instanceof Attributes ) {
			$keys = array_keys( $keys->get_atts() );
		}

		if ( is_array( $keys ) ) {

			foreach( $keys as $key ) {
				$this->remove_attr( $key );
			}

		}

		return $this;

	}

	/**
	 * Remove an attribute.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Attributes\Attributes
	 */
	public function remove_attr( $key ) {

		$key = strtolower( $key );

		if ( is_array( $this->attributes ) ) {
			unset( $this->attributes[ $key ] );
		}

		return $this;

	}

	// Reset

	/**
	 * Reset the attributes array.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Attributes\Attributes
	 */
	public function reset_atts() {
		$this->attributes = array();
		return $this;
	}

	// Converters

	/**
	 * Convert the attributes array to string.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function to_string( array $args = NULL ) {

		$output = '';
		$atts = $this->get_atts();

		if ( count( $atts ) === 0 ) {
			return $output;
		}

		$args = array_merge( array(
			'before' => ' ',
			'after' => '',
		), (array) $args );

		$atts = array_map( 'strval', $atts );
		$output = trim( implode( ' ', $atts ) );

		if ( empty( $output ) ) {
			return $output;
		}

		return $args['before'] . $output . $args['after'];

	}

	public function __toString() {
		return $this->to_string();
	}

}


/**
 * The attribute interface.
 *
 * @since 1.1
 */
interface Attribute {

	/**
	 * Get the attribute key.
	 *
	 * @since 1.1
	 * @return string
	 */
	public function get_key();

	/**
	 * Get the attribute value.
	 *
	 * @since 1.1
	 * @return string
	 */
	public function get_value();

	/**
	 * Get the attribute output.
	 *
	 * @since 1.1
	 * @return string
	 */
	public function __toString();

}

/**
 * The simple attribute class.
 *
 * @since 1.1
 */
class SimpleAttribute implements Attribute {

	/*** Properties ***********************************************************/

	/**
	 * The attribute key.
	 *
	 * @since 1.1
	 * @var string
	 */
	protected $key;

	/**
	 * The attribute value.
	 *
	 * @since 1.1
	 * @var mixed
	 */
	protected $value;


	/*** Magic Methods ********************************************************/

	/**
	 * The Attribute class constructor.
	 *
	 * @since 1.1
	 */
	public function __construct( $key, $value ) {

		// Set the attribute key.
		$this->set_key( $key );

		// Set the attribute value.
		$this->set_value( $value );

	}


	/*** Methods **************************************************************/

	// Getters

	/**
	 * Get the attribute key.
	 *
	 * @since 1.1
	 * @return string
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * Get the attribute value.
	 *
	 * @since 1.1
	 * @return string
	 */
	public function get_value() {
		return $this->value;
	}

	// Setters

	/**
	 * Set the attribute key.
	 *
	 * @since 1.1
	 * @return Nmwdhj\Attributes\SimpleAttribute
	 */
	protected function set_key( $key ) {
		$this->key = $key;
		return $this;
	}

	/**
	 * Set the attribute value.
	 *
	 * @since 1.1
	 * @return Nmwdhj\Attributes\SimpleAttribute
	 */
	public function set_value( $value ) {
		$this->value = $value;
		return $this;
	}

	/**
	 * Get the attribute output.
	 *
	 * @since 1.1
	 * @return string
	 */
	public function __toString(){

		$output = '';

		// Get the attribute key.
		$key = $this->get_key();

		// Get the attribute value.
		$value = $this->get_value();

		if ( ! empty( $key ) && $value !== false ) {

			if ( $value === true ) {
				$value = $key;
			}

			$output = $key . '="' . esc_attr( $value ) . '"';

		}

		return $output;

	}

}

/**
 * The CSS classes attribute.
 *
 * @since 1.1
 */
class ClassAttribute extends SimpleAttribute {

	// Getters

	/**
	 * Get the classes list.
	 *
	 * @since 1.1
	 * @return string|array
	 */
	public function get_value( $type = 'string' ) {

		switch( strtolower( $type ) ) {

			case 'array':

				// Convert the classes list to an array.
				$this->value = $this->explode_classes( $this->value );

				break;

			default:
			case 'string':

				// Convert the classes list to a string.
				$this->value = $this->implode_classes( $this->value );

				break;

		}

		return $this->value;

	}

	// Checks

	/**
	 *
	 *
	 * @since 1.1
	 * @return bool
	 */
	public function has_classes( $classes ) {

		$classes = $this->explode_classes( $classes );

		if ( $classes ) {

			if ( in_array( $classes, $this->get_value( 'array' ) ) ) {
				return true;
			}

		}

		return false;

	}

	// Setters

	/**
	 * Adds many classes at once.
	 *
	 * @since 1.1
	 * @return Nmwdhj\Attributes\ClassAttribute
	 */
	public function add_classes( $classes ) {

		$classes = $this->explode_classes( $classes );

		if ( $classes ) {

			$classes = array_merge( $this->get_value( 'array' ), $classes );
			$this->set_value( array_unique( $classes ) );

		}

		return $this;

	}

	/**
	 * Removes many classes at once.
	 *
	 * @since 1.1
	 * @return Nmwdhj\Attributes\ClassAttribute
	 */
	public function remove_classes( $classes ) {

		$classes = $this->explode_classes( $classes );

		if ( $classes ) {

			$classes = array_diff( $this->get_value( 'array' ), $classes );
			$this->set_value( $classes );

		}

		return $this;

	}

	// Helpers

	/**
	 * Convert the classes list to an array.
	 *
	 * @since 1.1
	 * @return array
	 */
	protected function explode_classes( $value ) {

		if ( $value instanceof ClassAttribute ){
			$value = $value->get_value( 'array' );

		} elseif ( is_string( $value ) ) {
			$value = explode( ' ', $value );

		} elseif ( ! is_array( $value ) ) {
			$value = array();
		}

		$value = array_map( 'strtolower', $value );

		return $value;

	}

	/**
	 * Convert the classes list to a string.
	 *
	 * @since 1.1
	 * @return string
	 */
	protected function implode_classes( $value ) {

		if ( $value instanceof ClassAttribute ){
			$value = $value->get_value( 'string' );

		} elseif ( is_array( $value ) ) {
			$value = implode( ' ', $value );

		} else {
			$value = (string) $value;
		}

		$value = strtolower( $value );

		return $value;

	}

}