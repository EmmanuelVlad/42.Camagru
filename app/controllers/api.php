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

    public function email($email)
    {
        $this->return['success'] = false;
        
        if (isset($email)) {
            $check = $this->db->get("*", "users", "email", "'$email'");
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
            $check_username = $this->db->get("*", "users", "username", "'$username'");
            $check_email = $this->db->get("*", "users", "email", "'$email'");

            if (!preg_match("/^[a-z-A-Z0-9_-]{3,15}$/", $username)) {
                array_push($this->return['errors'], "Username must have between 3 and 15 alphanumeric characters only.");
            }
            if ($check_username) {
                array_push($this->return['errors'], "Username not available.");
            }
            if ($check_email) {
                array_push($this->return['errors'], "Email address already in use!");
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
                    if (mail($email, "Welcome on Camagru $username!", "Here is your activation link: http:" . URL . "activate/$key")) {
                        $this->return['success'] = true;
                    } else {
                        $this->db->action('DELETE', 'users', "WHERE username = '$username'");
                        array_push($this->return['errors'], "Couldn't send the mail");
                    }
                } else {
                    array_push($this->return['errors'], "There was an error while creating your account, please retry!");
                }
            }
        } else {
            array_push($this->return['errors'], "Please fill the form!");
        }

        $this->printReturn();
    }


    //
    //      RESET
    //
    public function reset()
    {
        $this->return['success'] = false;
        $this->return['errors'] = [];
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $user = $this->db->get("*", "users", "email", "'$email'");

            if (!$user) {
                array_push($this->return['errors'], "User with this email address does not exist.");
            }
            if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,3})$/", $email)) {
                array_push($this->return['errors'], "Your email address isn't valid.");
            }
            if (empty($this->return['errors'])) {
                $key = randChar(30);
                $query = $this->db->insert('reset_keys', [
                    "user"  => $user->id,
                    "key"   => $key
                ]);
                if ($query) {
                    if (mail($email, "You forgot your password $user->username?", "Here is your reset link: http:" . URL . "reset/$key")) {
                        $this->return['success'] = true;
                    } else {
                        $this->db->action('DELETE', 'reset_keys', "WHERE `key` = '$key'");
                        array_push($this->return['errors'], "Couldn't send the mail");
                    }
                } else {
                    array_push($this->return['errors'], "There was an error while creating your reset key, please retry!");
                }
            }
        } else {
            array_push($this->return['errors'], "Please fill the form!");
        }

        $this->printReturn();
    }


    //
    //      RESET PASSWORD
    //
    public function reset_password()
    {
        $this->return['success'] = false;
        $this->return['errors'] = [];
        if (isset($_POST['key']) && isset($_POST['password']) && isset($_POST['password2'])) {
            $key = $_POST['key'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $check = $this->db->get("*", "reset_keys", "`key`", "'$key'");

            if (!$check) {
                array_push($this->return['errors'], "Reset key doesn't exist!");
            }
            if (!preg_match("/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/", $password)) {
                array_push($this->return['errors'], "Your password must have at least 8 characters with at least 1 uppercase and 1 number.");
            }
            if (preg_match("/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/", $password) && $password2 !== $password) {
                array_push($this->return['errors'], "Your password doesn't match.");
            }

            if (empty($this->return['errors'])) {
                $delete = $this->db->action('DELETE', 'reset_keys', "WHERE `key` = '$key'");
                if ($delete) {
                    $query = $this->db->update("users", "`id`", "'$check->user'", ["password" => sha1($password)]);
                    if ($query) {
                        $this->return['success'] = true;
                    } else {
                        $this->db->insert('reset_keys', ["user" => $check->user, "key" => $key]);
                        array_push($this->return['errors'], "There was an error while updating your password, please retry!");
                    }
                } else {
                    array_push($this->return['errors'], "There was an error while deleting the key, please retry!");
                }
            }
        } else {
            array_push($this->return['errors'], "Please fill the form!");
        }

        $this->printReturn();
    }


    //
    //      Settings
    //
    public function settings()
    {
        $this->return['success'] = false;
        $this->return['errors'] = [];
        $user = Session::get('user');
        if ($user) {
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password2'])) {
                $old = $this->db->get("*", "users", "id", $user);
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $password2 = $_POST['password2'];
                $check_username = $this->db->get("*", "users", "username", "'$username'");
                $check_email = $this->db->get("*", "users", "email", "'$email'");

                if (!empty($username)) {
                    if (!preg_match("/^[a-z-A-Z0-9_-]{3,15}$/", $username)) {
                        array_push($this->return['errors'], "Username must have between 3 and 15 alphanumeric characters only.");
                    }
                    if ($check_username && $check_username->username !== $old->username) {
                        array_push($this->return['errors'], "Username not available.");
                    }
                }
                if (!empty($email)) {
                    if ($check_email && $check_email->email !== $old->email) {
                        array_push($this->return['errors'], "Email address not available.");
                    }
                    if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,3})$/", $email)) {
                        array_push($this->return['errors'], "Your email address isn't valid.");
                    }
                }
                if (!empty($password) && !empty($password2)) {
                    if (!preg_match("/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/", $password)) {
                        array_push($this->return['errors'], "Your password must have at least 8 characters with at least 1 uppercase and 1 number.");
                    }
                    if (preg_match("/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/", $password) && $password2 !== $password) {
                        array_push($this->return['errors'], "Your password doesn't match.");
                    }
                } else if ((!empty($password) && empty($password2)) || (!empty($password2) && empty($password))) {
                    array_push($this->return['errors'], "Please repeat your password!");
                }
                
                if (empty($this->return['errors'])) {
                    if (!empty($username)) {
                        $this->db->update("users", "id", $user, ["username" => $username]);
                    }
                    if (!empty($email)) {
                        $this->db->update("users", "id", $user, ["email" => $email]);
                    }
                    if (!empty($password) && !empty($password2)) {
                        $this->db->update("users", "id", $user, ["password" => sha1($password)]);
                    }
                    $new = $this->db->get("*", "users", "id", $user);
                    $this->return['username'] = $new->username;
                    $this->return['email'] = $new->email;
                    $this->return['success'] = true;
                }
            } else {
                array_push($this->return['errors'], "Please fill the form!");
            }
        }

        $this->printReturn();
    }


    //
    //      UPLOAD
    //
    function upload()
    {
        $this->return['success'] = false;
        $this->return['errors'] = [];

        $type = [
            'image/png',
            'image/jpeg',
            'image/jpeg',
            'image/jpeg'
        ];
        if (isset($_FILES['file'])) {
            $found = array_search($_FILES['file']['type'], $type);
            if ($found !== false) {
                $tmp = file_get_contents($_FILES['file']['tmp_name']);
                $base64 = "data: " . $type[$found] . ";base64," . base64_encode($tmp);
                $this->return['base64'] = $base64;
                $this->return['success'] = true;
            } else {
                array_push($this->return['errors'], "Incorrect mime type!");
            }
        }

        $this->printReturn();
    }
}