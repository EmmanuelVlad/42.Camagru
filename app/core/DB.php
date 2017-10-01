<?php
class DB {
    private static $_instance = null;
    private $pdo,
            $query,
            $error = false,
            $results,
            $count = 0,
            $data;

    private function __construct() {
        try {
            $this->pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db') . ';charset=utf8', Config::get('mysql/username'), Config::get('mysql/password'));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->pdo->exec('CREATE DATABASE IF NOT EXISTS `camagru` COLLATE utf8_general_ci');
            $this->pdo->exec('USE `camagru`');
            
            $this->pdo->exec('CREATE TABLE IF NOT EXISTS `users` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `username` varchar(30) NOT NULL,
              `email` varchar(255) NOT NULL,
              `password` varchar(255) NOT NULL,
              `active` tinyint(4) NOT NULL DEFAULT 0,
              PRIMARY KEY (`id`)
            )');
        } catch(PDOException $e) {
            die("test: ".$e->getMessage());
        }
    }

    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    public function query($sql, $params = array()) {
        $this->error = false;

        if ($this->query = $this->pdo->prepare($sql)) {
            $i = 1;
            if (count($params)) {
                foreach($params as $param) {
                    $this->query->bindValue($i, $param);
                    $i++;
                }
            }

            if ($this->query->execute()) {
                $query = explode(" ", $sql);
                if ($query[0] == "SELECT") {
                    $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
                }
                $this->count = $this->query->rowCount();
            } else {
                $this->error = "Couldn't execute the query!";
            }
        }

        return $this;
    }

    public function action($action, $table, $extra = '', $params = array()) {
        $sql = "{$action} FROM {$table} {$extra}";
        
        if (!$this->query($sql, $params)->error()) {
            return $this;
        }
        return false;
    }

    public function insert($table, $fields = array()) {
        if (count($fields)) {
            $keys = array_keys($fields);
            $sql = "INSERT INTO `{$table}` (`" . implode('`, `', $keys) . "`) VALUES (" . implode(',', array_fill(0, count($keys), '?')) . ")";
            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }

    public function update($table, $identifier, $id, $fields = array()) {
        if (count($fields)) {
            $keys = array_keys($fields);
            $sql = "UPDATE {$table} SET `" . implode('` = ?, `', $keys) . "` = ? WHERE {$identifier} = {$id}";
            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }

    public function results() {
        return $this->results;
    }

    public function first() {
        return $this->results()[0];
    }

    public function error() {
        return $this->error;
    }

    public function count() {
        return $this->count;
    }

    public function get($table, $identifier, $id, $field) {
        return $this->query("SELECT {$field} FROM {$table} WHERE {$identifier} = {$id}")->first();
    }
}