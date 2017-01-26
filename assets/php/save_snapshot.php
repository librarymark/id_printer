<?PHP
session_start();
include_once("/var/www/html/assets/php/id_generator.inc.php");

$snapshotData = $_POST['snapshot_image'];
$snapshotData = str_replace('data:image/png;base64,', '',$snapshotData);

$snapshotData  = str_replace('data:image/jpeg;base64,', '', $snapshotData );
$snapshotData = str_replace(' ', '+', $snapshotData);
$snapshotData = base64_decode($snapshotData);

$snapshot_filename = $var_folder .'/'. $_SESSION['station_ip'] . '-snapshot.jpg';

$success = file_put_contents($snapshot_filename, $snapshotData);