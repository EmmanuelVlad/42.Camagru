<?php

class Home extends Controller
{
    public function index()
    {
        $user = $this->model('User');

        $this->view('header');

        if (isset($user->id)) {
            $this->view('menu');
            $this->view('home/index', ['user' => $user]);
        } else {
            $this->view('home/register', ['user' => $user]);
        }

        $this->view('footer');
    }

    public function test($id, $name)
    {
        echo "ID = $id, name = $name";
    }

}