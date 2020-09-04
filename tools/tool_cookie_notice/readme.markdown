[back to overview](../../README.markdown#initial-functionality)

Tool Cookie Notize
===============================

## Check Website for Cookie Notice Need
[https://2gdpr.com](https://2gdpr.com)


## YouTube
Link zu YouTube eigenen Datenschützerklärung: https://policies.google.com/privacy

Borlab Cookie Plugin Lösung:
![Bildschirmfoto 2020-07-07 um 16.58.33](Bildschirmfoto%202020-07-07%20um%2016.58.33.png)



## Todo
- Admin Option Page
	- General Message
	- Options
		- YouTube (accept globaly)
		- Google Maps (accept globaly)
		- CooKies (a list with ID, name, Message, Description)
		- Datenschutz Seite

- Frontend Message
	- General Message
	- Checkboxes for Options
	- Datenschutz Seite

### Gesetze

#### EU „Cookie-Richtlinie“
Sieht eine ausdrückliche Einwilligung des Nutzers vor, wurde von Deutschland aber gar nicht umgesetzt.

#### DE § 15 Abs.3 Telemediengesetz (TMG)
Der besagt, dass es ausreicht, den Nutzer zu unterrichten und auf ein Widerspruchsrecht hinzuweisen. Das kann in einem Cookie-Hinweis in einem Banner mit Link auf die Datenschutzerklärung erfolgen.

#### Die DSGVO
Seit Mai 2018 gilt allerdings die DSGVO. Alle Webseitenbetreiber, die Cookies nutzen, müssen seit 2018 Ihre Datenschutzeklärung neu formulieren. Die DSGVO verlangt, dass in der Datenschutzerklärung die Rechtsgrundlagen für das Verwenden von Cookies genannt werden.
Die DSGVO regelt aber dasProblem "Cookies" nicht ausdrücklich. Das sollte die ePrivacy-Verordnung tun, diese ist aber weiterhin nicht in Kraft.

#### EuGH-Urteil

1. Für wen ist das EuGH-Urteil wichtig?
- Für alle Webseitenbetreiber, die auf Ihrer Webseite Cookies im Bereich Tracking oder Marketing einsetzen.
- Für alle Webseitenbetreiber, die den Facebook Like Button oder ähnliche Buttons anderer Sozialer Netzwerke auf Ihren Seiten eingebunden haben.

2. Was ist entschieden
- Webseitenbetreiber sind neben Facebook immer mit verantwortlich für Datenschutzverstöße.
- Die ungefragte Übertragung von Nutzerdaten durch den Facebook Like-Button auf Webseiten verstößt gegen Datenschutzrecht.
- Wettbewerbsverbände können Webseiten, die Facebooks Like-Button ohne Einwilligungsmöglichkeit eingebunden haben, kostenpflichtig abmahnen.
- Für Cookies, die zu Tracking- oder Werbezwecken gesetzt werden, ist eine echte Einwilligung der Webseitenbesucher nötig. Ein Cookie-Hinweis-Banner reich nicht aus.

#### Todo



Informieren Sie Ihre Nutzer in einer aktuellen und vollständigen Datenschutzerklärung über alle eingesetzten Tools, PlugIns und Dienste. Nutzen Sie dafür den Profi Datenschutz-Generator von eRecht24 Premium.

### Howto

````php
	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_{name}' => array(

			)
		)
	);

	tool( array(
		'name' => 'tool_name',
		'param' => array(

		),
	);

````

[back to overview](../../README.markdown#initial-functionality)
