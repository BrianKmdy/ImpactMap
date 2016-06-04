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
							<li><a href="#" onclick="warnContacts()">Delete</a></li>
						</ul>
					</div>
				</th>
				<th class="col-xs-4">Name</th>
				<th class="col-xs-4">Email</th>
				<th class="col-xs-3">Phone</th>
			</tr>
		</thead>
		<tbody>
			<?php

				/** 
				* The table is populated with contacts from the Center database. Clicking a center calls editContact(conid) with the center's id. Checkboxes also store center ids for deletion.
				*/

				require_once "../../common/dbConnect.php";
				require_once "../../common/class.map.php";

				$map = new Map();

				$contacts = $map -> load_contacts();
			?>


			<?php for ($i = 0; $i < count($contacts); $i++): ?>
				<tr>
					<?php if ($map->contact_referred_to($contacts[$i]['conid'])): ?>
						<td class='col-xs-1'>
							<input type='checkbox' class='delete' disabled='disabled' data-toggle='tooltip' title='Unable to delete this contact since a project refers to it' id=<?php echo "'" . $contacts[$i]['conid'] . "'"; ?>>
						</td>
					<?php else: ?>
						<td class='col-xs-1'>
							<input type='checkbox' class='delete' id=<?php echo "'" . $contacts[$i]['conid'] . "'"; ?>>
						</td>
					<?php endif; ?>
					
					<td class='clickable col-xs-4' class='clickable' onclick=editContact(<?php echo $contacts[$i]['conid']; ?>)><?php echo $contacts[$i]['name']; ?></td>
					<td class='clickable col-xs-4' class='clickable' onclick=editContact(<?php echo $contacts[$i]['conid']; ?>)><?php echo strlen($contacts[$i]['email']) > 35 ? substr($contacts[$i]['email'],0,30)."..." : $contacts[$i]['email']; ?></td>
					<td class='clickable col-xs-3' class='clickable' onclick=editContact(<?php echo $contacts[$i]['conid']; ?>)><?php echo $contacts[$i]['phone'] ?></td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
</div>
<div class="span7 text-center">
	<button type="button" class="btn btn-primary" onclick=editContact(-1)>Add a contact</button>
</div>
