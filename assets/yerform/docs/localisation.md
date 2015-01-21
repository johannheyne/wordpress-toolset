[back to overview](../readme.md#styles)

Localisation
===============

	config()
		gets current set of text by language key into $textcurr and extended by defaults.
	
	set()
		if a field is used as email for the header, add validation require and its text

	messages()
		usage of $textcurr[ {message-key} ]
	
	return_label()
		usage of $textcurr['fields']
	
	form_buttons()
		usage of $this->textcurr['buttons']
	
	fieldset_begin()
		$this->textcurr['fieldsets'][ {name} ]['legend']
		$this->textcurr['fieldsets'][ {name} ]['require_info']
		$this->textcurr['fieldset']['require_info']['text']


[back to overview](../readme.md#styles)