<?php
namespace Nmwdhj\Decorators;

/**
 * The Nmwdhj Decorators manager class.
 *
 * @since 1.0
 */
class Manager {

	/*** Properties ***********************************************************/

	/**
	 * Decorators list.
	 *
	 * @access private
	 * @var array
	 * @since 1.0
	 */
	private static $decorators = array();


	/*** Methods ***************************************************************/

	// Getters

	/**
	 * Get a decorator by key.
	 *
	 * @return object|bool
	 * @since 1.0
	 */
	public static function get_by_key( $key ) {

		if ( self::is_exists( $key ) ) {
			return self::$decorators[ $key ];
		}

		return false;

	}

	/**
	 * Retrieve a list of registered decorators.
	 *
	 * @return array
	 * @since 1.0
	 */
	public static function get( array $args = NULL, $operator = 'AND' ) {
		return wp_list_filter( self::$decorators, $args, $operator );
	}

	// Register/Deregister

	/**
	 * Register a new decorator.
	 *
	 * @return bool
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

		// Register the decorator.
		self::$decorators[ $key ] =  $args;

		return $args;

	}

	/**
	 * Register the Nmwdhj default decorators.
	 *
	 * @return void
	 * @since 1.0
	 */
	public static function register_defaults() {

		self::register( 'tag', array(
			'class_name' => 'Nmwdhj\Decorators\Tag',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/decorators/Tag.php' ),
		) );

		self::register( 'label', array(
			'class_name' => 'Nmwdhj\Decorators\Label',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/decorators/Label.php' ),
		) );

		self::register( 'description', array(
			'class_name' => 'Nmwdhj\Decorators\Description',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/decorators/Description.php' ),
		) );

	}

	/**
	 * Remove a registered decorator.
	 *
	 * @return bool
	 * @since 1.0
	 */
	public static function deregister( $key ) {

		if ( ! self::is_exists( $key ) ) {
			return false;
		}

		unset( self::$decorators[ $key ] );

		return true;

	}

	// Checks

	/**
	 * Check a decorator existence.
	 *
	 * @return bool
	 * @since 1.3
	 */
	public static function is_exists( $key ) {
		return ( ! empty( $key ) && isset( self::$decorators[ $key ] ) );
	}

	// Loaders

	/**
	 * Load decorator class file.
	 *
	 * @return void
	 * @since 1.0
	 */
	public static function load_class( $class_name, $require_once = false ) {

		if ( ! class_exists( $class_name, false ) ) {

			$decorator = self::get( array( 'class_name' => $class_name ), 'OR' );
			$decorator = reset( $decorator );

			if ( ! empty( $decorator->class_path ) && file_exists( $decorator->class_path ) ) {
				( $require_once ) ? require_once $decorator->class_path : require $decorator->class_path;
			}

		}

	}

}

// Register the autoload function.
spl_autoload_register( 'Nmwdhj\Decorators\Manager::load_class' );