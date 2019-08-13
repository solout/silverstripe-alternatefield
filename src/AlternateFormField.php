<?php
namespace SolutionsOutsourced\Fields;

use SolutionsOutsourced\Fields\AlternateField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\View\Requirements;
use SilverStripe\ORM\DataObjectInterface;


class AlternateFormField extends FormField {

    static $module_dir = '';  // This is initially set in _config.php

    private static $options = array();

    private static $empty_string = '-- Select --';

    private static $alternate_field_title = 'Please state:';

    protected $fieldSelectedValue = null;

    protected $fieldAlternateValue = null;

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
        $this->fieldAlternateValue = new TextField("{$name}[AlternateValue]", $this->getAlternateFieldTitle(), '', 255, $form);
        $this->fieldSelectedValue->setForm($form);
        $this->fieldAlternateValue->setForm($form);

        $this->fieldSelectedValue->setAttribute('data-for', $this->fieldAlternateValue->ID());
        $this->fieldAlternateValue->setAttribute('data-fieldid', $this->fieldAlternateValue->ID());
        $this->fieldSelectedValue->addExtraClass('selected-field');
        $this->fieldAlternateValue->addExtraClass('alternate-field');

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

    public function getAlternateFieldTitle() {
        return $this->config()->get('alternate_field_title');
    }

    function setForm($form) {
        $this->fieldSelectedValue->setForm($form);
        $this->fieldAlternateValue->setForm($form);
        return parent::setForm($form);
    }

    function setName($name){
        $this->fieldSelectedValue->setName("{$name}[SelectedValue]");
        $this->fieldAlternateValue->setName("{$name}[AlternateValue]");
        return parent::setName($name);
    }

    function setValue($val, $data = null) {
        $this->value = $val;
        if(is_array($val)) {
            $this->fieldSelectedValue->setValue($val['SelectedValue']);
            $this->fieldAlternateValue->setValue($val['AlternateValue']);
        } elseif($val instanceof AlternateField ) {
            $this->fieldSelectedValue->setValue($val->getSelectedValue());
            $this->fieldAlternateValue->setValue($val->getAlternateValue());
        }
    }

    /**
     * @return string
     */
    function Field($properties = array()) {

        Requirements::javascript('solout/silverstripe-alternate-field:js/AlternateFormField.js');
        Requirements::css('solout/silverstripe-alternate-field:css/AlternateFormField.css');
        return "<div class=\"fieldgroup AlternateFormField \">" .
            "<div class=\"fieldgroupField AlternateFormFieldSelectedValue\">" .
            $this->fieldSelectedValue->SmallFieldHolder() .
            "</div>" .
            "<div class=\"fieldgroupField AlternateFormFieldAlternateValue\">" .
            $this->fieldAlternateValue->SmallFieldHolder() .
            "</div>" .
            "</div>";
    }


    /**
     * SaveInto checks if set-methods are available and use them instead of setting the values directly. saveInto
     * initiates a new LinkField class object to pass through the values to the setter method.
     */
    function saveInto(DataObjectInterface $dataObject) {

        $fieldName = $this->name;
        if($dataObject->hasMethod("set$fieldName")) {
            $dataObject->$fieldName = DBField::create('AlternateField', array(
                "SelectedValue" => $this->fieldSelectedValue->Value(),
                "AlternateValue" => $this->fieldAlternateValue->Value()
            ));
        } else {
            $dataObject->$fieldName->setSelectedValue($this->fieldSelectedValue->Value());
            $dataObject->$fieldName->setAlternateValue($this->fieldAlternateValue->Value());
        }
    }



}
