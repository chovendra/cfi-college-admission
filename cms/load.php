<?php
if(isset($_GET['file'])){
    $file = "../".basename($_GET['file']);
    if(file_exists($file)){
        echo file_get_contents($file);
    }
}
