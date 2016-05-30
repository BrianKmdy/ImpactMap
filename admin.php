<html>
	<head>
		<meta charset="UTF-8">
		<!-- Latest compiled and minified CSS -->
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	    <!-- Optional theme -->
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxTuWupJtBOt5q0Cw-Iao0FDZgciKI0MI" async defer></script>

		<link rel="stylesheet" href="lib/datetimepicker/jquery.datetimepicker.min.css">
		<script src="lib/datetimepicker/jquery.datetimepicker.full.min.js"></script>

		<script src="lib/snowball_stemmer/Snowball.min.js"></script>

		<script src="lib/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
		<link rel="stylesheet" href="lib/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">

		<link rel="stylesheet" href="css/admin.css">
		<link rel="stylesheet" href="css/project_table.css">

		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

		<script src="js/admin.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    <div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#impactNav">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.html">
					<img alt="Brand" src="img/brand.png" height="40px" width="40px">
				</a>
		    </div>
		    <div class="collapse navbar-collapse" id="impactNav">
			    <ul class="nav navbar-nav"> 	
					<?php
						// This is just temporary until we have a database of users
						//$usertype = "admin";
						require_once "php/common/class.map.php";

						session_start();
		
						$map = new Map();
						$row = $map -> login_user($_SESSION['user_email']);
						$admin = $row['root'];
						$authenticated = $row['authenticated'];
					?>
					<?php if ($authenticated == true): ?>
						<li id="projects" class="active"><a href="#" onclick="loadProjects()">Projects</a></li>
						<li id="contacts"><a href="#" onclick="loadContacts()">Contacts</a></li>
					<?php endif; ?>
					<?php if ($admin == true): ?>
						<li id="centers"><a href="#" onclick="loadCenters()">Centers</a></li>
						<li id="history" class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#" onclick="showDateTimePicker()">History
							<span class="caret"></span></a>
							<ul id="datetimepickerdropdown" class="dropdown-menu">
								<li>
									<div id="timepickercontainer">
										<input type="text" id="datetimepicker" class="form-control" value="">
									</div>
								</li>
							</ul>
						</li>
						<li id="users"><a href="#" onclick="loadUsers()">Users</a></li>
					<?php endif; ?>
						<li id="profile" <?php if($authenticated == false) echo 'class="active"'; ?>><a href="#" onclick="loadProfile()">Profile</a></li>
				</ul>
			    <?php
			    	//$username = "Testy McTestface";
			    	  //session_start();

					  if(!isset($_SESSION['logged_in']))
					  {
					  	header("Location: login.php");
					  }
			    	  $fname = $_SESSION["logged_in"];
			    	  $lname = $_SESSION["logged_in2"];

			    	echo '<p class="navbar-text navbar-right"><a href="logout.php" class="navbar-link">Logout</a></p>';
			    ?>
			</div>
		  </div>
		</nav>
		<div id="content" class="container-fluid"></div>
		<div id="impactModal" class="modal fade" tabindex="-1" role="dialog">
		</div><!-- /.modal -->
	</body>
</html>