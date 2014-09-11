[back to overview](../README.markdown#initial-functionality)

YerParallax
===========

a basic jQuery plugin for parallax effects

You can animate properties of an element. If you chain the parallax function <code>.parallax().parallax()</code> you can combine the animation of serveral properties. If you scroll down, the animation starts by default, if the elements parent pass the viewports bottom. You can shift that point via <code>startFromBottom:</code> The duration of the animation is depending on <code>yDist:</code>
<code><pre>jQuery('.element').parallax({
  startFromBottom: 0,
  yDist: 300,
  property: 'left',
  propertySufix: 'px',
  valStart: -400,
  valEnd: 0,
});
</pre></code>

This plugin cant bring parallax effects to mobile touch devices.

[back to overview](../README.markdown#initial-functionality)