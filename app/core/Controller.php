<?php

class Controller
{
    public function model($model)
    {
        require_once ROOT . "app/models/{$model}.php";
        return new $model();
    }

    public function view($view, $data = [])
    {
        $data = (object)$data;
        require_once ROOT . "app/views/{$view}.php";
    }
}