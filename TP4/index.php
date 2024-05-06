<!DOCTYPE html>
<p lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Secure Recipe API</title>
        <link rel="stylesheet" href="style.css">
        <script src="api.js" defer></script>
    </head>
    <body>
        <h1>PHP test</h1>
        <?php
            echo "All good!";
            echo "<br>";
            echo "<cite>My server is working and can run PHP files.</cite>";
        ?>

        <h1>Test API</h1>
        <p>
            <section>
                <details id="test" open>
                    <summary>Test GET query to API</summary>
                    <fieldset>
                        <label for="url">Server</label>
                        <input name="url" type="text" disabled value="<?php echo $_SERVER['HTTP_HOST']; ?>/api/">
                        <label for="rest">REST URL</label>
                        <input name="rest" type="text" value="" enter secid="test"><br>
                        <label for="payload">Payload (JSON format)</label><br>
                        <input name="payload" type="text" value='{ "lala":"lolo", "lili":1 }' enter secid="test">
                        <br>
                        <button onclick="request('test')">test API request</button>
                    </fieldset>
                    <pre class="result json">nothing yet...</pre>
                </details>
            </section>

            <section>
                <details open>
                    <summary>Credentials for secure API usage</summary>
                    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/pwd.php'; ?>
                </details>
            </section>

            <form method="post" action="/api/reset.php">
                <input type="submit" class="button" value="reset bd" />
            </form>
        </p>

        <h1>Recipe API specification</h1>
        <section class="exercise">
            <h2>Exercise</h2>
            <p>
                In this work, your goal is to transform the Ingredient RESTful API to a secure one.
                The website has been modified to sent all requests via POST.
            </p>
            <p>
                <strong>After</strong> reading (below) the DB specifications and the API documentation,
                modify the API script and test it using the tool above, or by directly calling your
                script in the browser. You will work in the
                <span class="inlinejson">/api/index.php</span> file.
            </p>
            <p>
                <strong>Look for the <span class="inlinejson">TODO</span></strong>
                and complete the required methods.
            </p>
            <p>
                <strong>If you are done early</strong>, well... congrats.
            </p>
        </section>
        <p>
            <section>
                <details class="warning">
                    <summary>Course item: Tokens</summary>
                    <p>
                        Let's talk about this altogether, shall we?
                    </p>
                </details>
            </section>
            <section>
                <details class="warning">
                    <summary>Course item: Network developper tool</summary>
                    <p>
                        Let's talk about this altogether, shall we?
                    </p>
                </details>
            </section>

            <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/doc.php'; ?>
        </p>

        <h2>Content of the DB</h2>
        <p>
            The recipe database contains 3 tables:
            a <span class="inlinejson">Ingredient</span> table,
            a <span class="inlinejson">Recipe</span> table and
            a <span class="inlinejson">Preparation</span> table.
        </p>
        <p>
            The <span class="inlinejson">Ingredient</span> table stores
            the following information:
        </p>
        <ul>
            <li>a unique id (primary key);</li>
            <li>a name.</li>
        </ul>
        <p>
            The <span class="inlinejson">Recipe</span> table stores
            the following information:
        </p>
        <ul>
            <li>a unique id (primary key);</li>
            <li>a name.</li>
        </ul>
        <p>
            The <span class="inlinejson">Preparation</span> table stores
            which ingredients are used in a recipe:
        </p>
        <ul>
            <li>a <span class="inlinejson">ingredient</span> id corresponding to the ingredient;</li>
            <li>a <span class="inlinejson">recipe</span> id corresponding to the recipe;</li>
            <li>a <span class="inlinejson">quantity</span> corresponding to how much of the
                ingredient goes into the recipe.</li>
        </ul>

        <h2>API operations</h2>
        <p>
            Using the REST/CRUD principle, the API should allow you to perform the following
            operations:
        </p>
        <ul>
            <li>
                Ingredient:
                <ul>
                    <li>List all the ingredients in the DB;</li>
                    <li>Add an ingredient in the DB;</li>
                    <li>Update the name of an ingredient in the DB.</li>
                </ul>
            </li>
            <li>
                Recipe:
                <ul>
                    <li>List all the recipes in the DB (following optional filters);</li>
                    <li>Add a recipe in the DB;</li>
                    <li>Update the name of a recipe in the DB;</li>
                    <li>Delete a recipe from the DB.</li>
                </ul>
            </li>
            <li>
                Preparation:
                <ul>
                    <li>List all the ingredients in a specific recipe of the DB;</li>
                    <li>Add an ingredient to a recipe in the DB;</li>
                    <li>Update an ingredient of a recipe in the DB;</li>
                    <li>Delete an ingredient from a recipe from the DB.</li>
                </ul>
            </li>
        </ul>
    </body>
</html>
