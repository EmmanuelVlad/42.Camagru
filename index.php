<?php

require "app/init.php";

$url = (isset($_GET['url'])) ? $_GET['url'] : "";
$router = new Router($url);

$router->get('/', 'home');
$router->get('/test/<#id>/<:name|[A-Za-z]+>', 'home/test');

$router->get('/<#id>', function($id) {
    echo "id = $id";
});

$router->execute();