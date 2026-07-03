<?php
$sessionDir = __DIR__ . '/tmp_sessions';
if (!is_dir($sessionDir)) {
    mkdir($sessionDir, 0777, true);
}
ini_set('session.save_path', $sessionDir);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php
include("script_top.php");
?>
<body>
<?php 
include("google_tags.html"); 
include("script_header.html"); 
$show = isset($_REQUEST["show"]) ? $_REQUEST["show"]  : "home";
if($show=="home") {include("script_slider.html");}
$page = "page_".$show . ".html";
if(! file_exists($page)) $page = "404.html";
include($page);
include("script_footer.html");
include("script_chat.php");
include("script_bottom.php");
if($show=="home")
{
	//include("script_popup.php");
}	
?>
</body>
</html>