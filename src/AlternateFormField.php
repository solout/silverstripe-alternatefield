<?php
namespace SolutionsOutsourced\Fields;

use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\View\Requirements;

class AlternateFormField extends FormField {

    static $module_dir = '';  // This is initially set in _config.php

    private static $options = array();

    private static $empty_string = '-- Select --';

    private static $alternative_field_title = 'Please state:';

    protected $fieldSelectedValue = null;

    protected $fieldAlternativeValue = null;

    function __construct($name, $title = null, $options = [], $type = 'DropdownField', $value = null, $form = null) {
        if ($type === 'OptionsetField') {
            $this->fieldSelectedValue = OptionsetField::create("{$name}[SelectedValue]", '', $options, '', $form);
        } else if ($type === 'CheckboxsetField') {
            $this->fieldSelectedValue = CheckboxSetField::create("{$name}[SelectedValue]", '', $options, '', $form);
        } else {
            $this->fieldSelectedValue = DropdownField::create("{$name}[SelectedValue]", '', $options, '', $form);
        }

        if($this->getEmptyString()) {
            $this->fieldSelectedValue->setEmptyString($this->getEmptyString());
        }
        $this->fieldAlternativeValue = new TextField("{$name}[AlternativeValue]", $this->getAlternativeFieldTitle(), '', 255, $form);
        $this->fieldSelectedValue->setForm($form);
        $this->fieldAlternativeValue->setForm($form);

        $this->fieldSelectedValue->setAttribute('data-for', $this->fieldAlternativeValue->ID());
        $this->fieldAlternativeValue->setAttribute('data-fieldid', $this->fieldAlternativeValue->ID());
        $this->fieldSelectedValue->addExtraClass('selected-field');
        $this->fieldAlternativeValue->addExtraClass('alternate-field');

        parent::__construct($name, $title, $value, $form);
    }

    public function setOptions($options)
    {
        return $this->fieldSelectedValue->setOptions($options);
    }

    public function getOptions() {
        $options = $this->config()->get('options');
        return array_combine($options, $options);
    }

    public function getEmptyString() {
        return $this->config()->get('empty_string');
    }

    public function getAlternativeFieldTitle() {
        return $this->config()->get('alternative_field_title');
    }

    function setForm($form) {
        $this->fieldSelectedValue->setForm($form);
        $this->fieldAlternativeValue->setForm($form);
        return parent::setForm($form);
    }

    function setName($name){
        $this->fieldSelectedValue->setName("{$name}[SelectedValue]");
        $this->fieldAlternativeValue->setName("{$name}[AlternativeValue]");
        return parent::setName($name);
    }

    function setValue($val, $data = null) {
        $this->value = $val;
        if(is_array($val)) {
            $this->fieldSelectedValue->setValue($val['SelectedValue']);
            $this->fieldAlternativeValue->setValue($val['AlternativeValue']);
        } elseif($val instanceof AlternateField) {
            $this->fieldSelectedValue->setValue($val->getSelectedValue());
            $this->fieldAlternativeValue->setValue($val->getAlternativeValue());
        }
    }

    /**
     * @return string
     */
    function Field($properties = array()) {
        Requirements::javascript(self::$module_dir . '/js/AlternativeFormField.js');
        Requirements::css(self::$module_dir . '/css/AlternativeFormField.css');
        return "<div class=\"fieldgroup AlternativeFormField \">" .
            "<div class=\"fieldgroupField AlternativeFormFieldSelectedValue\">" .
            $this->fieldSelectedValue->SmallFieldHolder() .
            "</div>" .
            "<div class=\"fieldgroupField AlternativeFormFieldAlternativeValue\">" .
            $this->fieldAlternativeValue->SmallFieldHolder() .
            "</div>" .
            "</div>";
    }


    /**
     * SaveInto checks if set-methods are available and use them instead of setting the values directly. saveInto
     * initiates a new LinkField class object to pass through the values to the setter method.
     */
    function saveInto(\SilverStripe\ORM\DataObjectInterface $dataObject) {

        $fieldName = $this->name;
        if($dataObject->hasMethod("set$fieldName")) {
            $dataObject->$fieldName = DBField::create('AlternateField', array(
                "SelectedValue" => $this->fieldSelectedValue->Value(),
                "AlternativeValue" => $this->fieldAlternativeValue->Value()
            ));
        } else {
            $dataObject->$fieldName->setSelectedValue($this->fieldSelectedValue->Value());
            $dataObject->$fieldName->setAlternativeValue($this->fieldAlternativeValue->Value());
        }
    }



}
