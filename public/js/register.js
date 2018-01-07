document.addEventListener("DOMContentLoaded", function(event) { 
    var username = document.getElementById('username');
    var email = document.getElementById('email');
    var password = document.getElementById('password');
    var password2 = document.getElementById('password2');
    var register = document.getElementById('register');


    // On username change
    username.addEventListener("change", function(event) {
        if (/^[a-z-A-Z0-9_-]{3,15}$/.test(username.value)) {
            username.parentElement.classList.toggle('is-loading');
            var xhr = new XMLHttpRequest();
            xhr.open('GET', `api/username/${username.value}`);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    username.parentElement.classList.toggle('is-loading');
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        changeColor(username, "is-danger");
                        changeIcon(username.parentElement, "times");
                    } else {
                        changeColor(username, "is-success");
                        changeIcon(username.parentElement, "check");
                    }
                }
            };
            xhr.send();
        } else if (username.value === "") {
            changeColor(username, "");
            changeIcon(username.parentElement, "");
        } else {
            changeColor(username, "is-danger");
            changeIcon(username.parentElement, "check");
        }
    });

    // On email change
    email.addEventListener("input", function(event) {
        if (/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,3})$/.test(email.value)) {
            changeColor(email, "is-success");
            changeIcon(email.parentElement, "check");
        } else if (email.value === "") {
            changeColor(email, "");
            changeIcon(email.parentElement, "");
        } else {
            changeColor(email, "is-danger");
            changeIcon(email.parentElement, "times");
        }
    });

    // On password change
    password.addEventListener("input", function(event) {
        if (/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/.test(password.value)) {
            changeColor(password, "is-success");
            changeIcon(password.parentElement, "check");
        } else if (password.value === "") {
            changeColor(password, "");
            changeIcon(password.parentElement, "");
        } else {
            changeColor(password, "is-danger");
            changeIcon(password.parentElement, "times");
        }

        if (password2.value === password.value) {
            changeColor(password2, "is-success");
            changeIcon(password2.parentElement, "check");
        } else if (password2.value === "") {
            changeColor(password2, "");
            changeIcon(password2.parentElement, "");
        } else {
            changeColor(password2, "is-danger");
            changeIcon(password2.parentElement, "times");
        }
    });

    // On password2 change
    password2.addEventListener("input", function(event) {
        if (password2.value === password.value && /^(?=.*[A-Z])(?=.*[0-9]).{8,}$/.test(password.value)) {
            changeColor(password2, "is-success");
            changeIcon(password2.parentElement, "check");
        } else if (password2.value === "") {
            changeColor(password2, "");
            changeIcon(password2.parentElement, "");
        } else {
            changeColor(password2, "is-danger");
            changeIcon(password2.parentElement, "times");
        }
    });

    // On register click
    register.addEventListener("click", function(event) {
        console.log('-----------');
        console.log(`Username: ${username.value}`);
        console.log(`Email: ${email.value}`);
        console.log(`Password: ${password.value}`);
        console.log(`Password2: ${password2.value}`);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'api/register');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // var data = JSON.parse(xhr.responseText);
                console.log(xhr.responseText);
            }
        };
        xhr.send(encodeURI(`username=${username.value}&email=${email.value}&password=${password.value}&password2=${password2.value}`));
    });
});

