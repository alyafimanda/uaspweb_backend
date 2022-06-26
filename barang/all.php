<?php
include '../config.php';
/** @var $connection PDO */

if($_SERVER['REQUEST_METHOD'] !== 'GET'){
    header('Content-Type: application/json');
    http_response_code(400);
    $reply['error'] = 'DELETE method required';
    echo json_encode($reply);
    exit();
}

$dataFinal = [];
$idbarang = $_GET['idbarang'] ?? '';

if(empty($idbarang)){
    $reply['error'] = 'ID barang tidak boleh kosong';
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

try{
    $queryCheck = "SELECT * FROM barang WHERE idbarang = :idbarang";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':idbarang', $idbarang);
    $statement->execute();
    $dataBarang = $statement->fetch(PDO::FETCH_ASSOC);

        $dataFinal = [
            'idbarang' => $dataBarang['idbarang'],
            'namabarang' => $dataBarang['namabarang'],
            'merk' => $dataBarang['merk'],
            'jumlah' => $dataBarang['jumlah'],
            'hargabarang' => $dataBarang['hargabarang'],
            'expiredate' => $dataBarang['expiredate'],
        ];

}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

/*
 * Show response
 */
if(!$dataFinal){
    $reply['error'] = 'ID barang tidak ditemukan '.$idbarang;
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

/*
 * Otherwise show data
 */
$reply['status'] = true;
$reply['data'] = $dataFinal;
echo json_encode($reply);