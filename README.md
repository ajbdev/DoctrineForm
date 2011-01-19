# DoctrineForm
Author: Andy Baird <andybaird@gmail.com>

DoctrineForm is a way to automatically generate Zend_Form classes based on a Doctrine model. DoctrineForm does this by introspecting your Doctrine model columns and generating form elements for them.

## Installation
The default file structure follows standard Zend Framework conventions  and is as follows:
  /library/DoctrineForm
  /scripts/

You would also have your other ZF folders in this base directory as well, e.g:
  /application/
  /public/
  .. etc

You should replace bootstrap.php in the scripts/ folder with your own script bootstrap. 

## Usage

    Usage: doctrine-form.php [ options ] ... [ model ] ...
    --all|-a                     Generate forms for all models
    --intelligent-mapping|-i     Use column name to guess form element type
    --generate-relations|-r      Generate a form element for foreign key columns
    --form-name|-f <string>      Specify the format of the form class name
    --file-extension|-e <string> Set a file extension other than .php
    --extended-class|-c <string> Extend a class other than Zend_Form
    --path|-p <string>           Specify a path to place generated files

## Examples
    php doctrine-form.php User
Generate a form for User. By default the form will be placed in the same directory as the scripts folder.

    php doctrine-form.php -a -p ../application/forms/
This will generate forms for all models and place them in the /application/forms directory

    php doctrine-form.php -c MyCustomForm User Comment Post
This will generate forms for the User, Comment, and Post model's and extends MyCustomForm instead of Zend_Form

## Intelligent mapping
Intelligent mapping will take a best guess approach when selecting the element for a given column that take's the column's name into account.
As an example, if you have a column named "email", it will automatically apply e-mail validators the text field element. 

## Licensing
Copyright (C) 2011 by Andy Baird <andybaird@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.


