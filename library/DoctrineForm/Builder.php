<?php 
/**
 * Builds the actual PHP files based on the given elements
 *
 * @package     DoctrineForm
 * @author      Andy Baird <andybaird@gmail.com>
 */

class DoctrineForm_Builder {
        
    /**
     * Form class name to extend
     * @var string
     */
    protected $_extendedClass = 'Zend_Form';
    
    /**
     * Structure of name for forms.
     * Must contain `{*}` to insert model name. 
     * @var string
     */
    protected $_nameStructure = "{*}Form";
    
    /**
     * File extension to output
     * @var unknown_type
     */
    protected $_fileExtension = '.php';
    
    /**
     * Path to save file
     * @var string
     */
    protected $_path = '';
    
    /**
     * Fully parsed class name
     * @var string
     */
    protected $_className;

    /**
     * Form class for code generation
     * @var Zend_CodeGenerator_Class
     */
    protected $_class;
    
    /**
     * Name of Doctrine_Row model class
     * @var string
     */
    protected $_modelName;
    
    /**
     * Generated code
     * @var string
     */
    protected $_code = '';
    
    /**
     * Array of modeled Zend_Form_Element objects to introspect
     * @var array
     */
    protected $_elements = array();
    
    /**
     * Constructor
     * @param string $name
     * @param array $elements
     */
    public function __construct($name,$elements) {
        $this->_modelName = $name;
        $this->_elements = $elements;
        
    }
    
    /**
     * Set save file path
     * @param string $path
     */
    public function setPath($path) {
        $this->_path = $path;
    }
    
    /**
     * Set name structure for form class.
     * Requires `{*}` to insert model name
     * @param string $name
     */
    public function setNameStructure($name) {
        if (false === strpos($name,'{*}')) {
            throw new Zend_CodeGenerator_Exception('Name structure must include wildcard identifier `{*}`');
        }
        $this->_nameStructure = $name;
    }
    
    /**
     * Set outputing file extension
     * @param string $extension
     */
    public function setFileExtension($extension) {
        $this->_fileExtension = $extension;
    }
    
    /**
     * Set class to extend (default is Zend_Form)
     * @param string $class_name
     */
    public function setExtendedClass($class_name) {
        $this->_extendedClass = $class_name;
    }
    
    /**
     * Generate code
     */
    public function generate() {
        $this->_className = str_replace('{*}',$this->_modelName,$this->_nameStructure);
        $this->_createClass();
        foreach ($this->_elements as $el) {
            $code = new DoctrineForm_CodeGenerator_ZendFormElement();
            $code->setElement($el);
            $this->_code .= $code->generate();
        }
        
        $this->_class->getMethod('init')->setBody($this->_code);
        $file = new Zend_CodeGenerator_Php_File();
        $file->setClass($this->_class);
        $filename = $this->_path . $this->_className . $this->_fileExtension;
        echo 'Saving ' . $filename . PHP_EOL;
        file_put_contents($filename,$file->generate());
    }
    
    /**
     * Create extended form class shell
     */
    protected function _createClass()
    {
        $this->_class = new Zend_CodeGenerator_Php_Class();
        $class_docblock = new Zend_CodeGenerator_Php_Docblock(array(
            'shortDescription' => 'Form autogenerated by DoctrineForm',
            'tags'             => array(
                array(
                    'name'        => 'package',
                    'description' => '##PACKAGE##'  
                ),
                array(
                    'name'        => 'subpackage',
                    'description' => '##SUBPACKAGE##'   
                ),
                array(
                    'name'        => 'author',
                    'description' => '##NAME## <##EMAIL##>'
                ),
                array(
                    'name'        => 'version',
                    'description' => 'SVN: $Id:',
                )
            )
        ));
        
        $this->_class->setExtendedClass($this->_extendedClass)
                     ->setName($this->_className)
                     ->setDocblock($class_docblock);

        $method_docblock = new Zend_CodeGenerator_Php_Docblock(array(
            'shortDescription'  =>    'Overridden init method for constructing form elements',
            'tags'              =>    array(
                array(
                    'name'          =>    'return',
                    'description'   =>    'void',
                )
            )
        ));
        $method = new Zend_CodeGenerator_Php_Method();
        $method->setName('init')
               ->setDocblock($method_docblock);
        $this->_class->setMethod($method);
        
    }
}