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

$idkaryawan = $formData['idkaryawan'] ?? '';
$password = $formData['password'] ?? '';
$nama = $formData['nama'] ?? '';
$jeniskelamin = $formData['jeniskelamin'] ?? '';
$email = $formData['email'] ?? '';
$nohp = $formData['nohp'] ?? 0;
$idshift = $formData['idshift'] ?? '';

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
if(empty($idshift)){
    $reply['error'] = 'ID Shift harus diisi';
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
    $queryCheck = "SELECT * FROM karyawan where idkaryawan = :idkaryawan";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':idkaryawan', $idkaryawan, PDO::PARAM_STR);
    $statement->execute();
    $row = $statement->rowCount();
    /**
     * Jika data tidak ditemukan
     * rowcount == 0
     */
    if($row === 0){
        $reply['error'] = 'ID karyawan tidak ditemukan  '.$idkaryawan;
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
    $query = "UPDATE barang SET password = :password, nama = :nama, jeniskelamin = :jeniskelamin, email = :email, nohp = :nohp,  idshift = :idshift 
WHERE idkaryawan = :idkaryawan";
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
    $statement->bindValue(":idshift", $idshift);
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

$stmSelect = $connection->prepare("SELECT * FROM karyawan where idkaryawan = :idkaryawan");
$stmSelect->bindValue(':idkaryawan', $idkaryawan);
$stmSelect->execute();
$dataKaryawan = $stmSelect->fetch(PDO::FETCH_ASSOC);

$dataFinal = [
    'idkaryawan' => $dataKaryawan['idkaryawan'],
    'password' => $dataKaryawan['password'],
    'nama' => $dataKaryawan['nama'],
    'jeniskelamin' => $dataKaryawan['jeniskelamin'],
    'email' => $dataKaryawan['email'],
    'nohp' => $dataKaryawan['nohp'],
    'idshift' => $dataKaryawan['idshift'],
];


/**
 * Show output to client
 */
$reply['data'] = $dataFinal;
$reply['status'] = $isOk;
echo json_encode($reply);
