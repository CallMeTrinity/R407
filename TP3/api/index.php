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
        //        }    }
    }

    function stop($status, $res) {
        $res['status'] = $status;
        echo json_encode($res);
        exit();
    }

    if (!isset($_SERVER['REQUEST_URI'])) { $_SERVER['REQUEST_URI'] = '/api/'; }
    $rest = explode('/', explode('?',$_SERVER['REQUEST_URI'])[0]);
    array_shift($rest);
    array_shift($rest);
    $args = $_GET;

    header('Content-type: application/json; charset=utf-8');
    $res = array(
        'status' => false,
        'rest' => $rest,
        'args' => $args,
        'msg' => 'Nothing happened.',
        'data' => 'nothing'
    );

    if (sizeof($rest) < 2) {
        $res['msg'] = 'No enough arguments.';
        stop(false, $res);
    }

    $resource = $rest[0];
    $method = $rest[1];

    // TODO

    stop(true, $res);
?>