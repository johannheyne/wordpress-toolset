[back to overview](../../README.markdown#tools)

Tool Countdown
===============================

- [PHP](#php)
- [Translations](#translations)
- [HTML](#html)
- [SCSS](#scss)
- [JavaSkript](#javaskript)
	- [Actions](#actions)

### PHP

````php
$counter = new ToolCountdown(array(
	'event_time' => '2021-04-17T22:46:33+02:00', // ISO 8601 Date, date( 'c', time() );
	'caption' => 'My Caption',
	'label_days' => _x( 'Days', 'Layout "Countdown"', 'tool_translate' ),
	'label_hours' => _x( 'Hours', 'Layout "Countdown"', 'tool_translate' ),
	'label_minutes' => _x( 'Minutes', 'Layout "Countdown"', 'tool_translate' ),
	'label_secounds' => _x( 'Secounds', 'Layout "Countdown"', 'tool_translate' ),
	'expired_template' => function() { // default = false (counter remains zero)

		return '<p>expired</p>';
	},
));

echo $counter->get_counter();
````

### Translations

```php
$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
	'text' => 'Days', // use it like _x( 'Days', 'Layout "Countdown"', 'tool_translate' ),
	'context' => 'Layout "Countdown"',
	'param' => array(
		'text_default' => 'Days', // The default text if there is no translation
		'type' => 'text', // editing field type: 'text', 'textarea'
		'description' => '',
		'default_transl' => array(
			'de' => 'Tage',
			'en' => 'Days',
		),
	),
));

$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
	'text' => 'Hours', // use it like _x( 'Hours', 'Layout "Countdown"', 'tool_translate' ),
	'context' => 'Layout "Countdown"',
	'param' => array(
		'text_default' => 'Hours', // The default text if there is no translation
		'type' => 'text', // editing field type: 'text', 'textarea'
		'description' => '',
		'default_transl' => array(
			'de' => 'Stunden',
			'en' => 'Hours',
		),
	),
));

$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
	'text' => 'Minutes', // use it like _x( 'Minutes', 'Layout "Countdown"', 'tool_translate' ),
	'context' => 'Layout "Countdown"',
	'param' => array(
		'text_default' => 'Minutes', // The default text if there is no translation
		'type' => 'text', // editing field type: 'text', 'textarea'
		'description' => '',
		'default_transl' => array(
			'de' => 'Minuten',
			'en' => 'Minutes',
		),
	),
));

$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
	'text' => 'Secounds', // use it like _x( 'Secounds', 'Layout "Countdown"', 'tool_translate' ),
	'context' => 'Layout "Countdown"',
	'param' => array(
		'text_default' => 'Secounds', // The default text if there is no translation
		'type' => 'text', // editing field type: 'text', 'textarea'
		'description' => '',
		'default_transl' => array(
			'de' => 'Sekunden',
			'en' => 'Secounds',
		),
	),
));
```

### HTML
```html
<div class="countdown-root" data-countdown-event-time="2021-04-17T22:46:33+02:00">
	<table class="countdown-table">
		<caption class="countdown-caption">Caption</caption>
		<tbody>
			<tr class="countdown-numbers">
				<td class="js-countdown-d">10</td>
				<td class="js-countdown-h">09</td>
				<td class="js-countdown-m">15</td>
				<td class="js-countdown-s">08</td>
			</tr>
			<tr class="countdown-labels">
				<td>Days</td>
				<td>Hours</td>
				<td>Minutes</td>
				<td>Secounds</td>
			</tr>
		</tbody>
	</table>
</div>
```

### SCSS
```html
.countdown-root {

}

.countdown-table {

}

.countdown-caption {

}

.countdown-numbers {

}

.countdown-labels {

}
```

### JavaSkript

#### Actions

##### expired
Called after countdown reached 0

```js
App.Actions.add( 'ToolCountdown', 'expired', function( data ) {

	/*
	data.event_time
	data.obj_root
	data.obj_d
	data.obj_h
	data.obj_m
	data.obj_s
	*/
});
```

[back to overview](../../README.markdown#tools)
