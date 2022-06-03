<?php
require_once "../../config.php";
session_start();

$e = "";
$employees = [];
$sql = "SELECT id, f_name, m_name, l_name, b_date, student, maternity, hpp FROM employee;";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $employees[] = [
            "id" => $row[0],
            "fname" => $row[1],
            "mname" => $row[2],
            "lname" => $row[3],
            "bdate" => $row[4],
            "student" => $row[5],
            "maternity" => $row[6],
            "hpp" => $row[7]
        ];
    }
    mysqli_free_result($result);
} else {
    $e = $sql . "<br>" . mysqli_error($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Seznam zaměstnanců</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="text-center m-5 p-5">
    <div class="pb-3">
        <a class="btn btn-outline-secondary" href="../../index.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět</a>
        <h1 class="d-inline-block ms-2">Seznam zaměstnanců</h1>
    </div>
    <a class="btn btn-outline-success" href="empForm.php?add=1"><i class="pe-2 bi bi-person-plus"></i>Přidat zaměstnance</a>
    <table class="mt-3 table table-striped table-hover">
        <caption>Seznam zaměstnanců</caption>
        <thead class="table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Jméno</th>
                <th scope="col">Datum narození</th>
                <th scope="col">Student</th>
                <th scope="col">Mateřská dovolená</th>
                <th scope="col">HPP</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            unset($_SESSION["employees"]);
            for ($i = 0; $i < count($employees); $i++) {
                $_SESSION["employees"][$employees[$i]["id"]] = $employees[$i];
                echo '<tr>
                    <th scope="row">' . ($i + 1) . '</th>
                    <td>' . $employees[$i]["fname"] . ($employees[$i]["mname"] != null ? (" " . $employees[$i]["mname"]) : "") . " " . $employees[$i]["lname"] . '</td>
                    <td>' . $employees[$i]["bdate"] . '</td>
                    <td><i class="bi bi-' . ($employees[$i]["student"] == "1" ? "check-circle-fill text-success" : "x-circle-fill text-danger") . '"></i></td>
                    <td><i class="bi bi-' . ($employees[$i]["maternity"] == "1" ? "check-circle-fill text-success" : "x-circle-fill text-danger") . '"></i></td>
                    <td><i class="bi bi-' . ($employees[$i]["hpp"] == "1" ? "check-circle-fill text-success" : "x-circle-fill text-danger") . '"></i></td>
                    <td><a class="btn btn-outline-primary" href="empForm.php?empId=' . $employees[$i]["id"] . '"><i class="bi bi-pencil"></i></a></td>
                    <td><a class="btn btn-outline-danger deleteBtn" data-emp-id="' . $employees[$i]["id"] . '"><i class="bi bi-person-x"></i></a></td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
    <div class="modal fade" id="confDeleteModal" tabindex="-1" aria-labelledby="confDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Opravdu?</h5>
                </div>
                <div class="modal-body">
                    Skutečně chcete odstranit zaměstnance ze systému?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Zavřít</button>
                    <button type="button" class="btn btn-outline-danger" id="confDeleteBtn">Odstranit</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var empId;
            $(".deleteBtn").click(function() {
                empId = $(this).data("empId");
                $('#confDeleteModal').modal('show');
            });

            $("#confDeleteBtn").click(function() {
                window.location = "delEmpScript?id=" + empId;
            });
        });
    </script>
</body>

</html>