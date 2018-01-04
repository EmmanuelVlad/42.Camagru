<?php

class Errors extends Controller
{
    public function index()
    {
        return $this->notFound();
    }

    public function notFound()
    {
        echo "Error 404";
    }
}