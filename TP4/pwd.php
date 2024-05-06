<ul>
    <li>login : admin</li>
    <li>pwd (clear): admin</li>
    <li>
        Use secure version (add/update/delete actions will require authentication)?
        <input type="radio" id="secureON" name="secure" value="yes" onchange="toogleAuthentication()">
        <label for="secureON">Yes</label>

        <input type="radio" id="secureOFF" name="secure" value="no" onchange="toogleAuthentication()" checked>
        <label for="secureOFF">No</label>
        <fieldset id="authentication" class="hide">
            <legend>Authentication:</legend>
            <legend id="check" class="hide">Authentified &check;</legend>
            <legend id="cross">Authentified &cross;</legend>
            <section>
                <label for="login">Login:</label>
                <input type="text" name="login" value="">
            </section>
            <section>
                <label for="pwd">Password:</label>
                <input type="password" name="pwd">
            </section>
            <button onclick="authentication()">Authenticate</button>
            <button onclick="invalidate()">Invalidate all tokens</button>
        </fieldset>
        <br>
        <strong>Note that information will transit securely only if the httpS protocole is enabled.</strong>
    </li>
</ul>