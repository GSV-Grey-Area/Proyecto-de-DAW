<?php
	function MainFunction($target, $column)
	{
		$fp = fopen($target, "r");
		$output = array();		
		$i = 0;
		
		while ($data = fgetcsv($fp, 1000, ";"))
		{
			$output[$i] = $data[$column];
			$i++;
		}
		
		fclose($fp);
		
		return $output;
	}
?>