<?php
require_once "../config.php";
session_start();

$e = "";
$documents = [];
$sql = "SELECT id, label, date_start, date_end, cash_rate, file_name FROM document;";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $documents[] = [
            "id" => $row[0],
            "label" => $row[1],
            "dateStart" => $row[2],
            "dateEnd" => $row[3],
            "cashRate" => $row[4],
            "fileName" => $row[5]
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
    <title>Seznam pracovních vztahů</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="text-center m-5 p-5">
    <div class="pb-3">
        <a class="btn btn-outline-secondary" href="../../index.html"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět</a>
        <h1 class="d-inline-block ms-2">Seznam pracovních vztahů</h1>
    </div>
    <a class="btn btn-outline-success" href="contForm.php"><i class="bi bi-person-workspace"></i><i class="pe-2 bi bi-plus"></i>Přidat pracovní vztah</a>
    <table class="mt-3 table table-striped table-hover">
        <caption>Seznam smluv</caption>
        <thead class="table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Název</th>
                <th scope="col">Od</th>
                <th scope="col">Do</th>
                <th scope="col">Hodinová mzda [Kč/h]</th>
                <th scope="col">Soubor</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            unset($_SESSION["documents"]);
            for ($i = 0; $i < count($documents); $i++) {
                $_SESSION["documents"][$documents[$i]["id"]] = $documents[$i];
                echo '<tr>
                    <th scope="row">' . ($i + 1) . '</th>
                    <td>' . $documents[$i]["label"] . '</td>
                    <td>' . $documents[$i]["dateStart"] . '</td>
                    <td>' . $documents[$i]["dateEnd"] . '</td>
                    <td>' . $documents[$i]["cashRate"] . '</td>
                    <td>
                        <a class="btn btn-outline-info" href="uploads/' . $documents[$i]["fileName"] . '.pdf" target="_blank"><i class="bi bi-eye"></i> ' . $documents[$i]["fileName"] . '.pdf</a>
                        <a class="btn btn-outline-secondary" href="uploads/' . $documents[$i]["fileName"] . '.pdf" download><i class="bi bi-download"></i></a>
                    </td>
                    <td><a class="btn btn-outline-primary" href="docForm.php?docId=' . $documents[$i]["id"] . '"><i class="bi bi-pencil"></i></a></td>
                    <td><a class="btn btn-outline-danger deleteBtn" data-doc-id="' . $documents[$i]["id"] . '" data-doc-file="' . $documents[$i]["fileName"] . '"><i class="bi bi-file-earmark-x"></i></a></td>
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
                    Skutečně chcete odstranit smlouvu ze systému?
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
            var docId;
            var docFile;
            $(".deleteBtn").click(function() {
                docId = $(this).data("docId");
                docFile = $(this).data("docFile");
                $('#confDeleteModal').modal('show');
            });

            $("#confDeleteBtn").click(function() {
                window.location = "delDocScript?id=" + docId + "&file=" + docFile;
            });
        });
    </script>
</body>

</html>