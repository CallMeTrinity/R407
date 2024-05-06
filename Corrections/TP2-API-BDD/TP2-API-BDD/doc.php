<?php

    function echoAPIDoc($title, $URI, $desc, $target) {
        echo <<<EOL
            <details>
                <summary>{$title}</summary>
                <fieldset>
                    <label for="url">Server</label>
                    <input name="url" type="text" disabled value="{$_SERVER['HTTP_HOST']}/api/index.php?">
                    <label for="uri">URI</label>
                    <input name="uri" type="text" disabled value="{$URI}"><br>
                </fieldset>
                <p>{$desc}</p>
                <pre class="target json">{$target}</pre>
            </details>
        EOL;
    }

?>

<section>
    <details>
    <summary>API documentation</summary>
        <p>Modify the <span class="inlinejson">/doc.php</span> file.</p>
        <p>The following specification are examples, feel free to modify/delete them.</p>
        <section>
            <details>
                <summary>Create</summary>
                <?php
                    echoAPIDoc('Person',
                        'arg1=XXX&arg2=YYY',
                        'Create a new person XXX YYY',
                        '{"status":"true", "msg":"person created", "createdid":34}');
                    echoAPIDoc('Movie',
                        'arg1=XXX&arg2=YYY',
                        'Create a new movie XXX YYY',
                        '{"status":"true", "msg":"person created", "createdid":34}');

                ?>
            </details>
        </section>

        <section>
            <details>
                <summary>Read</summary>
                <?php
                    echo 'TODO';
                ?>
            </details>
        </section>

        <section>
            <details>
                <summary>Update</summary>
                <?php
                    echo 'TODO';
                ?>
            </details>
        </section>

        <section>
            <details>
                <summary>Delete</summary>
                <?php
                    echo 'TODO';
                ?>
            </details>
        </section>
    </details>
</section>