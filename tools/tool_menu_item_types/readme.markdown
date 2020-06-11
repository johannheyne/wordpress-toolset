[back to overview](../../README.markdown#initial-functionality)

Menu Item Types
===============================

Requires the methode `get_lang_field()` which is not implemented in the toolset yet.

Adding "mailto:", "tel:" or "fax:" to an menu item URL field without a number or mail address will use the the fieldgroup 'option-contactdata' by get_lang_field( 'opt_contactdata', 'option' ); to fill in the contact values in. Leafing the "Display Name" field blank will display the number or email-address in the menu.

### Subtitle

````php
	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_menu_item_types' => true
		)
	);

````

[back to overview](../../README.markdown#initial-functionality)
