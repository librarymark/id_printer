<?PHP
session_start();



?>
<form id="frmEditData">
<input type="hidden" name="call_workflows" value="N"> 
<input type="hidden" name="paste_id" value="N"> 
<input type="hidden" name="session_id" value="<?PHP echo $_SESSION['session_id'];?>" >  
<input type="hidden" name="location_printer" value="<?PHP echo $_SESSION['location_printer'];?>" >  

<table id="data_entry_table">
	<tbody><tr>
	<td style="">
		<input name="patron_fname" id="fname" value="<?php echo $_SESSION['patron_fname']; ?>" style="width:150px;"><br>
	</td>
	<td style="">
		<input name="patron_mname" id="mname" value="<?php echo $_SESSION['patron_mname']; ?>" style="width:150px;">
	</td>
	<td style="">
		<input name="patron_lname" id="lname" value="<?php echo $_SESSION['patron_lname']; ?>" style="width:150px;">
	</td>
	<td>
		<input value="<?php echo $_SESSION['patron_id']; ?>" name="patron_id" maxlength="14" id="id_number" style="width:135px;" readonly >
	</td>
</tr>
<tr>
</tr>
<tr>
	<td>
		<small>First Name</small>
	</td>
	<td>
		<small>Middle</small>
	</td>
	<td>
		<small>Last Name</small>
	</td>
	<td>
		<small>ID #</small>
	</td>
</tr>
</tbody>
</table>