<?php
$kasutaja="annaoleks";
$parool="123456";
$andmebaas="annaoleks";
$srverinimi="localhost";

$yhendus=new mysqli($srverinimi,$kasutaja,$parool,$andmebaas);
$yhendus->set_charset("utf8");
?>