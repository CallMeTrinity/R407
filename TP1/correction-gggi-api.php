<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once $_SERVER['DOCUMENT_ROOT'] . '/utils.php';

    $gggi = getCSVData($_SERVER['DOCUMENT_ROOT'] . '/gggi.csv');
    $codes = getCSVData($_SERVER['DOCUMENT_ROOT'] . '/codes.csv');

    function build_key_sorter($key, $asc) {
        return function ($a, $b) use ($key, $asc) {
            if (is_string($a)) {
                if ($asc) {
                    return strcmp($a[$key], $b[$key]);
                } else {
                    return strcmp($b[$key], $a[$key]);
                }
            } else {
                if ($a[$key] == $b[$key]) { return 0; }
                else if ($asc) {
                    return ($a[$key] < $b[$key]) ? -1 : 1;
                } else {
                    return ($b[$key] < $a[$key]) ? -1 : 1;
                }
            }
        };
    }

    function flattenTable($table, $key) {
        $data = array();
        if (sizeof($table) > 0 and !isset($table[0][$key])) { return $data; }
        for ($k = 0; $k < sizeof($table); $k++) {
            $data[] = $table[$k][$key];
        }
        return $data;
    }

    function filterDataInTable($table, $key, $comp, $compVal) {
        $data = array();
        if (sizeof($table) > 0 and !isset($table[0][$key])) { return $data; }

        switch ($comp) {
            case 'max':
                usort($table, build_key_sorter($key, false));
                for ($k = 0; $k < sizeof($table) and $k < $compVal; $k++) {
                    $data[] = $table[$k];
                }
                break;
            case 'min':
                usort($table, build_key_sorter($key, true));
                for ($k = 0; $k < sizeof($table) and $k < $compVal; $k++) {
                    $data[] = $table[$k];
                }
                break;
            case '==':
                for ($k = 0; $k < sizeof($table); $k++) {
                    if ($table[$k][$key] == $compVal) { $data[] = $table[$k]; }
                }
                break;
            case '!=':
                for ($k = 0; $k < sizeof($table); $k++) {
                    if ($table[$k][$key] != $compVal) { $data[] = $table[$k]; }
                }
                break;
            case '<':
                for ($k = 0; $k < sizeof($table); $k++) {
                    if ($table[$k][$key] < $compVal) { $data[] = $table[$k]; }
                }
                break;
            case '<=':
                for ($k = 0; $k < sizeof($table); $k++) {
                    if ($table[$k][$key] <= $compVal) { $data[] = $table[$k]; }
                }
                break;
            case '>':
                for ($k = 0; $k < sizeof($table); $k++) {
                    if ($table[$k][$key] > $compVal) { $data[] = $table[$k]; }
                }
                break;
            case '>=':
                for ($k = 0; $k < sizeof($table); $k++) {
                    if ($table[$k][$key] >= $compVal) { $data[] = $table[$k]; }
                }
                break;
            case 'in':
                for ($k = 0; $k < sizeof($table); $k++) {
                    if (in_array($table[$k][$key], $compVal)) { $data[] = $table[$k]; }
                }
                break;
            case 'out':
                for ($k = 0; $k < sizeof($table); $k++) {
                    if (!in_array($table[$k][$key], $compVal)) { $data[] = $table[$k]; }
                }
                break;
            default:
                break;
        }

        return $data;
    }

    function GETCountriesfilter($countries, $key, $excl) {
        global $codes;
        if ($countries == 'all') { $countries = $codes; }
        $comp = $excl ? '!=' : '==';
        if (is_array($_GET[$key])) {
            $comp = $excl ? 'out' : 'in';
        }
        return filterDataInTable($countries, $key, $comp, $_GET[$key]);;
    }

    function GETDatafilter($data, $key) {
        $comp = '==';
        if (is_array($_GET[$key])) {
            $comp = 'in';
        }
        return filterDataInTable($data, $key, $comp, $_GET[$key]);;
    }

    header('Content-type: application/json; charset=utf-8');
    $json = array(
        'status' => true,
        'nb' => 0,
        'args' => $_GET,
        'data' => array()
    );

    // Data initialization
    $data = $gggi;

    // Geographic filters
    $countries = 'all';
    $excl = isset($_GET['exclude']);
    if (isset($_GET['code']) and $_GET['code'] != '') { $countries = GETCountriesfilter($countries, 'code', $excl); }
    if (isset($_GET['country']) and $_GET['country'] != '') { $countries = GETCountriesfilter($countries, 'country', $excl); }
    if (isset($_GET['subregion']) and $_GET['subregion'] != '') { $countries = GETCountriesfilter($countries, 'subregion', $excl); }
    if (isset($_GET['region']) and $_GET['region'] != '') { $countries = GETCountriesfilter($countries, 'region', $excl); }

    if (is_array($countries) and sizeof($countries) == 0) {
        $data = array();
    } else if (is_array($countries)){
        $countries = flattenTable($countries, 'code');
        $data = filterDataInTable($data, 'code', 'in', $countries);
    }

    // Index filters
    if (isset($_GET['indicator']) and $_GET['indicator'] != '') { $data = GETDatafilter($data, 'indicator'); }
    if (isset($_GET['year']) and $_GET['year'] != '') { $data = GETDatafilter($data, 'year'); }


    // Value filter
    if ((isset($_GET['type']) or isset($_GET['func']) or isset($_GET['funcval'])) and
        !(isset($_GET['type']) and $_GET['type'] != '' and
        isset($_GET['func']) and $_GET['func'] != '' and
        isset($_GET['funcval']) and $_GET['funcval'] != '')) {
        $json['data'] = 'If used, "type", "func" and "funcval" must all be set.';
        $json['status'] = false;
        echo json_encode($json);
        exit();
    }

    if (isset($_GET['type']) and !in_array($_GET['type'], array('index', 'rank'))) {
        $json['data'] = '"type" parameter should either have the value "index" or "rank".';
        $json['status'] = false;
        echo json_encode($json);
        exit();
    }

    if (isset($_GET['func']) and !in_array($_GET['func'], array('==', '!=', '<=', '<', '>=', '>', 'max', 'min'))) {
        $json['data'] = '"func" parameter should either have the value "==", "!=", "<=", "<", ">=", ">", "max" or "min".';
        $json['status'] = false;
        echo json_encode($json);
        exit();
    }

    if (isset($_GET['funcval']) and !is_numeric($_GET['funcval'])) {
        $json['data'] = '"funcval" parameter should be a numeric value.';
        $json['status'] = false;
        echo json_encode($json);
        exit();
    }

    if (isset($_GET['type'])) {
        $data = filterDataInTable($data, $_GET['type'], $_GET['func'], $_GET['funcval']);
    }

    $json['nb'] = sizeof($data);
    $json['data'] = $data;
    echo json_encode($json);
?>