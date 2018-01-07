<?php

class Home extends Controller
{
    public function index()
    {
        $user = $this->model('User');

        if (isset($user->id)) {
            $this->view('header');
            $this->view('menu', ['user' => $user]);
            $this->view('home/index', ['user' => $user]);
        } else {
            $this->redirect("login");
        }

        $this->view('footer');
    }

}