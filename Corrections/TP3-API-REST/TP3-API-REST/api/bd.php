<?php

    class BDHandler {
        public $DB = 'sqlite:bd.sqlite';
        public $USER = '';
        public $PWD = '';
        public $conn;

        public function __construct() {
            $this->conn = NULL;
        }

        public function connect() {
            $this->disconnect();
            try{
                $this->conn = new PDO($this->DB, $this->USER, $this->PWD);
            } catch (PDOException $e){
                die ("Failed: ".$e);
            }
        }

        public function disconnect() {
            if ($this->isConnected()) {
                $this->conn->query('KILL CONNECTION_ID()');
                $this->conn = NULL;
            }
        }

        private function isConnected() {
            return $this->conn != NULL;
        }

        public function query($req) {
            if (!$this->isConnected()) { return false; }
            return $this->conn->query($req);
        }

        public function select($req) {
            $this->connect();
            $res = $this->query($req);
            $rows = $res->fetchAll();
            $this->disconnect();
            return $rows;
        }

        public function instruct($req) {
            $this->connect();
            $res = $this->query($req);
            $this->disconnect();
            return $res;
        }
    }

?>