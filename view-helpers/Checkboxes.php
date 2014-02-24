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
	 * Render the Element View.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function render_element( Element $e ){

		$content = '';

		// Fix the name attribute.
		if ( $e->has_attr( 'name' ) ) {

			$name = $e->get_attr( 'name' );

			if ( substr( $name, -2 ) != '[]' ) {
				$e->set_attr( 'name', $name . '[]' );
			}

		}

		foreach( $e->get_value_options() as $key => $option ) {

			if ( is_scalar( $option ) ) {

				$option = array(
					'value' => $key,
					'label' => $option,
				);

			}

			if ( ! isset( $option['atts'] ) ) {
				$option['atts'] = array();
			}

			// Set the option attributes.
			$option['atts'] = \Nmwdhj\create_atts_obj( $option['atts'] );
			$option['atts']->set_atts( $e->get_atts(), false );

			// Set the 'checked' attribute.
			if ( isset( $option['value'] ) && ! $option['atts']->has_attr( 'checked' ) ) {

				if ( in_array( $option['value'], (array) $e->get_value(), true ) ) {
					$option['atts']->set_attr( 'checked', true );
				}

			}

			// Render the option.
			$content .= $this->render_option( $option ) . "\n";

		}

		return $content;

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
	 * @return string
	 * @since 1.0
	 */
	public function render_option( array $option ) {

		// The default option arguments.
		$option = array_merge( array(
			'label_position' => 'surround_before',
			'label_atts' => array(),
			'disabled' => false,
			'checked' => false,
			'value' => null,
			'atts' => null,
			'label' => '',
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
		$content = '<input'. strval( $option['atts'] ) .' />';


		/** CheckBox Label ****************************************************/

		if ( ! empty( $option['label'] ) ) {

			$label_atts = \Nmwdhj\create_atts_obj( $option['label_atts'] );

			if ( $option['atts']->has_attr( 'id' ) ) {
				$label_atts->set_attr( 'for', $option['atts']->get_attr( 'id' ), false );
			}

			$content = $this->render_label( array(
				'position'	=> $option['label_position'],
				'label'		=> $option['label'],
				'atts'		=> $label_atts,
			), $content );

		}

		return $content;

	}

}