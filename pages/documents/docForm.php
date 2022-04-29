<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $_GET["add"] ? "Přidat" : "Upravit";?> smlouvu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="text-center m-5 p-5">
    <div class="pb-3">
        <a class="btn btn-outline-secondary" href="docList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět</a>
        <h1 class="d-inline-block ms-2"><?php echo $_GET["add"] ? "Přidat" : "Upravit";?> smlouvu</h1>
    </div>
    <form class="needs-validation" novalidate action=<?php echo $_GET["add"] ? "addDocScript.php" : "editDocScript.php?empId=" . $_GET["empId"];?> method="post" enctype="multipart/form-data">
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="label" name="label" required maxlength="100" value="<?php echo !$_GET["add"] ? $_SESSION["documents"][$_GET["docId"]]["label"] : "";?>">
        <label for="label">Název</label>
    </div>
    <div class="form-floating mb-3">
        <input type="date" class="form-control" id="dateStart" name="dateStart" required value="<?php echo !$_GET["add"] ? $_SESSION["documents"][$_GET["docId"]]["dateStart"] : "";?>">
        <label for="dateStart">Od</label>
    </div>
    <div class="form-floating mb-3">
        <input type="date" class="form-control" id="dateEnd" name="dateEnd" value="<?php echo !$_GET["add"] ? $_SESSION["documents"][$_GET["docId"]]["dateEnd"] : "";?>">
        <label for="dateEnd">Do</label>
    </div>
    <div class="form-floating mb-3">
        <input type="number" class="form-control" id="cashRate" name="cashRate" required value="<?php echo !$_GET["add"] ? $_SESSION["documents"][$_GET["docId"]]["cashRate"] : "";?>">
        <label for="cashRate">Hodinová mzda [Kč/h]</label>
    </div>
    <div class="mb-3 text-start">
        <label for="documentFile" class="form-label">Sken smlouvy</label>
        <?php
            if(!$_GET["add"]){
                echo '<br>Nahraný dokument: <a class="btn btn-outline-info" href="uploads/' . $_SESSION["documents"][$_GET["docId"]]["fileName"] . '.pdf" target="_blank"><i class="bi bi-eye"></i> ' . $_SESSION["documents"][$_GET["docId"]]["fileName"] . '.pdf</a>';
                echo '<div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="toggleFileInput">
                        <label class="form-check-label" for="toggleFileInput">Chci změnit sken smlouvy</label>
                    </div>';
            }
        ?>
        <!-- TODO upload new file btn? -->
        <input type="file" class="form-control" id="documentFile" name="documentFile" required <?php echo !$_GET["add"] ? "disabled" : "";?>>
    </div>
    <button type="submit" class="btn btn-outline-primary"><i class="pe-2 bi bi-<?php echo $_GET["add"] ? "file-earmark-plus" : "pencil";?>"></i><?php echo $_GET["add"] ? "Přidat" : "Upravit";?> smlouvu</button>
</form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function (){
        $("#toggleFileInput").on("click", function(){
            $("#documentFile").prop('disabled', !($(this).is(':checked')));
        });
    });

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