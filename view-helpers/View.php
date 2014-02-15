<?php
namespace Nmwdhj\Views;
use Nmwdhj\Elements\Element;

/**
 * The View abstract class.
 *
 * @since 1.0
 */
abstract class View {

	/*** Abstract Methods *****************************************************/

	/**
	 * Check the element.
	 *
	 * @since 1.0
	 * @return bool
	 */
	public function check( Element $element ){
		return true;
	}

	/**
	 * Prepare the element.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function prepare( Element $element ){}

	/**
	 * Render the element view, and return the output.
	 *
	 * @since 1.0
	 * @return string
	 */
	abstract public function render( Element $element );


	/*** Magic Methods ********************************************************/

	/**
	 * Invoke helper as functor.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function __invoke( Element $element ){

		if ( $this->check( $element ) ) {

			$this->prepare( $element );

			return $this->render( $element );

		}

	}

}