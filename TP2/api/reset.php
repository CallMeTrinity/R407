<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . '/api/bd.php';

    $bd = new BDHandler();
    $bd->connect();

    $query = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/api/bd.sql');
    $queries = preg_split('~\([^)]*\)(*SKIP)(*F)|;~', $query);
    array_pop($queries);
    foreach ($queries as $req) {
        $res = $bd->query($req);
        if (!$res) die("Failed query: " . $req);
    }

    $bd->disconnect();

    header('Location: /index.php');

?>