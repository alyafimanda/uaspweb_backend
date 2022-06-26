<?php
include '../config.php';
/**
 * @var $connection PDO
 */


$query ="select * from barang";

//prepare query
$statement = $connection->query($query);
$statement->setFetchMode(PDO::FETCH_ASSOC);
//jalankan query
$result = $statement->fetchAll();
$output = [
    'barang' => $result,
];
//Output json
header('Content-Type: application/json');
echo json_encode($output);