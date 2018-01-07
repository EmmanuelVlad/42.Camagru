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
            $this->redirect("register");
        }

        $this->view('footer');
    }

    public function login()
    {
        $this->view('header', [
            'title' => "Login",
            'custom_meta' => '<script src="'.URL.'public/js/login.js"></script>'
        ]);
        $this->view('menu');
        $this->view('home/login');
        $this->view('footer');
    }

    public function register()
    {
        $this->view('header', [
            'title' => "Register",
            'custom_meta' => '<script src="'.URL.'public/js/register.js"></script>'
        ]);
        $this->view('menu');
        $this->view('home/register');
        $this->view('footer');
    }

    public function test($id, $name)
    {
        echo "ID = $id, name = $name";
    }

}