[back to overview](../../README.markdown#tools)

Tool form
===============================

__Table of Content__
- [Add a Form](#add_a_form)
	- [Add Email Form](#add-email-form)
- [Form Messages](#form_messages)
- [Containers](#adding_containers)
- [Fieldsets](#adding_fieldsets)
- [Field Item Attributes](#field_wrapper_attrs)
- [Adding Fields](#adding_items_to_form)
	- [Filter: Field Template Data](#filter-field-template-data)
- [Field Types](#form_field)
	- [text](#field_text)
	- [search](#field_search)
	- [date](#field_date)
	- [textarea](#field_textarea)
	- [file](#field_file)
	- [radio](#field_radio)
	- [checkbox](#field_checkbox)
		- [Filter: Before Checkbox HTML](#filter-before-checkbox-html)
		- [Filter: After Checkbox HTML](#filter-after-checkbox-html)
	- [checkboxes](#field_checkboxes)
		- [Filter: Prepend Checkboxes Item](#filter-prepend-checkboxes-item)
		- [Filter: Append Checkboxes Item](#filter-append-checkboxes-item)
		- [Filter: Before and After Checkbox HTML](#filter-before-and-after-checkbox-html)
	- [todo] checkbox_list
	- [select](#field_select)
	- [taxonomy_select](#field_taxonomy_select)
	- [switch_toggle](#field_switch_toggle)
	- [custom](#custom_field)
	- [info](#info)
	- [submit](#submit)
	- [email](#field_email)
	- [hidden](#field_hidden)
- [todo] (Fields HTML5/JS Validation)
- [todo] Ajax
- [todo] E-Mail Form
- [Detect Form Requests](#detect_form_requests)
- [Filter: Do Email Check](#do_email_check)

__PHP Hooks__
- [request](#php_hook_request)
- [prepending, appending form content](#adding_content_prepending_appending_form)
- [before_item, after_item](#adding_item_wrapper)
- [wp_mail/param](#php_hook_wp_mail_param)
- [wp_mail/sent](#php_hook_wp_mail_sent)



__JS Hooks__

- [sent](#js_hook_sent)
- [success_selector](#js_hook_success_selector)


<a id="add_a_form"></a>
## Add a Form

```php
new Form( array(
	'form_id' => '{form_id}',
	'form_group' => '{form_group}',
	'form_attrs' => array(
		'action' => '',
		'method' => 'get',
		//'enctype' => false, // default is "multipart/form-data" when method is post
		'class' => array( 'my-form' ),
		'role' => 'form',
		'aria_label' => 'My Form',
		'return' => array( 'fields' ), // what to return/show on successfull validated form request, array( '' ) returns nothing
		// add more
	),
	'echo' => true,
	// add your own parameters, they will accessible in some hooks
) );
```
This adds a basic form…

```html
<form class="{class}" role="{role}" method="{method}" action="{action}" data-form-id="{form_id}" data-form-unique-id="{form_unique_id}" aria-label="{aria_label}">

	<input type="hidden" name="form_id" value="{form_id}" />

	<!-- Use a filter to add form items -->

</form>
```

### Add Email Form
```php
new Form( array(
	'form_id' => '{form_id}',
	'form_group' => '{form_group}',
	'form_attrs' => array(
		'action' => './',
		'method' => 'post',
		//'enctype' => false, // default is "multipart/form-data" when method is post
		'class' => array( 'my-form' ),
		'role' => 'form',
		'aria_label' => 'My Form',
		'return' => array( '' ), // what to return/show on successfull validated form request, array( '' ) returns nothing
		// add more
	),
	'email' => array(
		'status' => true,
		//'email_header' => array( 'Content-type: text/html' ),
		'email_to' => 'mail@johannheyne.de',
		'email_from' => '{email}',
		'email_from_name' => '{surename}',
		'email_subject' => 'Test from {surename}',
		'email_body' => '{surename} {email}',
	),
	'echo' => true,
	// add your own parameters, they will accessible in some hooks
) );
```

<a id="form_messages"></a>
## Define Form Messages

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

<a id="adding_containers"></a>
## Adding Container

Adds a div width given attributes

```
class/Form/containers
class/Form/containers/form_id=
class/Form/containers/form_group=
```

```php
add_filter( 'class/Form/containers/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'id' => '{container_id}',
		'container' => true,
		'attrs' => array(
			'class' => 'my-class',
		),
		'pos_key' => '{container_id}',
		'pos' => 10,
	);

	return $items;

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
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'fieldset',
		'legend' => 'My Fieldset',
		'id' => '{fieldset_id}',
		'attrs_field' => array(
			'class' => 'my-class',
		),
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

```
class/Form/items
class/Form/items/form_id=
class/Form/items/form_group=
```

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		// Field Parameters

		/* all fields have a template property
		'template' => array(
			'{label}',
			'{description}',
			'{before_field}',
			'{field}',
			'{after_field}',
			'{validation}',
		),
		*/
	);

	return $items;

}, 10, 2 );
```

### Filter: Field Template Data


```php
add_filter( 'class/Form/do_field_template/data', function( $data, $field ) {


	return $data;
});

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
		'attrs_elem' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
			'placeholder' => '',
		),
		'required' => false,
		'container_id' => '{container_id}',
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



<a id="field_search"></a>
### Search

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'search',
		'label' => '{label_text}',
		'attrs_label' => array(),
		'attrs_elem' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
			'placeholder' => '',
		),
		'required' => false,
		'container_id' => '{container_id}',
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



<a id="field_date"></a>
### Date

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'date',
		'label' => '{label_text}',
		'attrs_label' => array(),
		'attrs_elem' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
			'placeholder' => '',
		),
		'required' => false,
		'container_id' => '{container_id}',
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



<a id="field_textarea"></a>
### Textarea

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 't',
		'label' => '{label_text}',
		'attrs_label' => array(),
		'attrs_elem' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
			'placeholder' => '',
		),
		'required' => false,
		'container_id' => '{container_id}',
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



<a id="field_radio"></a>
### Radio

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

$items[] = array(
	'type' => 'radio',
	'label' => 'Label',
	'before_field' => 'Before Field',
	'after_field' => 'After Field',
	'before_checkbox' => 'Before Radio Main', // inherit
	'after_checkbox' => 'After Radio Main', // inherit
	'description' => 'Description',
	'attrs_label' => array(),
	'attrs_elem' => array(),
	'attrs_field' => array(
		'name' => 'radio',
		//'checked' => 'checked', // inherit
	),
	'radio' => array(
		array(
			'attrs_field' => array(
				'value' => '1',
				//'checked' => 'checked',
			),
			//'before_radio' => 'Before Checkbox 1',
			//'after_radio' => 'After Checkbox 1',
		),
		array(
			'attrs_field' => array(
				'value' => '2',
				'checked' => 'checked',
			),
			'before_radio' => 'Before Radio 2',
			'after_radio' => 'After Radio 2',
		)
	),
	'radio_layout' => 'align-vertical', // align-horizontal
	//'container_id' => '{container_id}',
	//'fieldset_id' => '{fieldset_id}',
	//'required' => true,
	/*'validation' => function( $value ) {

		if ( $value !== 'on' ) {

			$message_keys = array(
				'field' => array( 'This field is required.' ),
			);

			return $message_keys;
		}

	},*/
	'pos' => 10,
);

return $items;

}, 10, 2 );
```



<a id="field_checkbox"></a>
### Checkbox

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'checkbox',
		'label' => '{label_text}',
		'before_field' => 'Before',
		'after_field' => 'After',
		'custom_checkbox' => '<label class="custom-checkbox" for="{field_name}"></label>',
		'before_checkbox' => false,
		'after_checkbox' => false,
		'attrs_label' => array(),
		'attrs_elem' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
			'placeholder' => '',
		),
		'required' => false,
		'container_id' => '{container_id}',
		'fieldset_id' => '{fieldset_id}',
		'pos' => 10,
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

#### Filter: Before and After Checkbox HTML

```
class/Form/before_checkbox
class/Form/before_checkbox/form_group=
class/Form/before_checkbox/form_id=
```

```php
add_filter( 'class/Form/before_checkbox/form_id={form_id}', function( $html, $param ) {

	//$html = '<span>' . $p['before_checkbox'] . '</span>&nbsp;';

	return $html;

}, 10, 2 );

add_filter( 'class/Form/after_checkbox/form_id={form_id}', function( $html, $param ) {

	//$html = '&nbsp;<span>' . $p['after_checkbox'] . '</span>';

	return $html;

}, 10, 2 );
```


<a id="field_checkboxes"></a>

### Checkboxes

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

$items[] = array(
	'type' => 'checkboxes',
	'label' => 'Label',
	'before_field' => 'Before Field',
	'after_field' => 'After Field',
	'before_checkbox' => 'Before Checkbox Main', // inherit
	'after_checkbox' => 'After Checkbox Main', // inherit
	'description' => 'Description',
	'attrs_label' => array(),
	'attrs_elem' => array(),
	'attrs_field' => array(
		'name' => 'checkboxes',
		//'checked' => 'checked', // inherit
	),
	'checkboxes' => array(
		array(
			'attrs_field' => array(
				'value' => '1',
				//'name' => false, // inherit pattern name[]
				//'checked' => 'checked',
			),
			//'before_checkbox' => 'Before Checkbox 1',
			//'after_checkbox' => 'After Checkbox 1',
		),
		array(
			'attrs_field' => array(
				'value' => '2',
				'name' => 'custom_name',
				'checked' => 'checked',
			),
			'before_checkbox' => 'Before Checkbox 2',
			'after_checkbox' => 'After Checkbox 2',
		)
	),
	'checkbox_layout' => 'align-vertical', // align-horizontal
	//'container_id' => '{container_id}',
	//'fieldset_id' => '{fieldset_id}',
	//'required' => true,
	/*'validation' => function( $value ) {

		if ( $value !== 'on' ) {

			$message_keys = array(
				'field' => array( 'This field is required.' ),
			);

			return $message_keys;
		}

	},*/
	'pos' => 10,
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
		'attrs_elem' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
			'placeholder' => '',
		),
		'required' => false,
		'container_id' => '{container_id}',
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
		'attrs_elem' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
			'placeholder' => '',
		),
		'required' => false,
		'container_id' => '{container_id}',
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





<a id="field_switch_toggle"></a>
### Switch Toggle

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'switch_toggle',
		'label' => false,
		//'toggle' => '<span class="switch-toggle-on">{on}</span><span class="switch-toggle-off">{off}</span>',
		'label_on' => 'On',
		'label_off' => 'Off',
		'attrs_label' => array(),
		'attrs_toggle' => array(),
		'attrs_elem' => array(),
		'attrs_field' => array(
			'name' => 'switch_toggle',
			'value' => 'on',
			//'checked' => 'checked'
		),
		'container_id' => '{container_id}',
		'fieldset_id' => '{fieldset_id}',
		'required' => true,
		'pos' => 10,
	);

	return $items;

}, 10, 2 );
```



<a id="custom_field"></a>
### Custom

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'custom',
		'pos' => 10,
		'container_id' => '{container_id}',
		'fieldset_id' => '{fieldset_id}',
		'attrs_elem' => array(),
		'callback' => function() {

			return 'My Custom Content';
		},
	);

	return $items;

}, 10, 2 );
```



<a id="info"></a>
### Info

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'info',
		'pos' => 10,
		'content' => 'Hello World',
		'container_id' => '{container_id}',
		'fieldset_id' => '{fieldset_id}',
		'attrs_elem' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
		),
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
		'element' => 'input', // input, button
		'pos' => 10,
		'container_id' => '{container_id}',
		'fieldset_id' => '{fieldset_id}',
		'attrs_elem' => array(),
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



<a id="field_email"></a>
### Email

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'email',
		'label' => '{label_text}',
		'attrs_label' => array(),
		'attrs_elem' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
			'placeholder' => '',
		),
		'required' => false,
		'container_id' => '{container_id}',
		'fieldset_id' => '{fieldset_id}',
		'pos' => 10,
		'sanitize' => true,
	);

	return $items;

}, 10, 2 );
```



<a id="field_hidden"></a>
### Hidden

```php
add_filter( 'class/Form/items/form_id={form_id}', function( $items, $param ) {

	$items[] = array(
		'type' => 'hidden',
		//'label' => '{label_text}', // optional
		//'description' => '{label_text}', // optional
		'attrs_label' => array(),
		'attrs_field' => array(
			'name' => '{field_name}',
		),
		'container_id' => '{container_id}',
		'fieldset_id' => '{fieldset_id}',
		'pos' => 10,
		'sanitize' => true,
	);

	return $items;

}, 10, 2 );
```



<a id="detect_form_requests"></a>
## Detect Form Requests

```php
$form = new Form();

if ( $form->is_form_request( $form_id, true ) ) {

	// false: detect always
	// true: only if the fields has successfully passed the validation
}
```



<a id="do_email_check"></a>
## Filter: 'class/Form/wp_mail/do_email/check'

```php
add_filter( 'class/Form/wp_mail/do_email/check', function( $status, $param ) {

	// $param['request']
	// $param['p']
	// $status = false; // prevents email from sending

	return $status:

}, 10, 2);

```



## Form class workflow for setting up fields


```php

class Form {

	private $fieldtypes = array();

	__construct() {

		// inits all fieldtypes first
		$this->register_text_field();
		// add othe fields here

		// then gets the fieldtypes
		$this->fieldtypes = apply_filters( 'class/Form/add_fieldtype', $this->fieldtypes );
	}

	.register_text_field() {

		// defines fieldtype
		add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

			$fieldtypes['text'] = array(
				'default_param' => array(),
				'validation' => array(),
			);

			return $fieldtypes;
		});

		// defines field rendering
		add_filter( 'class/Form/get_fields_html/field_type=text', function( $html, $item ) {

			$p = array_replace_recursive( $this->fieldtypes['text']['default_param'], $item );

			return $html;
		}, 10, 2 );
	}

	.get_field( $html, $item ) {

		// render fields
		$html .= apply_filters( 'class/Form/get_fields_html/field_type=' $item['type'], $html, $item );
	}
}
```



## PHP Hooks

<a id="php_hook_request"></a>
### request

```
class/Form/request
class/Form/request/form_group=
class/Form/request/form_id=
class/Form/request/is_email
class/Form/request/is_save
```

```php
add_action( 'class/Form/request/form_id=my_form', function( $param ) {

	if ( ! empty( $param['has_massages'] ) {

		// Form not valide
	}

}, 10, 2 );
```

```php
add_filter( 'class/Form/request_value/form_id=my_form', function( $request, $param ) {


	return $request;

}, 10, 2 );
```



<a id="adding_content_prepending_appending_form"></a>
### form_prepend, form_append

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



<a id="adding_item_wrapper"></a>
### before_item, after_item

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



<a id="php_hook_wp_mail_param"></a>
### wp_mail/param

```php
add_filter( 'class/Form/wp_mail/param/form_id={form_id}', function( $param ) {

	// $param['to']
	// $param['subject']
	// $param['message']
	// $param['headers']
	// $param['attachements']

	return $param;

}, 10 );
```


<a id="php_hook_wp_mail_after"></a>
### wp_mail/after

```php
add_action( 'class/Form/wp_mail/after/form_id={form_id}', function( $param ) {

	// $param['request']
	// $param['mail_param']

}, 10 );
```



<a id="php_hook_wp_mail_sent"></a>
### wp_mail/sent

```php
add_action( 'class/Form/wp_mail/sent/form_id={form_id}', function() {


}, 10 );
```




<a id="js_hooks"></a>
## Javascript Hooks


### sent
<a id="js_hook_sent"></a>
```js
App.Actions.add( 'ToolForm', 'sent', function( param ) {

	// param.form_id
	// param.form_post_id
	// param.form_unique_id
	// param.form_offset

	// cals after form was sent
} );
```



### success_selector
<a id="js_hook_success_selector"></a>
```js
// Change the container to be removed on succsess and the succsess message placed after

App.Filters.add( 'ToolForm', 'success_selector', function( selector, param ) {

	// param.form_unique_id
	// param.form_id: form_id
	// param.form_post_id

	if ( 'kickstart' !== param.form_id ) {

		return selector;
	}

	selector = selector + ' ._kickstart';

	return selector;
});
```
