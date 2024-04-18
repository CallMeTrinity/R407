<?php

declare(strict_types=1);
ini_set('memory_limit', '256M');
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

$code = null;
$country = null;
$subRegion = null;
$region = null;
if (empty($json['args'])) {
    $json['data'] = $gggi;
    $json['nb'] = sizeof($gggi);
}

$parameters = ['code', 'country', 'subregion', 'region'];

foreach ($parameters as $param) {
    handleRequest($param);
    handleArrayRequest($param);
}

//if (isset($_GET['indicator'])) {
//    $element = $_GET['indicator'];
//    for ($i = 0, $iMax = sizeof($gggi); $i < $iMax; $i++) {
//        if ($gggi[$i]['indicator'] === $element) {
//            $json['data'][] = $gggi[$i];
//            $json['nb']++;
//        }
//    }
//}
//if (isset($_GET['year'])) {
//    $element = $_GET['year'];
//    for ($i = 0, $iMax = sizeof($gggi); $i < $iMax; $i++) {
//        if ($gggi[$i]['year'] === $element) {
//            $json['data'][] = $gggi[$i];
//            $json['nb']++;
//        }
//    }
//}
//if (isset($_GET['indicator']) && is_array($_GET['indicator'])) {
//    $elements = $_GET['indicator'];
//    foreach ($elements as $element) {
//        for ($i = 0, $iMax = sizeof($gggi); $i < $iMax; $i++) {
//            if ($gggi[$i]['indicator'] === $element) {
//                $json['data'][] = $gggi[$i];
//                $json['nb']++;
//            }
//        }
//    }
//
//}
//if (isset($_GET['year']) && is_array($_GET['year'])) {
//    $elements = $_GET['year'];
//    foreach ($elements as $element) {
//        for ($i = 0, $iMax = sizeof($gggi); $i < $iMax; $i++) {
//            if ($gggi[$i]['year'] === $element) {
//                $json['data'][] = $gggi[$i];
//                $json['nb']++;
//            }
//        }
//    }
//}

//if (isset($_GET['year'], $_GET['indicator'])) {
//    $indicator = $_GET['indicator'];
//    $year = $_GET['year'];
//
//    for ($i = 0, $iMax = sizeof($gggi); $i < $iMax; $i++) {
//        if ($gggi[$i]['year'] === $year && $gggi[$i]['indicator'] === $indicator) {
//            $json['data'][] = $gggi[$i];
//            $json['nb']++;
//        }
//    }
//}
if (isset($_GET['year'], $_GET['indicator']) && is_array($_GET['year'])) {
    $indicator = $_GET['indicator'];
    $years = $_GET['year'];

    for ($i = 0, $iMax = sizeof($gggi); $i < $iMax; $i++) {
        foreach ($years as $year) {
            if ($gggi[$i]['year'] === $year && $gggi[$i]['indicator'] === $indicator) {
                $json['data'][] = $gggi[$i];
                $json['nb']++;
            }
        }
    }
}

if (isset($_GET['year'], $_GET['code'])) {
    $code = $_GET['code'];
    $year = $_GET['year'];

    for ($i = 0, $iMax = sizeof($gggi); $i < $iMax; $i++) {
        if ($gggi[$i]['year'] === $year && $gggi[$i]['code'] === $code) {
            $json['data'][] = $gggi[$i];
            $json['nb']++;
        }
    }
}

if (isset($_GET['exclude'])) {
    $params = [];
    if (isset($_GET['code'])) {
        $params['code'] = 'code';
    }
    if (isset($_GET['region'])) {
        $params['region'] = 'region';
    }
    if (isset($_GET['subregion'])) {
        $params['subregion'] = 'subregion';
    }

    $filteredData = filterExclusions($params, $gggi);
    $json['data'] = array_merge($json['data'], $filteredData);
    $json['nb'] += count($filteredData);
}


function filterExclusions($params, $gggi)
{
    $excludeCodes = [];
    foreach ($params as $param => $type) {
        $items = is_array($_GET[$param]) ? $_GET[$param] : [$_GET[$param]];
        foreach ($items as $item) {
            $code = getCode($item, $type);
            foreach ($code as $c) {
                $excludeCodes[] = $c;
            }
        }
    }

    $filteredData = [];
    foreach ($gggi as $item) {
        if (!in_array($item['code'], $excludeCodes, true)) {
            $filteredData[] = $item;
        }
    }
    return $filteredData;
}

function handleArrayRequest(string $query): void
{
    global $json;
    if (isset($_GET[$query]) && !isset($_GET['exclude']) && is_array($_GET[$query])) {
        $codeArray = $_GET[$query];
        foreach ($codeArray as $item) {
            $code = getCode($item, $query);
            foreach ($code as $el) {
                $json = setData($el);
            }
        }
    }

}

function handleRequest(string $query): void
{
    global $json;
    if (isset($_GET[$query]) && !isset($_GET['exclude'])) {
        $country = $_GET[$query];
        $code = getCode($country, $query);
        foreach ($code as $el) {
            $json = setData($el);
        }
    }
}

function setData($arg): array
{
    global $gggi, $json;
    for ($i = 0, $iMax = sizeof($gggi); $i < $iMax; $i++) {
        if ($gggi[$i]['code'] === $arg) {
            $json['data'][] = $gggi[$i];
            $json['nb']++;
        }
    }
    return $json;
}

function getCode($country, $param): array
{
    global $codes;
    $code = [];
    for ($i = 0, $iMax = sizeof($codes); $i < $iMax; $i++) {
        if ($codes[$i][$param] === $country) {
            $code[] = $codes[$i]['code'];
        }
    }
    return $code;
}

echo json_encode($json, JSON_THROW_ON_ERROR);
?>