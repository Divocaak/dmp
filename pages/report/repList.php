<?php
require_once "../../config.php";
/* 
+-------------------+----------------------+-------------[0]------------+-------------[1]------------+------------[2]-----------+------------------[3]-----------------+---------------[4]-----------+--------------------------+--------------------+
| hodiny ze smlouvy | hodinovka ze smlouvy | [input] musí být vyplaceno | [input] reálně odpracované | vyplatit = [1] * [input] | doplatky (modal btn, zobariz částku) | celkem vyplatit = [2] + [3] | vrátit klubu = [0] - [4] | vyrovnáno (switch) |
+-------------------+----------------------+----------------------------+----------------------------+--------------------------+--------------------------------------+-----------------------------+--------------------------+--------------------+
*/

$reportContract = [];
$sql = "SELECT id_contract, to_pay, real_hours, real_to_pay, resolved FROM report_contract WHERE month=" . $_POST["month"] . " AND year=" . $_POST["year"] . ";";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $reportContract[$row[0]] = [
            "toPay" => $row[1],
            "realHours" => $row[2],
            "realToPay" => $row[3],
            "resolved" => $row[4]
        ];
    }
    mysqli_free_result($result);
}

$reportEmployee = [];
$sql = "SELECT id_employee, additional_payment, resolved FROM report_employee WHERE month=" . $_POST["month"] . " AND year=" . $_POST["year"] . ";";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $reportEmployee[$row[0]] = [
            "additionalPayment" => $row[1],
            "resolved" => $row[2]
        ];
    }
    mysqli_free_result($result);
}

$reportData = [];
$sql = "SELECT c.id_employee, c.max_hours, c.max_cash, SUM(e.minutes), c.id, d.label, d.cash_rate FROM contract c LEFT JOIN entry e ON e.id_contract=c.id RIGHT JOIN document d ON c.id_document=d.id 
        WHERE YEAR(e.date)=" . $_POST["year"] . " AND MONTH(e.date)=" . $_POST["month"] . " GROUP BY c.id_employee, c.id, c.max_cash, c.max_hours;";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        if(!isset($reportData[$row[0]]["additionalPayment"]) && !isset($reportData[$row[0]]["resolved"])){
            $reportData[$row[0]] = (isset($reportEmployee[$row[0]]) ? $reportEmployee[$row[0]] : ["additionalPayment" => 0, "resolved" => false]);
        }
        
        $reportData[$row[0]]["contracts"][$row[4]] = [
            "maxHours" => $row[1],
            "maxCash" => $row[2],
            "minutes" => $row[3],
            "label" => $row[5],
            "cashRate" => $row[6]
        ];
        $reportData[$row[0]]["contracts"][$row[4]] += (isset($reportContract[$row[4]]) ? $reportContract[$row[4]] : ["toPay" => 0, "realHours" => 0, "realToPay" => 0, "resolved" => false]);
    }
    mysqli_free_result($result);
}

echo json_encode($reportData);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Souhrn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="text-center m-5 p-5">
    <div class="pb-3">
        <a class="btn btn-outline-secondary" href="../../index.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět</a>
        <h1 class="d-inline-block ms-2">Souhrn</h1>
    </div>
    <form class="needs-validation" novalidate action="?" method="post">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <select class="form-select form-control" id="month" name="month" required>
                        <?php
                        $defVal = isset($_POST["month"]) ? $_POST["month"] : date("m");
                        $m = ["leden", "únor", "březen", "duben", "květen", "červen", "červenec", "srpen", "září", "říjen", "listopad", "prosinec"];
                        for ($i = 0; $i < count($m); $i++) {
                            echo "<option value=" . ($i + 1) . ($defVal == ($i + 1) ? " selected" : "") . ">" . $m[$i] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="month">Měsíc</label>
                </div>
            </div>
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="year" name="year" required value="<?php echo isset($_POST["year"]) ? $_POST["year"] : date("Y"); ?>">
                    <label for="year">Rok</label>
                </div>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-outline-primary"><i class="pe-2 bi bi-calendar-month"></i>Vybrat měsíc</button>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>