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
			'class_name'	=> '',
			'class_path'	=> '',
			'aliases'		=> array(),
		), $args );

		$args->key = $key; // Store the key.

		if ( ! $args->class_name ) {
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
			'class_name'		=> 'Nmwdhj\Elements\Button',
			'class_path'		=> \Nmwdhj\get_path( 'elements/Button.php' ),
			'aliases'			=> array( 'button_submit', 'button_reset' ),
		) );

		self::register( 'select', array(
			'class_name'		=> 'Nmwdhj\Elements\Select',
			'class_path'		=> \Nmwdhj\get_path( 'elements/Select.php' ),
		) );

		self::register( 'textarea', array(
			'class_name'		=> 'Nmwdhj\Elements\Textarea',
			'class_path'		=> \Nmwdhj\get_path( 'elements/Textarea.php' ),
		) );

		self::register( 'wp_editor', array(
			'class_name'		=> 'Nmwdhj\Elements\WP_Editor',
			'class_path'		=> \Nmwdhj\get_path( 'elements/WP_Editor.php' ),
		) );

		self::register( 'checkbox', array(
			'class_name'		=> 'Nmwdhj\Elements\Checkbox',
			'class_path'		=> \Nmwdhj\get_path( 'elements/Checkbox.php' ),
			'aliases'			=> array( 'input_checkbox' ),
		) );

		self::register( 'checkboxes', array(
			'class_name'		=> 'Nmwdhj\Elements\Checkboxes',
			'class_path'		=> \Nmwdhj\get_path( 'elements/Checkboxes.php' ),
			'aliases'			=> array( 'multi_checkbox' ),
		) );

		self::register( 'input', array(
			'class_name'		=> 'Nmwdhj\Elements\Input',
			'class_path'		=> \Nmwdhj\get_path( 'elements/Input.php' ),
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

/*** Helper Classes ***********************************************************/

/**
 * Event manager
 *
 * @since 1.3
 */
class EventManager {

	/*** Properties ***********************************************************/

	/**
	 * Registered events list.
	 *
	 * @var array
	 * @since 1.3
	 */
	protected $events = array();


	/** Methods ***************************************************************/

	/**
	 * Trigger all listeners for a given event
	 *
	 * @return void
	 * @since 1.3
	 */
	public function trigger( $event, $arg = '' ) {

		if ( is_array( $event ) ) {

			foreach( $event as $name ) {
				$this->trigger( $name );
			}

		} elseif ( isset( $this->events[ $event ] ) ) {

			$args = array();

			for ( $i = 1; $i < func_num_args(); $i++ ) {
				$args[] = func_get_arg( $i );
			}

			foreach( $this->events[ $event ] as $listener ) {
				call_user_func_array( $listener, $args );
			}

		}

	}

	/**
	 * Attach a listener to an event
	 *
	 * @return void
	 * @since 1.3
	 */
	public function attach( $event, $listener, $priority = 10 ) {

		if ( NULL === $listener ) {
			throw new Exception( 'The provided listener isn\'t a valid callback.' );
		}

		if ( is_array( $event ) ) {

			foreach( $event as $name ) {
				$this->attach( $name, $listener, $priority );
			}

		} else {

			if ( ! isset( $this->events[ $event ] ) ) {
				$this->events[ $event ] = new PriorityArray();
			}

			$this->events[ $event ]->offsetSet( $this->build_listener_id( $listener ), $listener, $priority );

		}

	}

	/**
	 * Unsubscribe a listener from an event
	 *
	 * @return void
	 * @since 1.3
	 */
	public function detach( $event, $listener ) {

		if ( NULL === $listener ) {
			throw new Exception( 'The provided listener isn\'t a valid callback.' );
		}

		if ( is_array( $event ) ) {

			foreach( $event as $name ) {
				$this->detach( $name, $listener );
			}

		} elseif ( isset( $this->events[ $event ] ) ) {

			$this->events[ $event ]->offsetUnset( $this->build_listener_id( $listener ) );

		}

	}

	/**
	 * Build Unique ID for listeners callbacks.
	 *
	 * @access private
	 * @return string
	 * @since 1.3
	 */
	protected function build_listener_id( $listener ) {

		if ( is_string( $listener ) ) {
			return $listener;
		}

		return spl_object_hash( (object) $listener );

	}

	/**
	 * Clear all listeners for a given event
	 *
	 * @return void
	 * @since 1.3
	 */
	public function clear_listeners( $event ) {
		unset( $this->events[ $event ] );
	}

	/**
	 * Gel all registered events.
	 *
	 * @return array
	 * @since 1.3
	 */
	public function get_events() {
		return array_keys( $this->events );
	}

}

/**
 * Priority Array
 *
 * @since 1.3
 */
class PriorityArray implements \IteratorAggregate, \ArrayAccess, \Serializable, \Countable {

	/*** Properties ***********************************************************/

	/**
	 * Elements List
	 *
	 * @var array
	 * @since 1.3
	 */
	protected $e6s = array();

	/**
	 * Priorities List.
	 *
	 * @var array
	 * @since 1.3
	 */
	protected $p8s = array();

	/**
	 * Is Sorted? (Flag)
	 *
	 * @var bool
	 * @since 1.3
	 */
	private $is_sorted = true;


	/*** Methods **************************************************************/

	/**
	 * @return void
	 * @since 1.3
	 */
	public function offsetSet( $index, $value, $priority = 10 ) {

		$this->p8s[ $index ] = (int) $priority;
		$this->e6s[ $index ] = $value;
		$this->is_sorted = false;

	}

	/**
	 * @return bool
	 * @since 1.3
	 */
	public function offsetExists( $index ) {
		return isset( $this->e6s[ $index ] );
	}

	/**
	 * @return void
	 * @since 1.3
	 */
	public function offsetUnset( $index ) {

		unset( $this->p8s[ $index ] );
		unset( $this->e6s[ $index ] );

	}

	/**
	 * @return mixed
	 * @since 1.3
	 */
	public function offsetGet( $index ) {

		if ( $this->offsetExists( $index ) ) {
			return $this->e6s[ $index ];
		}

	}

	/**
	 * @return ArrayIterator
	 * @since 1.3
	 */
	public function getIterator() {

		$this->maybeSort(); // Sort the array.
		return new \ArrayIterator( $this->e6s );

	}

	/**
	 * @return void
	 * @since 1.3
	 */
	public function unserialize( $data ) {
		$this->e6s = unserialize( $data );
	}

	/**
	 * @return string
	 * @since 1.3
	 */
	public function serialize() {
		return serialize( $this->e6s );
	}

	/**
	 * @return bool
	 * @since 1.3
	 */
	public function maybeSort() {

		if ( ! $this->is_sorted ) {

			$p8s = (array) $this->p8s;

			$this->is_sorted = uksort( $this->e6s,

				function( $a, $b ) use ( &$p8s ) {

					$p1 = (int) $p8s[ $a ];
					$p2 = (int) $p8s[ $b ];

					if ( $p1 === $p2 ) {
						return 0;
					}

					return ( $p1 < $p2 ) ? +1 : -1;

				}

			);

		}

		return $this->is_sorted;

	}

	/**
	 * @return int
	 * @since 1.3
	 */
	public function count() {
		return count( $this->e6s );
	}

}

/*** Exceptions Classes *******************************************************/

/**
 * The Nmwdhj exception class.
 *
 * @since 1.2
 */
class Exception extends \Exception {}