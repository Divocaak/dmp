<?php
require_once "../../config.php";

$e = "";
$sql = "INSERT INTO defaults (" . getKeys() . ") VALUES (" . getValues() . ") ON DUPLICATE KEY UPDATE " . getDuplicateKeyUpdate() . ";";
echo $sql;
if (!mysqli_query($link, $sql)) {
    $e = $sql . "<br>" . mysqli_error($link);
}

function getKeys(){
    $toRet = "";
    foreach($_POST as $key => $value){
        $toRet .= $key . ($value == end($_POST) ? "" : ", ");
    }
    return $toRet;
}

function getValues(){
    $toRet = "";
    foreach($_POST as $value){
        $toRet .= "'" . $value . ($value == end($_POST) ? "'" : "', ");
    }
    return $toRet;
}

function getDuplicateKeyUpdate(){
    $toRet = "";
    foreach($_POST as $key => $value){
        $toRet .= $key . "='" . $value . ($value == end($_POST) ? "'" : "', ");
    }
    return $toRet;
}
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