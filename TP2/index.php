<!DOCTYPE html>
<p lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Movie API</title>
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
                        <input name="url" type="text" disabled value="<?php echo $_SERVER['HTTP_HOST']; ?>/api/index.php?">
                        <label for="uri">URI</label>
                        <input name="uri" type="text" value="" secid="test"><br>
                        <button onclick="request('test')">test API request</button>
                    </fieldset>
                    <pre class="result json">nothing yet...</pre>
                </details>
            </section>

            <form method="post" action="/api/reset.php">
                <input type="submit" class="button" value="reset bd" />
            </form>
        </p>

        <h1>Movie API specification</h1>
        <section class="exercise">
            <h2>Exercise</h2>
            <p>
                In this work, your goal is to create an API which links to a Sqlite DB.
            </p>
            <p>
                <strong>After</strong> reading (below) the DB specifications and the possible operations
                of the API, start by creating the API documentation (using the format of the following
                examples). For this step, you will work in the <span class="inlinejson">/doc.php</span>
                file.
            </p>
            <p>
                <strong>Once you finished the documentation</strong>, modify the API script and test it
                using the tool above, or by directly calling your script in the browser.
                For this step, you will work in the <span class="inlinejson">/api/index.php</span> file.
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
                    <summary>Course item: CRUD API</summary>
                    <p>
                    <cite>Source: <a href="https://en.wikipedia.org/wiki/CRUD" class="link">https://en.wikipedia.org/wiki/CRUD</a></cite>
                    </p>

                    <p>
                        In computer programming, create, read, update, and delete (often referred to
                        via the acronym CRUD) are the four basic operations of persistent storage.
                        <br><br>
                        With a CRUD mechanism, the content is both
                        readable and updatable. Before a storage location can be read or updated
                        it needs to be created; that is allocated and initialized with content.
                        At some later point, the storage location may need to be destructed;
                        that is finalized and deallocated.
                        Together these four operations make up the basic operations of storage
                        management known as CRUD: Create, Read, Update and Delete.
                        <br><br>
                        The idea of a CRUD API is therefore to allow the following 4 operations:
                    </p>
                    <ul>
                        <li>Create, or add new entries;</li>
                        <li>Read, retrieve, search, or view existing entries;</li>
                        <li>Update, or edit existing entries;</li>
                        <li>Delete, deactivate, or remove existing entries.</li>
                    </ul>
                </details>
            </section>

            <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/doc.php'; ?>
        </p>

        <h2>Content of the DB</h2>
        <p>
            Your movie database should contain 3 tables:
            a <span class="inlinejson">person</span> table,
            a <span class="inlinejson">movie</span> table and
            a <span class="inlinejson">cast</span> table.
        </p>
        <p>
            The <span class="inlinejson">person</span> table should store
            (at least) the following information:
        </p>
        <ul>
            <li>a unique id (primary key);</li>
            <li>a name;</li>
            <li>a surname.</li>
        </ul>
        <p>
            The <span class="inlinejson">movie</span> table should store
            (at least) the following information:
        </p>
        <ul>
            <li>a unique id (primary key);</li>
            <li>a name;</li>
            <li>a release year;</li>
            <li>a <span class="inlinejson">person</span> id corresponding to the director.</li>
        </ul>
        <p>
            The <span class="inlinejson">cast</span> table should store
            whom starred in a particular movie:
        </p>
        <ul>
            <li>a <span class="inlinejson">person</span> id corresponding to the actor;</li>
            <li>a <span class="inlinejson">movie</span> id corresponding to the movie;</li>
        </ul>

        <h2>Possible API operations</h2>
        <p>
            Using the CRUD principle, your api should allow you to perform the following
            operations:
        </p>
        <ul>
            <li>
                Create:
                <ul>
                    <li>inserting a new person in the DB;</li>
                    <li>inserting a new movie in the DB;</li>
                    <li>inserting a new cast member in the DB.</li>
                </ul>
            </li>
            <li>
                Read:
                <ul>
                    <li>listing all the movies;</li>
                    <li>listing one movie;</li>
                    <li>listing all the movies released in a particular year;</li>
                    <li>listing director info for a particular movie;</li>
                    <li>listing cast members info for a particular movie;</li>
                    <li>listing all the persons from the DB;</li>
                    <li>listing one person from the DB.</li>
                </ul>
            </li>
            <li>
                Update:
                <ul>
                    <li>updating the information of a person;</li>
                    <li>updating the information of a movie.</li>
                </ul>
            </li>
            <li>
                Delete:
                <ul>
                    <li>deleting a person from the DB;</li>
                    <li>deleting a movie from the DB;</li>
                    <li>deleting a cast member from the DB.</li>
                </ul>
            </li>
        </ul>
    </body>
</html>
