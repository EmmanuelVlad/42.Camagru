<?php
class User {
    private $db;
    private $table = 'users';
    private $identifier = 'id';
    private $error = false;

    public  $data;
    public  $id;

    public function __construct($id = null, $username =  null) {
        $this->db = DB::getInstance();

        if (isset($id)) {
            $this->id = $id;
        } else if (isset($username) && $this->db->query('SELECT * FROM {$this->table} WHERE username = ?', array($username))->count()) {
            $this->id = $this->db->first()->id;
        } else if (Session::get('user')) {
            $this->id = Session::get('user');
        }

        if (isset($this->id)) {
            $this->data = $this->db->get("*", "users", "id", $this->id);
        }
    }

    public function create($fields = array()) {
        $this->error = false;
        if (!$this->db->query("SELECT * FROM {$this->table} WHERE username = ?", array($fields['username']))->count()) {
            return $this->db->insert($this->table, $fields);
        } else {
            $this->error = "Username " . $fields['username'] . " already exists!";
        }
        return false;
    }

    public function update($fields = array()) {
        return ($this->id) ? $this->db->update($this->table, $this->identifier, $this->id, $fields) : false;
    }

    public function get($field) {
        return ($this->id) ? $this->db->query("SELECT {$field} FROM {$this->table} WHERE {$this->identifier} = {$this->id}")->first()->$field : false;
    }

    public function error() {
        return $this->error;
    }
}