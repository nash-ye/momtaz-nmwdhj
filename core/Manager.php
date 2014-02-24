<?php
namespace Nmwdhj;

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
	private static $list = array();


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
			return self::$list[ $key ];

		} else {

			foreach ( self::$list as $element ) {

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
		return wp_list_filter( self::$list, $args, $operator );
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
			'element_class'	=> '',
			'element_path'	=> '',
			'view_class'		=> '',
			'view_path'		=> '',
			'aliases'		=> array(),
		), $args );

		$args->key = $key; // Store the key.

		if ( ! $args->element_class ) {
			return false;
		}

		// Register the element.
		self::$list[ $key ] =  $args;

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
			// The Element
			'element_class'		=> 'Nmwdhj\Elements\Button',
			'element_path'		=> \Nmwdhj\get_path( 'elements/Button.php' ),

			// The View
			'view_class'		=> 'Nmwdhj\Views\Button',
			'view_path'			=> \Nmwdhj\get_path( 'view-helpers/Button.php' ),

			// Other...
			'aliases'			=> array( 'button_submit', 'button_reset' ),
		) );

		self::register( 'select', array(
			// The Element
			'element_class'		=> 'Nmwdhj\Elements\Select',
			'element_path'		=> \Nmwdhj\get_path( 'elements/Select.php' ),

			// The View
			'view_class'		=> 'Nmwdhj\Views\Select',
			'view_path'			=> \Nmwdhj\get_path( 'view-helpers/Select.php' ),
		) );

		self::register( 'textarea', array(
			// The Element
			'element_class'		=> 'Nmwdhj\Elements\Textarea',
			'element_path'		=> \Nmwdhj\get_path( 'elements/Textarea.php' ),

			// The View
			'view_class'		=> 'Nmwdhj\Views\Textarea',
			'view_path'			=> \Nmwdhj\get_path( 'view-helpers/Textarea.php' ),
		) );

		self::register( 'wp_editor', array(
			// The Element
			'element_class'		=> 'Nmwdhj\Elements\WP_Editor',
			'element_path'		=> \Nmwdhj\get_path( 'elements/WP_Editor.php' ),

			// The View
			'view_class'		=> 'Nmwdhj\Views\WP_Editor',
			'view_path'			=> \Nmwdhj\get_path( 'view-helpers/WP_Editor.php' ),
		) );

		self::register( 'checkbox', array(
			// The Element
			'element_class'		=> 'Nmwdhj\Elements\Checkbox',
			'element_path'		=> \Nmwdhj\get_path( 'elements/Checkbox.php' ),

			// The View
			'view_class'		=> 'Nmwdhj\Views\Checkbox',
			'view_path'			=> \Nmwdhj\get_path( 'view-helpers/Checkbox.php' ),

			// Other...
			'aliases'			=> array( 'input_checkbox' ),
		) );

		self::register( 'checkboxes', array(
			// The Element
			'element_class'		=> 'Nmwdhj\Elements\Checkboxes',
			'element_path'		=> \Nmwdhj\get_path( 'elements/Checkboxes.php' ),

			// The View
			'view_class'		=> 'Nmwdhj\Views\Checkboxes',
			'view_path'			=> \Nmwdhj\get_path( 'view-helpers/Checkboxes.php' ),

			// Other...
			'aliases'			=> array( 'multi_checkbox' ),
		) );

		self::register( 'input', array(
			// The Element
			'element_class'		=> 'Nmwdhj\Elements\Input',
			'element_path'		=> \Nmwdhj\get_path( 'elements/Input.php' ),

			// The View
			'view_class'		=> 'Nmwdhj\Views\Input',
			'view_path'			=> \Nmwdhj\get_path( 'view-helpers/Input.php' ),

			// Other...
			'aliases'			=> array(
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

		unset( self::$list[ $key ] );
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

		if ( isset( self::$list[ $key ] ) ) {
			return true;

		} elseif ( $check_aliases ) {

			foreach ( self::$list as $element ) {

				if ( in_array( $key, (array) $element->aliases, true ) ) {
					return true;
				}

			}

		}

		return false;

	}

}