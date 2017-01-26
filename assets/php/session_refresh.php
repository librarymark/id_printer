<?php
session_start();

if (isset($_SESSION['session_id']))
$_SESSION['session_id'] = $_SESSION['session_id']; 
header('Content-Type: application/json');
echo json_encode($_SESSION);