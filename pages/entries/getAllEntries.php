<?php
require_once "../../config.php";
session_start();

$sql = "SELECT e.id, e.date, e.minutes, def.name, def.value, def.color, c.id, c.max_hours, c.max_cash, c.note, d.label, d.date_start, d.date_end, d.cash_rate 
        FROM entry e LEFT JOIN defaults def ON e.id_category=def.name INNER JOIN contract c ON e.id_contract=c.id INNER JOIN document d ON c.id_document=d.id
        WHERE YEAR(e.date)=" . $_POST["year"] . " AND MONTH(e.date)=" . $_POST["month"] . ";";
if ($result = mysqli_query($link, $sql)) {
    $entries = [];
    $contracts = [];
    while ($row = mysqli_fetch_row($result)) {
        $entries[$row[0]] = [
            "id" => $row[0],
            "date" => $row[1],
            "minutes" => $row[2],
            "tag" => [
                "id" => $row[3],
                "label" => $row[4],
                "color" => $row[5]
            ],
            "contract" => [
                "id" => $row[6],
                "maxHours" => $row[7],
                "maxCash" => $row[8],
                "note" => $row[9]
            ],
            "document" => [
                "label" => $row[10],
                "start" => $row[11],
                "end" => $row[12],
                "cashRate" => $row[13]
            ]
        ];
        if (!in_array($row[6], $contracts)) {
            $contracts[] = $row[6];
        }
    }
    mysqli_free_result($result);

    $_SESSION["entListData"] = [
        "month" => $_POST["month"],
        "year" => $_POST["year"],
        "entries" => $entries,
        "contracts" => $contracts
    ];
}
?>