<?php
	/*
	$server='localhost';
	$user='admin';
	$password='1admin!';
	$db = 'crm_web';
	$prefix = 'clic_web_';
	$joomla_prefix = 'clic_';
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
			$setting[$i]['table'] 		= str_ireplace($joomla_prefix,'#__',$row[0]);
			$i++;
		}
	}

	foreach($setting as $s)
	{
		$result = "<?php\r\n";
		$result .= "class Table".$s['name']." extends JTable\r\n";
		$result .= "{\r\n";
		foreach($s['columns'] as $c)
			$result .= "\t".'var $'.$c.' = null;'."\r\n";
		$result .= "\tfunction __construct(&\$db)\r\n";
		$result .= "\t{\r\n";
		$result .= "\t\tparent::__construct( '".$s['table']."', '".$s['primary']."', \$db );\r\n";
		$result .= "\t}\r\n";
		$result .= "} \r\n?>";
		@mkdir(dirname(__FILE__).'\\'.$component_name.'\\backend\\tables\\');
		file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\backend\\tables\\'.strtolower($s['name']).'.php',$result);
	}
?>