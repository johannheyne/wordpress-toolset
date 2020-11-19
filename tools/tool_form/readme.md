[back to overview](../../README.markdown#tools)

Tool form
===============================

__Table of Content__
- [Add a Form](#add_a_form)
- [Adding Form Messages](#adding_form_messages)
- [Adding Fieldsets](#adding_fieldsets)
- [Adding Field Item Attributes](#field_wrapper_attrs)
- [Adding Fields](#adding_items_to_form)
	- Paramameter sanitize
- [Field Types](#form_field)
	- [text](#field_text)
	- [select](#field_select)
	- [taxonomy_select](#field_taxonomy_select)
	- [todo] textarea
	- [todo] radio
	- [todo] checkbox
	- [todo] checkbox_list
	- [todo] select
	- [custom](#custom_field)
	- [submit](#submit)
- [todo] (Fields HTML5/JS Validation)
- [Adding Content Prepending and Appending Form](#adding_content_prepending_appending_form)
- [todo] Ajax
- [todo] E-Mail Form
- [Form Request Action](#form_request_action)
- [Detect Form Requests](#detect_form_requests)


<a id="add_a_form"></a>
## Add a Form

```php
new Form( array(
	'form_id' => '{form_id}',
	'form_group' => '{form_group}',
	'form_attrs' => array(
		'class' => array( 'my-form' ),
		'aria_label' => 'My Form',
		// 'method' => 'get',
		// 'action' => './',
		// 'role' => 'form',
		// 'enctype' => 'multipart/form-data',
		// add more
	),
	'echo' => true,
	// add your own parameters, they will accessible in some hooks
) );
```
This adds a basic form…

```html
<form class="{class}" role="{role}" aria-label="{aria_label}" method="{method}" action="{action}">

	<input type="hidden" name="form_id" value="{form_id}" />

	<!-- Use a filter to add form items -->

</form>
```



<a id="adding_form_messages"></a>
## Adding Form Messages

```
class/Form/messages
class/Form/messages/form_id=
class/Form/messages/form_group=
```

```php
add_filter( 'class/Form/messages', function( $messages, $param ) {

	$messages['required'] = array(
		'en_US' => 'This field is required.',
	);

	$messages['to_short'] = array(
		'en_US' => 'The field requires at least 10 characters.',
	);

	$messages['field_validation_error'] = array(
		'en_US' => 'At least one field has an validation error.',
	);

	return $messages;

}, 10, 2 );
```

<a id="adding_fieldsets"></a>
## Adding Fieldsets

```
class/Form/fieldsets
class/Form/fieldsets/form_id=
class/Form/fieldsets/form_group=
```

```php
add_filter( 'class/Form/fieldsets/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'legend' => 'My Fieldset',
		'id' => '{fieldset_id}',
		'pos_key' => '{fieldset_key}',
		'pos' => 10,
	);

	return $items;

}, 10, 2 );
```



<a id="field_wrapper_attrs"></a>
## Adding Field Wrapper Item Attributs

```
class/Form/item_attrs
class/Form/items/form_group=
class/Form/item_attrs/form_id=
```

```php
add_filter( 'class/Form/item_attrs/form_id={form_id}', function( $attrs, $param ) {

	$attrs['class'][] = 'my-class';

	return $attrs;

}, 10, 2 );
```



<a id="adding_items_to_form"></a>
## Adding Fields

Fields HTML can be added before and after the search field…

```
class/Form/items
class/Form/items/form_id=
class/Form/items/form_group=
```

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		// Field Parameters
	);

	return $items;

}, 10, 2 );
```



<a id="form_field"></a>
## Field Types



<a id="field_text"></a>
### Text

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'text',
		'label' => '{label_text}',
		'attrs_label' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
			'placeholder' => '',
		),
		'required' => false,
		'fieldset_id' => '{fieldset_id}',
		'pos' => 10,
		'sanitize' => true,
		'validation' => function( $value ) {

			$message_keys = array(
				'field' => array(),
				'form' => array(),
			);

			return $message_keys;
		},
	);

	return $items;

}, 10, 2 );
```



<a id="field_select"></a>
### Select

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'select',
		'label' => '{label_text}',
		'attrs_label' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
			'placeholder' => '',
		),
		'required' => false,
		'fieldset_id' => '{fieldset_id}',
		'pos' => 10,
		'sanitize' => true,
		'validation' => function( $value ) {

			$message_keys = array(
				'field' => array(),
				'form' => array(),
			);

			return $message_keys;
		},
		'event' => array( // requires: tools/tool_form/script-select-field-events.js
			'on_change' => 'change_location', // change_location, submit_form
		),
		'allow_null' => array( // false or array
			'label' => array(
				'default' => 'Choose…', // list of locales
			),
			'value' => '',
		),
		'options' => array(

		),
	);

	return $items;

}, 10, 2 );
```

#### Parameters

```php
['event']['on_change'] => 'change_location',
['value_type'] => 'permalink',
```
Redirects to the permalink of the selectfield selected options value.<br>
__Requires__ `'value_type'` to be `'permalink'`.<br>
__Requires__ tools/tool_form/script-select-field-events.js
```php
['event']['on_change'] => 'submit_form',
```
Submits the form.<br>
__Requires__ tools/tool_form/script-select-field-events.js



<a id="field_taxonomy_select"></a>
### Taxonomy Select

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'taxonomy_select',
		'label' => '{label_text}',
		'attrs_label' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
			'placeholder' => '',
		),
		'required' => false,
		'fieldset_id' => '{fieldset_id}',
		'pos' => 10,
		'sanitize' => true,
		'validation' => function( $value ) {

			$message_keys = array(
				'field' => array(),
				'form' => array(),
			);

			return $message_keys;
		},
		'taxonomy' => '{taxonomy_slug}',
		'hide_empty' => false,
		'value_type' => 'permalink', // permalink, term_id
		'event' => array( // requires: tools/tool_form/script-select-field-events.js
			'on_change' => 'change_location', // change_location, submit_form
		),
		'allow_null' => array( // false or array
			'label' => array(
				'default' => 'Choose…', // list of locales
			),
			'value' => '',
		),
	);

	return $items;

}, 10, 2 );
```

#### Parameters

```php
['event']['on_change'] => 'change_location',
['value_type'] => 'permalink',
```
Redirects to the permalink of the selectfield selected options value.<br>
__Requires__ `'value_type'` to be `'permalink'`.<br>
__Requires__ tools/tool_form/script-select-field-events.js
```php
['event']['on_change'] => 'submit_form',
```
Submits the form.<br>
__Requires__ tools/tool_form/script-select-field-events.js





<a id="custom_field"></a>
### Custom

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'custom',
		'pos' => 10,
		'callback' => function() {

			return 'My Custom Content';
		},
	);

	return $items;

}, 10, 2 );
```



<a id="submit"></a>
### Submit

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'submit',
		'pos' => 10,
		'attrs_field' => array(
			/* The 'name' and 'id' should different
			than "submit" because this conflicts
			jQuery $('form').submit(); methode */
			'name' => '{field_name}',
			'value' => 'Submit',
		),
	);

	return $items;

}, 10, 2 );

```


<a id="adding_item_wrapper"></a>
## Adding Field Item Wrapper Element

```
class/Form/before_item
class/Form/before_item/form_group={form_group}
class/Form/after_item
class/Form/after_item/form_group={form_group}
```

```php
add_filter( 'class/Form/before_item/form_group={form_group}', function() {

	return '<li class="item">';

}, 10 );

```



<a id="adding_content_prepending_appending_form"></a>
## Adding Content Prepending and Appending Form

```
class/Form/form_prepend
class/Form/form_prepend/form_group=
class/Form/form_append
class/Form/form_append/form_group=
```

```php
add_filter( 'class/Form/form_prepend', function( $html, $param ) {

	$html .= '<ul>';

	return $html;

}, 10, 2 );
```


<a id="form_request_action"></a>
## Form Request Action

```
class/Form/request
class/Form/request/form_group=
class/Form/request/form_id=
```

```php
add_filter( 'class/Form/request/form_id=my_form', function( $param ) {

	// Do something with $_REQUEST

	if ( ! empty( $param['has_massages'] ) {

		// Form not valide
	}

}, 10, 2 );
```

<a id="detect_form_requests"></a>
## Detect Form Requests

```php
$form = new Form();

if ( $form->is_form_request( $form_id ) ) {

	// Do Validations
}
```
