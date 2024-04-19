function APIcall(url) {
    return new Promise((resolve, reject) => {
        let request = new Request(url, {
            method: 'GET',
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
    let url = '/gggi-api.php?';
    let uri = document.querySelector(`#${secid} > fieldset > input[name="uri"]`).value;
    let divres = document.querySelector(`#${secid} > .result`);
    divres.innerHTML = 'nothing yet...';
    APIcall(`${url}${uri}`).then((res) => {
        try {
            divres.innerHTML = JSON.stringify(JSON.parse(res));
        } catch (err) {
            divres.innerHTML = err + '<br><br><strong>Raw text received:</strong><br>' + res;
        }
    }).catch((err) => {
        divres.innerHTML = err;
    })
}

document.querySelector('input[name="uri"]').addEventListener("keyup", (evt) => {
    if (evt.key === "Enter") { request(evt.target.getAttribute('secid')); }
})
