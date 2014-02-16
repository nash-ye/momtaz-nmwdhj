<?php
/**
 * Plugin Name: Nmwdhj
 * Plugin URI: http://nashwan-d.com
 * Description: An API for creating forms elements via code.
 * Author: Nashwan Doaqan
 * Author URI: http://nashwan-d.com
 * Version: 1.3-alpha
 *
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright (c) 2013 - 2014 Nashwan Doaqan.  All rights reserved.
 */

namespace Nmwdhj;
use Nmwdhj\Exceptions\Exception;

// Nmwdhj Version.
const VERSION = '1.3-alpha';

//*** Loaders *****************************************************************/

/**
 * A helper function to load the Nmwdhj classes.
 *
 * @return void
 * @since 1.2
 */
function class_loader( $class_name ) {

	$nps = explode( '\\', $class_name, 3 );

	if ( 'Nmwdhj' !== $nps[0] || count( $nps ) !== 3 ) {
		return;
	}

	switch( $nps[1] ) {

		case 'Views':

			if ( in_array( $nps[2], array( 'View', 'Manager' ) ) ) {
				$class_path = get_path( "view-helpers/{$nps[2]}.php" );

			} else {

				$list = Views\Manager::get( array( 'class_name' => $class_name ), 'OR' );

				if ( $list ) {
					$class_path = reset( $list )->class_path;
				}

			}

			break;

		case 'Elements':

			if ( in_array( $nps[2], array( 'Base', 'Element', 'Manager' ) ) ) {
				$class_path = get_path( "elements/{$nps[2]}.php" );

			} else {

				$list = Elements\Manager::get( array( 'class_name' => $class_name ), 'OR' );

				if ( $list ) {
					$class_path = reset( $list )->class_path;
				}

			}

			break;

		case 'Decorators':

			if ( in_array( $nps[2], array( 'Decorator', 'Manager' ) ) ) {
				$class_path = get_path( "view-helpers/decorators/{$nps[2]}.php" );

			} else {

				$list = Decorators\Manager::get( array( 'class_name' => $class_name ), 'OR' );

				if ( $list ) {
					$class_path = reset( $list )->class_path;
				}

			}

			break;

		case 'Attributes':
			$class_path = get_path( 'core/attributes.php' );
			break;

		case 'Exceptions':
			$class_path = get_path( 'core/exceptions.php' );
			break;

	}

	if ( ! empty( $class_path ) && file_exists( $class_path ) ) {
		require $class_path;
	}

}

// Register the autoload function.
spl_autoload_register( 'Nmwdhj\class_loader' );


//*** Functions ***************************************************************/

/**
 * Create an element object.
 *
 * @return Nmwdhj\Elements\Element
 * @throws Nmwdhj\Exceptions\Exception
 * @since 1.2
 */
function create_element( $key, array $properties = NULL ) {

	if ( ! ( $element = Elements\Manager::get_by_key( $key ) ) ) {
		throw new Exception( 'invalid_element' );
	}

	return new $element->class_name( $key, $properties );

}

/**
 * Create many elements objects at once.
 *
 * @return Nmwdhj\Elements\Element[]
 * @throws Nmwdhj\Exceptions\Exception
 * @since 1.2
 */
function create_elements( array $elements ) {

	$objects = array();

	foreach( $elements as $key => $element ) {

		if ( empty( $element['key'] ) ) {
			continue;
		}

		$objects[ $key ] = create_element( $element['key'], $element );

	}

	return $objects;

}

/**
 * Create an attributes object.
 *
 * @return Nmwdhj\Attributes\Attributes
 * @since 1.0
 */
function create_atts_obj( $atts ) {

	if ( $atts instanceof Attributes\Attributes ) {
		return $atts;
	}

	return new Attributes\Attributes( $atts );

}

/**
 * Create an attribute object.
 *
 * @return Nmwdhj\Attributes\Attribute
 * @since 1.1
 */
function create_attr_obj( $key, $value ) {

	if ( $value instanceof Attributes\Attribute ) {

		if ( strcasecmp( $value->get_key(), $key ) !== 0 ) {
			$obj = create_attr_obj( $key, $value->get_value() );
		}

	} else {

		switch( strtolower( $key ) ) {

			case 'class':
				$obj = new Attributes\ClassAttribute( $key, $value );
				break;

			default:
				$obj = new Attributes\SimpleAttribute( $key, $value );
				break;

		}

	}

	return $obj;

}

/**
 * Decorate an element.
 *
 * @return Nmwdhj\Decorators\Decorator
 * @throws Nmwdhj\Exceptions\Exception
 * @since 1.2
 */
function decorate_element( $key, Elements\Element &$element ) {

	if ( ! ( $decorator = Decorators\Manager::get_by_key( $key ) ) ) {
		throw new Exception( 'invalid_decorator' );
	}

	$element = new $decorator->class_name( $element );

	return $element;

}

// Paths

/**
 * Get the absolute system path to the plugin directory, or a file therein.
 *
 * @param string $path
 * @return string
 * @since 1.2
 */
function get_path( $path = '' ) {

	$base = dirname( __FILE__ );

	if ( ! empty( $path ) ) {
		$path = path_join( $base, $path );
	} else {
		$path = untrailingslashit( $base );
	}

	return $path;

}


//*** Initialize **************************************************************/

// Register the default decorators.
Decorators\Manager::register_defaults();

// Register the default elements.
Elements\Manager::register_defaults();

// Register the default views.
Views\Manager::register_defaults();

do_action( 'nmwdhj_init' );