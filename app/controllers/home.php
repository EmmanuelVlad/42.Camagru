<?php

class Home extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function index()
    {
        $user = $this->model('User');

        if (isset($user->id)) {
            $photos = $this->db->action("SELECT `photo`", "photos", "WHERE user = ? ORDER BY `id` DESC LIMIT 4", [$user->id])->results();
            $this->view('header', [
                'title' => "Montage",
                'custom_meta' => '<script src="'.URL.'public/js/montage.js"></script>'
            ]);
            $this->view('menu', ['user' => $user]);
            $this->view('home/index', ['user' => $user, 'photos' => $photos]);
        } else {
            $this->redirect("login");
        }

        $this->view('footer');
    }

}