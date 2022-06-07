<?php
require_once "../../config.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Zápis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="text-center m-5 p-5">
    <div class="pb-3">
        <a class="btn btn-outline-secondary" href="../../index.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět</a>
        <h1 class="d-inline-block ms-2">Zápis</h1>
    </div>
    <form class="needs-validation" novalidate action="addContScript.php" method="post">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <select class="form-select form-control" id="emp" name="emp" required>
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
                    <label for="emp">Zaměstnanec</label>
                </div>
            </div>
            <div class="col">
                <div class="form-floating mb-3">
                    <select class="form-select form-control" id="month" name="month" required>
                        <?php
                        $m = ["leden", "únor", "březen", "duben", "květen", "červen", "červenec", "srpen", "září", "říjen", "listopad", "prosinec"];
                        for ($i = 0; $i < count($m); $i++) {
                            echo "<option value=" . ($i + 1) . (($i + 1) == date("m") ? " selected" : "") . ">" . $m[$i] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="month">Měsíc</label>
                </div>
            </div>
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="year" name="year" required value="<?php echo date("Y"); ?>">
                    <label for="year">Rok</label>
                </div>
            </div>
            <div class="col">
                <a id="selectEmpBtn" class="btn btn-outline-primary"><i class="pe-1 bi bi-person-bounding-box"></i><i class="pe-2 bi bi-calendar-month"></i>Vybrat zaměstnance a měsíc</a>
            </div>
        </div>
    </form>


    <!-- <table class="mt-3 table table-striped table-hover">
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
        <tbody> -->
            <?php
            /* unset($_SESSION["employees"]);
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
            } */
            $month = 5;
            $year = 2022;

            $sql = "SELECT e.date, e.minutes, def.value, def.color, c.max_hours, c.max_cash, c.note, d.label, d.date_start, d.date_end, d.cash_rate 
            FROM entry e INNER JOIN defaults def ON e.id_category=def.name INNER JOIN contract c ON e.id_contract=c.id INNER JOIN document d ON c.id_document=d.id
            WHERE YEAR(e.date)=" . $year . " AND MONTH(e.date)=" . $month . ";";
            if ($result = mysqli_query($link, $sql)) {
                $entries = [];
                while ($row = mysqli_fetch_row($result)) {
                    $entries[] = [
                        "date" => $row[0],
                        "minutes" => $row[1],
                        "tag" => [
                            "label" => $row[2],
                            "color" => $row[3]
                        ],
                        "contract" => [
                            "maxHours" => $row[4],
                            "maxCash" => $row[5],
                            "note" => $row[6]
                        ],
                        "document" => [
                            "label" => $row[7],
                            "start" => $row[8],
                            "end" => $row[9],
                            "cashRate" => $row[10]
                        ]
                    ];
                }
                mysqli_free_result($result);

                for($day = 1; $day < cal_days_in_month(CAL_GREGORIAN, $month, $year) + 1; $day++){
                    foreach($entries as $entry){
                        if(DateTime::createFromFormat("Y-m-d", $entry["date"])->format("d") == $day){
                            echo $day . ": " . $entry["minutes"] . "<br>";
                        }
                    }

                    echo $day . "<br>";
                }
            } else {
                echo "eee";
            }
            
            ?>
        <!-- </tbody>
    </table> -->
    <!-- <div class="modal fade" id="confDeleteModal" tabindex="-1" aria-labelledby="confDeleteModalLabel" aria-hidden="true">
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
    </div> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script>
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
    </script> -->
</body>

</html>