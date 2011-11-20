<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class <component_name>Controller extends JController
{
	/**
	 * Custom Constructor
	 */

	function display()
	{			
		$task = JRequest::getCmd('task');
		$view = JRequest::getVar('view');
		$cid = JRequest::getVar('cid');
		$id = JRequest::getVar('id');
		

		switch($task)
		{
			case "remove":
				$model = $this->getModel($view);
				foreach($cid as $id)
					$model->delete($id);
				break;
			case "save":
					$model = $this->getModel($view);
					$model->save();
				break;
			case "add":
				JRequest::setVar('layout','form');
				break;
		}
		parent::display();
	}
}
?>