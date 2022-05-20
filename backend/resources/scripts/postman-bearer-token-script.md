### Postman bearer token automation script


To get an authentication token in Postman automatically just do this [Tutorial](https://itnext.io/getting-a-bearer-token-in-postman-newman-automatically-for-a-collection-d3001a0dc1ea) but use the script below as pre request script instead of the one from the manual.

Also use the collection variables section in the collection tab to set the used vars in this script.
```
pm.sendRequest({
    url: pm.collectionVariables.get("url") + "/authentication_token",
    method: 'POST',
    header: {
        'Accept': '*/*',
        'Content-Type': 'application/json; charset=UTF-8'
    },
    body: {
        mode: 'raw',
        raw: JSON.stringify({
        email: pm.collectionVariables.get("email"),
        password: pm.collectionVariables.get("password")
        })
    }
},
    (err, res) => {
        // Set BEARERTOKEN
        pm.globals.set("BEARERTOKEN", res.json().token)
        // console.log(res.json());
});
```