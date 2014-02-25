<?php
namespace Nmwdhj\Views;

use Nmwdhj\Elements\Element;

/**
 * The Element View abstract class.
 *
 * @since 1.3
 */
abstract class View {

	/**
	 * Render the View.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function __invoke( Element $e ) {

		$content = $this->render_element( $e );

		// Render the Element Label.
		$content = $this->render_label( array(
			'position'	=> $e->get_label_position(),
			'atts'		=> $e->get_label_atts(),
			'text'		=> $e->get_label(),
		), $content );

		return $content;

	}

	/**
	 * Render the Element View.
	 *
	 * @return string
	 * @since 1.3
	 */
	public abstract function render_element( Element $e );

	/**
	 * A helper method to render a custom <label> tag.
	 *
	 * @return string
	 * @since 1.3
	 */
	protected function render_label( array $args, $content ){

		$args = array_merge( array(
			'position' => 'after',
			'atts' => array(),
			'text' => '',
		), $args );

		if ( empty( $args['text'] ) ) {
			return $content;
		}

		switch( strtolower( $args['position'] ) ) {

			case 'after':
				$content = $content . $this->render_tag( 'label', $args['atts'], $args['text'] );
				break;

			case 'surround_after':
				$content = $this->render_tag( 'label', $args['atts'], $args['text'] . $content );
				break;

			case 'surround_before':
				$content = $this->render_tag( 'label', $args['atts'], $content . $args['text'] );
				break;

			default:
			case 'before':
				$content = $this->render_tag( 'label', $args['atts'], $args['text'] ) . $content;
				break;

		}

		return $content;

	}

	/**
	 * A helper method to render a description tag.
	 *
	 * @return string
	 * @since 1.3
	 */
	protected function render_hint( array $args, $content ){

		$args = array_merge( array(
			'atts' => array( 'class' => 'help' ),
			'position' => 'after',
			'tag' => 'p',
			'text'=> '',
		), $args );

		if ( empty( $args['text'] ) ) {
			return $content;
		}

		switch( strtolower( $args['position'] ) ) {

			case 'before':
				$content = $this->render_tag( $args['tag'], $args['atts'], $args['text'] ) . $content;
				break;

			default:
			case 'after':
				$content = $content . $this->render_tag( $args['tag'], $args['atts'], $args['text'] );
				break;

		}

	}

	/**
	 * A helper method to render a custom HTML tag.
	 *
	 * @return string
	 * @since 1.3
	 */
	protected function render_tag( $tag, $atts, $content ){

		if ( empty( $tag ) ) {
			return $content;
		}

		$atts = \Nmwdhj\create_atts_obj( $atts );

		$content = '<' . $tag . strval( $atts ) . '>'
						. $content .
					'</' . $tag . '>';

		return $content;

	}

}