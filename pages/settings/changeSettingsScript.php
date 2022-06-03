<?php
require_once "../../config.php";
session_start();

$e = "";
$sql = "INSERT INTO table (id, name, age) VALUES (1, 'A', 19) ON DUPLICATE KEY UPDATE name='A', age=19";

/* $sql = "INSERT INTO employee (f_name, " . ($_POST["mname"] != "" ? "m_name, " : "") . " l_name, b_date, student, maternity, hpp)
VALUES ('" . $_POST["fname"] . "'" . ($_POST["mname"] != "" ? (", '" . $_POST["mname"] . "'") : "") . ", '" . $_POST["lname"] . "', '" . $_POST["bdate"] . "', " . (isset($_POST["student"]) ? "1" : "0") . ", " . (isset($_POST["maternity"]) ? "1" : "0") . ", " . (isset($_POST["hpp"]) ? "1" : "0") . ");"; */
echo $sql;
/* if (!mysqli_query($link, $sql)) {
    $e = $sql . "<br>" . mysqli_error($link);
} */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Odpověď ze serveru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="text-center m-5 p-5">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Nastavení bylo změněno' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-outline-secondary" href="../../index.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít zpět do menu</a>
</body>

</html>