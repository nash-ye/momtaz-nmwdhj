=== Nmwdhj ===
Contributors: alex-ye
Tags: forms, api, html, settings, options
Requires at least: 3.1
Tested up to: 3.7
Stable tag: 1.2
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

* Simple Search Form:

`
// Create and output the search text input.
Nmwdhj\create_element( 'input_search' )
    ->set_atts( array(
        'placeholder' => _x( 'Search this site...', 'placeholder' ),
        'title' => _x( 'Search for:', 'label' ),
        'class' => 'search-text',
        'required' => true,
      ) )
    ->set_value_callback( 'get_search_query' )
    ->set_name( 's' )
    ->output();

// Create and output the search submit button.
Nmwdhj\create_element( 'input_submit' )
    ->set_value( __( 'Search' ) )
    ->output();
`

* Simple Login Form:

`
$username = Nmwdhj\create_element( 'input_text' );
Nmwdhj\decorate_element( 'label', $username )
    ->set_label( __( 'User Name:' ) )
    ->set_name( 'user_name' )
    ->output();

$userpass = Nmwdhj\create_element( 'input_password' );
Nmwdhj\decorate_element( 'label', $userpass )
    ->set_label( __( 'Password:' ) )
    ->set_name( 'user_pass' )
    ->output();

Nmwdhj\create_element( 'input_submit' )
    ->set_value( __( 'Submit' ) )
    ->output();
`

== Installation ==
1. Upload and install the plugin
2. Use the rich API to powerful your theme/plugin.

== Frequently Asked Questions ==

= What this plugin for? =
Nmwdhj is an API for creating forms elements via code.

= Is this plugin available on Github? =
Yes, You can follow the project from here:
https://github.com/nash-ye/Momtaz-Nmwdhj

== Changelog ==

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