[back to overview](../README.markdown#assets)

YerParallax
===========

A basic jQuery plugin for parallax effects.

You can animate properties of an element. If you chain the parallax function <code>.parallax().parallax()</code> you can combine the animation of serveral properties. If you scroll down, the animation starts by default, if the elements parent pass the viewports bottom. You can shift that point via <code>startFromBottom:</code> The duration of the animation is depending on <code>yDist:</code>
````javascript
jQuery('.element').parallax({
  startFromBottom: 0,
  yDist: 300,
  property: 'left',
  propertySufix: 'px',
  valStart: -400,
  valEnd: 0,
  beforeClass: false, // string
  whileClass: false, // string
  afterClass: false, // string
});
````

This plugin cant bring parallax effects to iOS touch devices while scrolling.

[back to overview](../README.markdown#assets)