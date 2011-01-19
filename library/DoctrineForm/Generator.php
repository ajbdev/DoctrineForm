<?php
/**
 * The generator class for creating forms based on Doctrine models
 *
 * @package     DoctrineForm
 * @author      Andy Baird <andybaird@gmail.com>
 */

class DoctrineForm_Generator {
    /**
     * Model to be used for generating form
     * @var Doctrine_Record
     */
    protected $_model;

    /**
     * Generator constructor
     * @param string $model
     * @param Zend_Console_Getopt $options
     */
    public function __construct($model,Zend_Console_Getopt $options) {
        $this->_model = new $model();
        $mapper = new DoctrineForm_Mapper($this->_model);
        if (isset($options->i)) {
            $mapper->setUseIntelligentMapping(true);
        }
        if ($options->r) {
            $mapper->setSkipRelations(false);
        }
        $elements = $mapper->getElements();
        $builder = new DoctrineForm_Builder($model,$elements);
        if (isset($options->e)) {
            $builder->setFileExtension($options->getOption('file-extension'));
        }
        if (isset($options->c)) {
            $builder->setExtendedClass($options->getOption('extended-class'));
        }
        if (isset($options->p)) {
            $builder->setPath($options->getOption('path'));
        }
        if (isset($options->f)) {
            $builder->setNameStructure($options->getOption('form-name'));
        }
        $builder->generate();
    }
    
    
    
}