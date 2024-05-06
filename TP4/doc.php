<?php

    function echoAPIDoc($title, $REST, $post, $desc, $target) {
        echo <<<EOL
            <details>
                <summary>{$title}</summary>
                <fieldset>
                    <label for="url">Server</label>
                    <input name="url" type="text" disabled value="{$_SERVER['HTTP_HOST']}/api/">
                    <label for="rest">REST URL</label>
                    <input name="rest" type="text" disabled value="{$REST}"><br>
                </fieldset>
                Post payload <span class="inlinejson">{$post}</span>.
                <p>{$desc}</p>
                <pre class="target json">{$target}</pre>
            </details>
        EOL;
    }

?>

<section>
    <details>
    <summary>API documentation</summary>
        <section>
            <details>
                <summary>Ingredient</summary>
                <?php
                    echoAPIDoc('List',
                        'ingredient/list',
                        '{  }',
                        'List all the ingredients.',
                        '{"status":"true", ... , "data":[...]}');
                    echoAPIDoc('Add',
                        'ingredient/add',
                        '{ "name": "XXX", "token": "validtoken" }',
                        'Add an ingredient XXX. <strong class="important">You need to be authenticated</strong>.',
                        '{"status":"true", ... , "data":"nothing"}');
                    echoAPIDoc('Update',
                        'ingredient/update',
                        '{ "id": X, "name": "XXX", "token": "validtoken" }',
                        'Update the ingredient X with a new name XXX. <strong class="important">You need to be authenticated</strong>.',
                        '{"status":"true", ... , "data":"nothing"}');
                ?>
            </details>
        </section>
        <section>
            <details>
                <summary>Recipe</summary>
                <?php
                echoAPIDoc('List',
                    'recipe/list',
                    '{ "maxingredient": X, "ingredient": Y }',
                    'List all the recipes. maxingredient and ingredient are optional arguments
                    allowing to specify a maximum number of ingredient, and specify at least one specific
                    ingredient.',
                    '{"status":"true", ... , "data":[...]}');
                echoAPIDoc('Add',
                    'recipe/add',
                    '{ "name": "XXX", "token": "validtoken" }',
                    'Add a recipe XXX. <strong class="important">You need to be authenticated</strong>.',
                    '{"status":"true", ... , "data":"nothing"}');
                echoAPIDoc('Update',
                    'recipe/update',
                    '{ "id": X, "name": "XXX", "token": "validtoken" }',
                    'Update the recipe X with a new name XXX. <strong class="important">You need to be authenticated</strong>.',
                    '{"status":"true", ... , "data":"nothing"}');
                echoAPIDoc('Delete',
                    'recipe/delete',
                    '{ "id": X, "token": "validtoken" }',
                    'Delete the recipe X. <strong class="important">You need to be authenticated</strong>.',
                    '{"status":"true", ... , "data":"nothing"}');
                ?>
            </details>
        </section>
        <section>
            <details>
                <summary>Preparation</summary>
                <?php
                echoAPIDoc('List',
                    'preparation/list',
                    '{ "rid": X }',
                    'List all the ingredients for the X recipe.',
                    '{"status":"true", ... , "data":[...]}');
                echoAPIDoc('Add',
                    'preparation/add?iid=X&rid=Y&quantity=Z',
                    '{ "iid": X, "rid": Y, "quantity": Z, "token": "validtoken" }',
                    'Add the ingredient X in recipe Y in quantity Z. <strong class="important">You need to be authenticated</strong>.',
                    '{"status":"true", ... , "data":"nothing"}');
                echoAPIDoc('Update',
                    'preparation/update?iid=X&rid=Y&quantity=Z',
                    '{ "iid": X, "rid": Y, "quantity": Z, "token": "validtoken" }',
                    'Update the ingredient X in recipe Y with quantity Z. <strong class="important">You need to be authenticated</strong>.',
                    '{"status":"true", ... , "data":"nothing"}');
                echoAPIDoc('Delete',
                    'preparation/delete?iid=X&rid=Y',
                    '{ "iid": X, "rid": Y, "token": "validtoken" }',
                    'Delete ingredient X in recipe Y. <strong class="important">You need to be authenticated</strong>.',
                    '{"status":"true", ... , "data":"nothing"}');
                ?>
            </details>
        </section>
        <section>
            <details>
                <summary>User</summary>
                <?php
                echoAPIDoc('Authenticate',
                    'user/authenticate',
                    '{ "login": "XXX", "pwd": "XXX" }',
                    'Authentication, pwd is not hashed. If successful a valid token is sent back.',
                    '{"status":"true", ... , "data":true, "token":"validtoken"}');
                echoAPIDoc('Invalidate',
                    'invalidate',
                    '{  }',
                    'Invalidate all tokens.',
                    '{"status":"true", ... , "data":"nothing"}');
                ?>
            </details>
        </section>
    </details>
</section>