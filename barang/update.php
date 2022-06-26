<?php
include '../config.php';

/**
 * @var $connection PDO
 */

if($_SERVER['REQUEST_METHOD'] !== 'PATCH'){
    header('Content-Type: application/json');
    http_response_code(400);
    $reply['error'] = 'PATCH method required';
    echo json_encode($reply);
    exit();
}
/**
 * Get input data PATCH
 */
$formData = [];
parse_str(file_get_contents('php://input'), $formData);

$idbarang = $formData['idbarang'] ?? '' ;
$namabarang = $formData['namabarang'] ?? '';
$merk = $formData['merk'] ?? '';
$jumlah = $formData['jumlah'] ?? 0;
$hargabarang = $formData['hargabarang'] ?? '';
$expiredate = $formData['expiredate'] ?? date('Y-m-d');

/**
 * Validation int value
 */
$jumlahFilter = filter_var($jumlah, FILTER_VALIDATE_INT);

/**
 * Validation empty fields
 */
$isValidated = true;
if($jumlahFilter === false){
    $reply['error'] = "Jumlah harus format angka";
    $isValidated = false;
}
if(empty($idbarang)){
    $reply['error'] = 'ID barang harus diisi';
    $isValidated = false;
}
if(empty($namabarang)){
    $reply['error'] = 'Nama barang harus diisi';
    $isValidated = false;
}
if(empty($merk)){
    $reply['error'] = 'Merk harus diisi';
    $isValidated = false;
}
 if(empty($hargabarang)){
    $reply['error'] = 'Harga barang harus diisi';
    $isValidated = false;
}
if(empty($expiredate)){
    $reply['error'] = 'Expire date harus diisi';
    $isValidated = false;
}
/*
 * Jika filter gagal
 */
if(!$isValidated){
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
/**
 * METHOD OK
 * Validation OK
 * Check if data is exist
 */
try{
    $queryCheck = "SELECT * FROM barang where idbarang = :idbarang";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':idbarang', $idbarang, PDO::PARAM_STR);
    $statement->execute();
    $row = $statement->rowCount();
    /**
     * Jika data tidak ditemukan
     * rowcount == 0
     */
    if($row === 0){
        $reply['error'] = 'ID barang tidak ditemukan  '.$jumlahFilter;
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
 * Prepare query
 */
try{
    $fields = [];
    $query = "UPDATE barang SET namabarang = :namabarang, merk = :merk, jumlah = :jumlah, hargabarang = :hargabarang, expiredate = :expiredate 
WHERE idbarang = :idbarang";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":idbarang", $idbarang, PDO::PARAM_STR);
    $statement->bindValue(":namabarang", $namabarang);
    $statement->bindValue(":merk", $merk);
    $statement->bindValue(":jumlah", $jumlah, PDO::PARAM_INT);
    $statement->bindValue(":hargabarang", $hargabarang);
    $statement->bindValue(":expiredate", $expiredate);
    /**
     * Execute query
     */
    $isOk = $statement->execute();
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
/**
 * If not OK, add error info
 * HTTP Status code 400: Bad request
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#client_error_responses
 */
if(!$isOk){
    $reply['error'] = $statement->errorInfo();
    http_response_code(400);
}

/*
 * Get data
 */

$stmSelect = $connection->prepare("SELECT * FROM barang where idbarang = :idbarang");
$stmSelect->bindValue(':idbarang', $idbarang);
$stmSelect->execute();
$dataBarang = $stmSelect->fetch(PDO::FETCH_ASSOC);

   $dataFinal = [
        'idbarang' => $dataBarang['idbarang'],
        'namabarang' => $dataBarang['namabarang'],
        'merk' => $dataBarang['merk'],
        'jumlah' => $dataBarang['jumlah'],
        'hargabarang' => $dataBarang['hargabarang'],
        'expiredate' => $dataBarang['expiredate'],
    ];


/**
 * Show output to client
 */
$reply['data'] = $dataFinal;
$reply['status'] = $isOk;
echo json_encode($reply);