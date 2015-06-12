<?php
//require_once('functions.php');


//this will load all classes you may need.  might as well use this to load any other pages as well. Um, include init.php on any page you want to have all this stuff load. 


function autoloadClasses($className) {
    $filename = 'inc/class.' . $className . '.inc.php';
    if (is_readable($filename)) {
        require $filename;
    }
}

spl_autoload_register("autoloadClasses"); //you should use dditional spl_autoload_register calls to compartmentalize any other groups of files you ned to autoload.

?>