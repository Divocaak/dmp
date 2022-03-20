<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $_GET["add"] ? "Přidat" : "Upravit";?> zaměstnance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="text-center m-5 p-5">
    <div class="pb-3">
        <a class="btn btn-outline-secondary" href="empList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět</a>
        <h1 class="d-inline-block ms-2"><?php echo $_GET["add"] ? "Přidat" : "Upravit";?> zaměstnance</h1>
    </div>
    <form class="needs-validation" novalidate action=<?php echo $_GET["add"] ? "addEmpScript.php" : "editEmpScript.php";?> method="post">
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="fname" name="fname" required maxlength="20">
        <label for="fname">Křestní jméno</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="mname" name="mname" maxlength="20">
        <label for="mname">Prostřední jméno</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="lname" name="lname" required maxlength="20">
        <label for="lname">Příjmení</label>
    </div>
    <div class="form-floating mb-3">
        <input type="date" class="form-control" id="bdate" name="bdate" required>
        <label for="bdate">Datum narození</label>
    </div>
    <div class="mb-3 form-check form-group text-start">
        <input type="checkbox" class="form-check-input" id="student" name="student">
        <label for="student">Status studenta</label>
    </div>
    <div class="mb-3 form-check form-group text-start">
        <input type="checkbox" class="form-check-input" id="maternity" name="maternity">
        <label for="maternity">Mateřská dovolená</label>
    </div>
    <div class="mb-3 form-check form-group text-start">
        <input type="checkbox" class="form-check-input" id="hpp" name="hpp">
        <label for="hpp">HPP</label>
    </div>
    <button type="submit" class="btn btn-outline-primary"><i class="pe-2 bi bi-<?php echo $_GET["add"] ? "person-plus" : "pencil";?>"></i><?php echo $_GET["add"] ? "Přidat" : "Upravit";?> zaměstnance</button>
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