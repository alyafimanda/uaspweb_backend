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
$idkaryawan = $_POST['idkaryawan'] ?? '';
$password = $_POST['password'] ?? '';
$nama = $_POST['nama'] ?? '';
$jeniskelamin = $_POST['jeniskelamin'] ?? '';
$email = $_POST['email'] ?? '';
$nohp = $_POST['nohp'] ?? 0;

/**
 * Validation empty fields
 */
$isValidated = true;
if($idkaryawan === false){
    $reply['error'] = "ID karyawan harus diisi";
    $isValidated = false;
}
if(empty($password)){
    $reply['error'] = 'Password harus diisi';
    $isValidated = false;
}
if(empty($nama)){
    $reply['error'] = 'Nama harus diisi';
    $isValidated = false;
}
if(empty($jeniskelamin)){
    $reply['error'] = 'Jenis kelamin harus diisi';
    $isValidated = false;
}
if(empty($email)){
    $reply['error'] = 'Email harus diisi';
    $isValidated = false;
}
if(empty($nohp)){
    $reply['error'] = 'No HP harus diisi';
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
    $query = "INSERT INTO karyawan (idkaryawan, password, nama, jeniskelamin, email, nohp) 
                VALUES (:idkaryawan, :password, :nama, :jeniskelamin, :email, :nohp)";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":idkaryawan", $idkaryawan);
    $statement->bindValue(":password", $password);
    $statement->bindValue(":nama", $nama);
    $statement->bindValue(":jeniskelamin", $jeniskelamin);
    $statement->bindValue(":email", $email);
    $statement->bindValue(":nohp", $nohp);
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
$getResult = "SELECT * FROM karyawan WHERE idkaryawan = :idkaryawan";
$stm = $connection->prepare($getResult);
$stm->bindValue(':idkaryawan', $idkaryawan);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);

$dataFinal = [
    'idkaryawan' => $result['idkaryawan'],
    'password' => $result['password'],
    'nama' => $result['nama'],
    'jeniskelamin' => $result['jeniskelamin'],
    'email' => $result['email'],
    'nohp' => $result['nohp'],
];

/**
 * Show output to client
 * Set status info true
 */
$reply['data'] = $dataFinal;
$reply['status'] = $isOk;
echo json_encode($reply);