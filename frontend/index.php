<?php 

//errors controls
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/errorLog.txt');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: views/pages/login/home.php");
    exit;
}

require_once 'controllers/controller.template.php';

$template = new TemplateController();
$template->index();

