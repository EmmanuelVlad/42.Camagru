<?php

include "app/init.php";

$url = (isset($_GET['url'])) ? $_GET['url'] : "";
$router = new Router($url);

$router->get('/', 'home');
$router->get('/test/<#id>/<:name|[A-Za-z]+>', 'home/test');

$router->get('/test2/<#id>/<:name|[A-Za-z]+>', function($id, $name) use ($router) {
    // echo "id = $id, name = $name";
    echo $router->url('test2', ['id' => $id, 'name' => $name]);
}, 'test2');

$router->get('/<#id>', function($id) {
    echo "id = $id";
});

$router->execute();