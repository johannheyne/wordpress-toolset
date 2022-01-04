[back to overview](../../README.markdown#initial-functionality)

Tool Tabs2
===============================

### PHP

````php
$tabs = new ToolsetTabs2();

// adds a tab
$tabs->add_item(array(
	'label' => 'Tab Label',
	'callback' => function() {

		$html = '<p>Hello World</p>';

		return $html;
	}
));

echo $tabs->get_html();
````

[back to overview](../../README.markdown#initial-functionality)
