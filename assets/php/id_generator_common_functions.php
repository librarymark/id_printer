<?PHP
// =======================================
// FUNCTIONS
// =======================================
function generate_location_list($db, $location_in = ""){
	$query = "SELECT * from locations where 1";
	$locations = $db->get_results($query);

	$html_location_list =  "<select name='location' ID='location' >\n";
	$html_location_list .= "	<option value = '' >Choose a location</option>\n";
	foreach($locations as $location)
	{
		if ($location->abbr == $location_in) {
			$selected = "selected";
		}else{
			$selected = "";
		}
		$html_location_list .= "<option value = '{$location->abbr}' {$selected} >$location->name</option>\n";
	}
	$html_location_list .="</select>";
	return $html_location_list;
}

function generate_card_type_list($db, $cardtype_in = ""){
	$query = "SELECT * from card_types where 1";
	$card_types = $db->get_results($query);

	$html_card_type_list =  "<select name='card_type' ID='card_type' >\n";
	$html_card_type_list  .= "	<option value = '' >Choose a card type</option>\n";
	$checked = '';
	foreach($card_types as $card_type)
	{
		if ($card_type->folder == $cardtype_in) {
			$selected = "selected";
		}else{
			$selected = "";
		}
		$html_card_type_list .= "<option value = '{$card_type->folder}'  {$selected} >$card_type->name</option>\n";
	}
	$html_card_type_list  .="</select>";
	return $html_card_type_list ;
}

function generate_codabar_checksum($num_in){
	$sum = 0;
	$check_digit = 0;
	for($x=0; $x<=12; $x++){
		if($x&1){//  even digits
			$sum += substr($num_in,$x,1);
	   }else{// odd digits
			$odd_digit = substr($num_in,$x,1);
			$odd_digit = $odd_digit *2;
			if ($odd_digit >=10){
				$odd_digit = $odd_digit -9;
			}
			$sum += $odd_digit;
		}
	}
	$remainder = $sum % 10;
	if ($remainder == 0){
		$check_digit = 0;
	}else{
		$check_digit = 10 - $remainder;
	}
	return $check_digit;	
}

function send_message($message,$port){
        @$fp = fsockopen ($_SERVER['REMOTE_ADDR'], $port, $errno, $errstr, 5); //wait no more than 5 seconds for paster client
        @fputs ($fp, $message);
        @fclose ($fp);
}

function get_next_id_number($db){
	// get last printed ID number, generate codabar checksum, append it to the and put it back in the database...
	$query = "SELECT value from variables where variable = 'current_id' ";
	$current_id_number = $db->get_var($query);
	$current_id_number = substr($current_id_number,0,13); // first 13 is the id, 14th is the checksum - chop off the checksum
	$current_id_number++; 
	$checksum = generate_codabar_checksum($current_id_number);
	$next_id_number = $current_id_number . $checksum;
	$query = "UPDATE variables SET value = '{$next_id_number}'  WHERE variable = 'current_id'";
	$db->query($query);
	return $next_id_number;
}
