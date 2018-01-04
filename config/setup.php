<?php
    require "database.php";

    try {
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec('CREATE DATABASE IF NOT EXISTS `camagru` COLLATE utf8_general_ci');
        $pdo->exec('USE `camagru`');
        
        $pdo->exec('CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(30) NOT NULL,
            `email` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `active` tinyint(4) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        )');
    } catch(PDOException $e) {
        die("Error: ".$e->getMessage());
    }