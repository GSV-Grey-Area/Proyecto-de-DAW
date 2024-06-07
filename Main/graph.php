<?php
	// GET parameters:
	$q = $_GET['q']; // intval
	$innerWidth = $_GET['innerWidth'] * 0.8;
	$innerHeight = $_GET['innerHeight'] * 0.8;

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
	$output .= '<div style="margin-right: auto; margin-left: 17%;">';
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
	$output .= '<div style="margin-left: auto; margin-right: 13%;">';
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
	
	echo ('SELECT MIN(' . $xAxis . ') FROM ' . $q);
	
	// Data boundaries:
	$xDataMin = mysqli_fetch_array(mysqli_query($con, 'SELECT MIN(' . $xAxis . ') FROM ' . $q . ';'))[0];
	$xDataMax = mysqli_fetch_array(mysqli_query($con, 'SELECT MAX(' . $xAxis . ') FROM ' . $q))[0];
	$yDataMin = mysqli_fetch_array(mysqli_query($con, 'SELECT MIN(' . $yAxis . ') FROM ' . $q))[0];
	$yDataMax = mysqli_fetch_array(mysqli_query($con, 'SELECT MAX(' . $yAxis . ') FROM ' . $q))[0];
	$xInterval = $xDataMax - $xDataMin;
	$yInterval = $yDataMax - $yDataMin;
	$xRatio = ($innerWidth - $margin)/($xInterval);
	$yRatio = ($innerHeight - $margin)/($yInterval);
	
	// Data obtention:
	$result = mysqli_query($con, 'SELECT * FROM ' . $q . ';'); // WHERE id = '".$q."'";
	
	// Graph:
	// Parameters:
	$output .= '';
	$output .= 'var margin = ' . $margin . ';';
	$output .= 'var xm = ' . $xm . ';';
	$output .= 'var canvasX = x * 0.8;';
	$output .= 'var canvasY = y * 0.8 + xm;';
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
		$output .= 'ctx.moveTo(' . ($x - $xDataMin) * ($xRatio - 0.1)  + $margin . ' + elementSize/2 , canvasY - ' . ($y - $yDataMin) * $yRatio . ' - margin + xm);';
		$output .= 'ctx.arc(' . ($x - $xDataMin) * ($xRatio - 0.1) + $margin . ', canvasY - ' . ($y - $yDataMin) * ($yRatio + 0.005) + 2.5 . ' - margin + xm, elementSize/2 , 0, 2*Math.PI);';
		$output .= 'ctx.fill();';
		
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
	$output .= '<p>(Por hacer.)</p>';
	
	$i = 0;
	while($row = mysqli_fetch_array($select3))
	{
		if ($i != 0)
		{
			$output .= '<p class="filterTitle">' . $row['Field'] . ':</p>';
			
			$dataMin = mysqli_fetch_array(mysqli_query($con, 'SELECT MIN(' . $row['Field'] . ') FROM ' . $q))[0];
			$dataMax = mysqli_fetch_array(mysqli_query($con, 'SELECT MAX(' . $row['Field'] . ') FROM ' . $q))[0];
			
			$output .= '<input type="number" class="left" id="x" name="x" value="' . $dataMin . '">';
			$output .= '<input type="number" class="right" id="x" name="x" value="' . $dataMax . '">';
		}

		$i++;
	}
	
	$output .= '<br><br><button>Filtrar</button>';
	
	echo $output;
	mysqli_close($con);
?>