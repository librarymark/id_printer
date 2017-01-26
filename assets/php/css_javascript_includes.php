<?PHP 

$text =  <<< EOF

	<!-- CSS files -->
	<link rel="stylesheet" href="/assets/css/reset.css" />
	<link rel="stylesheet" href="/assets/css/desktop.css" />
	<link rel="stylesheet" href="/assets/css/jquery-ui.css" />
	<link rel="stylesheet" href="/assets/css/imgareaselect-default.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/id_generator.css" type="text/css" />

	<!-- Javascript  files -->
	<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.desktop.js.php"></script>
	<script type="text/javascript" src="/assets/js/jquery.imgareaselect.pack.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="/assets/js/idle-timer.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.numeric.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.playSound.js"></script>
	<script type="text/javascript" src="/assets/js/caman.full.js"></script> 
	
	<!-- Bring in some PHP to JS stuff here -->
	<script>
	
	 // shutter sound mp3 and loading gif preload
 $.ajax({url: "/assets/sounds/shutter.mp3"});
 $.ajax({url: "/assets/images/crop_ajax-loader.gif"});

		var session_id 			= '{$_SESSION['session_id']}';
		var station_ip 			= '{$_SESSION['station_ip']}';
		var var_folder 			= '{$_SESSION['http_var_folder']}';
		var location_abbr 		= '{$_SESSION['location_abbr']}';
		var location_name 		= '{$_SESSION['location_name']}';
		var location_printer  	= '{$_SESSION['location_printer']}';
		var preview_width		= '{$_SESSION['preview_width']}';
		var selected_x1			= 0;
		var selected_x2			= 0;
		var selected_y1 		= 0;
		var selected_y2			= 0;
		var selected_width		= 0;
		var selected_height		= 0;

	</script>

	<!-- document ready function, sourced from staff folder -->
	<script type="text/javascript" src="assets/js/id_generator.js"></script>

	
	
EOF;
echo $text;