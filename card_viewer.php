<?PHP
ini_set('memory_limit', '512M');
include_once "/var/www/html/assets/php/ezsql/shared/ez_sql_core.php";
include_once "/var/www/html/assets/php/ezsql/mysqli/ez_sql_mysqli.php";
include_once "/var/www/html/assets/php/id_generator.inc.php";

$db = new ezSQL_mysqli($user,$pass,$db,$host);
@$date_stamp = $_REQUEST['date'];
if (!isset($date_stamp)){
	$date_stamp = date('Y-m-d');
}

$card_records = $db->get_results("SELECT * FROM patron_records WHERE timestamp LIKE '{$date_stamp}%' ORDER BY timestamp desc");
?>
<HTML>
<HEAD>
<link rel="stylesheet" href="/assets/css/datagrid.css" />
<link rel="stylesheet" href="/assets/css/jquery-ui.css" />

<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery-ui.js"></script>


<style>
body{
	 font: 16px/150% Arial,Helvetica,sans-serif;
}

.card_front{
	width:257px;
	height:161px;
}

.content{
	width:540px;
	 margin: 0 auto;
	 text-align:center;
}

.ui-datepicker th{
	background-color:#006699;
}


</style>
<script>
//=============================================================
// DOCUMENT READY
//=============================================================
jQuery(document).ready(function() { 
	$("#datepicker").datepicker(
		{ 
		maxDate: "+0D" ,
		dateFormat: "yy-mm-dd",
		onSelect: function(date) {
			window.location.replace('https://' + document.domain + "/card_viewer.php?date=" + date);
		}
		});
	$('.card_front').click(function(e){
		e.preventDefault();
		var record_id = $(this).data("id");
		$("<div><img style='width:686px; height:430px;'  src='assets/php/show_image.php?id=" + record_id + "'/></div>")
		 .dialog({
			autoOpen: true,
			resizeable: false,
			modal: true,
			width: 720,
			closeOnEscape: true
		 })
		return false;
	});

	$('#btnViewAll').click(function(e){
		window.location.replace(url_pathname + "?date=");
	});
//=========================================================================
}); // END DOCUMENT READY
//=========================================================================
</script>
</HEAD>
<BODY>
<div class="content">
Willard ID Cards issued on <?PHP echo $date_stamp;?>
<p>Date: <input type="text" id="datepicker" value ="<?php echo $date_stamp; ?>" ><p>

<div class="datagrid">
<table>
<?PHP foreach ($card_records as $card_record ){ ?>
	
	<td>
		<?php echo $card_record->station_hostname; ?><br>
		<?php //echo $card_record->station_ip; ?><br>
		<?php echo $card_record->timestamp; ?><br>
		<?php //echo $card_record->patron_id; ?>
	</td>
	<td>
	<a href=''>
	<img  data-id=<?php echo $card_record->ID; ?> class="card_front" src="assets/php/show_image.php?id=<?php echo ($card_record->ID); ?>" />
	</a>
	</td>
</tr>
<?PHP } ?>
</table>
</div>
</div>
</BODY>
</HTML>
<div id='id_card_container'></div>
