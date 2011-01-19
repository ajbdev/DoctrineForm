<?php
/**
 * Map doctrine object properties to the Zend_Form equivelants as intelligently as possible
 *
 * @package     DoctrineForm
 * @author      Andy Baird <andybaird@gmail.com>
 */

class DoctrineForm_Mapper {
    /**
     * Doctrine Model to map
     * @var Doctrine_Record
     */
    protected $_model;
    
    /** 
     * Variable for using column names for mapping
     * @var boolean
     */
    protected $_useIntelligentMapping = false;
       
    /** 
     * Skip relations for foreign key columns
     * @var boolean
     */
    protected $_skipRelations = true;
        
    /**
     * Constructors
     * @param Doctrine_Record $model
     */
    public function __construct(Doctrine_Record $model) {
        $this->_model = $model;
    }
    
    /**
     * Skip relations for foreign key columns
     * @param boolean $boolean
     */
    public function setSkipRelations($boolean) {
        $this->_skipRelations = (boolean) $boolean;
    }
    
    /**
     * Use the column names to hint at form element types
     * @param boolean $boolean
     */
    public function setUseIntelligentMapping($boolean) {
        $this->_useIntelligentMapping = (boolean) $boolean;
    }
    
    /**
     * Get the Doctrine model
     * @return Doctrine_Record
     */
    public function getModel()
    {
        return $this->_model;
    }
    
    /**
     * Get the foreign key columns for the set model
     * @return array
     */
    public function getRelationColumns()
    {
        $relations = array();
        foreach ($this->getModel()->getTable()->getRelations() as $relation) {
            $relations[] = $relation->getLocalColumnName();
        }
        return $relations;
    }
    
    /**
     * Map doctrine record columns to elements
     * @return array
     */
    public function getElements() {
        $columns = $this->getModel()->getTable()->getColumns();
        $relationColumns = $this->getRelationColumns();
        $elements = array();
        foreach ($columns as $colname => $properties) {
            $element = null;
            if ($this->_skipRelations && in_array($colname,$relationColumns))
                continue;
            if ($this->_useIntelligentMapping) {
                if ($colname=='password' && $properties['type']=='string') {
                    $element = new Zend_Form_Element_Password($colname);
                }
            }
            if (is_null($element)) {
                switch ($properties['type']) {
                    case 'integer':
                        if (!(isset($property['primary']) && $property['primary']=='true')) {
                            $element = new Zend_Form_Element_Text($colname);
                            $element->addValidator(new Zend_Validate_Int());
                        }           
                        break;        
                    case 'string':
                        $element = new Zend_Form_Element_Text($colname);
                        break;
                    case 'boolean':
                        $element = new Zend_Form_Element_Checkbox($colname);
                        break;
                    case 'timestamp':
                        $element = new Zend_Form_Element_Text($colname);
                        $element->addValidator(new Zend_Validate_Date());
                        break;
                    case 'enum':
                        $element = new Zend_Form_Element_Multiselect($colname);
                        foreach ($properties['values'] as $value) {
                            $element->addMultiOption($value,$value);
                        }
                        break;
                    default:
                        break;
                }
            }
            if (isset($properties['length']) && $element) {
                $element->addValidator(new Zend_Validate_StringLength(array('max' => $properties['length'])));
            }
            if (isset($properties['notnull']) && $element && $properties['notnull']=='true') {
                $element->addValidator(new Zend_Validate_NotEmpty());
            }
            if ($this->_useIntelligentMapping) {
                if ($colname=='email' && $properties['type']=='string') {
                    $element->addValidator(new Zend_Validate_EmailAddress());
                }
            }
            if ($element)
                $elements[] = $element;
        }
        return $elements;
    }
}