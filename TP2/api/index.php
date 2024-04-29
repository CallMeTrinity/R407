<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/bd.php';

class API extends BDHandler
{
    //create

    public function createPerson(string $name, string $surname): bool
    {
        $query = "INSERT INTO Person (name, surname) VALUES (:name, :surname)";
        return $this->prepareAndExecute($query, [':name' => $name, ':surname' => $surname]);
    }

    private function prepareAndExecute($query, $params)
    {
        $this->connect();
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        unset($val);
        $success = $stmt->execute();
        $this->disconnect();
        return $success;
    }

    public function createMovie(string $name, int $year, string $director): bool
    {
        $directorId = $this->fetchSingleId('SELECT id FROM Person WHERE name = :director LIMIT 1', ':director', $director);
        $query = "INSERT INTO Movie (name, year, director) VALUES (:name, :year, :director)";
        return $this->prepareAndExecute($query, [':name' => $name, ':year' => $year, ':director' => $directorId]);
    }

    private function fetchSingleId($query, $paramKey, $paramValue)
    {
        $this->connect();
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam($paramKey, $paramValue);
        $stmt->execute();
        $result = $stmt->fetch();
        $this->disconnect();
        return $result['id'] ?? null;
    }

    public function createCast(string $person, string $movie): bool
    {
        $personId = $this->fetchSingleId('SELECT id FROM Person WHERE name = :person LIMIT 1', ':person', $person);
        $movieId = $this->fetchSingleId('SELECT id FROM Movie WHERE name = :movie LIMIT 1', ':movie', $movie);
        $query = "INSERT INTO Cast (person, movie) VALUES (:person, :movie)";
        return $this->prepareAndExecute($query, [':person' => $personId, ':movie' => $movieId]);
    }

    public function selectPerson(?string $name = '', ?string $surname = ''): ?array
    {
        $this->connect();
        $sql = 'SELECT * FROM Person WHERE name = :name OR surname = :surname';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->execute();
        $result = $stmt->fetch();
        $this->disconnect();
        if ($result){
            return [
                'id' => $result['id'],
                'name' => $result['name'],
                'surname' => $result['surname'],
            ];
        }
        return null;
    }

    public function selectAllPerson(): array
    {
        $this->connect();
        $results = $this->select('SELECT * FROM Person');
        $data = [];
        foreach ($results as $person) {
            $data[] = [
                'id' => $person['id'],
                'name' => $person['name'],
                'surname' => $person['surname'],
            ];
        }
        return $data;
    }

    public function selectMovie(?string $name, ?int $year, ?string $director) : ?array
    {

    }

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
    'status' => false,
    'msg' => '',
    'data' => [],
);

$api = new API();

$request = $_GET['type'] ?? '';

if (isset($_GET['crud'])) {
    if ($_GET['crud'] === 'c') {
        switch ($request) {
            case 'person':
                if (isset($_GET['name'], $_GET['surname'])) {
                    $res = $api->createPerson($_GET['name'], $_GET['surname']);
                }
                break;
            case 'movie':
                if (isset($_GET['name'], $_GET['year'], $_GET['director'])) {
                    $res = $api->createMovie($_GET['name'], $_GET['year'], $_GET['director']);
                }
                break;
            case 'cast':
                if (isset($_GET['person'], $_GET['movie'])) {
                    $res = $api->createCast($_GET['person'], $_GET['movie']);
                }
                break;
            default:
                $res['msg'] = 'Please enter a Type value';
        }
    }
    if ($_GET['crud'] === 'r') {
        switch ($request) {
            case 'person':
                if (isset($_GET['name']) || isset($_GET['surname'])) {
                    $res['data'] = $api->selectPerson($_GET['name']??'', $_GET['surname']??'');
                } else {
                    $res['data'] = $api->selectAllPerson();
                }
                break;
            case 'movie':
                if (isset($_GET['name'], $_GET['year'], $_GET['director'])) {
                    $res['data'] = $api->selectMovie($_GET['name'], $_GET['year'], $_GET['director']);
                }
                break;
            case 'cast':
                if (isset($_GET['person'], $_GET['movie'])) {
                    $res['data'] = $api->selectCast($_GET['person'], $_GET['movie']);
                }
                break;
            default:
                $res['msg'] = 'Please enter a Type value';
        }
    }
}
echo json_encode($res);
?>