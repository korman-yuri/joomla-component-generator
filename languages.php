<?php
	$server='localhost';
	$user='admin';
	$password='1admin!';
	$db = 'crm_web';
	$prefix = 'clic_web_';
	$joomla_prefix = 'clic_';
	$component_name = 'com_web';
	$component = 'clic';
	$language = 'ru-RU';
	
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
			$setting[$i]['table'] 		= str_ireplace($joomla_prefix,'#__',$row[0]);
			$i++;
		}
	}
	
	foreach($setting as $s)
	{
		foreach($s['columns'] as $c)
			$result .= strtoupper($c)."=\r\n";
			
		@mkdir(dirname(__FILE__).'\\languages\\');
		file_put_contents(dirname(__FILE__).'\\languages\\'.$language.'.ini',$result);
	}

?>