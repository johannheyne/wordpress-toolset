[back to overview](../README.markdown#assets)

YerRespFontSize
===========

Scales fontsize depending on element width. Set a reference fontsize by data attribut that behaves linear to the changes of the elments measured width.

The fontsize `"fs":"60"` is relative to fontsize of `60` pixels on `960` pixel element width.

The `"w":"margin"` defines the messurement of the elements with which can be `"inner"` width of the element or expand over `"padding"` or `"border"` or `"margin"`.

The `"fsmin":"16"` and `"fsmax":"100"` delimit the auto fontsize adaptation in pixels.

````html
<h1 data-rfz='{"fs":"60","w":"margin","fsmin":"16","fsmax":"100"}'>Headline</h1>
````

````javascript
var respfontsize = new YerRespFontSize();
respfontsize.init({
	'data_key': 'rfs',
	'blockwidth': 960, // reference element width for the fontsize
	'fontsize_min': 16,
	'resize_timeout': 250
});
````

[back to overview](../README.markdown#assets)