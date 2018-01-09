var loading = false;

function upload(parent)
{
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
                            addNotification(notifications, 'is-success', "Uploaded!");
                            image.src = data.base64;
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

function makeVideo(parent)
{
    var figure = document.createElement("figure");
    figure.classList.add("image");
    figure.classList.add("is-16by9");

    var video = document.createElement("video");

    // figure.appendChild(video);
    parent.appendChild(video);
}

document.addEventListener("DOMContentLoaded", function(event) {
    var parent = document.getElementById('montage');
    navigator.getUserMedia = navigator.getUserMedia ||
                            navigator.webkitGetUserMedia ||
                            navigator.mozGetUserMedia;

    if (navigator.getUserMedia) {
        navigator.getUserMedia({ audio: false, video: { width: 1280, height: 720 } },
            function(stream) {
                makeVideo(parent);
                var video = document.querySelector('video');
                video.srcObject = stream;
                video.onloadedmetadata = function(e) {
                video.play();
                };
            },
            function(err) {
                upload(parent);
                console.log("The following error occurred: " + err.name);
            }
        );
    } else {
        upload(parent);
        console.log("getUserMedia not supported");
    }
});
