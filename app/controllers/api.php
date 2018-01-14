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

    public function images($start, $end)
    {
        $this->return['success'] = false;
        $images = $this->db->action("SELECT `photo`", "photos", "ORDER BY `id` DESC LIMIT $start,$end")->results();
        if ($images) {
            $this->return['images'] = $images;
            $this->return['success'] = true;
        }
        $this->printReturn();
    }

    public function image($image)
    {
        $this->return['success'] = false;
        
        if (isset($image)) {
            $check = $this->db->get("*", "photos", "photo", "'$image'");
            if ($check) {
                if (Session::get('user')) {
                    $is_liked = $this->db->action('SELECT *', 'likes', 'WHERE user = ? AND photo = ?', [Session::get('user'), $check->id])->first();
                } else {
                    $is_liked = false;
                }
                $author = $this->db->query("SELECT id, username FROM users WHERE id = ?", [$check->user])->first();
                $likes = $this->db->get("*", "likes", "photo", $check->id);
                $likes = ($likes) ? count($likes) : 0;
                $comments = $this->db->query("SELECT comments.comment, users.username FROM comments, users WHERE comments.photo = ? AND users.id = comments.user ORDER BY comments.id DESC", [$check->id])->results();
                $this->return['success'] = true;
                $this->return['id'] = $check->id;
                $this->return['author'] = $author;
                $this->return['image'] = $image;
                $this->return['likes'] = $likes;
                $this->return['is_liked'] = ($is_liked) ? true : false;
                $this->return['comments'] = $comments;
            }
        }
        $this->printReturn();
    }

    public function comment()
    {
        $this->return['success'] = false;
        $this->return['errors'] = [];

        if (Session::get('user') && isset($_POST['photo']) && isset($_POST['comment'])) {
            $photo = $_POST['photo'];
            $check = $this->db->get('*', 'photos', 'id', $photo);
            if ($check) {
                $comment = $_POST['comment'];
                $query = $this->db->insert('comments', [
                    "user"      => Session::get('user'),
                    "photo"     => $photo,
                    "comment"   => $comment
                ]);
                if ($query) {
                    $target = $this->db->query("SELECT users.email, users.notification FROM users, photos WHERE photos.user = users.id AND photos.id = $photo")->first();
                    $user = $this->db->get('*', 'users', 'id', Session::get('user'));
                    if ($target->notification == 1) {
                        mail($target->email, "$user->username commented your photo!", "You got a new comment on your photo from $user->username : $comment");
                    }
                    $this->return['username'] = $user->username;
                    $this->return['comment'] = $comment;
                    $this->return['success'] = true;
                }
            } else {
                array_push($this->return['errors'], "This photo doesn't exist!");
            }
        } else {
            array_push($this->return['errors'], "Please log in first!");
        }
        $this->printReturn();
    }

    public function like()
    {
        $this->return['success'] = false;
        $this->return['errors'] = [];

        if (Session::get('user') && isset($_POST['photo'])) {
            $photo = $_POST['photo'];
            $check = $this->db->get('*', 'photos', 'id', $photo);
            if ($check) {
                $check = $this->db->action('SELECT *', 'likes', 'WHERE user = ? AND photo = ?', [Session::get('user'), $photo])->first();
                if (!$check) {
                    $query = $this->db->insert('likes', [
                        "user"      => Session::get('user'),
                        "photo"     => $photo
                    ]);
                    if ($query) {
                        $likes = $this->db->get("*", "likes", "photo", $photo);
                        $likes = ($likes) ? count($likes) : 0;
                        $this->return['likes'] = $likes;
                        $this->return['success'] = true;
                    }
                } else {
                    array_push($this->return['errors'], "You've already liked this post!");
                }
            } else {
                array_push($this->return['errors'], "This photo doesn't exist!");
            }
        } else {
            array_push($this->return['errors'], "Please log in first!");
        }
        $this->printReturn();
    }

    public function unlike()
    {
        $this->return['success'] = false;
        $this->return['errors'] = [];

        if (Session::get('user') && isset($_POST['photo'])) {
            $photo = $_POST['photo'];
            $check = $this->db->get('*', 'photos', 'id', $photo);
            if ($check) {
                $check = $this->db->action('SELECT *', 'likes', 'WHERE user = ? AND photo = ?', [Session::get('user'), $photo])->first();
                if ($check) {
                    $this->db->action('DELETE', 'likes', "WHERE user = ? AND photo = ?", [Session::get('user'), $photo]);
                    $likes = $this->db->get("*", "likes", "photo", $photo);
                    $likes = ($likes) ? count($likes) : 0;
                    $this->return['likes'] = $likes;
                    $this->return['success'] = true;
                } else {
                    array_push($this->return['errors'], "You don't like this post!");
                }
            } else {
                array_push($this->return['errors'], "Please log in first!");
            }
        } else {
            array_push($this->return['errors'], "Please log in first!");
        }
        $this->printReturn();
    }

    public function delete()
    {
        $this->return['success'] = false;
        $this->return['errors'] = [];
        if (Session::get('user') && isset($_POST['photo'])) {
            $photo = $_POST['photo'];
            $check = $this->db->get("*", "photos", "photo", "'$photo'");
            if ($check) {
                if ($check->user === Session::get('user')) {
                    $this->db->action('DELETE', 'photos', "WHERE id = $check->id");
                    $this->return['success'] = true;
                } else {
                    array_push($this->return['errors'], "You are not the owner of this photo!");
                }
            } else {
                array_push($this->return['errors'], "Photo doesn't exist");
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
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['notification']) && isset($_POST['password']) && isset($_POST['password2'])) {
                $old = $this->db->get("*", "users", "id", $user);
                $username = $_POST['username'];
                $email = $_POST['email'];
                $notification = ($_POST['notification'] === "true") ? 1 : 0;
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
                    $this->db->update("users",  "id", $user, ["notification" => $notification]);
                    $new = $this->db->get("*", "users", "id", $user);
                    $this->return['username'] = $new->username;
                    $this->return['email'] = $new->email;
                    $this->return['notification'] = $notification;
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

        $type = ['image/png', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif'];
        if (isset($_FILES['file'])) {
            if (in_array($_FILES['file']['type'], $type)) {
                $tmp = file_get_contents($_FILES['file']['tmp_name']);
                $base64 = "data: " . $_FILES['file']['type'] . ";base64," . base64_encode($tmp);
                $this->return['base64'] = $base64;
                $this->return['success'] = true;
            } else {
                array_push($this->return['errors'], "Bad mime type");
            }
        }

        $this->printReturn();
    }


    //
    //      Montage
    //
    function montage()
    {
        $this->return['success'] = false;
        $this->return['errors'] = [];

        $stickers = ["michael.png", "dwight.png", "monkas.png", "thenking.png"];
        $type = ['image/png', 'image/jpg', 'image/jpeg'];
        if (isset($_FILES['img']) && isset($_POST['sticker']) && Session::get('user')) {
            $sticker = $_POST['sticker'];

            if (!in_array($sticker, $stickers)) {
                array_push($this->return['errors'], "Sticker not found!");
            }
            if (!in_array($_FILES['img']['type'], $type)) {
                array_push($this->return['errors'], "Wrong image format");
            }

            if (empty($this->return['errors'])) {
                $rand = randChar(50);
                $filename = $_FILES['img']['tmp_name'];
                $img = file_get_contents($filename);
                list($width, $height) = getimagesize($filename);
                $png = imagecreatefrompng(ROOT . "public/images/$sticker");
                $marge_right = 10;
                $marge_bottom = 10;
                $sx = imagesx($png);
                $sy = imagesy($png);

                // RESIZING
                $resized_img = imagecreatetruecolor(1280, 720);
                $source = imagecreatefromstring($img);
                imagecopyresized($resized_img, $source, 0, 0, 0, 0, 1280, 720, $width, $height);

                // MERGE
                imagecopyresampled($resized_img, $png, 1280 - $sx - $marge_right, 720 - $sy - $marge_bottom, 0, 0, imagesx($png), imagesy($png), imagesx($png), imagesy($png)); 

                imagejpeg($resized_img, ROOT . "public/images/montage/$rand.jpeg");
                imagedestroy($resized_img);
                imagedestroy($png);

                $query = $this->db->insert('photos', [
                    "user"  => Session::get('user'),
                    "photo"   => $rand
                ]);

                $this->return['image'] = URL . "public/images/montage/$rand.jpeg";
                $this->return['sticker'] = $sticker;
                $this->return['success'] = true;
            }
        }

        $this->printReturn();
    }
}