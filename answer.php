<?php

session_start();

if (isset($_POST["reset"])){
    $_SESSION = array();
    session_destroy();
    return;
}

if (isset($_POST["save"])){
    echo "<table id='outputTable'>
        <tr>
        <th>x</th>
        <th>y</th>
        <th>r</th>
        <th>Точка входит в ОДЗ</th>
        <th>Текущее время</th>
        <th>Время работы скрипта</th>
        </tr>";
    if (isset($_SESSION["tableRows"])) {
        foreach ($_SESSION["tableRows"] as $tableRow)
            echo $tableRow;
    }
    echo "</table>";
    return;
}

$valid = true;
date_default_timezone_set('Europe/Moscow');
$xStr =  $_POST["x"];
$yStr =  $_POST["y"];
$rStr =  $_POST["r"];

$x = $xStr;
$y = $yStr;
$r = $rStr;

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(!is_numeric($x) || !is_numeric($y) || !is_numeric($r))
        $valid = false;
    if($y <= -5 || $y > 3)
        $valid = false;
    if($r < 2 || $r > 5)
        $valid = false;
    if(!in_array($x, array(-4, -3, -2, -1, 0, 1, 2, 3, 4)))
        $valid = false;

    if(!$valid){
        http_response_code(400);
        //header("Status: 400 bad request!", true, 400);
        exit;
    }
}

if(checkData($x, $y, $r)){
    if (!isset($_SESSION["tableRows"])) $_SESSION["tableRows"] = array();
    $coordinatesStatus = checkCoordinates($x, $y, $r);
    $currentTime = date("H : i : s");
    $benchmarkTime = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

    echo "<table id=\"outputTable\">
        <tr>
        <th>x</th>
        <th>y</th>
        <th>r</th>
        <th>Точка входит в ОДЗ</th>
        <th>Текущее время</th>
        <th>Время работы скрипта</th>
        </tr>";

    array_push($_SESSION["tableRows"], "<tr>
    <td>$x</td>
    <td>$y</td>
    <td>$r</td>
    <td>$coordinatesStatus</td>
    <td>$currentTime</td>
    <td>$benchmarkTime</td>
    </tr>");
    foreach ($_SESSION["tableRows"] as $tableRow) echo $tableRow;
    echo "</table>";
}else{
    http_response_code(400);
    return;
}

function checkData($x, $y, $r){
    return is_numeric($y) && ($y > -5 && $y < 3) &&
        in_array($x, array(-4, -3, -2, -1, 0, 1, 2, 3, 4)) &&
        is_numeric($r) && ($r > 2 && $r < 5);
}

function checkCoordinates($x, $y, $r){
    if ((($x >= -$r/2) && ($x <= 0) && ($y <= 0) && ($y <= $r)) ||
        (($x >= -$r) && ($x <= 0) && ($y >= 0) && ($y <= $r)) ||
        (($x >= 0) && ($x <= $r) && ($y >= 0) && ($y <= $r/2))) return "да";
    else return "нет";
}

session_write_close();
