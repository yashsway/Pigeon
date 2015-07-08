<?php
$GLOBALS['appMode'] = 0;
function autoloadClasses($className) {
    $filename = 'inc/class.' . $className . '.inc.php';
    if (is_readable($filename)) {
        require $filename;
    }
}
spl_autoload_register("autoloadClasses");
require_once('functions.php');
//fetchReports();
displayReports(true);
?>
