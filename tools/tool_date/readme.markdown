[back to overview](../../README.markdown#initial-functionality)

Tool Date
===============================

- [Date](#date)
- [HTML Time](#html-time)
- [WordPress Date/Time Functions](#wordpress-datetime-functions)

----
### Date

````php
tool( array(
	'name' => 'tool_date',
	'param' => array(
		'date' => false, // optional, time() or '20211206'
		'format' => false, // optional, 'd.m.Y'
		'local' => false, // optional, 'de_DE'
		'timezone' => false, // optional, 'Etc/GMT', 'Europe/Berlin'
		'date_formatter' => 'medium', // optional: none, short, medium, long, full
		'time_formatter' => 'none', // optional: none, short, medium, long, full
	)
));
````

#### Properties

|Property|Description|
|:---|:---|
|date|optional,<br> default is time(),<br>can be a Unixtimestamp or an date string from database|
|format|optional,<br> string 'Y.m.d H.m:i',<br>falls back to 'date_formatter' and 'time_formatter' settings|
|local|optional,<br> string 'de_DE',<br> default is $GLOBALS['toolset']['frontend_locale'] or get_locale()|
|timezone|optional,<br> string 'Europe/Berlin',<br> default is wp_timezone_string()|
|date_formatter|optional,<br> default is 'medium'|
|time_formatter|optional,<br> default is 'none'|

#### Formatters

|Key|date-formatted|time-formatted|
|:---|:---|:---|
|short|20.12.21|11:30|
|medium|20.12.2021|11:31:53|
|long|20. Dezember 2021|um 11:32:28 MEZ|
|full|Montag, 20. Dezember 2021|um 11:33:05 Mitteleuropäische Normalzeit|


----
### HTML Time

Returns…
````html
<time class="_date" datetime="2021-12-20T10:54:49+00:00">20.12.2021</time>`
````
````php
tool( array(
	'name' => 'tool_html_time',
	'param' => array(
		'field' => false, // optional, ACF date field value lieke '20211206'
		'date' => false, // optional, can be a Unixtimestamp or an date string from database
		'format' => false, // optional, 'd.m.Y H:mm:ss' // https://unicode-org.github.io/icu/userguide/format_parse/datetime/
		'local' => false, // optional, 'de_DE'
		'timezone' => false, // optional, default: wp_timezone_string(), date_default_timezone_get(), 'Europe/Berlin'
		'date_formatter' => 'medium', // optional: none, short, medium, long, full
		'time_formatter' => 'none', // optional: none, short, medium, long, full
	)
));

// Returns: <time class="_date" datetime="2021-12-20T10:54:49+00:00">20.12.2021</time>
````



## WordPress Date/Time Functions

- [get_post_time()](https://developer.wordpress.org/reference/functions/get_post_time/) <br>Retrieve the time at which the post was written.
- [get_date_from_gmt()](https://developer.wordpress.org/reference/functions/get_date_from_gmt/) <br>Given a date in UTC or GMT timezone, returns that date in the timezone of the site.
- [get_gmt_from_date()](https://developer.wordpress.org/reference/functions/get_gmt_from_date/) <br>Given a date in the timezone of the site, returns that date in UTC.
- [get_post_datetime()](https://developer.wordpress.org/reference/functions/get_post_datetime/) <br>Retrieve post published or modified time as a DateTimeImmutable object instance.
- [get_post_timestamp()](https://developer.wordpress.org/reference/functions/get_post_timestamp/) <br>Retrieve post published or modified time as a Unix timestamp.
- [wp_timezone()](https://developer.wordpress.org/reference/functions/wp_timezone/) <br>Retrieves the timezone from site settings as a `DateTimeZone` object.<br>[Timezones Wikipedia](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones)



----
[back to overview](../../README.markdown#initial-functionality)
