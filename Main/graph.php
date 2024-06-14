<?php
	// GET parameters:
	$q = $_GET['q']; // intval
	$innerWidth = $_GET['innerWidth'] * 0.8;
	$innerHeight = $_GET['innerHeight'] * 0.6;
	
	// Possibly a matrix receiving all values... not sure...

	//// DATABASE SECTION ////
	// Connection setup:
	$con = mysqli_connect('localhost:3306', 'root', '', 'proyecto');
	if (!$con) {die('Could not connect: ' . mysqli_error($con));}
	
	// Data for selectors:
	$sql = 'DESCRIBE ' . $q . ';';
	$select0 = mysqli_query($con, $sql);
	$select1 = mysqli_query($con, $sql); // X axis columns
	$select2 = mysqli_query($con, $sql); // Y axis columns
	$select3 = mysqli_query($con, $sql); // For the filters.

	//// RENDERING SECTION ////
	$margin = 100;
	$xm = 10;
	$elementSize = 10;
	
	$output = '';
	
	// Field list:
	$fields = array(); 
	while($row = mysqli_fetch_array($select0)){$fields[] = $row['Field'];}
	$xAxis = $_GET['xAxis'] ?? $fields[3];
	$yAxis = $_GET['yAxis'] ?? $fields[4];
	
	// Selector 1:
	$output .= '<div style="margin-right: auto; margin-left: 100px;">';
	$output .= '<select name="Y" id="Y">';
	$i = 0;
	while($row = mysqli_fetch_array($select2))
	{
		if ($i != 0)
		{
			$output .= '<option value="' . $row['Field'] . '"';
			if ($row['Field'] == $yAxis) {$output .= ' selected';}
			$output .= '>' . $row['Field'] . '</option>';
		}
		$i++;
	}
	$output .= '</select>';
	$output .= '</div>';
	$output .= 'XXXXXXXXX';
	
	// Selector 2:
	$output .= '<div style="margin-left: auto; margin-right: 50px;  float: right;">';
	$output .= '<select name="X" id="X">';
	$i = 0;
	while($row = mysqli_fetch_array($select1))
	{
		if ($i != 0)
		{
			$output .= '<option value="' . $row['Field'] . '"';
			if ($row['Field'] == $xAxis) {$output .= ' selected';}
			$output .= '>' . $row['Field'] . '</option>';
		}
		$i++;
	}
	$output .= '</select>';
	$output .= '</div>';
	$output .= 'XXXXXXXXX';
	
	// Data boundaries:
	$xDataMin = mysqli_fetch_array(mysqli_query($con, 'SELECT MIN(' . $xAxis . ') FROM ' . $q . ';'))[0];
	$xDataMax = mysqli_fetch_array(mysqli_query($con, 'SELECT MAX(' . $xAxis . ') FROM ' . $q))[0];
	$yDataMin = mysqli_fetch_array(mysqli_query($con, 'SELECT MIN(' . $yAxis . ') FROM ' . $q))[0];
	$yDataMax = mysqli_fetch_array(mysqli_query($con, 'SELECT MAX(' . $yAxis . ') FROM ' . $q))[0];
	$xInterval = $xDataMax - $xDataMin;
	$yInterval = $yDataMax - $yDataMin;
	$xRatio = ($innerWidth - $margin)/($xInterval);
	$yRatio = ($innerHeight - $margin - $xm)/($yInterval);
	
	// Data obtention:
	$result = mysqli_query($con, 'SELECT * FROM ' . $q . ';'); // WHERE id = '".$q."'";
	$result2 = mysqli_query($con, 'SELECT * FROM ' . $q . ';');
	
	// Graph:
	// Parameters:
	$output .= '';
	$output .= 'var margin = ' . $margin . ';';
	$output .= 'var xm = ' . $xm . ';';
	$output .= 'var canvasX = x * 0.8;';
	$output .= 'var canvasY = y * 0.6 + xm;';
	$output .= 'var elementSize = ' . $elementSize . ';';
	
	// Canvas:
	$output .= 'const canvas = document.getElementById("myCanvas");';
	$output .= 'canvas.height = canvasY;';
	$output .= 'canvas.width = canvasX + 50;';
	$output .= 'const ctx = canvas.getContext("2d");';

	// Background:
	$output .= 'ctx.beginPath();';
	$output .= 'ctx.fillStyle = "#FFFFFF";';
	$output .= 'ctx.fillRect(margin, xm, canvasX - margin, canvasY - margin);';
	$output .= 'ctx.stroke();';
	
	// Frame:
	$output .= 'ctx.beginPath();';
	$output .= 'ctx.fillStyle = "#000000";';
	$output .= 'ctx.moveTo(margin, 1 + xm);';
	$output .= 'ctx.lineTo(canvasX - 1, 1 + xm);';
	$output .= 'ctx.lineTo(canvasX - 1, canvasY - 1 - margin + xm);';
	$output .= 'ctx.lineTo(margin, canvasY - 1 - margin + xm);';
	$output .= 'ctx.lineTo(margin, 0 + xm);';
	$output .= 'ctx.stroke();';

	// This is the part that makes this being in PHP make sense, I think:
	$output .= 'ctx.beginPath();';
	while($row = mysqli_fetch_array($result))
	{
		// Circles:
		$x = $row[$xAxis];
		$y = $row[$yAxis];
		
		$output .= 'ctx.fillStyle = "#0000FF";';
		
		
		$calcX = ($x - $xDataMin) * ($xRatio) + $margin;
		$calcY = ' canvasY - ' . ($y - $yDataMin) * ($yRatio - 0.000001) . ' - margin + xm';
		
		$output .= 'ctx.moveTo(' . $calcX . ' + elementSize/2,' . $calcY . ');';
		
		$output .= 'ctx.arc(' . $calcX . ',' . $calcY . ' +2.5, elementSize/2 , 0, 2*Math.PI);';
		$output .= 'ctx.fill();';
		
		$output .= 'ctx.font = "15px Cambria";';
		$output .= 'ctx.fillStyle = "#CCCCCC";';
		$output .= 'ctx.textAlign = "left";';
		$output .= 'ctx.fillText(' . $row['ID'] . ', ' . $calcX . ' + 10,' . $calcY . ' + 5);';
		
		// X axis:
		$output .= 'ctx.beginPath();';
		$output .= 'ctx.fillStyle = "#FFFFFF";';
		$output .= 'ctx.fillRect(0 + margin - elementSize, canvasY - margin + xm, canvasX, elementSize);';
		$output .= 'ctx.strokeStyle = "#000000";';

		for
		(
			$i = 0, $j = $xDataMin;
			$i < $innerWidth - $margin, $j <= $xDataMax + 1;
			$i += ($innerWidth - $margin)/10 - 0.1, $j += ($xInterval)/10
		)
		{
			$output .= 'ctx.moveTo(' . $i . ' + margin, canvasY - margin + xm);';
			$output .= 'ctx.lineTo(' . $i . ' + margin, canvasY - margin + 10 + xm);';
			
			$output .= 'ctx.font = "15px Cambria";';
			$output .= 'ctx.fillStyle = "#000000";';
			$output .= 'ctx.textAlign = "left";';
			$output .= 'ctx.fillText("' .  $j . '", ' . $i . ' + margin - 4, canvasY - margin + 25 + xm);';
		}

		// Y axis:
		for
		(
			$i = $innerHeight - $margin - 2 + $xm, $j = $yDataMin;
			$i > 0, $j <= $yDataMax + 1;
			$i -= ($innerHeight - $margin)/10 + 0.7, $j += ($yInterval)/10
		)
		{
			$output .= 'ctx.moveTo(margin, ' . $i . ' + xm);';
			$output .= 'ctx.lineTo(margin - 10, ' . $i . ' + xm);';

			$output .= 'ctx.font = "15px Arial";';
			$output .= 'ctx.fillStyle = "#000000";';
			$output .= 'ctx.textAlign = "right";';
			$output .= 'ctx.fillText("' . $j . '", ' . $margin - 15 . ', ' . $i + 5 . ' + xm);';
		}
	}

	$output .= 'ctx.stroke();';
	$output .= 'XXXXXXXXX';
	
	$output .= '<h3>Filtros</h3>';
	
	$i = 0;
	while($row = mysqli_fetch_array($select3))
	{
		if ($i > 2)
		{
			$title = str_replace('_', ', ', $row['Field']);
			$output .= '<p class="filterTitle">' . $title . ':</p>';
			
			$dataMin = mysqli_fetch_array(mysqli_query($con, 'SELECT MIN(' . $row['Field'] . ') FROM ' . $q))[0];
			$dataMax = mysqli_fetch_array(mysqli_query($con, 'SELECT MAX(' . $row['Field'] . ') FROM ' . $q))[0];
			
			$output .= '<input type="number" class="formed left" id="x" name="' . $row['Field'] . 'Min" value="' . $dataMin . '">-';
			$output .= '<input type="number" class="formed right" id="x" name="' . $row['Field'] . 'Max" value="' . $dataMax . '">';
		}

		$i++;
	}
	
	$output .= '<br><br><button>Filtrar</button>';
	$output .= 'XXXXXXXXX';
	
	// List:
	$output .= '<div style="padding-left: 100px;">';
	$i = 0;
	while($row = mysqli_fetch_array($result2))
	{
		$xxx = explode(' ', $row['Nombre']);
		$out = '';
		for($j = 0; $j < count($xxx); $j++)
		{
			$out .= $xxx[$j] . ' ';
			if ($j == 0){$out .= '<i>';}
		}
		$out .= '</i>';
		
		$exactA = ' <span class="greyed-out">(' . $row[$xAxis] . ', ' . $row[$yAxis] . ')</span>';
		
		$output .= '<p><b>' . $row["ID"] . ':</b> ' . $out . $exactA . '</p>';
		
		$i++;
	}
	$output .= '</div>';
	
	echo $output;
	mysqli_close($con);
?>