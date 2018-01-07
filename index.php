<?php

include "app/init.php";

$url = (isset($_GET['url'])) ? $_GET['url'] : "";
$router = new Router($url);

$router->get('/', 'home');
$router->get('/login', 'auth/login');
$router->get('/register', 'auth/register');
$router->get('/activate/<:key|[a-z-A-Z0-9]{30}>', 'auth/activate');
$router->get('/test/<#id>/<:name|[A-Za-z]+>', 'home/test');

$router->get('/test2/<#id>/<:name|[A-Za-z]+>', function($id, $name) use ($router) {
    // echo "id = $id, name = $name";
    echo $router->url('test2', ['id' => $id, 'name' => $name]);
}, 'test2');

$router->get('/<#id>', function($id) {
    echo "id = $id";
    echo randChar(30);
});


$router->get('/api/username/<:name|[a-z-A-Z0-9_-]{2,15}>', 'api/username');
$router->post('/api/register', 'api/register');


$router->execute();