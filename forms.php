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
		$result = '<?php $db=&JFactory::getDBO();?>'."\r\n";
		$result .= "<form id=\"adminForm\" name=\"adminForm\" method=\"post\"action=\"index.php?option=".$component_name."&view=".strtolower($s['name'])."\"  class=\"form-validate\">\r\n";
		$result .= "<div class=\"width-60 fltlft\">";
		$result .= "\t<fieldset class=\"adminform\">\r\n";
		$result .= "<legend><?php echo JText::_('".$s['name']."');?></legend>";
		$result .= "<table class=\"formcontent\">";
		foreach($s['columns'] as $c)
		{
			if($s['primary']!=$c)
			{
				$result .= "\t\t<tr><td><label for=\"".strtolower($c)."\"><?php echo JText::_('".$c."');?></label></td></tr>\r\n";
				
				if(in_array(strtolower($c),$field_list))
				{
					
					foreach($fields as $f)
						if($f['name']==strtolower($c))
							switch($f['type'])
							{
								case "select":
									$result.='<?php $db->setQuery("SELECT '.$f['fid'].','.$f['fname'].' FROM '.$f['table'].'");?>'."\r\n";
									$result.='<?php $'.strtolower($c).'=$db->loadObjectList();?>';
									$result.='<tr><td><select id="'.strtolower($c).'" name="'.strtolower($c).'">'."\r\n";
									$result.='<option value="0">--<?php echo JText::_("select");?>--</option>'."\r\n";									
									$result.='<?php foreach($'.strtolower($c).' as $itm): ?>'."\r\n";
									$result.='<option value="<?php echo $itm->'.$f['fid'].';?>" <?php echo $itm->'.$f['fid'].'==$this->item->'.$c.'?"selected":"";?>><?php echo $itm->'.$f['fname'].';?></option>'."\r\n";
									$result.='<?php endforeach; ?>'."\r\n";
									$result.='</select></td></tr>'."\r\n";
									break;									
								case "textarea":
									$result.='<tr><td><textarea id="'.$f['name'].'" name="'.$f['name'].'"><?php echo $this->item->'.$c.';?></textarea></tr></td>'."\r\n";
									break;
							}
				}
				else
					$result .= "\t\t<tr><td><input type=\"text\" name=\"".strtolower($c)."\" value=\"<?php echo \$this->item->".$c.";?>\"/></td></tr>\r\n";
			}else
			{
				$result .= "\t\t<input type=\"hidden\" name=\"".strtolower($c)."\" value=\"<?php echo \$this->item->".$c.";?>\"/>";
			}
		}
		$result .= "</table>";
		$result .= "\t<input type=\"hidden\" name=\"task\" value=\"save\"/>";
		//$result .= "\t<input type=\"submit\" class=\"button\"/>\r\n";
		$result .= "\t</fieldset>\r\n";		
		$result .= "</div>";
		$result .= "</form>";
		@mkdir(dirname(__FILE__).'\\'.$component_name.'\\backend\\views\\'.strtolower($s['name']));
		@mkdir(dirname(__FILE__).'\\'.$component_name.'\\backend\\views\\'.strtolower($s['name']).'\\tmpl');
		file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\backend\\views\\'.strtolower($s['name']).'\\tmpl\\form.php',$result);
		
		@mkdir(dirname(__FILE__).'\\'.$component_name.'\\frontend\\views\\'.strtolower($s['name']));
		@mkdir(dirname(__FILE__).'\\'.$component_name.'\\frontend\\views\\'.strtolower($s['name']).'\\tmpl');
		file_put_contents(dirname(__FILE__).'\\'.$component_name.'\\frontend\\views\\'.strtolower($s['name']).'\\tmpl\\form.php',$result);		
	}

?>