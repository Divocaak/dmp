<?php
require_once "../../config.php";

$e = "";
$sql = "INSERT INTO defaults (name, value, color) VALUES " . getValues() . " ON DUPLICATE KEY UPDATE value=" . duplicateValueUpdate() . ", color=" . duplicateColorUpdate() . ", status='1';";
if (!mysqli_query($link, $sql)) {
    $e = $sql . "<br>" . mysqli_error($link);
}

function getValues(){
    $toRet = "";
    foreach($_POST as $key => $value){
        if(preg_match("/tag([0-9])Color/", $key) != null){
            continue;
        }
        
        preg_match("/tag([0-9])Value/", $key, $valueMatchRet);
        $toRet .= "('" . (($valueMatchRet != []) ? ($valueMatchRet[1] . "', '" . $_POST[$key] . "', '" . str_replace("#", "", $_POST["tag" . $valueMatchRet[1] . "Color"]) . "'") : ($key . "', '" . $value . "', NULL")) . "), ";
    }
    return substr($toRet, 0, -2);
}

function duplicateValueUpdate(){
    $toRet = "(CASE";
    foreach($_POST as $key => $value){
        if(preg_match("/tag([0-9])Color/", $key) != null){
            continue;
        }
        
        preg_match("/tag([0-9])Value/", $key, $valueMatchRet);
        $elementKey = ($valueMatchRet != []) ? $valueMatchRet[1] : $key;
        $toRet .= " WHEN name='" . $elementKey . "' THEN '" . $_POST[$key] . "'";
    }
    return $toRet . " END)";
}

function duplicateColorUpdate(){
    $toRet = "(CASE";
    foreach($_POST as $key => $value){
        preg_match("/tag([0-9])Color/", $key, $colorMatchRet);
        $toRet .= ($colorMatchRet != []) ? (" WHEN name='" . $colorMatchRet[1] . "' THEN '" . str_replace("#", "", $_POST[$key]) . "'") : "";
    }
    return $toRet . " END)";
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