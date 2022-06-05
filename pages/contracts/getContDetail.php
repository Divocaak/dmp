<?php
session_start();
echo json_encode($_SESSION["contracts"][$_POST["index"]]);
?>