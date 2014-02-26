<?php
namespace Nmwdhj\Elements;

use Nmwdhj\Attributes\Attributes;

/**
 * The abstract simple element class.
 *
 * @since 1.0
 */
abstract class Element {

	/*** Properties ***********************************************************/

	/**
	 * Element key.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $key;

	/**
	 * Element value.
	 *
	 * @var mixed
	 * @since 1.0
	 */
	protected $value;

	/**
	 * Element attributes object.
	 *
	 * @var Nmwdhj\Attributes\Attributes
	 * @since 1.3
	 */
	protected $atts;

	/**
	 * Element value callback.
	 *
	 * @var array
	 * @since 1.0
	 */
	protected $value_callback;

	/**
	 * Element options.
	 *
	 * @var array
	 * @since 1.0
	 */
	protected $options = array();


	/*** Magic Methods ********************************************************/

	/**
	 * The default element constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $key = '', array $properties = NULL ) {

		$this->set_key( $key );

		if ( is_array( $properties ) ) {

			foreach ( $properties as $property => $value ) {

				switch( strtolower( $property ) ) {

					case 'id':
						$this->set_ID( $value );
						break;

					case 'nid':
						$this->set_NID( $value );
						break;

					case 'name':
						$this->set_name( $value );
						break;

					case 'atts':
						$this->set_atts( $value );
						break;

					case 'value':
						$this->set_value( $value );
						break;

					case 'label':
						$this->set_label( $value );
						break;

					case 'options':
						$this->set_options( $value );
						break;

				}

			}

		}

	}


	/*** Methods **************************************************************/

	// Key

	/**
	 * Get the element key.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * Set the element key.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	protected function set_key( $key ) {

		if ( ! empty( $key ) ) {
			$this->key = $key;
		}

		return $this;

	}

	// Value

	/**
	 * Get the element value.
	 *
	 * @return mixed
	 * @since 1.0
	 */
	public function get_value() {

		if ( is_null( $this->value ) ) {
			$this->set_value( $this->call_value_callback() );
		}

		return $this->value;

	}

	/**
	 * Set the element value.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_value( $value ) {
		$this->value = $value;
		return $this;
	}

	/**
	 * Get the element value callback.
	 *
	 * @return array
	 * @since 1.0
	 */
	public function get_value_callback() {
		return $this->value_callback;
	}

	/**
	 * Call the element value callback.
	 *
	 * @return mixed
	 * @since 1.3
	 */
	public function call_value_callback() {

		$callback = $this->get_value_callback();

		if ( is_array( $callback ) && ! empty( $callback ) ) {
			return call_user_func_array( $callback['name'], $callback['args'] );
		}

	}

	/**
	 * Set a value callback.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_value_callback( $callback ) {

		$params = array_slice( func_get_args(), 1 );
		$this->set_value_callback_array( $callback, $params );

		return $this;

	}

	/**
	 * Set a value callback with an array of parameters.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.1
	 */
	public function set_value_callback_array( $callback, array $param ) {

		if ( is_callable( $callback ) ) {

			$this->value_callback = array(
				'name' => $callback,
				'args' => $param,
			);

		}

		return $this;

	}

	// The Special Attributes

	/**
	 * Set the element 'id' and 'name' attributes.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function set_NID( $value ) {
		$this->set_name( $value );
		$this->set_ID( $value );
		return $this;
	}

	/**
	 * Get the element ID attribute.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_ID( $def = '' ) {
		return $this->get_attr( 'id', $def );
	}

	/**
	 * Set the element ID attribute.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_ID( $value ) {
		$this->set_attr( 'id', $value );
		return $this;
	}

	/**
	 * Get the element name attribute.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_name( $def = '' ) {
		return $this->get_attr( 'name', $def );
	}

	/**
	 * Set the element name attribute.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_name( $value ) {
		$this->set_attr( 'name', $value );
		return $this;
	}

	// Output

	/**
	 * Get the element output.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_output() {

		$element = \Nmwdhj\Manager::get_by_key( $this->get_key() );

		if ( $element && class_exists( $element->view_class ) ) {
			return call_user_func( new $element->view_class, $this );
		}

	}

	/**
	 * Display the element output.
	 *
	 * @since 1.0
	 */
	public function output() {
		echo $this->get_output();
	}

	// Attributes

	/**
	 * Get all the attributes array.
	 *
	 * @since 1.0
	 */
	public function get_atts( $type = 'array' ) {

		switch( $type ) {

			case 'obj':
				return $this->get_atts_obj();

			default:
			case 'array':
				return $this->get_atts_obj()->get_atts();

			case 'string':

				$args = NULL;

				if ( func_num_args() > 1 ) {
					$args = (array) func_get_arg( 1 );
				}

				return $this->get_atts_obj()->to_string( $args );

		}

	}

	/**
	 * Get an attribute value.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_attr( $key, $def = '' ) {
		return $this->get_atts_obj()->get_attr( $key, $def );
	}

	/**
	 * Get an attribute object.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_attr_obj( $key ) {
		return $this->get_atts_obj()->get_attr_obj( $key );
	}

	/**
	 * Check for an attribute existence.
	 *
	 * @return bool
	 * @since 1.0
	 */
	public function has_attr( $key ) {
		return $this->get_atts_obj()->has_attr( $key );
	}

	/**
	 * Set many attributes at once.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_atts( array $atts ) {

		foreach( $atts as $key => $value ) {
			$this->set_attr( $key, $value );
		}

		return $this;

	}

	/**
	 * Set an attribute value.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_attr( $key, $value ) {
		$this->get_atts_obj()->set_attr( $key, $value );
		return $this;
	}

	/**
	 * Remove many attributes at once.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function remove_atts( array $keys ) {

		foreach( $keys as $key ) {
			$this->remove_attr( $key );
		}

		return $this;

	}

	/**
	 * Remove an attribute.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function remove_attr( $key ) {
		$this->get_atts_obj()->remove_attr( $key );
		return $this;
	}

	/**
	 * Get the attributes object.
	 *
	 * @return Nmwdhj\Attributes\Attributes
	 * @since 1.0
	 */
	public function get_atts_obj() {

		if ( is_null( $this->atts ) ) {
			$this->atts = new Attributes();
		}

		return $this->atts;

	}

	// Options

	/**
	 * Get the defined options.
	 *
	 * @return array
	 * @since 1.0
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Get a specified option.
	 *
	 * @return mixed
	 * @since 1.0
	 */
	public function get_option( $option, $def = '' ) {

		if ( ! empty( $option ) ) {

			$options = $this->get_options();

			if ( isset( $options[ $option ] ) ) {
				return $options[ $option ];
			}

		}

		return $def;

	}

	/**
	 * Set the element options.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_options( $options ) {

		if ( is_array( $options ) ) {
			$this->options = $options;
		}

		return $this;

	}

	/**
	 * Set a specified option.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_option( $option, $value ) {

		if ( ! empty( $option ) ) {
			$this->options[ $option ] = $value;
		}

		return $this;

	}

	/**
	 * Remove all/specified options.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function remove_options( $options = '' ) {

		if ( is_array( $options ) && ! empty( $options ) ) {

			foreach( $options as $option ) {
				$this->remove_option( $option );
			}

		} else {

			$this->set_options( array() );

		}

		return $this;

	}

	/**
	 * Remove a specified option.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function remove_option( $option ) {

		if ( ! empty( $option ) ) {
			unset( $this->options[$option] );
		}

		return $this;

	}

	// Label Position:

	/**
	 * Set the label position.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.3
	 */
	public function set_label_position( $position ) {
		$this->set_option( 'label_position', $position );
		return $this;
	}

	/**
	 * Get the label position.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function get_label_position() {
		return $this->get_option( 'label_position' );
	}

	// Label Attributes:

	/**
	 * Set the label attributes.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.3
	 */
	public function set_label_atts( $atts ) {
		$this->set_option( 'label_atts', $atts );
		return $this;
	}

	/**
	 * Get the label attributes.
	 *
	 * @return Nmwdhj\Attributes\Attributes
	 * @since 1.3
	 */
	public function get_label_atts() {

		$atts = $this->get_option( 'label_atts' );

		if ( ! $atts instanceof Attributes ) {
			$atts = new Attributes( $atts );
			$this->set_label_atts( $atts );
		}

		return $atts;

	}

	// Label Text:

	/**
	 * Set the label text.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.3
	 */
	public function set_label( $text ) {
		$this->set_option( 'label', $text );
		return $this;
	}

	/**
	 * Get the label text.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function get_label() {
		return $this->get_option( 'label' );
	}

}