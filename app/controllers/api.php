<?php

class Api extends Controller
{
    private $return = [];

    public function __construct()
    {
        header('Content-Type: application/json');
    }

    public function printReturn()
    {
        echo json_encode($this->return);
    }

    public function index()
    {
        $this->return['error'] = "Bad Request";
        $this->printReturn();
    }
}