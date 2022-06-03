<?php
require_once "../../config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Přidat pracovní vztah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="text-center m-5 p-5">
    <div class="pb-3">
        <a class="btn btn-outline-secondary" href="contList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět</a>
        <h1 class="d-inline-block ms-2">Přidat pracovní vztah</h1>
    </div>
    <form class="needs-validation" novalidate action="addContScript.php" method="post">
        <div class="form-floating mb-3">
            <select class="form-select form-control" id="empSelect" name="empSelect" required>
                <?php
                $sql = "SELECT id, f_name, m_name, l_name, b_date FROM employee;";
                if ($result = mysqli_query($link, $sql)) {
                    while ($row = mysqli_fetch_row($result)) {
                        echo "<option value=" . $row[0] . ">" . $row[1] . ($row[2] != "" ? (" " . $row[2]) : "") . " " . $row[3] . " (*" . $row[4] . ")</option>";
                    }
                    mysqli_free_result($result);
                } else {
                    echo "<option selected>Někde se stala chyba</option>";
                }
                ?>
            </select>
            <label for="empSelect">Zaměstnanec</label>
        </div>
        <div class="form-floating mb-3">
            <select class="form-select form-control" id="docSelect" name="docSelect" required>
                <?php
                $sql = "SELECT id, label, date_start, cash_rate FROM document;";
                if ($result = mysqli_query($link, $sql)) {
                    while ($row = mysqli_fetch_row($result)) {
                        echo "<option value=" . $row[0] . ">" . $row[1] . " (od: " . $row[2] . ", za: " . $row[3] . " Kč/h)</option>";
                    }
                    mysqli_free_result($result);
                } else {
                    echo "<option selected>Někde se stala chyba</option>";
                }
                ?>
            </select>
            <label for="docSelect">Smlouva</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="maxHours" name="maxHours" required>
            <label for="maxHours">Maximální odpracovatelné hodiny</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="maxCash" name="maxCash" required>
            <label for="maxCash">Maximální odpracovatelná částka [Kč]</label>
        </div>
        <div class="form-floating mb-3">
            <textarea class="form-control" id="note" name="note" maxlength="200" rows="3"></textarea>
            <label for="note">Poznámka</label>
        </div>
        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-person-workspace"></i><i class="pe-2 bi bi-plus"></i>Přidat pracovní vztah</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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