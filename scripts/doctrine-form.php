<?php
/**
 * The CLI script for generating Zend Forms
 *
 * @package     DoctrineForm
 * @author      Andy Baird <andybaird@gmail.com>
 */

chdir(dirname(__FILE__));

require_once "bootstrap.php";

try {
    $options = new Zend_Console_Getopt(array(
        'all|a'                         =>      'Generate forms for all models',
        'intelligent-mapping|i'         =>      'Use column name to guess form element type',
        'generate-relations|r'          =>      'Generate a form element for foreign key columns',
        'form-name|f=s'                 =>      'Specify the format of the form class name',
        'file-extension|e=s'            =>      'Set a file extension other than .php',
        'extended-class|c=s'            =>      'Extend a class other than Zend_Form',
        'path|p=s'                      =>      'Specify a path to place generated files'
    ));
    $options->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    echo $e->getUsageMessage();
    exit();
}
$models = $options->getRemainingArgs();

if (empty($models) && !isset($options->all)) {
    echo 'Must pass a Doctrine model as an argument. Try --help for more information.' . PHP_EOL;
}

if (isset($options->all)) {
    $models = Doctrine_Core::getLoadedModels();
}

foreach ($models as $model) {
    try {
        $dg = new DoctrineForm_Generator($model,$options);
    } catch (Exception $e) {
        echo $e->getMessage() . PHP_EOL;
        exit();
    }
}
