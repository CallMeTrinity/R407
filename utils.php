<?php

    function getCSVData($filename) {
        // ouverture du fichier en lecture seul
        $fichier = fopen($filename, 'r') or die("impossible d'ouvrir le fichier");
        // lecture des entêtes
        $headers = fgetcsv($fichier) or die ('fichier vide !');
        $data = [];
        // lecture du contenu
        while ($ligne = fgetcsv($fichier)) {
            //conversion de la ligne (tableau) en tableau associatif par array_combine et ajout au tableau $data
            $data [] = array_combine($headers, $ligne);
        }
        fclose($fichier);

        return $data;
    }

    function echoTabAslist($tab) {
        echo "<ul>";
        foreach ($tab as $key => $item) {
            echo "<li><strong>$key : </strong>";
            if (is_array($item)) echoTabAslist($item);
            else echo "$item";
            echo "</li>";
        }
        echo "</ul>";
    }

    function echoAPITest($id, $title, $URI, $target) {
        echo <<<EOL
            <details id="${id}">
                <summary>${title}</summary>
                <fieldset>
                    <label for="url">Server</label>
                    <input name="url" type="text" disabled value="${_SERVER['HTTP_HOST']}/gggi-api.php?">
                    <label for="uri">URI</label>
                    <input name="uri" type="text" disabled value="${URI}" secid="${id}"><br>
                    <button onclick="request('${id}')">test API request</button>
                </fieldset>
                <pre class="target json">${target}</pre>
                <pre class="result json">nothing yet...</pre>
            </details>
        EOL;
    }

    function echoSection($title, $explanation) {
        echo <<<EOL
            <section>
                <details>
                    <summary><span class="inlinejson">${title}</span></summary>
                    <p>${explanation}</p>
                </details>
            </section>
        EOL;
    }

    $examples = array();
    $examples[] = array(
        'title' => 'No arguments',
        'URI' => '',
        'target' => 'Expected: 10325 data points'
    );
    $examples[] = array(
        'title' => 'Argument "code"',
        'URI' => 'code=FRA',
        'target' => 'Expected: 75 data points'
    );
    $examples[] = array(
        'title' => 'Argument "country"',
        'URI' => 'country=Belize',
        'target' => 'Expected: 70 data points'
    );
    $examples[] = array(
        'title' => 'Argument "subregion"',
        'URI' => 'subregion=South-eastern Asia',
        'target' => 'Expected: 670 data points'
    );
    $examples[] = array(
        'title' => 'Argument "region"',
        'URI' => 'region=Africa',
        'target' => 'Expected: 2395 data points'
    );
    $examples[] = array(
        'title' => 'Argument "code[]"',
        'URI' => 'code[]=FRA&code[]=ARG',
        'target' => 'Expected: 150 data points'
    );
    $examples[] = array(
        'title' => 'Argument "country[]"',
        'URI' => 'country[]=Kuwait&country[]=Zambia',
        'target' => 'Expected: 135 data points'
    );
    $examples[] = array(
        'title' => 'Argument "subregion[]"',
        'URI' => 'subregion[]=Northern America&subregion[]=Southern Europe',
        'target' => 'Expected: 915 data points'
    );
    $examples[] = array(
        'title' => 'Argument "region[]"',
        'URI' => 'region[]=Africa&region[]=Asia',
        'target' => 'Expected: 5325 data points'
    );
    $examples[] = array(
        'title' => 'Argument "exclude" 1',
        'URI' => 'code=ARG&exclude',
        'target' => 'Expected: 10250 data points'
    );
    $examples[] = array(
        'title' => 'Argument "exclude" 2',
        'URI' => 'region[]=Europe&region[]=Asia&exclude',
        'target' => 'Expected: 4630 data points'
    );
    $examples[] = array(
        'title' => 'Argument "exclude" 3',
        'URI' => 'region=Europe&subregion=Northern America&exclude',
        'target' => 'Expected: 7410 data points'
    );
    $examples[] = array(
        'title' => 'Argument "indicator"',
        'URI' => 'indicator=glob',
        'target' => 'Expected: 2065 data points'
    );
    $examples[] = array(
        'title' => 'Argument "year"',
        'URI' => 'year=2017',
        'target' => 'Expected: 720 data points'
    );
    $examples[] = array(
        'title' => 'Argument "indicator[]"',
        'URI' => 'indicator[]=edu&indicator[]=health',
        'target' => 'Expected: 4130 data points'
    );
    $examples[] = array(
        'title' => 'Argument "year[]"',
        'URI' => 'year[]=2017&year[]=2006',
        'target' => 'Expected: 1295 data points'
    );
    $examples[] = array(
        'title' => 'Argument combination 1',
        'URI' => 'indicator=pol&year=2020',
        'target' => 'Expected: 147 data points'
    );
    $examples[] = array(
        'title' => 'Argument combination 2',
        'URI' => 'indicator=pol&year[]=2007&year[]=2010',
        'target' => 'Expected: 262 data points'
    );
    $examples[] = array(
        'title' => 'Argument combination 3',
        'URI' => 'year=2007&code=FRA',
        'target' => 'Expected: 5 data points'
    );
    $examples[] = array(
        'title' => 'Complete test 1',
        'URI' => 'year=2019&type=rank&func===&funcval=1.0',
        'target' => 'Expected: 66 data points'
    );
    $examples[] = array(
        'title' => 'Complete test 2',
        'URI' => 'region=Asia&year=2020&type=index&func=>=&funcval=0.7',
        'target' => 'Expected: 106 data points'
    );
    $examples[] = array(
        'title' => 'Complete test 3',
        'URI' => 'year=2006&type=rank&func=max&funcval=10',
        'target' => 'Expected: 10 data points'
    );
    $examples[] = array(
        'title' => 'Complete test 4',
        'URI' => 'region[]=Europe&subregion[]=Northern America&exclude&year=2019&type=rank&func===&funcval=1.0',
        'target' => 'Expected: 42 data points'
    );
    $examples[] = array(
        'title' => 'Complete test 5',
        'URI' => 'year=2006&indicator=edu&type=index&func=>&funcval=0.7',
        'target' => 'Expected: 110 data points'
    );

?>