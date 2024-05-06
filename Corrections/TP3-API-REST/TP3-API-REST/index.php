<!DOCTYPE html>
<p lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recipe API</title>
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
                        <input name="rest" type="text" value="" secid="test"><br>
                        <button onclick="request('test')">test API request</button>
                    </fieldset>
                    <pre class="result json">nothing yet...</pre>
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
                In this work, your goal is to create a RESTful API which links to a Sqlite DB.
            </p>
            <p>
                <strong>After</strong> reading (below) the DB specifications and the API documentation,
                modify the API script and test it using the tool above, or by directly calling your
                script in the browser. You will work in the
                <span class="inlinejson">/api/index.php</span> file.
            </p>
            <p>
                <strong>If you are done early</strong>, you will create unit tests following the API test
                tool. When done, you can create a new page and work on the
                display and visual presentation of the results.
            </p>
        </section>
        <p>
            <section>
                <details class="warning">
                    <summary>Course item: RESTful API</summary>
                    <p>
                        <cite>Source: <a href="https://en.wikipedia.org/wiki/REST" class="link">https://en.wikipedia.org/wiki/REST</a></cite>
                    </p>
                    <p>
                        Representational state transfer (REST) is a software architectural style
                        that describes the architecture of the Web.
                        The REST architectural style defines six guiding constraints.
                        A system that complies with some or all of these constraints is
                        loosely referred to as <strong>RESTful</strong>.

                        The formal REST constraints are as follows:
                    </p>
                    <ul>
                        <li>Client–server architecture;</li>
                        <li>Statelessness;</li>
                        <li>Cacheability;</li>
                        <li>Layered system;</li>
                        <li>Code on demand;</li>
                        <li>Uniform interface.</li>
                    </ul>
                    <p>
                        In practice, calls to RESTful APIs have the following scheme
                        <br>
                        <span class="inlinejson">website.com/api/{RESOURCE}/{METHOD}?{ARGS}</span>
                        <br>
                        with: <span class="inlinejson">website.com/api/</span> being the API entry
                        point (script processing the call); <span class="inlinejson">{RESOURCE}</span>
                        the resources or entities that will be manipulated;
                        <span class="inlinejson">{METHOD}</span> the manipulation method or action; and
                        <span class="inlinejson">{ARGS}</span> some optional arguments that can be passed
                        on to the method (in here <span class="inlinejson">{ARGS}</span> are passed through
                        using <span class="inlinejson">GET</span>).
                    </p>
                </details>
            </section>
            <section>
                <details class="warning">
                    <summary>Course item: .htacces for RESTful APIs</summary>
                    <p>
                        At the server root, you will find the following
                        <span class="inlinejson">.htaccess</span> file.
                    </p>
                    <pre class="json">
RewriteEngine on

# If the request is a file, folder or symlink that exists, serve it up
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]

# redirect all api calls to /api/index.php, QSA to keep the query string parameter (GET)
RewriteRule ^api/((?!index\.php$).+)$ api/index.php [L,NC,QSA]
                    </pre>
                    <p>
                        It does two things: <strong>1)</strong> ensuring that if the incoming
                        URL points to a file/document that exists in your server, this
                        file/document is served up; and <strong>2)</strong> ensuring that all
                        <span class="inlinejson">website.com/api/{RESOURCE}/{METHOD}?{ARGS}</span>
                        URLs end up being processed by the <span class="inlinejson">/api/index.php</span>
                        PHP script.
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
