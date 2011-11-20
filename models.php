<?php
	/*
	$server='localhost';
	$user='admin';
	$password='1admin!';
	$db = 'crm_web';
	$prefix = 'clic_web_';
	$joomla_prefix = 'clic_';
	$component_name = 'com_clic';
	$component = 'clic';
	$global = 'mainframe';
	*/
	$connect= mysql_connect($server,$user,$password);
	mysql_query("set names 'utf8'");
	mysql_select_db($db,$connect);
	$resource = mysql_query('SHOW TABLES FROM '.$db);
	$setting;
	$i = 0;
	while($row=mysql_fetch_array($resource))
	{
		if(stripos($row[0],$prefix)===0)
		{
			$columns = null;
			$r_columns = mysql_query('SHOW COLUMNS FROM '.$row[0]);
			while($row_coumn=mysql_fetch_array($r_columns))
			{
				$columns[] = $row_coumn[0];
				if($row_coumn[3]=='PRI')
					$setting[$i]['primary'] = $row_coumn[0];
			}
			$setting[$i]['name'] 		= strtoupper(substr(str_ireplace($prefix,'',$row[0]),0,1)).substr(str_ireplace($prefix,'',$row[0]),1);
			$setting[$i]['columns'] 	= $columns;
			$setting[$i]['table'] 		= '#__'.substr($row[0],stripos($row[0],$joomla_prefix)+strlen($joomla_prefix));
			$i++;
		}
	}
	


	
	foreach($setting as $s)
	{
		$col_select = '';
		foreach($s['columns'] as $key=>$column)
		{
			if($key == 0)
				$col_select .= '`'.$column.'`'."\r\n";
			else
				$col_select .= "\t\t\t\t\t\t\t\t".',`'.$column.'`'."\r\n";
		}	
		$result = "<?php \r\n";
		$result .= "\tdefined( '_JEXEC' ) or die( 'Restricted access' );\r\n";
		$result .= "\tjimport( 'joomla.application.component.model' );\r\n";
		$result .= "\tclass ".$component."Model".$s['name']." extends JModel\r\n";
		$result .= "\t{\r\n";
		$result .= "\t\tvar \$_page  = null;\r\n";
		$result .= "\t\tfunction getItems()\r\n";
		$result .= "\t\t{\r\n";
		$result .= "\t\t\tglobal \$".$global.";\r\n";
		$result .= "\t\t\$limitstart = JRequest::getInt('limitstart',0);\r\n";
		$result .="\t\t\$limit = JRequest::getInt('limit',20);\r\n";
		$result .= "\t\t\t\$query = 'SELECT ".$col_select." \t\t\t\t\t\t\t\t FROM `".$s['table']."`';\r\n";
		$result .= "\t\t\t\$db = &JFactory::getDBO();\r\n";
		$result .= "\t\t\t\$this->_data = \$this->_getList(\$query,\$limitstart,\$limit);\r\n";
		$result .= "\t\t\t\$db->setQuery('SELECT count(*) FROM `".$s['table']."`');\r\n";
		$result .= "\t\t\t\$total = \$db->loadResult();\r\n";
		$result .= "\t\t\tjimport('joomla.html.pagination');\r\n";
		$result .= "\t\t\t\$this->_page = new JPagination(\$total, \$limitstart, \$limit);\r\n";
		$result .= "\t\t\treturn \$this->_data;\r\n";
		$result .= "\t\t}\r\n";
		$result .= "\t\tfunction getAll()\r\n";
		$result .= "\t\t{\r\n";
		$result .= "\t\t\tglobal \$".$global.";\r\n";
		$result .= "\t\t\t\$query = 'SELECT ".$col_select." \t\t\t\t\t\t\t\t FROM `".$s['table']."`';\r\n";
		$result .= "\t\t\t\$db = &JFactory::getDBO();\r\n";
		$result .= "\t\t\t\$this->_data = \$this->_getList(\$query);\r\n";
		$result .= "\t\t\treturn \$this->_data;\r\n";
		$result .= "\t\t}\r\n";		
		$result .= "\t\tfunction getItem()\r\n";
		$result .= "\t\t{\r\n";
		$result .= "\t\t\t\$id = JRequest::getVar('cid');\r\n";
		$result .= "\t\t\t\$table = \$this->getTable();\r\n";
		$result .= "\t\t\t\$table->load(\$id[0]);\r\n";
		$result .= "\t\t\treturn \$table;\r\n";
		$result .= "\t\t}\r\n";
		$result .= "\t\tfunction getPagination()\r\n";
		$result .= "\t\t{\r\n";
		$result .="\t\t\tif (is_null(\$this->_list) || is_null(\$this->_page))\r\n";
		$result .="\t\t\t{\r\n";
		$result .="\t\t\t\t\$this->getItems();\r\n";
		$result .="\t\t\t}\r\n";
		$result .="\t\t\treturn \$this->_page;\r\n";
		$result .= "\t\t}\r\n";
		$result .="\t\tfunction delete(\$id)\r\n";
		$result .="\t\t{\r\n";
		$result .="\t\t\t\$table = \$this->getTable();\r\n";
		$result .="\t\t\t\$table->delete(\$id);\r\n";
		$result .="\t\t}\r\n";
		$result .="\t\tfunction save()\r\n";
		$result .="\t\t{\r\n";
		$result .="\t\t\t\$table = \$this->getTable();\r\n";
		$result .="\t\t\tif(!\$table->bind( JRequest::get('post')))\r\n";
		$result .="\t\t\t\tJError::raiseError(500, \$table->getError() );\r\n";
		$result .="\t\t\tif(!\$table->store())\r\n";
		$result .="\t\t\t\tJError::raiseError(500, \$table->getError() );\r\n";
		$result .="\t\t}\r\n";
		$result .= "\t}\r\n";
		$result .= "?>";
		@mkdir(dirname(__FILE__).'\\'.$component_name.'\\backend\\models');
		file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\backend\\models\\'.strtolower($s['name']).'.php',$result);
		
		@mkdir(dirname(__FILE__).'\\'.$component_name.'\\frontend\\models');
		file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\frontend\\models\\'.strtolower($s['name']).'.php',$result);		
	}
?>