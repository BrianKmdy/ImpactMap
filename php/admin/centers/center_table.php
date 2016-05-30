<?php 

	require_once "check_authenticated.php";	

?>

<div class="panel panel-default">
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
							<li><a href="#" onclick="warnCenters()">Delete</a></li>
						</ul>
					</div>
				</th>
				<th class="col-xs-4">Name</th>
				<th class="col-xs-4">Acronym</th>
				<th class="col-xs-3">Color</th>
			</tr>
		</thead>
		<tbody>
			<?php

				/** 
				* The table is populated with centers from the Center database. Clicking a center calls editCenter(cid) with the center's id. Checkboxes also store center ids for deletion.
				*/

				require_once "../../common/dbConnect.php";
				require_once "../../common/class.map.php";

				$map = new Map();

				$centers = $map -> load_centers();

				for ($i = 0; $i < count($centers); $i++) {
					echo "<tr>";
					if ($map->center_referred_to($centers[$i]['cid'])) {
						echo "<td class='col-xs-1'><input type='checkbox' class='delete' id='" . $centers[$i]['cid'] . "' disabled='disabled' data-toggle='tooltip' title='Unable to delete this center since a project refers to it'></td>";
					} else {
						echo "<td class='col-xs-1'><input type='checkbox' class='delete' id='" . $centers[$i]['cid'] . "'></td>";
					}
					
					echo "<td class='clickable col-xs-4' onclick=editCenter(" . $centers[$i]['cid'] . ")> " . $centers[$i]['name'] . " </td>";
					echo "<td class='clickable col-xs-4' onclick=editCenter(" . $centers[$i]['cid'] . ")> " . $centers[$i]['acronym'] . " </td>";
					echo "<td class='clickable col-xs-3' onclick=editCenter(" . $centers[$i]['cid'] . ") style='color: " . $centers[$i]['color'] . "'> " . $centers[$i]['color'] . " </td>";
					echo "</tr>";
				}
			?>
		</tbody>
	</table>
</div>
<div class="span7 text-center">
	<button type="button" class="btn btn-primary" onclick="editCenter(-1)">Add a center</button>
</div>
