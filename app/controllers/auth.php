<?php

class Auth extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function login()
    {
        if (!Session::get('user')) {
            $this->view('header', [
                'title' => "Login",
                'custom_meta' => '<script src="'.URL.'public/js/login.js"></script>'
            ]);
            $this->view('menu');
            $this->view('home/login');
            $this->view('footer');
        } else {
            $this->redirect('');
        }
    }

    public function register()
    {
        if (!Session::get('user')) {
            $this->view('header', [
                'title' => "Register",
                'custom_meta' => '<script src="'.URL.'public/js/register.js"></script>'
            ]);
            $this->view('menu');
            $this->view('home/register');
            $this->view('footer');
        } else {
            $this->redirect('');
        }
    }

    public function activate($key)
    {
        if (!Session::get('user')) {
            $check = $this->db->get("*", "users", "`key`", "'$key'");
            if ($check) {
                $this->db->update("users", "`key`", "'$key'", ["key" => ""]);
                Session::set('user', $check->id);
                $this->redirect('');
            } else {
                $this->redirect('login');
            }
        } else {
            $this->redirect('');
        }
    }

}