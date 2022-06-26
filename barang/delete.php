<?php
include '../config.php';
/** @var $connection PDO */

if($_SERVER['REQUEST_METHOD'] !== 'DELETE'){
    http_response_code(400);
    $reply['error'] = 'DELETE method required';
    echo json_encode($reply);
    exit();
}

/**
 * Get input data from RAW data
 */
$data = file_get_contents('php://input');
$res = [];
parse_str($data, $res);
$idbarang = $res['idbarang'] ?? '';

/**
 *
 * Cek apakah ID Barang tersedia
 */
try{
    $queryCheck = "SELECT * FROM barang where idbarang = :idbarang";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':idbarang', $idbarang);
    $statement->execute();
    $row = $statement->rowCount();
    /**
     * Jika data tidak ditemukan
     * rowcount == 0
     */
    if($row === 0){
        $reply['error'] = 'ID Barang tidak ditemukan '.$idbarang;
        echo json_encode($reply);
        http_response_code(400);
        exit(0);
    }
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

/**
 * Hapus data
 */
try{
    $queryCheck = "DELETE FROM barang WHERE idbarang = :idbarang";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':idbarang', $idbarang);
    $statement->execute();
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

/*
 * Send output
 */
$reply['status'] = true;
echo json_encode($reply);