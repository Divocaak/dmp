<?php
define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Pgema333');
define('DB_NAME', 'mage');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_set_charset($link,"utf8");

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// NOTE settings
// game
$gameName = "Mage <i>(mby přejmenovat)</i> " . $version;
$version = "<i>(dev state)</i>";
// trees
$maxPoints = 10;
// elements
$elementGlossary = ["dmg" => "Damage"];
// casuals
// matchmaking
$maxOpenBattles = 5;
$maxWinRateDifference = 5; // TODO chceme v caualech i podle winrateu?
$maxLevelDifference = 5;
// hand making
$elementsPerIteration = 5;
$drawIterations = 2;
?>