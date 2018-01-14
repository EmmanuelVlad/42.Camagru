var loading = false;

function init() {
    var montage = document.getElementById("montage");
    montage.innerHTML = "";
    if (navigator.getUserMedia) {
        navigator.getUserMedia({ audio: false, video: { width: 1280, height: 720 } },
            function(stream) {
                makeVideo();
                var video = document.querySelector('video');
                video.srcObject = stream;
                video.onloadedmetadata = function(e) {
                video.play();
                };
            },
            function(err) {
                upload();
            }
        );
    } else {
        upload();
    }
}

function upload()
{
    var parent = document.getElementById('montage');
    var form = document.createElement("form");
    form.id = "form";
    form.action = "";
    form.method = "post";
    form.enctype = "multipart/form-data";

    var figure = document.createElement("figure");
    figure.classList.add("image");
    figure.classList.add("is-16by9");
    
    var image = document.createElement("img");
    image.src = "https://bulma.io/images/placeholders/640x360.png"

    var field = document.createElement("div");
    field.classList.add("field");

    var file = document.createElement("div");
    file.classList.add("file");
    file.classList.add("is-primary");
    file.classList.add("has-name");
    file.classList.add("is-fullwidth");

    var file_label = document.createElement("label");
    file_label.classList.add("file-label");

    var file_input = document.createElement("input");
    file_input.classList.add("file-input");
    file_input.type = "file";
    file_input.name = "file";

    var file_cta = document.createElement("span");
    file_cta.classList.add("file-cta");

    var file_icon = document.createElement("span");
    file_icon.classList.add("file-icon");
    file_icon.innerHTML = '<i class="fa fa-upload"></i>';

    var span_file_label = document.createElement("span");
    span_file_label.classList.add('file-label');
    span_file_label.innerText = "Choose file";

    var file_name = document.createElement("span");
    file_name.classList.add("file-name");
    file_name.innerText = "File name";

    figure.appendChild(image);


    file_cta.appendChild(file_icon);
    file_cta.appendChild(span_file_label);

    file_label.appendChild(file_input);
    file_label.appendChild(file_cta);
    file_label.appendChild(file_name);

    file.appendChild(file_label)

    form.appendChild(file);
    field.appendChild(form);

    parent.appendChild(figure);
    parent.appendChild(field);

    file_input.onchange = function() {
        if (!loading) {
            deleteNotifications();
            // if (file_input.files.length > 0 && file_input.files[0].type.match('image.*')) {
            if (file_input.files.length > 0) {
                var formData = new FormData();
                formData.append('file', file_input.files[0], file_input.files[0].name);

                file_name.innerText = file_input.files[0].name;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', `${url}api/upload`);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            image.src = data.base64;
                            parent.removeChild(field);
                            makeButtons(image.src);
                        } else {
                            if (data.errors) {
                                data.errors.forEach(function(el) {
                                    addNotification(notifications, 'is-danger', el);
                                });
                            }
                        }
                    }
                    loading = false;
                };
                xhr.send(formData);
            }
        }
    };
}

function makeVideo()
{
    var parent = document.getElementById('montage');
    var figure = document.createElement("figure");
    figure.classList.add("image");
    figure.classList.add("is-16by9");

    var video = document.createElement("video");
    var capture = document.createElement("button");
    capture.classList.add("button");
    capture.classList.add("is-primary");
    capture.innerText = "Capture";

    capture.addEventListener("click", function(event) {
        var canvas = document.createElement("canvas");
        var image = document.createElement("img");

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        image.src = canvas.toDataURL();

        parent.removeChild(video);
        parent.removeChild(capture);
        figure.appendChild(image);
        parent.appendChild(figure);
        makeButtons(image.src);
    });

    parent.appendChild(video);
    parent.appendChild(capture);
}

function makeButtons(img)
{
    var parent = document.getElementById('montage');
    var process_btn = document.createElement("button");
    process_btn.classList.add("button");
    process_btn.classList.add("is-primary");
    process_btn.innerText = "Process";

    var retry = document.createElement("button");
    retry.classList.add("button");
    retry.classList.add("is-danger");
    retry.classList.add("is-pulled-right");
    retry.innerText = "Retry";

    process_btn.addEventListener("click", function(event) {
        var sticker = document.querySelector('input[name="sticker"]:checked');
        var notifications = document.getElementById('notifications');

        if (!process_btn.classList.contains("is-loading")) {
            if (sticker) {
                var base64ImageContent = img.replace(/^data:(| )image\/(png|jpg|jpeg);base64,/, "");
                var blob = base64ToBlob(base64ImageContent, 'image/jpeg');            
                var formData = new FormData();
                formData.append('img', blob, 'img');
                formData.append('sticker', sticker.value);

                process_btn.classList.add("is-loading");
                deleteNotifications();
                var xhr = new XMLHttpRequest();
                xhr.open('POST', `${url}api/montage`);
                xhr.onload = function() {
                    process_btn.classList.remove("is-loading");
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            addImage(data.image);
                            addNotification(notifications, 'is-success', "Image uploaded!");
                            init();
                        } else {
                            if (data.errors) {
                                data.errors.forEach(function(el) {
                                    addNotification(notifications, 'is-danger', el);
                                });
                            }
                        }
                    }
                };
                xhr.send(formData);
                // xhr.send(encodeURI(`img=${img}&sticker=${sticker.value}`));
            } else {
                addNotification(notifications, 'is-danger', "Please select a sticker first.");
            }
        }
    });

    retry.addEventListener("click", function(event) {
        init();
    });

    parent.appendChild(process_btn);
    parent.appendChild(retry);
}

function addImage(image)
{
    var parent = document.getElementById("photos");

    var column = document.createElement("div");
    column.classList.add("column");

    var figure = document.createElement("figure");
    figure.classList.add("image");
    figure.classList.add("is-16by9");

    var img = document.createElement("img");
    img.src = image;
    img.alt = "image";

    figure.appendChild(img);
    column.appendChild(figure);
    if (parent.children.length > 3) {
        parent.removeChild(parent.lastElementChild);
    }
    parent.insertBefore(column, parent.childNodes[0]);
}

document.addEventListener("DOMContentLoaded", function(event) {
    navigator.getUserMedia = navigator.getUserMedia ||
                            navigator.webkitGetUserMedia ||
                            navigator.mozGetUserMedia;
    init();
});
