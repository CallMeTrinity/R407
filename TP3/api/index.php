<?php

use JetBrains\PhpStorm\NoReturn;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/bd.php';

class API extends BDHandler
{
    public function listIngredients(): array
    {
        return $this->select('SELECT * FROM Ingredient');
    }

    public function listRecipe(?string $name = ''): array
    {
        if (!$name) {
            return $this->select('SELECT * FROM Recipe');
        }
        $req = 'SELECT * FROM Recipe WHERE name = ?';
        $this->connect();
        $stmt = $this->conn->prepare($req);
        $stmt->execute([$name]);
        return $stmt->fetchAll();
    }

    public function listPreparation(int $recipe): array
    {
        $req = 'SELECT * FROM Preparation WHERE recipe = ?';
        $this->connect();
        $stmt = $this->conn->prepare($req);
        $stmt->execute([$recipe]);
        return $stmt->fetchAll();
    }

    public function addIngredient(string $name): string
    {
        $req = 'INSERT INTO Ingredient (name) VALUES (?)';
        $this->connect();
        $stmt = $this->conn->prepare($req);
        $success = $stmt->execute([$name]);
        if ($success) {
            return "Element $name added to the ingredients";
        }
        return "An error occured while adding an element to the database";
    }

    public function addRecipe(string $name): string
    {
        $req = 'INSERT INTO Recipe (name) VALUES (?)';
        $this->connect();
        $stmt = $this->conn->prepare($req);
        $success = $stmt->execute([$name]);
        if ($success) {
            return "Element $name added to the recipes";
        }
        return "An error occured while adding an element to the database";
    }

    public function addPreparation(int $ingredient, int $recipe, int $quantity): string
    {
        $req = 'INSERT INTO Preparation (ingredient, recipe, quantity) VALUES (?,?,?)';
        $this->connect();
        $stmt = $this->conn->prepare($req);
        $success = $stmt->execute([$ingredient, $recipe, $quantity]);
        if ($success) {
            return "Preparation created with $quantity number of ingredients (ID=$ingredient) added to the recipe (ID=$recipe)";
        }
        return "An error occurred while adding an element to the database";
    }

    public function updateRecipe(string $name, string $newName): string
    {
        if (!$this->checkExistence('name', $name, 'Recipe')) {
            return sprintf('No elements with the name %s found in the database', $name);
        }
        $req = "UPDATE Recipe SET name = ? WHERE name = ?";
        $this->connect();
        $stmt = $this->conn->prepare($req);
        $success = $stmt->execute([$newName, $name]);
        if ($success) {
            return "Recipe name updated from $name to $newName";
        }
        return "An error occurred while updating an element to the database";
    }

    private function checkExistence(string $param, string $test, string $table): bool
    {
        $req = "SELECT * FROM $table WHERE $param = ?";
        $this->connect();
        $stmt = $this->conn->prepare($req);
        $stmt->execute([$test]);
        $result = $stmt->fetchAll();
        if (!empty($result)) {
            return true;
        }
        return false;
    }

    public function updateIngredient(string $name, string $newName): string
    {
        if (!$this->checkExistence('name', $name, 'Ingredient')) {
            return sprintf('No elements with the name %s found in the database', $name);
        }
        $req = "UPDATE Ingredient SET name = ? WHERE name = ?";
        $this->connect();
        $stmt = $this->conn->prepare($req);
        $success = $stmt->execute([$newName, $name]);
        if ($success) {
            return sprintf('Ingredient name updated from %s to %s', $name, $newName);
        }
        return "An error occurred while updating an element to the database";
    }

    public function updatePreparation(int $recipe, int $ingredient, int $quantity): string
    {
        if (!$this->checkExistence('recipe', $recipe, 'Preparation')) {
            return sprintf('No elements with the id %d found in the database', $recipe);
        }
        $req = "UPDATE Preparation SET quantity = ? WHERE recipe = ? AND ingredient = ?";
        $this->connect();
        $stmt = $this->conn->prepare($req);
        $success = $stmt->execute([$quantity, $recipe, $ingredient]);
        if ($success) {
            return sprintf('Preparation quantity updated to %d', $quantity);
        }
        return "An error occurred while updating an element to the database";
    }

    public function deleteRecipe(string $name): string
    {
        if (!$this->checkExistence('name', $name, 'Recipe')) {
            return sprintf('No elements with the name %s found in the database', $name);
        }

        $req = "DELETE FROM Recipe WHERE name = ?";
        $this->connect();
        $stmt = $this->conn->prepare($req);
        $success = $stmt->execute([$name]);
        if ($success) {
            return sprintf('Deleted %s', $name);
        }
        return "An error occurred while updating an element to the database";
    }

    public function deletePreparation(int $ingredient): string
    {
        if (!$this->checkExistence('ingredient', $ingredient, 'Preparation')) {
            return sprintf('No elements with the id %d found in the database', $ingredient);
        }

        $req = "DELETE FROM Preparation WHERE ingredient = ?";
        $this->connect();
        $stmt = $this->conn->prepare($req);
        $success = $stmt->execute([$ingredient]);
        if ($success) {
            return sprintf('Deleted %d', $ingredient);
        }
        return "An error occurred while updating an element to the database";
    }
}

/**
 * @throws JsonException
 */
#[NoReturn] function stop($status, $res)
{
    $res['status'] = $status;
    echo json_encode($res, JSON_THROW_ON_ERROR);
    exit();
}

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = '/api/';
}
$rest = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);
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

$api = new API();
$resource = $rest[0];
$method = $rest[1];

$name = '';
$recipe = null;
$ingredient = null;
$quantity = null;
$newName = '';

if (isset($_GET['name'])) {
    $name = ucfirst(strtolower($_GET['name']));
}
if (isset($_GET['recipe']) && is_numeric($_GET['recipe']) && $_GET['recipe'] > 0) {
    $recipe = $_GET['recipe'];
}
if (isset($_GET['ingredient']) && is_numeric($_GET['ingredient']) && ($_GET['ingredient'] > 0)) {
    $ingredient = $_GET['ingredient'];
}
if (isset($_GET['quantity']) && is_numeric($_GET['quantity']) && $_GET['quantity'] > 0) {
    $quantity = $_GET['quantity'];
}
if (isset($_GET['name'], $_GET['newName'])) {
    $name = $_GET['name'];
    $newName = $_GET['newName'];
}

switch ($resource) {
    case 'ingredient':
        switch ($method) {
            case 'list':
                $res['data'] = $api->listIngredients();
                break;
            case 'add':
                if (!$name) {
                    stop('failed', $res);
                }
                $res['msg'] = $api->addIngredient($name);
                break;
            case 'update':
                if (!$newName) {
                    stop('failed', $res);
                }
                $res['msg'] = $api->updateIngredient($name, $newName);
                break;
        }
        break;
    case 'preparation':
        switch ($method) {
            case 'list':
                if (empty($args)) {
                    $res['msg'] = 'No args provided';
                    break;
                }
                if (!$recipe) {
                    $res['msg'] = 'Invalid Recipe ID';
                    break;
                }
                $res ['data'] = $api->listPreparation($recipe);
                break;
            case 'add':
                if (!$recipe || !$ingredient || !$quantity) {
                    stop('Invalid Parameters', $res);
                }
                $res['msg'] = $api->addPreparation($ingredient, $recipe, $quantity);
                break;
            case 'update':
                if (!$quantity) {
                    stop('failed', $res);
                }
                $res['msg'] = $api->updatePreparation($recipe, $ingredient, $quantity);
                break;
            case 'delete':
                if (!$ingredient) {
                    stop('failed', $res);
                }
                $res['msg'] = $api->deletePreparation($ingredient);
        }
        break;
    case 'recipe':
        switch ($method) {
            case 'list':
                if (empty($args)) {
                    $res['data'] = $api->listRecipe();
                    break;
                }
                $res ['data'] = $api->listRecipe($name);
                break;
            case 'add':
                if (!$name) {
                    stop('failed', $res);
                }
                $res['msg'] = $api->addRecipe($name);
                break;
            case 'update':
                if (!$newName) {
                    stop('failed', $res);
                }
                $res['msg'] = $api->updateRecipe($name, $newName);
                break;
            case 'delete':
                if (!$name) {
                    stop('failed', $res);
                }
                $res['msg'] = $api->deleteRecipe($name);
        }
        break;
}

stop(true, $res);
?>