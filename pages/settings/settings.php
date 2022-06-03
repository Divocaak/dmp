<?php
require_once "../../config.php";
session_start();

$e = "";
$settings = [];
$sql = "SELECT name, value, color FROM defaults;";
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
    <title>Nastavení</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="text-center m-5 p-5">
    <div class="pb-3">
        <a class="btn btn-outline-secondary" href="../../index.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět</a>
        <h1 class="d-inline-block ms-2">Nastavení</h1>
    </div>
    <form class="needs-validation" novalidate action="changeSettingsScript.php" method="post">
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="maxHours" name="maxHours" value="<?php if($settings["maxHours"] != null) {echo $settings["maxHours"];} ?>">
            <label for="maxHours">Maximální odpracovatelné hodiny</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="maxCash" name="maxCash" value="<?php if($settings["maxCash"] != null) {echo $settings["maxCash"];} ?>">
            <label for="maxCash">Maximální odpracovatelná částka [Kč]</label>
        </div>
        <button type="submit" class="btn btn-outline-primary"><i class="pe-2 bi bi-save"></i>Uložit změny</button>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script>
        (function() {
            "use strict"
            var forms = document.querySelectorAll(".needs-validation")
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener("submit", function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add("was-validated")
                    }, false)
                })
        })()
    </script>
</body>

</html>