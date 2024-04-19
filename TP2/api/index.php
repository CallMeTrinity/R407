<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once $_SERVER['DOCUMENT_ROOT'] . '/api/bd.php';

    class API extends BDHandler {
        // TODO
        //
        //        public function insertExample() {
        //            $req  = "INSERT INTO Table (colA, colB) VALUES ('a', 200)";
        //            $this->instruct($req);
        //        }
        //
        //        public function selectExample() {
        //            $req = "SELECT * FROM Table";
        //            return $this->select($req);
        //        }
        //
        //        public function updateExample() {
        //            $req  = "UPDATE Table SET colA = 'titi', colB = 300";
        //            $this->instruct($req);
        //        }
        //
        //        public function deleteExample() {
        //            $req = "DELETE FROM Table WHERE id = 4";
        //            $this->instruct($req);
        //        }
    }

    header('Content-type: application/json; charset=utf-8');
    $res = array(
        'status' => false
    );

    $api = new API();
    // TODO

    echo json_encode($res);
?>