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

			?>

			<?php for ($i = 0; $i < count($centers); $i++): ?>
				<tr>
					<?php if ($map->center_referred_to($centers[$i]['cid'])): ?>
						<td class='col-xs-1'>
							<input type='checkbox' class='delete' disabled='disabled' data-toggle='tooltip' title='Unable to delete this center since a project refers to it' id=<?php echo "'". $centers[$i]['cid'] . "'"; ?>>
						</td>
					<?php else: ?>
						<td class='col-xs-1'>
							<input type='checkbox' class='delete' id=<?php echo "'". $centers[$i]['cid'] . "'"; ?>>
						</td>
					<?php endif; ?>
					
					<td class='clickable col-xs-4' onclick=editCenter(<?php echo $centers[$i]['cid']; ?>)><?php echo strlen($centers[$i]['name']) > 40 ? substr($centers[$i]['name'],0,40)."..." : $centers[$i]['name']; ?></td>
					<td class='clickable col-xs-4' onclick=editCenter(<?php echo $centers[$i]['cid']; ?>)><?php echo $centers[$i]['acronym']; ?></td>
					<td class='clickable col-xs-3' onclick=editCenter(<?php echo $centers[$i]['cid']; ?>) style=<?php echo '"color: ' . $centers[$i]['color'] . ';"'; ?>><?php echo $centers[$i]['color']; ?></td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
</div>
<div class="span7 text-center">
	<button type="button" class="btn btn-primary" onclick=editCenter(-1)>Add a center</button>
</div>
