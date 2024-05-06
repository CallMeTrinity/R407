<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once $_SERVER['DOCUMENT_ROOT'] . '/api/bd.php';

    class API extends BDHandler {
        private $authorization = true;

        public function setAuthorization($val) {
            $this->authorization = $val;
        }
        public function add($req) {
            // Test if the user is authorized
            // return false if the user is not allowed to add
            // otherwise let the method finish
            // TODO
            $this->instruct($req);
            return true;
        }
        public function list($req) { return $this->select($req); }
        public function update($req) {
            // Test if the user is authorized
            // return false if the user is not allowed to add
            // otherwise let the method finish
            // TODO
            $this->instruct($req);
            return true;
        }
        public function delete($req) {
            // Test if the user is authorized
            // return false if the user is not allowed to add
            // otherwise let the method finish
            // TODO
            $this->instruct($req);
            return true;
        }
    }

    function stop($status, $res) {
        $res['status'] = $status;
        echo json_encode($res);
        exit();
    }

    trait Controller {
        private $res;
        private $data;
        private $api;

        public function __construct($res, $data) {
            // Read and understand, you may want to ask questions...
            // TODO

            $this->res = $res;
            $this->data = $data;
            $this->api = new API();

            $authorization = true;
            if (!isset($this->data['secure'])) { $this->data['secure'] = false; }
            if ($this->data['secure']) {
                $authorization = $this->validateToken($this->data['token']);
            }
            $this->res['secure'] = $this->data['secure'];
            $this->res['authorization'] = $authorization;

            $this->api->setAuthorization($authorization);
        }

        private function validateToken($token) {
            // Perform a SELECT query on the Token table to test if
            // the $token argument is in the table
            // return true the token exists, false otherwise
            // TODO
            return true;
        }

        public function stop($status) {
            stop($status, $this->res);
        }

        public function isset($key, $msg) {
            if (!isset($this->data[$key]) or $this->data[$key] == '') {
                $this->res['msg'] = $msg;
                $this->stop(false);
            }
        }

        public function isnum($key, $msg) {
            if (!is_numeric($this->data[$key])) {
                $this->res['msg'] = $msg;
                $this->stop(false);
            }
        }

        public abstract function list();
        public abstract function add();
        public abstract function update();
        public abstract function delete();
        public function authenticate($data) {
            $this->res['msg'] = "Only users can be authenticated.";
            $this->stop(false);
        }
        public function invalidate($data) {
            $this->res['msg'] = "Only users can be invalidated.";
            $this->stop(false);
        }
    }

    class Ingredient {
        use Controller;

        public function list() {
            $this->res['data'] = $this->api->list(<<<EOL
                SELECT * FROM "Ingredient"
            EOL);
            $this->res['msg'] = "List all ingredients.";
            $this->stop(true);
        }

        public function add() {
            $this->isset('name', "Ingredient name should be specified (add).");

            if (!$this->api->add(<<<EOL
                INSERT INTO "Ingredient" ("id", "name") VALUES (NULL, '{$this->data['name']}');
            EOL)) {
                $this->res['msg'] = "Not authorized.";
                $this->stop(false);
            }
            $this->res['msg'] = "Ingredient '{$this->data['name']}' added.";
            $this->stop(true);
        }

        public function update() {
            $this->isset('id', "Ingredient id should be specified (update).");
            $this->isnum('id', "Ingredient id should be numeric (update).");
            $this->isset('name', "Ingredient name should be specified (update).");

            if (!$this->api->update(<<<EOL
                UPDATE "Ingredient"
                SET name = '{$this->data['name']}'
                WHERE id = {$this->data['id']};
            EOL)) {
                $this->res['msg'] = "Not authorized.";
                $this->stop(false);
            }
            $this->res['msg'] = "Ingredient '{$this->data['name']}' updated.";
            $this->stop(true);
        }

        public function delete() {
            $this->res['msg'] = "Ingredient can't be deleted.";
            $this->stop(false);
        }
    }

    class Recipe {
        use Controller;

        public function list() {
            $ming = '';
            if (isset($this->data['maxingredient'])) {
                $this->isnum($this->data, 'maxingredient',
                    "Recipe maxingredient should be numeric (list).");
                $ming = $this->data['maxingredient'];
            }
            $ing = '';
            if (isset($this->data['ingredient'])) {
                $this->isnum($this->data, 'ingredient',
                    "Recipe ingredient should be numeric (list).");
                $ing = $this->data['ingredient'];
            }

            if ($ming == '' and $ing == '') {
                $this->res['data'] = $this->api->list(<<<EOL
                    SELECT * FROM "Recipe";
                EOL);
                $this->res['msg'] = "List all recipes.";
            } else if ($ming != '' and $ing == '') {
                $this->res['data'] = $this->api->list(<<<EOL
                    SELECT * FROM "Recipe"
                    WHERE id IN (
                        SELECT recipe FROM "Preparation"
                        GROUP BY recipe
                        HAVING COUNT(*) <= {$ming}
                    );
                EOL);
                $this->res['msg'] = "List all recipes with less than {$ming} ingredients.";
            } else if ($ming == '' and $ing != '') {
                $this->res['data'] = $this->api->list(<<<EOL
                    SELECT * FROM "Recipe"
                    WHERE id IN (
                        SELECT recipe FROM "Preparation"
                        WHERE ingredient = {$ing}
                    );
                EOL);
                $this->res['msg'] = "List all recipes that have {$ing}.";
            } else {
                $this->res['data'] = $this->api->list(<<<EOL
                    SELECT * FROM "Recipe"
                    WHERE id IN (
                        SELECT recipe FROM "Preparation"
                        WHERE recipe IN (
                            SELECT recipe FROM "Preparation"
                            WHERE ingredient = {$ing}
                        )
                        GROUP BY recipe
                        HAVING COUNT(*) <= {$ming}
                    );
                EOL);
                $this->res['msg'] = "List all recipes with less than {$ming} ingredients, and have {$ing}.";
            }
            $this->stop(true);
        }

        public function add() {
            $this->isset('name', "Recipe name should be specified (add).");

            if (!$this->api->add(<<<EOL
                INSERT INTO "Recipe" ("id", "name") VALUES (NULL, '{$this->data['name']}');
            EOL)) {
                $this->res['msg'] = "Not authorized.";
                $this->stop(false);
            }
            $this->res['msg'] = "Recipe '{$this->data['name']}' added.";
            $this->stop(true);
        }

        public function update() {
            $this->isset('id', "Recipe id should be specified (update).");
            $this->isnum('id', "Recipe id should be numeric (update).");
            $this->isset('name', "Recipe name should be specified (update).");

            if (!$this->api->update(<<<EOL
                UPDATE "Recipe"
                SET name = '{$this->data['name']}'
                WHERE id = {$this->data['id']};
            EOL)) {
                $this->res['msg'] = "Not authorized.";
                $this->stop(false);
            }
            $this->res['msg'] = "Recipe '{$this->data['name']}' updated.";
            $this->stop(true);
        }

        public function delete() {
            $this->isset('id', "Recipe id should be specified (delete).");
            $this->isnum('id', "Recipe id should be numeric (delete).");

            if (!$this->api->delete(<<<EOL
                DELETE FROM "Recipe" WHERE id = {$this->data['id']};
            EOL)) {
                $this->res['msg'] = "Not authorized.";
                $this->stop(false);
            }
            $this->res['msg'] = "Recipe '{$this->data['id']}' has been deleted.";
            $this->stop(true);
        }
    }

    class Preparation {
        use Controller;

        public function list($data) {
            $this->isset('rid', "Recipe id should be specified (list).");
            $this->isnum('rid', "Recipe id should be numeric (list).");

            $this->res['data'] = $this->api->list(<<<EOL
                    SELECT Preparation.*, Recipe.name as rname, Ingredient.name as iname
                    FROM "Preparation", "Recipe", "Ingredient"
                    WHERE recipe = {$data['rid']} AND
                          recipe = Recipe.id AND ingredient = Ingredient.id
            EOL);
            $this->res['msg'] = "List all ingredients of recipe {$data['rid']}.";
            $this->stop(true);
        }

        public function add() {
            $this->isset('rid', "Recipe id should be specified (add).");
            $this->isnum('rid', "Recipe id should be numeric (add).");
            $this->isset('iid', "Ingredient id should be specified (add).");
            $this->isnum('iid', "Ingredient id should be numeric (add).");
            $this->isset('quantity', "Quantity should be specified (add).");
            $this->isnum('quantity', "Quantity should be numeric (add).");

            if (!$this->api->add(<<<EOL
                    INSERT INTO "Preparation" ("recipe", "ingredient", "quantity")
                    VALUES ({$this->data['rid']}, {$this->data['iid']}, {$this->data['quantity']});
            EOL)) {
                $this->res['msg'] = "Not authorized.";
                $this->stop(false);
            }
            $this->res['msg'] = "Ingredient '{$this->data['iid']}' added to recipe '{$this->data['rid']}'.";
            $this->stop(true);
        }

        public function update() {
            $this->isset('rid', "Recipe id should be specified (update).");
            $this->isnum('rid', "Recipe id should be numeric (update).");
            $this->isset('iid', "Ingredient id should be specified (update).");
            $this->isnum('iid', "Ingredient id should be numeric (update).");
            $this->isset('quantity', "Quantity should be specified (update).");
            $this->isnum('quantity', "Quantity should be numeric (update).");

            if (!$this->api->update(<<<EOL
                    UPDATE "Preparation"
                    SET quantity = '{$this->data['quantity']}'
                    WHERE recipe = {$this->data['rid']} AND ingredient = {$this->data['iid']};
            EOL)) {
                $this->res['msg'] = "Not authorized.";
                $this->stop(false);
            }
            $this->res['msg'] = "Ingredient '{$this->data['iid']}' updated in recipe '{$this->data['rid']}'.";
            $this->stop(true);
        }

        public function delete() {
            $this->isset('rid', "Recipe id should be specified (delete).");
            $this->isnum('rid', "Recipe id should be numeric (delete).");
            $this->isset('iid', "Ingredient id should be specified (delete).");
            $this->isnum('iid', "Ingredient id should be numeric (delete).");

            if (!$this->api->delete(<<<EOL
                DELETE FROM "Preparation"
                WHERE recipe = {$this->data['rid']} AND ingredient = {$this->data['iid']};
            EOL)) {
                $this->res['msg'] = "Not authorized.";
                $this->stop(false);
            }
            $this->res['msg'] = "Ingredient '{$this->data['iid']}' deleted from recipe '{$this->data['rid']}'.";
            $this->stop(true);
        }
    }

    class User {
        use Controller;

        public function list() {
            $this->res['msg'] = "Users can't be listed.";
            $this->stop(true);
        }

        public function add() {
            $this->res['msg'] = "Users can't be added.";
            $this->stop(true);
        }

        public function update() {
            $this->res['msg'] = "Users can't be updated.";
            $this->stop(true);
        }

        public function delete() {
            $this->res['msg'] = "Users can't be deleted.";
            $this->stop(false);
        }

        public function getUser($login) {
            // Perform a SELECT query on the User table to test if
            // the $login argument is in the table
            // return the associated information or an empty table otherwise
            // TODO
            return array();
        }

        public function setUserToken($login, $token) {
            // Perform a INSERT query on the Token table to add
            // the $login and the $token
            // TODO
        }

        public function unsetUserTokens() {
            // Perform a DELETE query on the Token table to remove
            // all tokens
            // TODO
        }

        public function authenticate() {
            // Read and understand, you may want to ask questions...
            // TODO
            $user = $this->getUser($this->data['login']);
            $this->res['data'] = false;
            if (sizeof($user) == 1) {
                if (password_verify($this->data['pwd'], $user[0]['hash'])) {
                    $token = bin2hex(openssl_random_pseudo_bytes(32));
                    $this->setUserToken($this->data['login'], $token);
                    $this->res['data'] = true;
                    $this->res['token'] = $token;
                }
            }
            $this->res['msg'] = "Authentication.";
            $this->stop(true);
        }

        public function invalidate() {
            // Read and understand, you may want to ask questions...
            // TODO
            $this->unsetUserTokens();
            $this->res['msg'] = "Invalidate all token.";
            $this->stop(true);
        }
    }

    if (!isset($_SERVER['REQUEST_URI'])) { $_SERVER['REQUEST_URI'] = '/api/'; }
    $rest = explode('/', explode('?',$_SERVER['REQUEST_URI'])[0]);
    array_shift($rest);
    array_shift($rest);

    $args = json_decode(file_get_contents('php://input'), true);

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

    if (!in_array($resource, array('ingredient', 'recipe', 'preparation', 'user'))) {
        $res['msg'] = "Resource '{$resource}' is unknown.";
        stop(false, $res);
    }

    if (!in_array($method, array('list', 'add', 'update', 'delete', 'authenticate', 'invalidate'))) {
        $res['msg'] = "Method '{$method}' is unknown.";
        stop(false, $res);
    }

    switch ($resource) {
        case 'ingredient':
            $o = new Ingredient($res, $args);
            $o->{$method}();
            break;
        case 'recipe':
            $o = new Recipe($res, $args);
            $o->{$method}();
            break;
        case 'preparation':
            $o = new Preparation($res, $args);
            $o->{$method}();
            break;
        case 'user':
            $o = new User($res, $args);
            $o->{$method}();
            break;
        default:
            $res['msg'] = "Resource '{$resource}' is not yet implemented.";
            stop(false, $res);
    }
?>