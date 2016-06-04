<?php 

	require_once "check_authenticated.php";	

?>

<div class="panel panel-default table-responsive">
	<table class="table table-hover table-fixed">
		<thead>
			<tr>
				<th class="col-xs-1">
					<div class="btn-group">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="#" onclick="selectAll()">Select all</a></li>
							<li><a href="#" onclick="unselectAll()">Unselect all</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#" onclick="updateProjects('hide')">Hide</a></li>
							<li><a href="#" onclick="updateProjects('show')">Show</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#" onclick="warnProjects()">Delete</a></li>
						</ul>
					</div>
				</th>
				<th class="col-xs-1">Visible</th>
				<th class="col-xs-2">Title</th>
				<th class="col-xs-1">Center</th>
				<th class="col-xs-1">Status</th>
				<th class="col-xs-1">Start date</th>
				<th class="col-xs-5">Summary</th>
			</tr>
		</thead>
		<tbody>
			<?php
				/**
				* The table of projects. Each checkbox stores the id of the project it's next to for deletion. Clicking on a project calls editProject(pid) where
				* pid is the id of that project.
				*/

				require_once "../../common/dbConnect.php";
				require_once "../../common/class.map.php";

				$map = new Map();	
				$projects = $map -> load_projects_full();
			?>

			<?php for ($i = 0; $i < count($projects); $i++): ?>
				<tr>
				<td class='col-xs-1'><input type='checkbox' class='delete' id=<?php echo "'" . $projects[$i]['pid'] . "'"; ?>></td>
				<td class='clickable col-xs-1' onclick=editProject(<?php echo $projects[$i]['pid']; ?>)><span class='glyphicon glyphicon-eye-open' aria-hidden='true' <?php if ($projects[$i]['visible'] == FALSE) echo "style='opacity: 0.1;'"; ?>></span></td>
				<td class='clickable col-xs-2' onclick=editProject(<?php echo $projects[$i]['pid']; ?>)> <?php echo strlen($projects[$i]['title']) > 25 ? substr($projects[$i]['title'],0,25) . "..." : $projects[$i]['title']; ?> </td>
				<td class='clickable col-xs-1' onclick=editProject(<?php echo $projects[$i]['pid']; ?>)> <?php echo $projects[$i]['acronym']; ?> </td>
				<td class='clickable col-xs-1' onclick=editProject(<?php echo $projects[$i]['pid']; ?>)> <?php echo $STATUS[$projects[$i]['status']]; ?> </td>
				<td class='clickable col-xs-1' onclick=editProject(<?php echo $projects[$i]['pid']; ?>)> <?php echo $projects[$i]['startDate']; ?> </td>
				<td class='clickable col-xs-5' onclick=editProject(<?php echo $projects[$i]['pid']; ?>)> <?php echo strlen($projects[$i]['summary']) > 150 ? substr($projects[$i]['summary'],0,150) . "..." : $projects[$i]['summary']; ?> </td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
</div>
<div class="span7 text-center">
	<button type="button" class="btn btn-primary" onclick="editProject(-1)">Add a project</button>
</div>
