 <?php
	$myfile = fopen("X.svg", "w") or die("Unable to open file!");
	$svg =
		'<svg xmlns="http://www.w3.org/2000/svg" height="250px" width="2500px">
			<text x="0" y="180" font-family="Cambria, sans-serif" font-size="250">
				' . $title . '
			</text>
		</svg>';

	fwrite($myfile, $svg);
	fclose($myfile);
?> 