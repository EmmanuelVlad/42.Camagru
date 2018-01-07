document.addEventListener("DOMContentLoaded", function(event) {
    var form = document.getElementById('form');
    var notifications = document.getElementById('notifications');
    var email = document.getElementById('email');
    var submit = document.getElementById('submit');
    var loading = false;

    // On email change
    email.addEventListener("change", function(event) {
        if (/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,3})$/.test(email.value)) {
            email.parentElement.classList.toggle('is-loading');
            var xhr = new XMLHttpRequest();
            xhr.open('GET', `${url}api/email/${email.value}`);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    email.parentElement.classList.toggle('is-loading');
                    console.log(xhr.responseText);
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        changeColor(email, "is-success");
                        changeIcon(email.parentElement, "check");
                    } else {
                        changeColor(email, "is-danger");
                        changeIcon(email.parentElement, "times");
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

    // On reset click
    form.addEventListener("submit", function(event) {
        if (!loading) {
            deleteNotifications();
            loading = true;

            changeState(submit, 'is-loading');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', `${url}api/reset`);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        addNotification(notifications, 'is-success', "Check your emails!")
                        reset(email);
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
            xhr.send(encodeURI(`email=${email.value}`));
        }
        event.preventDefault();
    });
});

