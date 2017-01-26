<?PHP

require("fpdf/fpdf.php");

define ('BARCODE_GENERATOR', '/var/www/html/assets/scripts/generate_barcode');
define ('BARCODE_IMG_PATH','/var/www/html/var/barcode_drive');

mkdir(BARCODE_IMG_PATH);

@$text = $_REQUEST['text'];
@$starting_number = $_REQUEST['start'];
@$page_count = $_REQUEST['pages'];

if (
	(!isset($starting_number)||!isset($page_count))
	||
	(strlen($starting_number)<> 13)

	){
echo <<< EOF
	<HTML>
	<HEAD>
	<link type="text/css" href="/assets/css/id_generator.css" rel="stylesheet">
	<link href="/assets/css/desktop.css" rel="stylesheet">
	<style>
	input{
		width:200px;
	}
	div{
		height:200px;
		position:absolute;
		left:50%;
		top:50%;
		margin:-200px 0 0 -200px;
	}
	table{
		padding:20px;
	}
	</style>
	</HEAD>
	<BODY>
	<div>
	<img src="/assets/images/wl_logo1.jpg"><p>
	<h1>Card Drive Card Generator</h1>

	<form action="{$_SERVER['PHP_SELF']}">
	<table>
	<tr>
	<td align=right>Text: </td><td><input name = "text"></td>
	</tr>
	<tr>
	<td align=right>Starting barcode number: </td><td><input name = "start"></td>
	</tr>
	<tr>
	<td align=right>Page Count:</td><td><input name = "pages"><td>
	</tr>
	<tr>
	<td  align=center>&nbsp;</td><td><input type='submit' value= "Generate PDF"></td>
	</tr>
	</table>
	</form>
EOF;
if (isset($starting_number)&&(strlen($starting_number)<> 13)){
	echo "<span style='color:white;font-size:16px;'>The starting barcode number must be a <b>13 digit</b> number. <br>The 14th (check digit) will be added automatically</span>";
	}
echo <<< EOF
	<div>
	</BODY>
	</HTML>

EOF;
exit;
}


$pdf = new FPDF('P','in','Letter');
$pdf->SetLeftMargin(0.75);
$pdf->SetRightMargin(0.75);
$pdf->SetTopMargin(0.5);
$pdf->SetFont('Arial','B',12);
$col = 1.0;$row = 0.55; 

for($i = 0; $i < $page_count; $i++){
	$pdf->AddPage();
	$starting_number = output_front_side($pdf, $text, $col, $row, $starting_number);
	$pdf->AddPage();
	output_back_side($pdf);
}




$pdf->Output();

exec('rm -r ' .BARCODE_IMG_PATH.'/*');

exit;

function output_back_side($pdf){
$col = 1.0;$row = 1.5; 

$pdf->Text($col+0,$row+0 ,'________________________________');
$pdf->Text($col+0,$row+0.3 ,'Name');
$col = $col;$row = $row+2;
$pdf->Text($col+0,$row+0 ,'________________________________');
$pdf->Text($col+0,$row+0.3 ,'Name');
$col = $col;$row = $row+2;
$pdf->Text($col+0,$row+0 ,'________________________________');
$pdf->Text($col+0,$row+0.3 ,'Name');
$col = $col;$row = $row+2;
$pdf->Text($col+0,$row+0 ,'________________________________');
$pdf->Text($col+0,$row+0.3 ,'Name');
$col = $col;$row = $row+2;
$pdf->Text($col+0,$row+0 ,'________________________________');
$pdf->Text($col+0,$row+0.3 ,'Name');

$col = 4.5;$row = 1.5; 

$pdf->Text($col+0,$row+0 ,'________________________________');
$pdf->Text($col+0,$row+0.3 ,'Name');
$col = $col;$row = $row+2;
$pdf->Text($col+0,$row+0 ,'________________________________');
$pdf->Text($col+0,$row+0.3 ,'Name');
$col = $col;$row = $row+2;
$pdf->Text($col+0,$row+0 ,'________________________________');
$pdf->Text($col+0,$row+0.3 ,'Name');
$col = $col;$row = $row+2;
$pdf->Text($col+0,$row+0 ,'________________________________');
$pdf->Text($col+0,$row+0.3 ,'Name');
$col = $col;$row = $row+2;
$pdf->Text($col+0,$row+0 ,'________________________________');
$pdf->Text($col+0,$row+0.3 ,'Name');

}

function output_front_side($pdf, $text, $col, $row, $starting_number){
	$row_offset = 2.00;
	
	$col = $col;$row =  $row;               output_card($pdf, $text, $col, $row, $starting_number);$starting_number++;
	$col = $col;$row =  $row + $row_offset ;output_card($pdf, $text, $col, $row, $starting_number);$starting_number++;
	$col = $col; $row = $row + $row_offset ;output_card($pdf, $text, $col, $row, $starting_number);$starting_number++;
	$col = $col; $row = $row + $row_offset ;output_card($pdf, $text, $col, $row, $starting_number);$starting_number++;
	$col = $col; $row = $row + $row_offset ;output_card($pdf, $text, $col, $row, $starting_number);$starting_number++;
	
	$col = 4.5;$row = 0.5; 
	
	$col = $col;$row =  $row;               output_card($pdf, $text, $col, $row, $starting_number);$starting_number++;
	$col = $col;$row =  $row + $row_offset ;output_card($pdf, $text, $col, $row, $starting_number);$starting_number++;
	$col = $col; $row = $row + $row_offset ;output_card($pdf, $text, $col, $row, $starting_number);$starting_number++;
	$col = $col; $row = $row + $row_offset ;output_card($pdf, $text, $col, $row, $starting_number);$starting_number++;
	$col = $col; $row = $row + $row_offset ;output_card($pdf, $text, $col, $row, $starting_number);$starting_number++;
	return $starting_number;
}

function output_card($pdf, $text, $col, $row, $id_num){
	$barcode_text = $id_num.generate_codabar_checksum($id_num);
	$barcode_filename = "/var/www/html/var/barcode_drive/{$barcode_text}.png";
	$barcode_image = shell_exec(BARCODE_GENERATOR . ' ' . $barcode_text);
	file_put_contents($barcode_filename,$barcode_image);
	$pdf->SetXY($col,$row);
	$pdf->Image('/var/www/html/assets/images/card_top.jpg',$col,$row,3);
	// output barcode 
	$pdf->Image($barcode_filename,$col+0.50,$row+1.25,2.00,'PNG');
	$pdf->SetXY($col,$row+1.0);
	$pdf->Cell(3.0,0.2,$text,0,1,"C");
	//$pdf->Text($col+0.6,$row + 1.15,$text);
	// output barcode text
	$pdf->Text($col+0.75,$row + 1.8,$barcode_text);
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

