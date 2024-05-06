<!DOCTYPE html>
<p lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>The Global Gender Gap Index API</title>
        <link rel="stylesheet" href="style.css">
        <script src="api.js" defer></script>
    </head>
    <body>
        <h1>PHP test</h1>
        <?php
            echo "All good!";
            echo "<br>";
            echo "<cite>My server is working and can run PHP files.</cite>";

            require_once $_SERVER['DOCUMENT_ROOT'] . '/utils.php';
        ?>

        <h1>Test API</h1>
        <p>
            <section>
                <details id="test" open>
                    <summary>Test GET query to API</summary>
                    <fieldset>
                        <label for="url">Server</label>
                        <input name="url" type="text" disabled value="<?php echo $_SERVER['HTTP_HOST']; ?>/gggi-api.php?">
                        <label for="uri">URI</label>
                        <input name="uri" type="text" value="" secid="test"><br>
                        <button onclick="request('test')">test API request</button>
                    </fieldset>
                    <pre class="result json">nothing yet...</pre>

                    <details>
                        <summary>Query examples</summary>

                        <?php
                            for ($k = 0; $k < sizeof($examples); $k++) {
                                echoAPITest("example-{$k}",
                                    $examples[$k]['title'],$examples[$k]['URI'],
                                    $examples[$k]['target']);
                            }
                        ?>
                    </details>
                </details>
            </section>
        </p>

        <h1>API specification</h1>
        <section class="exercise">
            <h2>Exercise</h2>
            <p>
                In this work, your goal is to create an API corresponding to the following specification.
            </p>
            <p>
                <strong>After</strong> reading (below) the API specifications and the GGGI documentation,
                modify the API script and test it
                using the tool above, or by directly calling your script in the browser.
                For this step, you will work in the <span class="inlinejson">/gggi-api.php</span> file.
            </p>
            <p>
                <strong>If you are done early</strong>, you can create a new page and work on the
                display and visual presentation of the results.
            </p>
        </section>
        <p>
            <section>
            <details class="warning">
                <summary>Course item: URI parameters</summary>
                    To pass URI arguments: <span class="inlinejson">www.apiserver.com/apiscript.php?URIARGS</span>.<br>
                    With <span class="inlinejson">URIARGS</span> taking the following form:
                    <ul>
                        <li>
                            to pass multiple arguments, use the <span class="inlinejson">&amp;</span> character as a separator.<br>
                            <cite>
                                For instance: <span class="inlinejson">arg1&amp;arg2&amp;arg3</span>to pass 3 arguments
                                <span class="inlinejson">arg1</span>, <span class="inlinejson">arg2</span> and
                                <span class="inlinejson">arg3</span>.
                            </cite>
                        </li>
                        <li>
                            arguments have 3 possible formats:<br>
                            <span class="inlinejson">arg</span> an argument "arg" than has no value,
                            <span class="inlinejson">arg=XXX</span> an argument "arg" that has the value "XXX" and
                            <span class="inlinejson">arg[]=a&amp;arg[]=b&amp;arg[]=c</span> an argument "arg" which is a list with
                            3 values "a", "b" and "c".
                        </li>
                        <li>
                            all values are passed as strings! You need to convert them when parsing them in your API.<br>
                            <cite>
                                For instance: <span class="inlinejson">arg=toto</span> the argument "arg" have the <strong>string</strong> value
                                "toto", <span class="inlinejson">arg=120</span> the argument "arg" have the <strong>string</strong> value
                                "120", <span class="inlinejson">arg=0.99</span> the argument "arg" have the <strong>string</strong> value
                                "0.99" and <span class="inlinejson">arg=false</span> the argument "arg" have the <strong>string</strong> value
                                "false".
                            </cite>
                        </li>
                    </ul>
                </details>
            </section>
            <section>
                <details>
                    <summary>Country parameters</summary>
                    <?php
                        echoSection('code=XXX', 'Only selects data in the country which
                                                                <span class="inlinejson">code</span> is
                                                                <span class="inlinejson">XXX</span>.');
                        echoSection('country=XXX', 'Only selects data in the country named
                                                                <span class="inlinejson">XXX</span>.');
                        echoSection('subregion=XXX', 'Only selects data from the world subregion
                                                                named <span class="inlinejson">XXX</span>.');
                        echoSection('region=XXX', 'Only selects data from the world region named
                                                                <span class="inlinejson">XXX</span>.');
                        echoSection('param[]=XXX&amp;param[]=YYY', 'The above parameters can have multiple
                                                                values. For instance,
                                                                <span class="inlinejson">region[]=Asia&amp;region[]=Africa</span>
                                                                selects data from Asia and Africa.');
                        echoSection('exclude', 'Exclusion parameter allowing to select data from
                                                                outside a particular country/subregion/region. For instance
                                                                <span class="inlinejson">code=ARG&amp;exclude</span> only selects
                                                                data not coming from Argentina,
                                                                <span class="inlinejson">region[]=Europe&amp;region[]=Asia&amp;exlude</span> only selects
                                                                data from outside Europe and Asia, and
                                                                <span class="inlinejson">region=Europe&amp;subregion=Northern America&amp;exclude</span> only selects
                                                                data from outside Europe and Northern America.<br>
                                                                <strong>Should be used jointly with one of the above parameters</strong>.');
                    ?>
                </details>
                <details>
                    <summary>GGGIs parameters</summary>
                    <?php
                        echoSection('indicator=XXX', 'Only selects data referring to the 
                                                                 <span class="inlinejson">XXX</span> indicator.');
                        echoSection('year=XXX', 'Only selects data from the year
                                                                 <span class="inlinejson">XXX</span>.');
                        echoSection('param[]=XXX&amp;param[]=YYY', 'The above parameters can have multiple
                                                                 values. For instance,
                                                                 <span class="inlinejson">year[]=2019&amp;year[]=2016</span>
                                                                 selects data from 2019 and 2016.');
                    ?>
                </details>
                <details>
                    <summary>GGGIs indexes</summary>
                    <?php
                        echoSection('type=XXX&amp;func=YYY&amp;funcval=ZZZ',
                            'To perform a selection on the values, you need to specify:
                                        the <span class="inlinejson">type</span> which can be either 
                                        <span class="inlinejson">index</span> (actual value of the index) or
                                        <span class="inlinejson">rank</span> (the relative position of each country),
                                        the <span class="inlinejson">func</span> (function use to filter the values)
                                        which can be either
                                        <span class="inlinejson">==</span> (value is equal to <span class="inlinejson">funcval</span>),
                                        <span class="inlinejson">!=</span> (value is different from <span class="inlinejson">funcval</span>),
                                        <span class="inlinejson"><</span> (value is lower than <span class="inlinejson">funcval</span>),
                                        <span class="inlinejson"><=</span> (value is equal or lower than <span class="inlinejson">funcval</span>),
                                        <span class="inlinejson"><</span> (value is greater than <span class="inlinejson">funcval</span>),
                                        <span class="inlinejson"><=</span> (value is equal or greater than <span class="inlinejson">funcval</span>),
                                        <span class="inlinejson">max</span> (value is in the top <span class="inlinejson">funcval</span>) or
                                        <span class="inlinejson">min</span> (value is in the bottom <span class="inlinejson">funcval</span>), and
                                        the <span class="inlinejson">funcval</span> (or numeric value used for the filter function).<br>
                                        <strong>All three parameters should be used</strong>.');
                    ?>
                </details>
            </section>
        </p>

        <h1>Documentation</h1>
        <p>
            GGGI = Global Gender Gap Index.<br>
            Data used by the World Economic Forum to publish their <a href="https://reports.weforum.org/global-gender-gap-report-2020/the-global-gender-gap-index-2020/">Gender Gap Index Report</a>.
        </p>

        <h2>Content</h2>
        <p>
            <ul>
                <li><strong>gggi.csv</strong> Global Gender Gap Index details</li>
                <li><strong>codes.csv</strong> country codes</li>
            </ul>
        </p>

        <h2>Data structure</h2>
        <p>
            The attributes present in the <strong>gggi</strong> CSV are:
            <ul>
                <li><strong>code</strong> the <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3">ISO 3166-1 alpha-3</a> country code.</li>
                <li><strong>indicator</strong> national gender gap benchmark, {'glob', 'eco', 'edu', 'health', 'pol'} for "Global index", "Economic Participation", "Educational Attainment", "Health and Survival" and "Political Empowerment".</li>
                <li><strong>year</strong> year of the indicator [2006-2020].</li>
                <li><strong>index</strong> value of the indicator in the [0.-1.] range, values are floats.</li>
                <li><strong>rank</strong> value of the rank in the [1-156] range, all values are floats.</li>
            </ul>
        </p>
        <p>
            The attributes present in the <strong>countries</strong> table are:
            <ul>
                <li><strong>code</strong> the <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3">ISO 3166-1 alpha-3</a> country code.</li>
                <li><strong>country</strong> name of the country in english.</li>
                <li><strong>subregion</strong> world subregion (~sub continent).</li>
                <li><strong>region</strong> world region (~continent).</li>
            </ul>
        </p>
    </body>
</html>
