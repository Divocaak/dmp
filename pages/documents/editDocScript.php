<?php
session_start();
require_once "../../config.php";

$e = "";
$target_file = "../uploads/" . basename($_FILES["documentFile"]["name"]);
$finalFileName = $_SESSION["documents"][$_GET["docId"]]["fileName"];
if(!empty($_FILES)){
  $finalFileName = str_replace(".pdf", "", basename($_FILES["documentFile"]["name"]));
  if (!unlink("../uploads/" . $_SESSION["documents"][$_GET["docId"]]["fileName"] . ".pdf")) {
    $e = "Při odstraňování PDF smlouvy nastala chyba.";
  } else { 
    if (file_exists($target_file)) {
      $e = "Soubor se stejným názvem již existuje. ";
    } else if(strtolower(pathinfo($target_file, PATHINFO_EXTENSION)) != "pdf") {
      $e = "Lze nahrát jen soubory formátu PDF. ";
    } else {
      if (!move_uploaded_file($_FILES["documentFile"]["tmp_name"], $target_file)) {
        $e .= "Při nahrávání souboru došlo k chybě. ";
      }
    }
  }
}

$sql = "UPDATE document SET label='" . $_POST["label"] . "', date_start='" . $_POST["dateStart"] . "', date_end=" . ($_POST["dateEnd"] != "" ? ("'" . $_POST["dateEnd"] . "'") : "NULL") . ", 
cash_rate='" . $_POST["cashRate"] . "', file_name='" . $finalFileName . "' WHERE id=" . $_GET["docId"] . ";";
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
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Smlouva byla upravena' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-outline-secondary" href="docList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít na seznam smluv</a>
</body>

</html>
