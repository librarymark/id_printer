<?PHP
ini_set('memory_limit', '512M');
include_once "/var/www/html/assets/php/ezsql/shared/ez_sql_core.php";
include_once "/var/www/html/assets/php/ezsql/mysqli/ez_sql_mysqli.php";
include_once "/var/www/html/assets/php/id_generator.inc.php";

$db = new ezSQL_mysqli($user,$pass,$db,$host);
@$date_stamp = $_REQUEST['date'];
//$date_stamp = '2015-12-07';

if (!isset($date_stamp)){
	$date_stamp = date('Y-m-d');
}

$card_records = $db->get_results("SELECT * FROM patron_records WHERE timestamp LIKE '{$date_stamp}%' ORDER BY timestamp desc");

if (count($card_records) == 0){
	echo "Sorry - No cards available to reprint!";
	exit;
}

?>
<HTML>
<HEAD>

<style>

.card_front{
	width:257px;
	height:161px;
}

.content{

	 margin: 0 auto;
	 text-align:center;
}


</style>
</HEAD>
<BODY>
<div class="content">
<div class="datagrid">
<div id='reprint_chooser_table'>
<?PHP foreach ($card_records as $card_record ){ ?>

<div  style=" border:2px solid black; margin:5px; " >
	<div >
		<?php echo $card_record->station_hostname; ?>
		<?php echo substr($card_record->timestamp,11); ?><br>
	</div>
	<div ">
	<img  data-cardID=<?php echo ($card_record->ID); ?> class="loadReprintChoice card_front" src="/assets/php/show_image.php?id=<?php echo ($card_record->ID); ?>" />
	</div>
</div>

<?PHP } ?>
</div>
</div>
</div>
</BODY>
</HTML>
<div id='id_card_container'></div>