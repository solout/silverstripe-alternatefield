# Silverstripe Alternate-field Module

## Introduction
Simple dropdown field which displays a text field when 'Other' is selected. 

Based heavily on https://github.com/Leapfrognz/alternative-field with some restrictions relaxed

## How to use
Add the dropdown options to the _config/AlternateFormField.yml file.

```yml
AlternateFormField:
  empty_string: '-- Select --'
  alternative_field_title: 'Please state:'

```

## Requirements
SilverStripe 3.0 or higher is required.

Form Field Usage:

```php
    AlternateFormField::create($name, $title = null, $options = [], $type = 'DropdownField', $value = null, $form = null);

```

## Example
```php
<?php

    private static $db = array(
        'Honorific' => 'AlternateField'
    );


    public function getCMSFields() {
	
    	$fields = parent::getCMSFields();
		$fields->addFieldsToTab("Root.Name", array(
            AlternateFormField::create(
                $name = 'Honorific', 
                $title = 'Honorific', 
                $options = array(
                    'Mr' => 'Mr',
                    'Mrs' => 'Mrs',
                    'Ms' => 'Ms'
                ),
                $type = 'DropdownField',
                $value = 'Mrs',
                $form = $form
            ),
			TextField::create('FirstName', 'First name'),
			TextField::create('Surname', 'Last name')
		));

		return $fields;
	}
```
