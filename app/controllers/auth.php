<?php

class Auth extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function login($error = null)
    {
        if (!Session::get('user')) {
            $this->view('header', [
                'title' => "Login"
            ]);
            $this->view('menu');
            $this->view('home/login', ["error" => $error]);
            $this->view('footer');
        } else {
            $this->redirect('');
        }
    }

    public function login_post()
    {
        if (!Session::get('user') && isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $check = $this->db->get("*", "users", "username", "'$username'");
            if ($check) {
                if ($check->key === "") {
                    if ($check->password === sha1($password)) {
                        Session::set('user', $check->id);
                        $this->redirect('');
                    } else {
                        $this->login("Incorrect password.");
                    }
                } else {
                    $this->login("Your account isn't activated yet.");
                }
            } else {
                $this->login("Account doesn't exist.");
            }
        } else {
            $this->redirect('');
        }
    }

    public function logout()
    {
        if (Session::get('user')) {
            Session::unset('user');
        }
        $this->redirect('');
    }

    public function reset()
    {
        if (!Session::get('user')) {
            $this->view('header', [
                'title' => "Reset password",
                'custom_meta' => '<script src="'.URL.'public/js/reset.js"></script>'
            ]);
            $this->view('menu');
            $this->view('home/reset');
            $this->view('footer');
        } else {
            $this->redirect('');
        }
    }

    public function reset_password($key)
    {
        if (!Session::get('user')) {
            $check = $this->db->get("*", "reset_keys", "`key`", "'$key'");
            if ($check) {
                $user = $this->db->get("*", "users", "id", $check->user);
                $this->view('header', [
                    'title' => "Reset password",
                    'custom_meta' => '<script src="'.URL.'public/js/reset_password.js"></script>'
                ]);
                $this->view('menu');
                $this->view('home/reset_password', ['user' => $user]);
                $this->view('footer');
            } else {
                $this->redirect('login');
            }
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

    public function settings()
    {
        if (Session::get('user')) {
            $user = $this->db->get("*", "users", "`id`", Session::get('user'));
            $this->view('header', [
                'title' => "Settings",
                'custom_meta' => '<script src="'.URL.'public/js/settings.js"></script>'
            ]);
            $this->view('menu');
            $this->view('home/settings', ["user" => $user]);
            $this->view('footer');
        } else {
            $this->redirect('');
        }
    }

}