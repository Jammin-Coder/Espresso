function get(url, callback) {
    
    let http = new XMLHttpRequest();
    http.onreadystatechange = function () {
        if (http.readyState === XMLHttpRequest.DONE) {
            if (callback && typeof(callback) === 'function') {
                callback(http.getResponseHeader('api-response'));
            }
        }
    };
    http.open('GET', url);
    http.send();
}


function post(url, params) {
    
    let http = new XMLHttpRequest();
    http.onreadystatechange = function () {
        if (http.readyState === XMLHttpRequest.DONE) {
            console.log(http.status);
        }
    };
    http.open('POST', url);
    http.send(params);
}

// get('http://localhost:8000/api', response => {
//     console.log(response);
// });
get('http://localhost:8000/api', response => {console.log(response);})
post('http://localhost:8000/post-api', 'data=DATA_HERE');