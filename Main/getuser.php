<!DOCTYPE html>
<html>
	<body>
		<?php
			//$q = intval($_GET['q']);
			$q = $_GET['q'];
		
			$con = mysqli_connect('localhost:3306', 'root', '', 'proyecto');
			if (!$con)
			{
				die('Could not connect: ' . mysqli_error($con));
			}
			
			$sql="SELECT * FROM " . $q . ";"; // WHERE id = '".$q."'";
			$result = mysqli_query($con,$sql);
			
			echo "<table>
				<tr>
					<th>Firstname</th>
				</tr>";

				while($row = mysqli_fetch_array($result))
				{
					echo "<tr>";
						echo "<td>" . $row['x'] . "</td>";
					echo "</tr>";
				}

			echo "</table>";
			mysqli_close($con);
		?>
	</body>
</html>
