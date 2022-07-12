<?php
session_start();
echo json_encode($_SESSION["entries"][($_POST["day"] < 10 ? "0" : "") . $_POST["day"] . ";" . $_POST["contId"]]);
?>