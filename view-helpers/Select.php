<?php
namespace Nmwdhj\Views;
use Nmwdhj\Elements\Element;

/**
 * The Select elements view class.
 *
 * @since 1.0
 */
class Select extends View {

	/**
	 * Prepare the element.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function prepare( Element $element ){

		// Fix the name attribute.
		if ( $element->has_attr( 'multiple' ) ) {

			$name = $element->get_attr( 'name' );

			if ( ! empty( $name ) && substr( $name, -2 ) != '[]' ) {
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

		// Open tag.
		$output = '<select' . $element->get_atts_string() . '>';

		// Options list.
		$output .= $this->render_options( $element->get_value_options(), $element->get_value() );

		// Close tag
		$output .= '</select>';

		return $output;

	}

	/**
	 * Render an array of options.
	 *
	 * Individual options should be of the form:
	 *
	 * <code>
	 * array(
	 *	 'value'	=> $value,
	 *	 'label'	=> $label,
	 *	 'disabled' => $boolean,
	 *	  selected  => $boolean,
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
	public function render_options( array $options, $value ) {

		$chunks = array();

		foreach( $options as $key => $option ) {

			if ( is_scalar( $option ) ) {

				$option = array(
					'value' => $key,
					'label' => $option,
				);

			}

			if ( isset( $option['options'] ) && is_array( $option['options'] ) ) {
				$chunks[] = $this->render_optgroup( $option );
				continue;
			}

			if ( isset( $option['value'] ) && in_array( $option['value'], (array) $value ) ) {
				$option['selected'] = true;
			}

			$chunks[] = $this->render_option( $option );

		}

		return implode( "\n", $chunks );

	}

	/**
	 * Render an optgroup.
	 *
	 * Should be of the form:
	 * <code>
	 * array(
	 *	 'label'	=> 'label',
	 *	 'atts'	 => $array,
	 *	 'options'  => $array,
	 * )
	 * </code>
	 *
	 * @since 1.0
	 * @return string
	 */
	public function render_optgroup( array $optgroup ) {

		$optgroup = array_merge( array(
			'options' => array(),
			'atts' => array(),
			'label' => '',
		), $optgroup );

		$optgroup['atts'] = \Nmwdhj\create_atts_obj( $optgroup['atts'] );

		if ( ! empty( $optgroup['label'] ) ) {
			$optgroup['atts']->set_attr( 'label', $optgroup['label'] );
		}

		return '<optgroup'. strval( $optgroup['atts'] ) .'>'. $this->render_options( $optgroup['options'] ) .'</optgroup>';

	}

	/**
	 * Render an individual option.
	 *
	 * Should be of the form:
	 * <code>
	 * array(
	 *	 'value'	=> 'value',
	 *	 'label'	=> 'label',
	 *	 'disabled' => $boolean,
	 *	  selected  => $boolean,
	 * )
	 * </code>
	 *
	 * @since 1.0
	 * @return string
	 */
	public function render_option( array $option ) {

		$option = array_merge( array(
			'value' => '',
			'label' => '',
			'atts' => array(),
			'disabled' => false,
			'selected' => false,
		), $option );

		foreach( array( 'value', 'disabled', 'selected' ) as $k ) {

			$option['atts'][ $k ] = $option[ $k ];
			unset( $option[ $k ] );

		}

		$attributes = strval( \Nmwdhj\create_atts_obj( $option['atts'] ) );

		return '<option'. $attributes .'>'. esc_html( $option['label'] ) .'</option>';

	}

}