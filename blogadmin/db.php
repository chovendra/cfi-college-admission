<?php
$db = new PDO('sqlite:../blogdata.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>