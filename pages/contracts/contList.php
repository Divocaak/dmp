<?php
require_once "../../config.php";
session_start();

$e = "";
$contracts = [];
$sql = "SELECT c.id, c.max_hours, c.max_cash, c.note, e.f_name, e.m_name, e.l_name, e.b_date, e.student, e.maternity, e.hpp, d.label, d.date_start, d.date_end, d.cash_rate, d.file_name 
    FROM contract c INNER JOIN employee e ON c.id_employee=e.id INNER JOIN document d ON c.id_document=d.id;";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $contracts[] = [
            "cont" => [
                "id" => $row[0],
                "maxHours" => $row[1],
                "maxCash" => $row[2],
                "note" => $row[3]
            ],
            "emp" => [
                "fName" => $row[4],
                "mName" => $row[5],
                "lName" => $row[6],
                "bDate" => $row[7],
                "student" => $row[8],
                "maternity" => $row[9],
                "hpp" => $row[10]
            ],
            "doc" => [
                "label" => $row[11],
                "start" => $row[12],
                "end" => $row[13],
                "cashRate" => $row[14],
                "fileName" => $row[15]
            ]
        ];
    }
    $_SESSION["contracts"] = $contracts;
    mysqli_free_result($result);
} else {
    $e = $sql . "<br>" . mysqli_error($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Seznam pracovních vztahů</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="text-center m-5 p-5">
    <div class="pb-3">
        <a class="btn btn-outline-secondary" href="../../index.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět</a>
        <h1 class="d-inline-block ms-2">Seznam pracovních vztahů</h1>
    </div>
    <a class="btn btn-outline-success" href="contForm.php"><i class="bi bi-person-workspace"></i><i class="ps-1 pe-2 bi bi-plus"></i>Přidat pracovní vztah</a>
    <div class="table-responsive">
        <table class="mt-3 table table-striped table-hover">
            <caption>Seznam smluv</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Limity</th>
                    <th scope="col">Zaměstnanec</th>
                    <th scope="col">Začátek poměru</th>
                    <th scope="col">Konec poměru</th>
                    <th scope="col">Hodinová mzda [Kč]</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < count($contracts); $i++) {
                    echo '<tr">
                    <th scope="row">' . ($i + 1) . '</th>
                    <td>' . $contracts[$i]["cont"]["maxHours"] . " hodin, " . $contracts[$i]["cont"]["maxCash"] . ' Kč</td>
                    <td>' . $contracts[$i]["emp"]["fName"] . ($contracts[$i]["emp"]["mName"] != null ? (" " . $contracts[$i]["emp"]["mName"]) : "") . " " . $contracts[$i]["emp"]["lName"] . '</td>
                    <td>' . $contracts[$i]["doc"]["start"]  . '</td>
                    <td>' . $contracts[$i]["doc"]["end"] . '</td>
                    <td>' . $contracts[$i]["doc"]["cashRate"] . '</td>
                    <td><a class="btn btn-outline-info detailBtn" data-cont-index="' . $i . '"><i class="bi bi-person-workspace pe-1"></i><i class="bi bi-eye"></i></a></td>
                    <td><a class="btn btn-outline-danger deleteBtn" data-cont-id="' . $contracts[$i]["cont"]["id"] . '"><i class="bi bi-person-workspace pe-1"></i><i class="bi bi-trash"></i></a></td>
                </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="confDeleteModal" tabindex="-1" aria-labelledby="confDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Opravdu?</h5>
                </div>
                <div class="modal-body">
                    Skutečně chcete odstranit pracovní vztah ze systému?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Zavřít</button>
                    <button type="button" class="btn btn-outline-danger" id="confDeleteBtn">Odstranit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Podrobnosti</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p id="detailContLimits"></p>
                            <p id="detailContNote"></p>
                        </div>
                        <div class="col-6">
                            <p id="detailEmpName"></p>
                            <p id="detailEmpBDate"></p>
                            <p>Student: <i class="" id="detailEmpStudent"></i></p>
                            <p>Mateřská dovolená: <i class="" id="detailEmpMaternity"></i></p>
                            <p>HPP: <i class="" id="detailEmpHpp"></i></p>
                        </div>
                        <div class="col-6">
                            <p id="detailDocLabel"></p>
                            <p id="detailDocStart"></p>
                            <p id="detailDocEnd"></p>
                            <p id="detailDocCashRate"></p>
                            <a id="detailDocFileBtn" class="btn btn-outline-info" href="" target="_blank"><i class="bi bi-eye"></i>
                                <p id="detailDocFileName"></p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".detailBtn").click(function() {
                $.post("getContDetail.php", {
                    index: $(this).data("contIndex")
                }, function(data) {
                    var dataDecoded = JSON.parse(data);
                    $("#detailContLimits").text("Limity: " + dataDecoded["cont"]["maxHours"] + " hodin, " + dataDecoded["cont"]["maxCash"] + "Kč");
                    $("#detailContNote").text("Poznámka: " + dataDecoded["cont"]["note"]);
                    $("#detailEmpName").text("Jméno: " + dataDecoded["emp"]["fName"] + (dataDecoded["emp"]["mName"] != null ? (" " + dataDecoded["emp"]["mName"]) : "") + " " + dataDecoded["emp"]["lName"]);
                    $("#detailEmpBDate").text("Datum narození: " + dataDecoded["emp"]["bDate"]);
                    $("#detailEmpStudent").attr('class', "bi bi-" + (dataDecoded["emp"]["student"] == "1" ? "check-circle-fill text-success" : "x-circle-fill text-danger"));
                    $("#detailEmpMaternity").attr('class', "bi bi-" + (dataDecoded["emp"]["maternity"] == "1" ? "check-circle-fill text-success" : "x-circle-fill text-danger"));
                    $("#detailEmpHpp").attr('class', "bi bi-" + (dataDecoded["emp"]["hpp"] == "1" ? "check-circle-fill text-success" : "x-circle-fill text-danger"));
                    $("#detailDocLabel").text("Název: " + dataDecoded["doc"]["label"]);
                    $("#detailDocStart").text("Začátek: " + dataDecoded["doc"]["start"]);
                    $("#detailDocEnd").text("Konec: " + dataDecoded["doc"]["end"]);
                    $("#detailDocCashRate").text("Hodinová mzda: " + dataDecoded["doc"]["cashRate"] + " Kč/h");
                    $("#detailDocFileName").text(dataDecoded["doc"]["fileName"] + ".pdf");
                    $("#detailDocFileBtn").attr("href", "../uploads/" + dataDecoded["doc"]["fileName"] + ".pdf")

                    $('#detailModal').modal('show');
                });
            });

            var contId;
            $(".deleteBtn").click(function() {
                contId = $(this).data("contId");
                $('#confDeleteModal').modal('show');
            });

            $("#confDeleteBtn").click(function() {
                window.location = "delContScript.php?id=" + contId;
            });
        });
    </script>
</body>

</html>