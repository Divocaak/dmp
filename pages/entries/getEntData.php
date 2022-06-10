<?php
session_start();
echo json_encode($_SESSION["entListData"]["entries"][$_POST["index"]]);
?>