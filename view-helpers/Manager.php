<?php
namespace Nmwdhj\Views;

/**
 * The Nmwdhj Views Manager class.
 *
 * @since 1.0
 */
final class Manager {

	/*** Properties ***********************************************************/

	/**
	 * Views list.
	 *
	 * @access private
	 * @var array
	 * @since 1.0
	 */
	private static $views = array();


	/*** Public Methods *******************************************************/

	// Getters

	/**
	 * Get a view by key.
	 *
	 * @return object|bool
	 * @since 1.0
	 */
	public static function get_by_key( $key ) {

		if ( self::is_exists( $key ) ) {
			return self::$views[ $key ];
		}

		return false;

	}

	/**
	 * Retrieve a list of registered views.
	 *
	 * @return array
	 * @since 1.0
	 */
	public static function get( array $args = NULL, $operator = 'AND' ) {
		return wp_list_filter( self::$views, $args, $operator );
	}

	// Register/Deregister

	/**
	 * Register a new view.
	 *
	 * @return object|bool
	 * @since 1.0
	 */
	public static function register( $key, array $args ) {

		if ( self::is_exists( $key ) ) {
			return false;
		}

		$args = (object) array_merge( array(
			'class_name' => '',
			'class_path' => '',
		), $args );

		$args->key = $key; // Store the key.

		if ( empty( $args->class_name ) ) {
			return false;
		}

		// Register the view.
		self::$views[ $key ] =  $args;

		return $args;

	}

	/**
	 * Register the default views.
	 *
	 * @return void
	 * @since 1.0
	 */
	public static function register_defaults() {

		self::register( 'button', array(
			'class_name' => 'Nmwdhj\Views\Button',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Button.php' ),
		) );

		self::register( 'input', array(
			'class_name' => 'Nmwdhj\Views\Input',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Input.php' ),
		) );

		self::register( 'select', array(
			'class_name' => 'Nmwdhj\Views\Select',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Select.php' ),
		) );

		self::register( 'textarea', array(
			'class_name' => 'Nmwdhj\Views\Textarea',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Textarea.php' ),
		) );

		self::register( 'wp_editor', array(
			'class_name' => 'Nmwdhj\Views\WP_Editor',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/WP_Editor.php' ),
		) );

		self::register( 'checkbox', array(
			'class_name' => 'Nmwdhj\Views\Checkbox',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Checkbox.php' ),
		) );

		self::register( 'checkboxes', array(
			'class_name' => 'Nmwdhj\Views\Checkboxes',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Checkboxes.php' ),
		) );

	}

	/**
	 * Remove a registered view.
	 *
	 * @return bool
	 * @since 1.0
	 */
	public static function deregister( $key ) {

		if ( ! self::is_exists( $key ) ) {
			return false;
		}

		unset( self::$views[ $key ] );

		return true;

	}

	// Checks

	/**
	 * Check a view existence.
	 *
	 * @return bool
	 * @since 1.3
	 */
	public static function is_exists( $key ) {
		return ( ! empty( $key ) && isset( self::$views[ $key ] ) );
	}

	// Loaders

	/**
	 * Load view class file.
	 *
	 * @return void
	 * @since 1.0
	 */
	public static function load_class( $class_name, $require_once = false ) {

		if ( ! class_exists( $class_name, false ) ) {

			$view = self::get( array( 'class_name' => $class_name ), 'OR' );
			$view = reset( $view );

			if ( ! empty( $view->class_path ) && file_exists( $view->class_path ) ) {
				( $require_once ) ? require_once $view->class_path : require $view->class_path;
			}

		}

	}

}

// Register the autoload function.
spl_autoload_register( 'Nmwdhj\Views\Manager::load_class' );