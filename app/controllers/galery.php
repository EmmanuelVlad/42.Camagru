<?php

class Galery extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function index()
    {
        $user = $this->model('User');
        
        $photos = $this->db->action("SELECT `photo`", "photos", "ORDER BY `id` DESC LIMIT 6")->results();
        $this->view('header', [
            'title' => "Galery",
            'custom_meta' => '<script src="'.URL.'public/js/galery.js"></script>'
        ]);
        $this->view('menu', ['user' => $user]);
        $this->view('home/galery', ['user' => $user, 'photos' => $photos]);

        $this->view('footer');
    }

}