<?php
namespace Nmwdhj\Elements;

/**
 * The Nmwdhj Elements Manager class.
 *
 * @since 1.0
 */
final class Manager {

	/*** Properties ***********************************************************/

	/**
	 * Elements list.
	 *
	 * @access private
	 * @var array
	 * @since 1.0
	 */
	private static $elements = array();


	/** Public Methods ********************************************************/

	// Getters

	/**
	 * Get an element by key.
	 *
	 * @return object|bool
	 * @since 1.0
	 */
	public static function get_by_key( $key ) {

		if ( self::is_exists( $key ) ) {
			return self::$elements[ $key ];

		} else {

			foreach ( self::$elements as $element ) {

				if ( in_array( $key, (array) $element->aliases, true ) ) {
					return $element;
				}

			}

		}

		return false;

	}

	/**
	 * Retrieve a list of registered elements.
	 *
	 * @return array
	 * @since 1.0
	 */
	public static function get( array $args = NULL, $operator = 'AND' ) {
		return wp_list_filter( self::$elements, $args, $operator );
	}

	// Register/Deregister

	/**
	 * Registers a new element.
	 *
	 * @return object|bool
	 * @since 1.0
	 */
	public static function register( $key, array $args ) {

		if ( self::is_exists( $key ) ) {
			return false;
		}

		$args = (object) array_merge( array(
			'aliases' => array(),
			'class_name' => '',
			'class_path' => '',
		), $args );

		$args->key = $key; // Store the key.

		if ( empty( $args->class_name ) ) {
			return false;
		}

		// Register the element.
		self::$elements[ $key ] =  $args;

		return $args;

	}

	/**
	 * Register the default elements.
	 *
	 * @return void
	 * @since 1.0
	 */
	public static function register_defaults() {

		self::register( 'button', array(
			'class_name'	=> 'Nmwdhj\Elements\Button',
			'class_path'	=> \Nmwdhj\get_path( 'elements/Button.php' ),
			'aliases'		=> array( 'button_submit', 'button_reset' ),
		) );

		self::register( 'select', array(
			'class_name'	=> 'Nmwdhj\Elements\Select',
			'class_path'	=> \Nmwdhj\get_path( 'elements/Select.php' ),
		) );

		self::register( 'textarea', array(
			'class_name'	=> 'Nmwdhj\Elements\Textarea',
			'class_path'	=> \Nmwdhj\get_path( 'elements/Textarea.php' ),
		) );

		self::register( 'wp_editor', array(
			'class_name'	=> 'Nmwdhj\Elements\WP_Editor',
			'class_path'	=> \Nmwdhj\get_path( 'elements/WP_Editor.php' ),
		) );

		self::register( 'checkbox', array(
			'class_name'	=> 'Nmwdhj\Elements\Checkbox',
			'class_path'	=> \Nmwdhj\get_path( 'elements/Checkbox.php' ),
			'aliases'		=> array( 'input_checkbox' ),
		) );

		self::register( 'checkboxes', array(
			'class_name'	=> 'Nmwdhj\Elements\Checkboxes',
			'class_path'	=> \Nmwdhj\get_path( 'elements/Checkboxes.php' ),
			'aliases'		=> array( 'multi_checkbox' ),
		) );

		self::register( 'input', array(
			'class_name'	=> 'Nmwdhj\Elements\Input',
			'class_path'	=> \Nmwdhj\get_path( 'elements/Input.php' ),
			'aliases'		=> array(
				'input_text', 'input_url', 'input_email', 'input_range', 'input_search', 'input_date', 'input_file',
				'input_hidden', 'input_number', 'input_password', 'input_color', 'input_submit', 'input_week',
				'input_time', 'input_radio', 'input_month', 'input_image',
			),
		) );

	}

	/**
	 * Remove a registered element.
	 *
	 * @return bool
	 * @since 1.0
	 */
	public static function deregister( $key ) {

		if ( ! self::is_exists( $key ) ) {
			return false;
		}

		unset( self::$elements[ $key ] );
		return true;

	}

	// Checks

	/**
	 * Check an element existence.
	 *
	 * @return bool
	 * @since 1.3
	 */
	public static function is_exists( $key, $check_aliases = false ) {

		if ( empty( $key ) ) {
			return false;
		}

		if ( isset( self::$elements[ $key ] ) ) {
			return true;

		} elseif ( $check_aliases ) {

			foreach ( self::$elements as $element ) {

				if ( in_array( $key, (array) $element->aliases, true ) ) {
					return true;
				}

			}

		}

		return false;

	}

}