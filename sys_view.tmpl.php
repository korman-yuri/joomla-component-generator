<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

class <component_name>View<view_name> extends Jview
{
	function display($tpl = null)
	{
		$items = $this->get('Items');
		$this->assignRef('items',  $items);	
		
		$item = $this->get('Item');
		$this->assignRef('item',  $item);
		
		JToolBarHelper::title(JText::_('<view_name>'),'sample');
		if(JRequest::getVar('layout')!='form')
		{
			JToolBarHelper::addNew();
			JToolBarHelper::deleteList();
			
		}else
		{
			JToolBarHelper::save();
			JToolBarHelper::cancel();
		}
		
		parent::display($tpl);
	}
	
	function allow($groups)
	{
		$return = false;
		jimport('joomla.user.helper');
		$user = &JFactory::getUser();	
		$user_groups = JUserHelper::getUserGroups($user->id);
		$user_group = current($user_groups);	
		
		foreach($groups as $group)
			if($group == $user_group)
				$return = true;
		return $return;
	}
}
?>