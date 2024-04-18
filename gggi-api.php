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


function filterExclusions($params, $gggi) {
    $excludeCodes = [];
    foreach ($params as $param => $type) {
        if (isset($_GET[$param])) {
            $items = is_array($_GET[$param]) ? $_GET[$param] : [$_GET[$param]];
            foreach ($items as $item) {
                $codes = getCode($item, $type);
                $excludeCodes = array_merge($excludeCodes, $codes);
            }
        }
    }
    $excludeCodes = array_unique($excludeCodes);

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