<?php
require_once "config.php";
session_start();

$e = "";
$sql = "SELECT name, value, color FROM defaults WHERE status=1;";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $settings[$row[0]] = [
            "value" => $row[1],
            "color" => $row[2]
        ];
    }
    mysqli_free_result($result);
    $_SESSION["settings"] = $settings;
} else {
    $e = $sql . "<br>" . mysqli_error($link);
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Vladykův dvůr</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="text-center m-5 p-5">
    <h1 class="pb-3">Vladykův dvůr IS</h1>
    <a class="btn btn-outline-secondary" href="pages/settings/settings.php"><i class="pe-2 bi bi-gear"></i>Nastavení</a>
    <div class="py-3">
        <a class="btn btn-outline-primary" href="pages/employees/empList.php"><i class="pe-2 bi bi-person"></i>Zaměstnanci</a>
        <a class="btn btn-outline-primary" href="pages/documents/docList.php"><i class="pe-2 bi bi-file-earmark-text"></i>Smlouvy</a>
        <a class="btn btn-outline-primary" href="pages/contracts/contList.php"><i class="pe-2 bi bi-person-workspace"></i>Pracovní vztahy</a>
    </div>
    <a class="btn btn-outline-primary" href="pages/entries/entList.php"><i class="pe-2 bi bi-pen"></i>Zápis</a>
    <a class="btn btn-outline-primary" href="pages/report/repList.php"><i class="pe-2 bi bi-graph-up"></i>Souhrn</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>