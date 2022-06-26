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
$idkaryawan = $res['idkaryawan'] ?? '';

/**
 *
 * Cek apakah ID Karyawan tersedia
 */
try{
    $queryCheck = "SELECT * FROM karyawan where idkaryawan = :idkaryawan";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':idkaryawan', $idkaryawan);
    $statement->execute();
    $row = $statement->rowCount();
    /**
     * Jika data tidak ditemukan
     * rowcount == 0
     */
    if($row === 0){
        $reply['error'] = 'ID Karyawan tidak ditemukan '.$idkaryawan;
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
    $queryCheck = "DELETE FROM karyawan WHERE idkaryawan = :idkaryawan";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':idkaryawan', $idkaryawan);
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