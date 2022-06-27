<?php
include '../config.php';
/** @var $connection PDO */

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(400);
    $reply['error'] = 'POST method required';
    echo json_encode($reply);
    exit();
}
/**
 * Get input data POST
 */
$idbarang = $_POST['idbarang'] ?? '';
$namabarang = $_POST['namabarang'] ?? '';
$merk = $_POST['merk'] ?? '';
$jumlah = $_POST['jumlah'] ?? 0;
$hargabarang = $_POST['hargabarang'] ?? '';
$expiredate = $_POST['expiredate'] ?? date('Y-m-d');
$idkaryawan = $_POST['idkaryawan'] ?? 0;

/**
 * Validation int value
 */
$jumlahFilter = filter_var($jumlah, FILTER_VALIDATE_INT);

/**
 * Validation empty fields
 */
$isValidated = true;
if($jumlahFilter === false){
    $reply['error'] = "Jumlah harus format angka!";
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
if(empty($hargabarang)){
    $reply['error'] = 'Harga barang harus diisi';
    $isValidated = false;
}
if(empty($idkaryawan)){
    $reply['error'] = 'ID Karyawan harus diisi';
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
 * Method OK
 * Validation OK
 * Prepare query
 */
try{
    $query = "INSERT INTO barang (idbarang, namabarang, merk, jumlah, hargabarang, expiredate, idkaryawan) 
                VALUES (:idbarang, :namabarang, :merk, :jumlah, :hargabarang, :expiredate, :idkaryawan)";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":idbarang", $idbarang);
    $statement->bindValue(":namabarang", $namabarang);
    $statement->bindValue(":merk", $merk);
    $statement->bindValue(":jumlah", $jumlah, PDO::PARAM_INT);
    $statement->bindValue(":hargabarang", $hargabarang);
    $statement->bindValue(":expiredate", $expiredate);
    $statement->bindValue(":idkaryawan", $idkaryawan);
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
 * Get last data
 */
$getResult = "SELECT * FROM barang WHERE idbarang = :idbarang";
$stm = $connection->prepare($getResult);
$stm->bindValue(':idbarang', $idbarang);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);

$dataFinal = [
    'idbarang' => $result['idbarang'],
    'namabarang' => $result['namabarang'],
    'merk' => $result['merk'],
    'jumlah' => $result['jumlah'],
    'expiredate' => $result['expiredate'],
    'hargabarang' => $result['hargabarang'],
    'idkaryawan' => $result['idkaryawan'],
];

/**
 * Show output to client
 * Set status info true
 */
$reply['data'] = $dataFinal;
$reply['status'] = $isOk;
echo json_encode($reply);
