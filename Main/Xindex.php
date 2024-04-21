<?php 
	$canvasX = 1000;
	$canvasY = 1000;
	
	$output = "";
	$output .= 'var canvasX = ' . $canvasX . ';';
	$output .= 'var canvasY = ' . $canvasY . ';';
					
	$output .= 'document.getElementById("myCanvas").height = canvasX;
			document.getElementById("myCanvas").width = canvasY;

			const canvas = document.getElementById("myCanvas");
			const ctx = canvas.getContext("2d");

			ctx.beginPath();
			ctx.fillStyle = "#FF0000";';
			
	$x = GetCSV('Xtest', 0);
	$y = GetCSV('Xtest', 1);
	$elementSize = 50;
				
	function GetCSV($target, $column)
	{
		require_once('XGetCSV.php');
		$test = MainFunction($target . '.csv', $column);
		return $test;
	}
				
	$arrlength = count($x);
	
	for($i = 1; $i < $arrlength; $i++)
	{
		$output .= 'ctx.fillRect(' . $x[$i]*100 . ', ' . $canvasY - $y[$i] - $elementSize + 1 . ', ' . $elementSize . ', ' . $elementSize . ');';
		$output .= 'base_image = new Image();';
		$output .= 'base_image.src = "../Other/Concepts/[2024-01-07] Canvas test C 1/';
		$output .= '';
		if ($i < 11)
		{
			$output .= '0';
		}
		$output .= $i - 1 . '.png";';
		
		$output .= 'ctx.drawImage(base_image, ' . $x[$i]*100 . ', ' . $canvasY - $y[$i] - $elementSize + 1 . ', ' . $elementSize . ', ' . $elementSize . ');';
	}
	
	$output .= 'ctx.stroke();';
	
	echo $output;
?>