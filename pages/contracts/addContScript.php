<?php
require_once "../config.php";

$e = "";
$sql = "INSERT INTO contract (id_employee, id_document, max_hours, max_cash" . ($_POST["note"] != "" ? ", note" : "") . ")
VALUES (" . $_POST["empSelect"] . ", " . $_POST["docSelect"] . ", " . $_POST["maxHours"] . ", " . $_POST["maxCash"] . ($_POST["note"] != "" ? (", '" . $_POST["note"] . "'") : "") . ");";
if (!mysqli_query($link, $sql)) {
    $e = $sql . "<br>" . mysqli_error($link);
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
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Pracovní vztah byl uložen do systému' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-outline-secondary" href="contList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít na seznam pracovních vztahů</a>
    <a class="btn btn-outline-secondary" href="contForm.php"><i class="bi bi-person-workspace"></i><i class="pe-2 bi bi-plus"></i>Přidat další pracovní vztah</a>
</body>

</html>