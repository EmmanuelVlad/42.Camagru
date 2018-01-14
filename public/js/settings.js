document.addEventListener("DOMContentLoaded", function(event) {
    var form = document.getElementById('form');
    var notifications = document.getElementById('notifications');
    var username = document.getElementById('username');
    var email = document.getElementById('email');
    var password = document.getElementById('password');
    var password2 = document.getElementById('password2');
    var submit = document.getElementById('submit');
    var loading = false;

    // On username change
    username.addEventListener("change", function(event) {
        if (/^[a-z-A-Z0-9_-]{3,15}$/.test(username.value)) {
            username.parentElement.classList.toggle('is-loading');
            var xhr = new XMLHttpRequest();
            xhr.open('GET', `${url}api/username/${username.value}`);
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
            reset(username);
        } else {
            changeColor(username, "is-danger");
            changeIcon(username.parentElement, "check");
        }
    });

    // On email change
    email.addEventListener("change", function(event) {
        if (/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,3})$/.test(email.value)) {
            email.parentElement.classList.toggle('is-loading');
            var xhr = new XMLHttpRequest();
            xhr.open('GET', `${url}api/email/${email.value}`);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    email.parentElement.classList.toggle('is-loading');
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        changeColor(email, "is-danger");
                        changeIcon(email.parentElement, "times");
                    } else {
                        changeColor(email, "is-success");
                        changeIcon(email.parentElement, "check");
                    }
                }
            };
            xhr.send();
        } else if (email.value === "") {
            reset(email);
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
            reset(password);
        } else {
            changeColor(password, "is-danger");
            changeIcon(password.parentElement, "times");
        }

        if (/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/.test(password.value) && password2.value === password.value) {
            changeColor(password2, "is-success");
            changeIcon(password2.parentElement, "check");
        } else if (password2.value === "") {
            reset(password2);
        } else {
            changeColor(password2, "is-danger");
            changeIcon(password2.parentElement, "times");
        }
    });

    // On password2 change
    password2.addEventListener("input", function(event) {
        if (/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/.test(password.value) && password2.value === password.value) {
            changeColor(password2, "is-success");
            changeIcon(password2.parentElement, "check");
        } else if (password2.value === "") {
            reset(password2);
        } else {
            changeColor(password2, "is-danger");
            changeIcon(password2.parentElement, "times");
        }
    });

    // On form submit
    form.addEventListener("submit", function(event) {
        if (!loading) {
            deleteNotifications();
            loading = true;

            changeState(submit, 'is-loading');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', `${url}api/settings`);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    console.log(data);
                    if (data.success) {
                        addNotification(notifications, 'is-success', "Your settings have been updated!")
                        reset(username);
                        reset(email);
                        reset(password);
                        reset(password2);
                        username.value = data.username;
                        email.value = data.email;
                    } else {
                        if (data.errors) {
                            data.errors.forEach(function(el) {
                                addNotification(notifications, 'is-danger', el);
                            });
                        }
                    }
                }
                changeState(submit, '');
                loading = false;
            };
            xhr.send(encodeURI(`username=${username.value}&email=${email.value}&notification=${document.getElementById('notification').checked}&password=${password.value}&password2=${password2.value}`));
        }
        event.preventDefault();
    });
});

