<?php
$GLOBALS['appMode'] = 0;
function autoloadClasses($className) {
    $filename = 'inc/class.' . $className . '.inc.php';
    if (is_readable($filename)) {
        require $filename;
    }
}
//Execute Classes (Database Connection)
spl_autoload_register("autoloadClasses");
?>
