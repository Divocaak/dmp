<?php
require_once "../../config.php";
$sql = "INSERT INTO report_" . ($_POST["contract"] === "true" ? "contract" : "employee") . " (id_" . ($_POST["contract"] === "true" ? "contract" : "employee") . ", month, year, resolved)
        VALUES (" . $_POST["id"] . ", " . $_POST["month"] . ", " . $_POST["year"] . ", " . $_POST["status"] . ")
        ON DUPLICATE KEY UPDATE resolved=" . $_POST["status"] . ";";

if (!mysqli_query($link, $sql)) {
    echo "Chyba" . $sql . "<br>" . mysqli_error($link);
}else{
    echo "Status  upraven";
}
?>