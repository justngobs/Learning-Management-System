<?php
session_start();

if(!isset($_SESSION['loggedin'])){
	header('Location: login.php');
	exit();
}

$cantcatchme = 0;

if($_SESSION['ulevel'] == 2){
	$cantcatchme += 1;
}

if($cantcatchme == 1){

}

else{
	echo "You do not have permission to view this page on this server";
	exit();
}

?>
<!DOCTYPE html>
<html>
<head>

	<title> Quiz Performance</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<link rel="stylesheet" type="text/css" href="tooltip.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<script type="text/javascript" src="javascriptFormsValidations.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</head>
<body style="background-color: rgb(249,247,243)">
	<br>
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">

	<?php
		if(isset($_SESSION['current_subject']) && isset($_SESSION['subject_name']) && isset($_SESSION['subject_code'])){

			echo "<a href='quizzes.php'><i class='fa fa-arrow-circle-left' style='font-size:48px;color:yellow'></i></a><a href='teacher.php'><i class='fa fa-home' style='font-size:48px;color:yellow;margin-left: 5%'></i></a>
			<br>";
			echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars(ucfirst($_SESSION['subject_name']))." ( ".htmlspecialchars($_SESSION['subject_code'])." </u>)</h4><br>";

			//Teachers navigation menu
			require 'teachermenu.php';
		}
		else{
			echo "<script> document.location = 'teacher.php'; </script>";
		}
	?>
</div>
<div style="margin-left: 25%">
	<div id = "content">
		<form action="performanceReport.php" method="post">
			<b>Available Quizzes :</b>
			<select style="border-radius: 7px; border-color: lightblue;" type="text" name="quiz_selected" required="required">
				<option value="default" selected="selected" disabled="disabled">Select quiz</option>
				<?php

				require_once 'app_config.php';
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

				$c_subject = $_SESSION['current_subject'];
				if(is_numeric($c_subject))
				{
					$sql = "SELECT quiz_id, sub_id, quiz_title FROM quiz WHERE sub_id = '$c_subject'";
					$result = mysqli_query($connection, $sql);
					if(mysqli_num_rows($result) > 0){
						while($row = mysqli_fetch_assoc($result)){
							echo "<option value='".$row['quiz_id']."'>".$row['quiz_title']."</option>";
						}
					}
				}
				mysqli_close($connection);
				?>
			</select>
			<input type="submit" name="submit" value="View Performance Report">
		</form>		
		<br>
		<div>
			<div class="w3-left">
				<div id="piechart"></div>
			</div>

			<div class="w3-right" style="margin-right: 35%">
				<?php
				if(isset($_SESSION['highest']) && isset($_SESSION['lowest']) && isset($_SESSION['average'])){
					echo "
					<br>
					Quiz Average: ".$_SESSION['average']."
					<br>
					<br>
					Highest Mark: ".$_SESSION['highest']."
					<br>
					<br>
					Lowest Mark: ".$_SESSION['lowest']."
					";
				}
				?>
			</div>
	    </div>
		<br>
		<?php
		$cantcatchme = 0;
		if(isset($_POST['quiz_selected']) && is_numeric($_POST['quiz_selected'])){
			$cantcatchme = 1;
			$selected_quiz = $_POST['quiz_selected'];
		}

		if($cantcatchme == 1){

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			$sql = "SELECT quiz.n_of_questions, quiz.quiz_id, seed.stu_id, seed.stu_name, seed.stu_surname, quiz_mark.stu_id, quiz_mark.stu_id, quiz_mark.quiz_id, quiz_mark.stu_score FROM seed, quiz_mark, quiz WHERE quiz_mark.quiz_id = '$selected_quiz' AND seed.stu_id = quiz_mark.stu_id AND quiz_mark.quiz_id = quiz.quiz_id ORDER BY quiz_mark.stu_score DESC";

			$result = mysqli_query($connection, $sql);
			$counter = 0;
			$passed = 0;
			$failed = 0;

			$marks = array();

			if(mysqli_num_rows($result) > 0){
				echo "<table class='w3-table w3-striped w3-bordered w3-hoverable' style='width:98%'>
				<thead style='background-color: rgb(74,74,74);'>
				<tr>
					<th style='color:rgb(235,238,196)'>#</th>
					<th style='color:rgb(235,238,196)'>Name</th>
					<th style='color:rgb(235,238,196)'>Surname</th>
					<th style='color:rgb(235,238,196)'>Mark(%)</th>
				</tr>
				</thead>
				<tbody>";
				while($row = mysqli_fetch_assoc($result)){
					$counter += 1;

					$marks[$counter - 1] = round((($row['stu_score'])/($row['n_of_questions']))*100);

					echo "<tr>
							<td>".($counter)."</td>
							<td>".$row['stu_name']."</td>
							<td>".$row['stu_surname']."</td>
							<td>".round(((($row['stu_score'])/($row['n_of_questions']))*100))."</td>
						</tr>";

					if(round(((($row['stu_score'])/($row['n_of_questions']))*100)) >= 50){
						$passed += 1;
					}
					if(round(((($row['stu_score'])/($row['n_of_questions']))*100)) < 50){
						$failed += 1;
					}

				}
				$_SESSION['passed'] = $passed;
				$_SESSION['failed'] = $failed;

				$inarray = count($marks);
				$total = array_sum($marks);
				$highest = max($marks);
				$lowest = min($marks);

				$_SESSION['average'] = round($total/$inarray);
				$_SESSION['highest'] = $highest;
				$_SESSION['lowest'] = $lowest;
				echo "</tbody></table>";
			}

			else{
				echo "No submissions yet for this quiz or quiz is not published yet!";
			}

			mysqli_close($connection);
		}
		?>
	</div>
</div>

<?php 
	if(isset($_SESSION['passed']) && isset($_SESSION['failed'])){
		echo "<script type='text/javascript'>
		// Load google charts
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);

		// Draw the chart and set the chart values
		function drawChart() {
		  var data = google.visualization.arrayToDataTable([
		  ['Task', 'Hours per Day'],
		  ['Passed', ".$_SESSION['passed']."],
		  ['Failed', ".$_SESSION['failed']."]
		]);

		  // Optional; add a title and set the width and height of the chart
		  var options = {'title':'Pass versus Fail', 'width':350, 'height':200};


		  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
		  chart.draw(data, options);
		}
		</script>";
	}

?>


</body>
</html>