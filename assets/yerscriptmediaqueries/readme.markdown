[back to overview](../README.markdown#assets)

YerScript MediaQueries
===============

Define multiple breakpoints and fire seperate code before and after the breakpoint.

```php
var mediaqueries = new YerScriptMediaQueries();
mediaqueries.init({
	'mediaqueries': [
		{
			breakpoint: 840,
			before: function(){

				// action before breakpoint
			},
			after: function(){

				// action after breakpoint
			}
		}
	],
});
`````

[back to overview](../README.markdown#assets)