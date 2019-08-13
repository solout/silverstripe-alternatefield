<?php
namespace SolutionsOutsourced\Fields;

use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBComposite;
use SilverStripe\ORM\FieldType\DBField;

class AlternateField extends DBComposite
{

    /**
     * @var string The selected value for this field
     */
    protected $selectedValue;

    /**
     * @var string The alternate value for this field
     */
    protected $alternateValue;

    /**
     * @var boolean Is this record changed or not?
     */
    protected $isChanged = false;

    private static $composite_db = array(
        'SelectedValue'    => 'Varchar(255)',
        'AlternateValue' => 'Varchar(255)',
    );
//
//    public function setValue($value, $record = null, $markChanged = true)
//    {
//
//        $select = $this->name . 'SelectedValue';
//
//
//        if ($value instanceof AlternateField && $value->exists())
//        {
//
//            $this->setSelectedValue($value->getSelectedValue(), $markChanged);
//            $this->setAlternateValue($value->getAlternateValue(), $markChanged);
//            if ($markChanged)
//            {
//                $this->isChanged = true;
//            }
//
//        }
//        else if ($record && is_array($record) && (isset($record[$this->name . 'SelectedValue']) || isset($record[$this->name . 'AlternateValue'])))
//        {
////            var_dump($record[$this->name . 'SelectedValue']);
////            exit();
//            $this->setSelectedValue((isset($record[$this->name . 'SelectedValue'])) ? $record[$this->name . 'SelectedValue'] : null, $markChanged);
//            $this->setAlternateValue((isset($record[$this->name . 'AlternateValue'])) ? $record[$this->name . 'AlternateValue'] : null, $markChanged);
//            if ($markChanged)
//            {
//                $this->isChanged = true;
//            }
//
//        }
//        else if (is_array($value))
//        {
//            if (array_key_exists('SelectedValue', $value))
//            {
//                $this->setSelectedValue($value['SelectedValue'], $markChanged);
//            }
//            if (array_key_exists('AlternateValue', $value))
//            {
//                $this->setAlternateValue($value['AlternateValue'], $markChanged);
//            }
//            if ($markChanged)
//            {
//                $this->isChanged = true;
//            }
//
//        }
//
//        // if not using the default then set alternate to null
//        if ($this->getSelectedValue() != 'Other')
//        {
//            $this->setAlternateValue(null);
//        }
//
//    }
//
//    public function requireField()
//    {
//        $fields = $this->compositeDatabaseFields();
//        if ($fields)
//        {
//            foreach ($fields as $name => $type)
//            {
//                DB::requireField($this->tableName, $this->name . $name, $type);
//            }
//        }
//
//    }
//
//    public function writeToManipulation(&$manipulation)
//    {
//        if ($this->getSelectedValue())
//        {
//            $manipulation['fields'][$this->name . 'SelectedValue'] = $this->prepValueForDB($this->getSelectedValue());
//        }
//        else
//        {
//            $manipulation['fields'][$this->name . 'SelectedValue'] = DBField::create_field('Varchar', $this->getSelectedValue())->nullValue();
//        }
//
//        if ($this->getAlternateValue())
//        {
//            $manipulation['fields'][$this->name . 'AlternateValue'] = $this->prepValueForDB($this->getAlternateValue());
//        }
//        else
//        {
//            $manipulation['fields'][$this->name . 'AlternateValue'] = DBField::create_field('Varchar', $this->getAlternateValue())->nullValue();
//        }
//    }
//
//    public function addToQuery(&$query)
//    {
//        parent::addToQuery($query);
//    }
//
//    public function compositeDatabaseFields()
//    {
//        return static::$composite_db;
//    }
//
//    public function isChanged()
//    {
//        return $this->isChanged;
//    }
//

//
//    public function getSelectedValue()
//    {
//        return $this->selectedValue;
//    }
//
//    public function setSelectedValue($selectedValue, $markChanged = true)
//    {
//
//        $this->isChanged     = $markChanged;
//        $this->selectedValue = $selectedValue;
//    }
//
//    public function getAlternateValue()
//    {
//        return $this->alternateValue;
//    }
//
//    public function setAlternateValue($alternateValue, $markChanged = true)
//    {
//        $this->isChanged        = $markChanged;
//        $this->alternateValue = $alternateValue;
//    }
//
//    public function scaffoldFormField($title = null, $params = null)
//    {
//        $field = new AlternateFormField($this->name);
//        return $field;
//    }
//
//    public function getActualValue()
//    {
//        if ($this->selectedValue == 'Other')
//        {
//            return $this->alternateValue;
//        }
//        else if ($this->selectedValue)
//        {
//            return $this->selectedValue;
//        }
//        else
//        {
//            return null;
//        }
//
//    }
//
//    public function __toString()
//    {
//        return (String) $this->getActualValue();
//    }
//
//    public function forTemplate()
//    {
//        return (String) $this->getActualValue();
//    }

    public function getValue()
    {
        if (!$this->exists()) {
            return null;
        }

        if ($this->getSelectedValue() === 'Other') {
            return $this->getAlternateValue();
        }

        return $this->getSelectedValue();
    }

    /**
     * @return string
     */
    public function getSelectedValue()
    {
        return $this->getField('SelectedValue');
    }

    /**
     * @param string $currency
     * @param bool $markChanged
     * @return $this
     */
    public function setSelectedValue($value, $markChanged = true)
    {
        $this->setField('SelectedValue', $value, $markChanged);
        return $this;
    }

    /**
     * @return float
     */
    public function getAlternateValue()
    {
        return $this->getField('AlternateValue');
    }

    /**
     * @param mixed $amount
     * @param bool $markChanged
     * @return $this
     */
    public function setAlternateValue($value, $markChanged = true)
    {
        $this->setField('AlternateValue', $value, $markChanged);
        return $this;
    }

    public function exists()
    {
        return ($this->getSelectedValue() !== null || $this->getAlternateValue() !== null);
    }
}
