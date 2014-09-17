[back to overview](../README.markdown#assets)

YerRespFontSize
===========

Scales fontsize depending on element width. Set a reference fontsize by data attribut that behaves linear to the changes of the elments width. The fontsize of `data-rfz="60"` is relative to fontsize of `60` pixels on `960` pixel element width.

````html
<h1 data-rfz="60">Headline</h1>
````

````javascript
var repfontsize = new YerRespFontSize();
repfontsize.init({
	'data_key': 'rfs',
	'blockwidth': 960, // reference element width for the fontsize
	'fontsize_min': 16,
	'resize_timeout': 250,
});
````

[back to overview](../README.markdown#assets)