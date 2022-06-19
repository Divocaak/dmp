<?php
require_once "../../config.php";
session_start();

$e = "";
$settings = [];

$highestTagKey = 0;
$tags = [];

$sql = "SELECT name, value, color, status FROM defaults";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $settingName = $row[0];
        $setting = [
            "value" => $row[1],
            "color" => $row[2],
            "status" => $row[3]
        ];

        $settings[$settingName] = $setting;
        if ($setting["color"] != null) {
            $tags[$settingName] = $setting;

            $tagIndex = intval($settingName);
            $highestTagKey = ($tagIndex > strval($highestTagKey) ? $tagIndex : $highestTagKey);
        }
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
            <input type="number" class="form-control" id="maxHours" name="maxHours" value="<?php echo ($settings["maxHours"] != null ? $settings["maxHours"]["value"] : ""); ?>">
            <label for="maxHours">Maximální odpracovatelné hodiny</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="maxCash" name="maxCash" value="<?php echo ($settings["maxCash"] != null ? $settings["maxCash"]["value"] : ""); ?>">
            <label for="maxCash">Maximální odpracovatelná částka [Kč]</label>
        </div>
        <a class="btn btn-outline-success" id="addTagBtn"><i class="bi bi-tag"></i><i class="pe-2 bi bi-plus"></i>Přidat značku</a>
        <div class="table-responsive">
            <table class="mt-3 table table-striped table-hover" id="tableDataHolder" data-highest-key="<?php echo $highestTagKey; ?>">
                <caption>Seznam značek</caption>
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Název</th>
                        <th scope="col">Barva</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="tagTableBody">
                    <?php
                    foreach ($tags as $key => $tag) {
                        if ($tag["status"] == "1") {
                            $valueId = "tag" . $key . "Value";
                            $colorId = "tag" . $key . "Color";
                            echo '<tr>
                        <td><input type="text" class="form-control" id="' . $valueId . '" name="' . $valueId . '" value="' . $tag["value"] . '"></td>
                        <td><input type="color" class="form-control form-control-color" id="' . $colorId . '" name="' . $colorId . '" value="#' . $tag["color"] . '"></td>
                        <td><a class="btn btn-outline-danger removeTagBtn" data-tag-name="' . $key . '"><i class="bi bi-tag"></i><i class="bi bi-dash"></i></a></td>
                        </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-outline-primary"><i class="pe-2 bi bi-save"></i>Uložit změny</button>
    </form>
    <div class="modal fade" id="confDeleteModal" tabindex="-1" aria-labelledby="confDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Opravdu?</h5>
                </div>
                <div class="modal-body">
                    Skutečně chcete odstranit značku ze systému?<br>
                    Neuložené (žluté) značky budou ztraceny.
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
        var tagName;
        $(document).on('click', '.removeTagBtn', function() {
            var tagRow = $(this).parent().parent();
            if (tagRow.hasClass("table-warning")) {
                tagRow.remove();
            } else {
                tagName = $(this).data("tagName");
                $('#confDeleteModal').modal('show');
            }
        });

        $(document).ready(function() {
            $("#confDeleteBtn").click(function() {
                window.location = "delTagScript?id=" + tagName;
            });

            $("#addTagBtn").click(function() {
                var tableDataHolder = $("#tableDataHolder");
                var nextTagKey = tableDataHolder.data("highestKey") + 1;

                var tagElement = '<tr class="table-warning"><td><input type="text" class="form-control" id="tag' + nextTagKey +
                    'Value" name="tag' + nextTagKey + 'Value" value="značka ' + nextTagKey + '"></td><td><input type="color" class="form-control form-control-color" id="tag' + nextTagKey +
                    'Color" name="tag' + nextTagKey + 'Color" value="#000000"></td><td><a class="btn btn-outline-danger removeTagBtn" data-tag-name="' + nextTagKey +
                    '"><i class="bi bi-tag"></i><i class="bi bi-dash"></i></a></td></tr>';

                $("#tagTableBody").append(tagElement);
                tableDataHolder.data("highestKey", nextTagKey);
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