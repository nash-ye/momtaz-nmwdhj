<?php
namespace Nmwdhj\Views;
use Nmwdhj\Elements\Element;

/**
 * The Checkboxes element view class.
 *
 * @since 1.0
 */
class Checkboxes extends View {

	/**
	 * Check the element.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public function check( Element $element ){

		// Get the 'type' attribute.
		$type = $element->get_attr( 'type' );

		// Check the 'type' attribute.
		if ( strcasecmp( $type, 'checkbox' ) !== 0 ) {
			return false;
		}

		// Check the value options.
		if ( ! $element->get_value_options() ) {
			return false;
		}

		return true;

	}

	/**
	 * Prepare the element.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function prepare( Element $element ){

		// Fix the name attribute.
		if ( $element->has_attr( 'name' ) ) {

			$name = $element->get_attr( 'name' );

			if ( substr( $name, -2 ) != '[]' ) {
				$element->set_attr( 'name', $name . '[]' );
			}

		}

	}

	/**
	 * Render the element view, and return the output.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function render( Element $element ) {
		return $this->render_options( $element );
	}

	/**
	 * Render an array of options.
	 *
	 * Individual options should be of the form:
	 *
	 * <code>
	 * array(
	 *	 'atts'		=> $atts,
	 *	 'value'	=> $value,
	 *	 'label'	=> $label,
	 *	 'disabled' => $boolean,
	 *	 'checked'  => $boolean,
	 * )
	 * </code>
	 *
	 * or:
	 *
	 * <code>
	 * array(
	 *	 $value	=> $label,
	 * )
	 * </code>
	 *
	 * @since 1.0
	 * @return string
	 */
	public function render_options( Element $element ) {

		$chunks = array();

		foreach( $element->get_value_options() as $key => $option ) {

			if ( is_scalar( $option ) ) {

				$option = array(
					'value' => $key,
					'label' => $option,
				);

			}

			if ( ! isset( $option['atts'] ) ) {
				$option['atts'] = null;
			}

			// Set the option attributes.
			$option['atts'] = \Nmwdhj\create_atts_obj( $option['atts'] );
			$option['atts']->set_atts( $element->get_atts(), false );

			// Set the 'checked' attribute.
			if ( isset( $option['value'] ) && ! $option['atts']->has_attr( 'checked' ) ) {

				if ( in_array( $option['value'], (array) $element->get_value(), true ) ) {
					$option['atts']->set_attr( 'checked', true );
				}

			}

			// Render the option.
			$chunks[] = $this->render_option( $option, $element );

		}

		return implode( "\n", $chunks );

	}

	/**
	 * Render an individual option.
	 *
	 * Should be of the form:
	 * <code>
	 * array(
	 *	 'atts'			=> $atts,
	 *	 'value'		=> $value,
	 *	 'label'		=> $label,
	 *	 'disabled'		=> $boolean,
	 *	 'checked'		=> $boolean,
	 * )
	 * </code>
	 *
	 * @since 1.0
	 * @return string
	 */
	public function render_option( array $option, Element $element ) {

		// The default option arguments.
		$option = array_merge( array(
			'label' => '',
			'atts' => null,
			'value' => null,
			'checked' => false,
			'disabled' => false,
		), $option );


		/** CheckBox Input ****************************************************/

		// Get the Attributes object.
		$option['atts'] = \Nmwdhj\create_atts_obj( $option['atts'] );

		// Set the value attribute.
		if ( ! is_null( $option['value'] ) ) {
			$option['atts']->set_attr( 'value', $option['value'] );
		}

		// Set the checked attribute.
		if ( ! empty( $option['checked'] ) ) {
			$option['atts']->set_attr( 'checked', true );
		}

		// Set the disabled attribute.
		if ( ! empty( $option['disabled'] ) ) {
			$option['atts']->set_attr( 'disabled', true );
		}

		// The checkbox input output.
		$input = '<input'. strval( $option['atts'] ) .' />';


		/** CheckBox Label ****************************************************/

		$label_atts = $element->get_option( 'option_label_atts' );
		$label_atts = \Nmwdhj\create_atts_obj( $label_atts );

		if ( $option['atts']->has_attr( 'id' ) ) {
			$label_atts->set_attr( 'for', $option['atts']->get_attr( 'id' ), false );
		}


		/** Output ************************************************************/

		switch( strtolower( $element->get_option( 'option_label_position' ) ) ) {

			case 'after':
				$output  = $input;
				$output .= '<label'. strval( $label_atts ) .'>' . $option['label'] . '</label>';
				break;

			case 'before':
				$output  = '<label'. strval( $label_atts ) .'>' . $option['label'] . '</label>';
				$output .= $input;
				break;

			case 'surround_after':
				$output = '<label'. strval( $label_atts ) .'>' . $option['label'] . $input . '</label>';
				break;

			default:
			case 'surround_before':
				$output = '<label'. strval( $label_atts ) .'>' . $input . $option['label'] . '</label>';
				break;

		}

		return $output;

	}

}