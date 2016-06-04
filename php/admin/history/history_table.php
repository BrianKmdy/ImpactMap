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
							<li><a href="#" onclick="restoreHistory()">Restore</a></li>
						</ul>
					</div>
				</th>
				<th class="col-xs-1">Edited by</th>
				<th class="col-xs-2">Title</th>
				<th class="col-xs-1">Center</th>
				<th class="col-xs-2">Status</th>
				<th class="col-xs-2">Start date</th>
				<th class="col-xs-3">Summary</th>
			</tr>
		</thead>
		<tbody>
			<?php
				/**
				* A table populated with entries in the History table that represent the Project table at a certain point in time. The time
				* the table will represent is $_POST['timestamp']. Each checbox stores the id of a history entry to facilitate recovering 
				* entries in the history. Clicking on an item in the history table calls viewHistory(hid) where hid is the id of that item
				* in the history table.
				*/

				require_once "../../common/dbConnect.php";
				require_once "../../common/class.map.php";

				$infinity = 1000000;

				$map = new Map();
				$filters = array();

				$filters['limit'] = $infinity;
				$filters['timestamp'] = $_POST['timestamp'];

				$history = $map -> load_history_full($filters);
			?>

			<?php for ($i = 0; $i < count($history); $i++): ?>
				<tr>
				<td class='col-xs-1'>
					<input type='checkbox' class='delete' id=<?php echo "'" . $history[$i]['hid'] . "'"; ?>>
				</td>
				<td class='col-xs-1 clickable' onclick=viewHistory(<?php echo $history[$i]['hid']; ?>)><?php echo $history[$i]['firstName'] . " " . substr($history[$i]['lastName'], 0, 1); ?></td>
				<td class='col-xs-2 clickable' onclick=viewHistory(<?php echo $history[$i]['hid']; ?>)><?php echo strlen($history[$i]['title']) > 25 ? substr($history[$i]['title'],0,25)."..." : $history[$i]['title']; ?></td>
				<td class='col-xs-1 clickable' onclick=viewHistory(<?php echo $history[$i]['hid']; ?>)><?php echo $history[$i]['acronym']; ?></td>
				<td class='col-xs-2 clickable' onclick=viewHistory(<?php echo $history[$i]['hid']; ?>)><?php echo $STATUS[$history[$i]['status']]; ?></td>
				<td class='col-xs-2 clickable' onclick=viewHistory(<?php echo $history[$i]['hid']; ?>)><?php echo $history[$i]['startDate']; ?></td>
				<td class='col-xs-3 clickable' onclick=viewHistory(<?php echo $history[$i]['hid']; ?>)><?php echo strlen($history[$i]['summary']) > 100 ? substr($history[$i]['summary'],0,100)."..." : $history[$i]['summary']; ?></td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
</div>

<div class="span7 text-center">
	<button type="button" class="btn btn-primary" onclick="restoreWholeTable()">Restore whole table</button>
</div>
