=== Nmwdhj ===
Contributors: alex-ye
Tags: api, html, settings, options, forms, form
Requires at least: 3.1
Tested up to: 4.0
Stable tag: 1.3.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Nmwdhj is an API for creating forms elements via code.

== Description ==

= Important Notes: =
1. This plugin requires at least PHP 5.3.x .
2. This plugin is for developers, not general users.

Nmwdhj is an API for creating, editing and rendering forms programmatically.
This plugin doesn't have a GUI, It's only a helper library for the PHP & WP developers.

You can use this plugin to create individual form elements in the meta boxes, front-end, or anything else you might want a form for.

= Basic Examples =
You can use this plugin in many ways depending on your needs, this examples only for learning purposes:

* Simple WordPress Search Form:

`
Nmwdhj\create_element( array(
    'type'  => 'form',
    'atts'  => array(
        'method'    => 'GET',
        'role'      => 'search',
        'action'    => home_url( '/' ),
    ),
    'elements'  => array(
        'search' => array(
            'name'  => 's',
            'type'  => 'input_search',
            'value' => get_search_query(),
            'atts'  => array(
                'placeholder'   => _x( 'Search this site...', 'placeholder' ),
                'class'         => 'search-text',
                'required'      => true,
            ),
        ),
        'submit' => array(
            'type'  => 'input_submit',
            'value' => __( 'Search' ),
        ),
    ),
) )->output();
`

* Simple Login Form:

`
Nmwdhj\create_element( array(
    'type'  => 'form',
    'atts'  => array(
        'method' => 'POST',
    ),
) )
->add( array(
    'name'  => 'user_name',
    'type'  => 'input_text',
    'label' => __( 'User Name' ),
) )
->add( array(
    'name'  => 'user_pass',
    'type'  => 'input_password',
    'label' => __( 'Password' ),
) )
->add( array(
    'name'  => 'user_submit',
    'type'  => 'input_submit',
    'value' => __( 'Submit' ),
) )
->output();
`

= Contributing =
Developers can contribute to the source code on the [Nmwdhj GitHub Repository](https://github.com/nash-ye/Momtaz-Nmwdhj).


== Installation ==
1. Upload and install the plugin
2. Use the rich API to powerful your theme/plugin.

== Changelog ==

= 1.3.4 =
- Sort the priority array elements correctly.

= 1.3.3 =
- Additional methods in the Nmwdhj\Manager class, to store the elements objects.
- A better way to instance the element object via Nmwdhj\create_element function.

= 1.3.2 =
- Use loose comparison when check the selected and checked values.
- Add '+options' to append a group of element options at once.

= 1.3.1 =
- Fix some serious bugs in FieldSet,Checkboxes elements and Select view.

= 1.3 =
* Better directory structure.
* Code formatting improvements and optimizations.
* Introduce the PriorityArray and EventManager classes.
* Recode the Elements Views and remove the Decorators classes.
...etc

= Breaking Changes =
* Nmwdhj 1.3 is NOT completely compatible with any pervious version, so please don't update unless you know what you are doing!

= 1.2.1 =
* Fix the error when you use `Nmwdhj/decorate_element()` function.
* Add the ability to set the value-options directly by the constructor method.

= 1.2 =
* Remove the "Momtaz" prefix form the plugin title and classes names.
* Remove the not-needed custom view-key from the elements classes.
* Rewrite the plugin to use PHP namespaces.
* Add the Nmwdhj/Exception class.
* Add the plugin link on Github.
* Fix some minor bugs.

= Breaking Changes =
* Nmwdhj 1.2 is NOT compatible with any pervious version, so please don't update unless you know what you are doing!

= 1.1 =
* Enhance the check_class() method with the PHP function is_subclass_of().
* implement some easy methods to Add/Remove/Check the CSS 'class' attribute.
* implement a smart attributes system, you can now store the attributes as an objects.
* Replace any use for the deprecated PHP function is_a() by the instanceof operator.

= Breaking Changes =
* The behavior of set_value_callback() method has changed, it's now accept an optional list of permeates, if you want to pass an array of arguments please use the new method set_value_callback_array().

= 1.0 =
* The Initial version.