<?php

include "app/init.php";

$url = (isset($_GET['url'])) ? $_GET['url'] : "";
$router = new Router($url);

$router->get('/', 'home');
$router->get('/login', 'auth/login');
$router->post('/login', 'auth/login_post');
$router->get('/logout', 'auth/logout');
$router->get('/reset', 'auth/reset');
$router->get('/reset/<:key|[a-z-A-Z0-9]{30}>', 'auth/reset_password');
$router->get('/register', 'auth/register');
$router->get('/activate/<:key|[a-z-A-Z0-9]{30}>', 'auth/activate');

// API
$router->get('/api/username/<:name|[a-z-A-Z0-9_-]{2,15}>', 'api/username');
$router->get('/api/email/<:email|([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,3})>', 'api/email');
$router->post('/api/register', 'api/register');
$router->post('/api/reset', 'api/reset');
$router->post('/api/reset_password', 'api/reset_password');


// Example in case I forget about
$router->get('/test2/<#id>/<:name|[A-Za-z]+>', function($id, $name) use ($router) {
    echo "id = $id, name = $name";
    echo $router->url('test2', ['id' => $id, 'name' => $name]);
}, 'test2');


$router->execute();