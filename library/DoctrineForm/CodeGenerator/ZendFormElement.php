<?php 
/**
 * Generate Zend_Form_Element based on a real Zend_Form_Element
 *
 * @package     DoctrineForm
 * @author      Andy Baird <andybaird@gmail.com>
 */

class DoctrineForm_CodeGenerator_ZendFormElement extends Zend_CodeGenerator_Php_Abstract {
    protected $_classNamePrefix = 'Zend_Form_Element_';
    protected $_element;
    protected $_name;
    
    /**
     * Zend_Form_Element to model
     * @param Zend_Form_Element $element
     */
    public function setElement(Zend_Form_Element $element) {
        $this->_element = $element;
        return $this;
    }
    
    /**
     * Get set Zend_Form_Element
     */
    public function getElement()
    {
        return $this->_element;
    }
    
    /**
     * Generate code for this element
     */
    public function generate() {
        $element = $this->getElement();
        $output = '';
        $output .='$' . $element->getName()
                . ' = new ' . get_class($this->_element)
                . "('" . $element->getName() . "');" . self::LINE_FEED;
        
        if ($element instanceof Zend_Form_Element_Multiselect) {
            $options = $element->getMultiOptions();
            if (count($options)>0) {
                $output .= '$' . $element->getName();
                $j = 0;
                foreach ($element->getMultiOptions() as $option => $value) {
                    if ($j>0) {
                        $output .= self::LINE_FEED;
                        $output .= $this->__whitespace(strlen($element->getName())+1);
                    }
                    $output .= "->addMultiOption('{$option}')";
                    $j++;
                }
                $output .= ';' . self::LINE_FEED;
            }
        }
                
        $validators = $element->getValidators();
        if (count($validators)>0) {
            $output .= '$' . $element->getName();
            $j = 0;
            foreach ($validators as $validator) {
                if ($j>0) {
                    $output .= self::LINE_FEED;
                    $output .= $this->__whitespace(strlen($element->getName())+1);
                }
                $j++;
                $params = '';
                switch (get_class($validator)) {
                    case 'Zend_Validate_StringLength':
                        $params = "array('max' => " . $validator->getMax() . "," 
                                . "'min' => " . $validator->getMin() . ")";
                        break;
                    default:
                        break;
                }
                $output .= '->addValidator(new '
                         . get_class($validator) . "({$params}))";
            }
            $output .= ';';
        }
        $output .= self::LINE_FEED . '$this->addElement($' . $element->getName() .');' . self::LINE_FEED;
        $output .= self::LINE_FEED;
        return $output;
    }
    
    /**
     * Pad whitespace to string
     * @param integer $num
     */
    private function __whitespace($num) {
        $return = '';
        for ($i=0;$i<$num;$i++) {
            $return .= ' ';
        }
        return $return;
    }
    
    /**
     * setName()
     *
     * @param string $name
     * @return Zend_CodeGenerator_Php_Member_Abstract
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
}