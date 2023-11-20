<?php
    require_once "./config/app.php";
    require_once "./autoload.php";

    if(isset($_GET['views'])){
        $url=explode("/",$_GET['views']);
    }else{
        $url= ["login"];
    }
?>