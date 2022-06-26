<?php

include '../config.php';
/**
 * @var $connection PDO
 */

header("Content-Type: application/json; charset=UTF-8");

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM `user` where $username = :username AND $password = :password";

$execute = mysqli_query($connection, $query);
$response = [];
$row = mysqli_fetch_assoc($execute);

if (count($row))

$json = json_encode($response, JSON_PRETTY_PRINT);
echo $json;