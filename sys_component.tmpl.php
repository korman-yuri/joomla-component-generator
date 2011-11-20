<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Create the controller
$controller = new <component_name>Controller();

// Perform the Request task
$controller->execute(JRequest::getVar('task', null, 'default', 'cmd'));
$controller->redirect();
?>