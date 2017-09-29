<?php
try
{
	$db = new PDO('mysql:host=localhost;charset=utf8', 'root', '');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
  die('Error : ' . $e->getMessage());
}

$db->exec('CREATE DATABASE IF NOT EXISTS `camagru` COLLATE utf8_general_ci');
$db->exec('USE `camagru`');

$db->exec('CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)');
