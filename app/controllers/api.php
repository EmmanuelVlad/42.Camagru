<?php

class Api extends Controller
{
    private $return = [];
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
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

    public function username($name)
    {
        $this->return['success'] = false;
        
        if (isset($name)) {
            $check = $this->db->get("*", "users", "username", "'$name'");
            if ($check) {
                $this->return['success'] = true;
            }
        }
        $this->printReturn();
    }


    //
    //      REGISTER
    //
    public function register()
    {
        $this->return['success'] = false;
        $this->return['errors'] = [];
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password2'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];

            if (!preg_match("/^[a-z-A-Z0-9_-]{3,15}$/", $username)) {
                array_push($this->return['errors'], "Username must have between 3 and 15 alphanumeric characters only.");
            }
            if ($this->db->get("*", "users", "username", "'$username'")) {
                array_push($this->return['errors'], "Username not available.");
            }
            if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,3})$/", $email)) {
                array_push($this->return['errors'], "Your email address isn't valid.");
            }
            if (!preg_match("/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/", $password)) {
                array_push($this->return['errors'], "Your password must have at least 8 characters with at least 1 uppercase and 1 number.");
            }
            if (preg_match("/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/", $password) && $password2 !== $password) {
                array_push($this->return['errors'], "Your password doesn't match.");
            }
            if (empty($this->return['errors'])) {
                $key = randChar(30);
                $query = $this->db->insert('users', [
                    "username"  => $username,
                    "email"     => $email,
                    "password"  => sha1($password),
                    "key"       => $key
                ]);
                if ($query) {
                    // $this->return['mail'] = mail($email, "test", "test");
                    if (mail($email, "Welcome on Camagru $username!", "Here is your activation link: http:" . URL . "activate/$key")) {
                        $this->return['success'] = true;
                    } else {
                        $this->db->action('DELETE', 'users', "WHERE username = '$username'");
                        $this->return['error'] = "Couldn't send the mail";
                    }
                } else {
                    $this->return['error'] = "There was an error while creating your account, please retry!";
                }
            }
        } else {
            $this->return['error'] = "Please fill the form!";
        }

        $this->printReturn();
    }
}