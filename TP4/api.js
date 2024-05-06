var token = 'none';

function APIcall(url, payload) {
    return new Promise((resolve, reject) => {
        try {
            payload = JSON.parse(payload);
        } catch (err) {
            payload = null;
        }

        let secure_version = document.querySelector('input[name="secure"]:checked').value == 'yes';
        if (payload == null) { payload = {}; }
        payload['secure'] = secure_version;
        payload['token'] = token;

        let request = new Request(url, {
            method: 'POST',
            body: JSON.stringify(payload),
            headers: new Headers({
                'Content-Type': 'application/json'
            })
        });

        fetch(request).then((res) => {
            if (res.ok) {
                res.text().then((text) => {
                    resolve(text);
                }).catch((err) => {
                    reject(`Unable to parse response: ${JSON.stringify(err)}`);
                });
            } else {
                reject(`Unable to parse response: ${JSON.stringify(res)}`);
            }
        }).catch((err) => {
            reject(`Unable to reach API: ${JSON.stringify(err)}`);
        })
    })
}

function request(secid) {
    let url = '/api/';
    let rest = document.querySelector(`#${secid} > fieldset > input[name="rest"]`).value;
    let payload = document.querySelector(`#${secid} > fieldset > input[name="payload"]`).value;
    let divres = document.querySelector(`#${secid} > .result`);
    divres.innerHTML = 'nothing yet...';
    APIcall(`${url}${rest}`, payload).then((res) => {
        try {
            divres.innerHTML = JSON.stringify(JSON.parse(res));
        } catch (err) {
            divres.innerHTML = err + '<br><br><strong>Raw text received:</strong><br>' + res;
        }
    }).catch((err) => {
        divres.innerHTML = err;
    })
}

document.querySelectorAll('input[enter]').forEach((item) => {
    item.addEventListener("keyup", (evt) => {
        if (evt.key === "Enter") { request(evt.target.getAttribute('secid')); }
    })
})

function updateAuthentificationBadge(val) {
    if (val) {
        document.querySelector('#check').classList.remove('hide');
        document.querySelector('#cross').classList.add('hide');
    } else {
        document.querySelector('#check').classList.add('hide');
        document.querySelector('#cross').classList.remove('hide');
    }
}

function toogleAuthentication() {
    let toogle = document.querySelector('input[name="secure"]:checked').value;
    if (toogle == 'yes') {
        document.querySelector('#authentication').classList.remove('hide');
    } else {
        document.querySelector('#authentication').classList.add('hide');
    }
    document.querySelector('input[name="login"]').value = '';
    document.querySelector('input[name="pwd"]').value = '';
    token = 'none';
}

function authentication() {
    updateAuthentificationBadge(false);
    let login = document.querySelector('input[name="login"]').value;
    let pwd = document.querySelector('input[name="pwd"]').value;
    APIcall('/api/user/authenticate', `{"login": "${login}", "pwd": "${pwd}"}`).then((res) => {
        try {
            res = JSON.parse(res);
            if (res.data) {
                token = res.token;
            }
            updateAuthentificationBadge(res.data);
        } catch (err) {
            divres.innerHTML = err + '<br><br><strong>Raw text received:</strong><br>' + res;
        }
    }).catch((err) => {
        console.log(err);
    })
}

function invalidate() {
    APIcall('/api/user/invalidate').then((res) => {
        updateAuthentificationBadge(false);
        token = 'none';
    }).catch((err) => {
        console.log(err);
    })
}