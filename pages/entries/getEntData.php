<?php
session_start();
echo json_encode($_SESSION["entries"][$_POST["index"]]);
?>