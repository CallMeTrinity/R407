<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once $_SERVER['DOCUMENT_ROOT'] . '/utils.php';

    $gggi = getCSVData($_SERVER['DOCUMENT_ROOT'] . '/gggi.csv');
    $codes = getCSVData($_SERVER['DOCUMENT_ROOT'] . '/codes.csv');

    header('Content-type: application/json; charset=utf-8');
    $json = array(
        'status' => true,
        'nb' => 0,
        'args' => $_GET,
        'data' => array()
    );

    // TODO

    echo json_encode($json);
?>
