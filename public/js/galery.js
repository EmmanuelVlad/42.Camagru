var load = 6;

function appendComment(user, comment)
{
    var media = document.createElement("media");
    media.classList.add("media");

    var media_content = document.createElement("div");
    media_content.classList.add("media-content");

    var content = document.createElement("div");
    content.classList.add("content");


    var strong = document.createElement("strong");
    var p = document.createElement("p");

    strong.innerText = user;
    p.innerText = comment;

    content.appendChild(strong);
    content.appendChild(p);

    media_content.appendChild(content);
    media.appendChild(media_content);
    return media;
}

function addComment(textarea, modal_content, button, photo)
{
    if (!button.classList.contains("is-loading")) {
        button.classList.add("is-loading");
        var xhr = new XMLHttpRequest();
        xhr.open('POST', `${url}api/comment`);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                
                var data = JSON.parse(xhr.responseText);
                console.log(data);
                if (data.success) {
                    textarea.value = "";
                    modal_content.insertBefore(appendComment(data.username, data.comment), modal_content.childNodes[3]);
                } else {
                    if (data.errors) {
                        data.errors.forEach(function(el) {
                            addNotification(notifications, 'is-danger', el);
                        });
                    }
                }
            }
            button.classList.remove("is-loading");
        };
        xhr.send(encodeURI(`comment=${textarea.value}&photo=${photo}`));
    }
}

function handleLike(like, like_text, likes, photo)
{
    var xhr = new XMLHttpRequest();
    var type;
    if (like.classList.contains("is-link")) {
        type = "unlike";
        xhr.open('POST', `${url}api/unlike`);
    } else {
        type = "like";
        xhr.open('POST', `${url}api/like`);
    }
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr.responseText);
            var data = JSON.parse(xhr.responseText);
            console.log(data);
            if (data.success) {
                if (type === "unlike") {
                    like_text.data = " Like";
                } else if (type === "like") {
                    like_text.data = " Liked";
                }
                if (data.likes === 1) {
                    likes.data = data.likes + " like";
                } else {
                    likes.data = data.likes + " likes";
                }
                like.classList.toggle('is-link');
            } else {
                if (data.errors) {
                    data.errors.forEach(function(el) {
                        addNotification(notifications, 'is-danger', el);
                    });
                }
            }
        }
    };
    xhr.send(encodeURI(`photo=${photo}`));
}

function setModal(data)
{
    var modal = document.getElementById("modal");
    var modal_content = document.getElementById("modal-content");
    modal_content.innerHTML = "";
    var figure = document.createElement("figure");
    figure.classList.add("image");
    figure.classList.add("is-16by9");

    var img = document.createElement("img");
    img.src = url + "public/images/montage/" + data.image + ".jpeg";
    img.alt = data.image;

    figure.appendChild(img);
    modal_content.appendChild(figure);

    var media = document.createElement("article");
    media.classList.add("media");
    var media_content = document.createElement("div");
    media_content.classList.add("media-content");
    var likes = document.createTextNode(data.likes + " likes");
    if (data.likes === 1) {
        likes.data = data.likes + " like";
    }
    var like = document.createElement("button");
    like.classList.add("button");
    like.id = "like";
    like.addEventListener("click", function(event) {
        handleLike(like, like_text, likes, data.id);
    });
    var like_icon = document.createElement("i");
    like_icon.classList.add("fa");
    like_icon.classList.add("fa-thumbs-up");
    var like_text = document.createTextNode(" Like");

    if (data.is_liked) {
        like_text.data = " Liked";
        like.classList.add('is-link');
    }

    like.appendChild(like_icon);
    like.appendChild(like_text);
    media_content.appendChild(like);
    media_content.appendChild(likes);
    if (user == data.author.id) {
        var deleteButton = document.createElement("button");
        deleteButton.classList.add("button");
        deleteButton.classList.add("is-danger");
        deleteButton.classList.add("is-pulled-right");
        deleteButton.addEventListener("click", function(event) {
            deletePhoto(data.image);
        });
        var deleteText = document.createTextNode(" Delete");

        deleteButton.appendChild(deleteText)
        media_content.appendChild(deleteButton);
    }
    media.appendChild(media_content);
    modal_content.appendChild(media);
    
    var media = document.createElement("article");
    media.classList.add("media");
    var media_content = document.createElement("div");
    media_content.classList.add("media-content");
    var field = document.createElement("div");
    field.classList.add("field");
    var field2 = document.createElement("div");
    field.classList.add("field");
    var control = document.createElement("p");
    control.classList.add("control");
    var control2 = document.createElement("p");
    control.classList.add("control");
    var textarea = document.createElement("textarea");
    textarea.classList.add("textarea");
    textarea.placeholder = "Add a comment..."
    console.log(connected);
    var button = document.createElement("button");
    button.classList.add("button");
    button.classList.add("is-primary");
    button.innerText = "Post comment";
    button.addEventListener("click", function(event) {
        addComment(textarea, modal_content, button, data.id);
    });
    textarea.addEventListener("keyup", function(event) {
        if (event.keyCode == 13) {
            addComment(textarea, modal_content, button, data.id);
        }
    });

    control.appendChild(textarea);
    field.appendChild(control);
    media_content.appendChild(field);

    control2.appendChild(button);
    field2.appendChild(control2);
    media_content.appendChild(field2);

    media.appendChild(media_content);
    modal_content.appendChild(media);
    
    modal.classList.add("is-active");

    if (!connected) {
        textarea.disabled = true;
        button.disabled = true;
        like.disabled = true;
    }

    data.comments.forEach(function(el) {
        modal_content.appendChild(appendComment(el.username, el.comment));
    });
}

function getImage(image)
{
    var xhr = new XMLHttpRequest();
    xhr.open('GET', `${url}api/image/${image}`);
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr.responseText);
            
            var data = JSON.parse(xhr.responseText);
            console.log(data);
            if (data.success) {
                setModal(data);
            } else {
                if (data.errors) {
                    data.errors.forEach(function(el) {
                        addNotification(notifications, 'is-danger', el);
                    });
                }
            }
        }
    };
    xhr.send();
}

function addPhoto(photo)
{
    var column = document.createElement("div");
    column.classList.add("column");
    column.classList.add("is-6-mobile");
    column.classList.add("is-4-tablet");
    column.id = photo;

    var figure = document.createElement("figure");
    figure.classList.add("image");
    figure.classList.add("is-16by9");
    figure.addEventListener("click", function(event) {
        getImage(photo);
    })

    var img = document.createElement("img");
    img.src = url + "public/images/montage/" + photo + ".jpeg";
    img.alt = "image";

    figure.appendChild(img);
    column.appendChild(figure);

    return column;
}

function loadMore(int = 6)
{
    var photos = document.getElementById("photos");
    var xhr = new XMLHttpRequest();
    xhr.open('GET', `${url}api/images/${load}/${int}`);
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr.responseText);
            var data = JSON.parse(xhr.responseText);
            console.log(data);
            if (data.success) {
                data.images.forEach(function(el) {
                    photos.appendChild(addPhoto(el.photo));
                });
                if (data.images.length != int) {
                    document.getElementById("loadmore").remove();
                }
                load += int;
            }
        }
    };
    xhr.send();
}

function deletePhoto(photo)
{
    var xhr = new XMLHttpRequest();
    xhr.open('POST', `${url}api/delete`);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr.responseText);
            var data = JSON.parse(xhr.responseText);
            console.log(data);
            if (data.success) {
                load -= 1;
                loadMore(1);
                document.getElementById(photo).remove();
            }
        }
    };
    xhr.send(encodeURI(`photo=${photo}`));
}