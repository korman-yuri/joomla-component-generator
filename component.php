<?php
	$server='localhost';
	$user='root';
	$password='pass';
	$db = 'joomla17flot';
	$prefix = 'flot_fin_';
	$joomla_prefix = 'flot_';
	$component_name = 'com_fin';
	$component = 'fin';
	$global = 'mainframe';
	//fields
	/*$field_list[0] = 'exchange_id';
	$field_list[1] = 'sector_id';	
	$field_list[2] = 'company_id';
	
	$fields[0]['name'] = 'exchange_id';
	$fields[0]['table'] = 'flot_fin_exchanges';
	$fields[0]['fname'] = 'exchange_name';
	$fields[0]['fid'] = 'exchange_id';
	$fields[0]['type'] = 'select';	

	$fields[1]['name'] = 'sector_id';
	$fields[1]['table'] = 'flot_fin_sectors';
	$fields[1]['fname'] = 'sector_name';
	$fields[1]['fid'] = 'sector_id';
	$fields[1]['type'] = 'select';

	$fields[2]['name'] = 'company_id';
	$fields[2]['table'] = 'flot_fin_companies';
	$fields[2]['fname'] = 'company_name';
	$fields[2]['fid'] = 'company_id';
	$fields[2]['type'] = 'select';
	*/
	@mkdir(dirname(__FILE__).'\\'.$component_name);	@mkdir(dirname(__FILE__).'\\'.$component_name.'\\frontend');
	@mkdir(dirname(__FILE__).'\\'.$component_name.'\\backend');
	$result = file_get_contents(dirname(__FILE__).'\\sys_component.tmpl.php');
	$result = str_ireplace('<component_name>',$component,$result);
	file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\backend\\'.$component.'.php', $result);
	file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\frontend\\'.$component.'.php', $result);
	//sys_controller.tmpl.php
	$result = file_get_contents(dirname(__FILE__).'\\sys_controller.tmpl.php');
	$result = str_ireplace('<component_name>',$component,$result);
	file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\backend\\controller.php', $result);
	file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\frontend\\controller.php', $result);

	
	require_once(dirname(__FILE__).'\\defaults.php');
	require_once(dirname(__FILE__).'\\forms.php');
	require_once(dirname(__FILE__).'\\models.php');
	require_once(dirname(__FILE__).'\\tables.php');
	require_once(dirname(__FILE__).'\\views.php');
	//sys_component.tmpl.xml
	$result = file_get_contents(dirname(__FILE__).'\\sys_component.tmpl.xml');
	$result = str_ireplace('<component_name>',$component,$result);
	
	$result = str_ireplace('<sub_menu>',$submenu,$result);
	file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\'.$component.'.xml', $result);
	file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\backend\\'.$component.'.xml', $result);
	file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\frontend\\'.$component.'.xml', $result);
?>