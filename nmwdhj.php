<?php
/**
 * Plugin Name: Nmwdhj
 * Plugin URI: http://wordpress.org/plugins/momtaz-nmwdhj/
 * Description: An API for creating forms elements via code.
 * Author: Nashwan Doaqan
 * Author URI: http://nashwan-d.com
 * Version: 1.3-alpha-1
 *
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright (c) 2013 - 2014 Nashwan Doaqan.  All rights reserved.
 */

namespace Nmwdhj;

// Nmwdhj Version.
const VERSION = '1.3-alpha-1';

//*** Loaders *****************************************************************/

/**
 * A helper function to load the Nmwdhj classes.
 *
 * @return void
 * @since 1.2
 */
function class_loader( $class_name ) {

	$nps = explode( '\\', $class_name, 3 );

	if ( 'Nmwdhj' !== $nps[0] ) {
		return;
	}

	if ( count( $nps ) <= 3 ) {

		switch( $nps[1] ) {

			case 'Views':

				if ( 'View' === $nps[2] ) {
					$class_path = get_path( "view-helpers/View.php" );

				} else {

					$list = Manager::get( array( 'view_class' => $class_name ), 'OR' );

					if ( $list ) {
						$class_path = reset( $list )->view_path;
					}

				}

				break;

			case 'Elements':

				if ( 'Element' === $nps[2] ) {
					$class_path = get_path( "elements/Element.php" );

				} else {

					$list = Manager::get( array( 'element_class' => $class_name ), 'OR' );

					if ( $list ) {
						$class_path = reset( $list )->element_path;
					}

				}

				break;

			case 'Attributes':
				$class_path = get_path( 'core/Attributes.php' );
				break;

			case 'Exceptions':
				$class_path = get_path( 'core/Exceptions.php' );
				break;

			case 'Manager':
				$class_path = get_path( 'core/Manager.php' );
				break;

		}

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

	if ( ! ( $element = Manager::get_by_key( $key ) ) ) {
		throw new Exceptions\Exception( 'invalid_element' );
	}

	return new $element->element_class( $key, $properties );

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

		if ( strcasecmp( $value->get_key(), $key ) === 0 ) {
			return $value;
		}

		return create_attr_obj( $key, $value->get_value() );

	} else {

		switch( strtolower( $key ) ) {

			case 'class':
				return new Attributes\ClassAttribute( $key, $value );

			default:
				return new Attributes\SimpleAttribute( $key, $value );

		}

	}

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

// Register the default settings.
Manager::register_defaults();

do_action( 'nmwdhj_init' );

//*** Tests *******************************************************************/

// Input Text:
$input_text = new \Nmwdhj\Elements\Input( 'input_text' );
$input_text->set_value( 'Nashwan Doaqan' )
			->set_label( 'Your Name' )
			->set_NID( 'name' )
			->output();

// Input URL:
$input_url = new \Nmwdhj\Elements\Input( 'input_url' );
$input_url	->set_value( 'http://google.com' )
			->set_label( 'Website' )
			->set_NID( 'url' )
			->output();

// Input URL:
$input_url = new \Nmwdhj\Elements\Input( 'input_url' );
$input_url	->set_value( 'http://google.com' )
			->set_label( 'Website' )
			->set_NID( 'url' )
			->output();

// Textarea:
$textarea = new \Nmwdhj\Elements\Textarea();
$textarea	->set_value( 'bla bla bla' )
			->set_label( 'Bio' )
			->set_NID( 'bio' )
			->output();

// Checkbox:
$checkbox = new \Nmwdhj\Elements\Checkbox();
$checkbox	->set_unchecked_value( 'unactive' )
			->set_checked_value( 'active' )
			->set_value( 'active' )
			->set_label( 'Mode?' )
			->set_NID( 'mode' )
			->output();

// Checkboxes:
$checkboxes = new \Nmwdhj\Elements\Checkboxes();
$checkboxes ->set_value_options( array(
				'1' => 'One',
				'2' => 'Two',
				'3' => 'Three',
			) )
			->set_label( 'Mode?' )
			->set_value( 2 )
			->output();

