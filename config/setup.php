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
            `notification` tinyint NOT NULL DEFAULT 1,
            `password` varchar(255) NOT NULL,
            `key` varchar(30) NOT NULL,
            PRIMARY KEY (`id`)
        )');

        $pdo->exec('CREATE TABLE IF NOT EXISTS `reset_keys` (
            `user` int NOT NULL,
            `key` varchar(30) NOT NULL
        )');

        $pdo->exec('CREATE TABLE IF NOT EXISTS `photos` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user` int NOT NULL,
            `photo` longtext NOT NULL
        )');

        $pdo->exec('CREATE TABLE IF NOT EXISTS `likes` (
            `photo` int NOT NULL,
            `user` int NOT NULL
        )');

        $pdo->exec('CREATE TABLE IF NOT EXISTS `comments` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user` int NOT NULL,
            `photo` int NOT NULL,
            `comment` text NOT NULL
        )');
    } catch(PDOException $e) {
        die("Error: ".$e->getMessage());
    }