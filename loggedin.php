<?php

include '../config.php';
/** @var $connection PDO */

if($_POST){
    //data
    $idkaryawan = $_POST['idkaryawan'] ?? 0;
    $password = $_POST['password'] ?? '';

    $response = []; //Data Response

    //Cek ID Karyawan dalam database
    $userQuery = $connection->prepare("SELECT * FROM karyawan where idkaryawan = ?");
    $userQuery->execute(array($idkaryawan));
    $query = $userQuery->fetch();

    if($userQuery->rowCount() == 0){
        $response['status'] = false;
        $response['message'] = "ID Karyawan tidak ditemukan";
    } else {
        // Ambil password di database

        $passwordDB = $query['password'];

        if(strcmp(md5($password), $passwordDB) === 0){
            $response['status'] = true;
            $response['message'] = "Login Berhasil";
            $response['data'] = [
                'idkaryawan' => $query['idkaryawan'],
                'nama' => $query['idkaryawan'],
                'jeniskelamin' => $query['jeniskelamin'],
                'email' => $query['email'],
                'no_hp' => $query['no_hp'],
            ];
        } else {
            $response['status'] = false;
            $response['message'] = "Password salah";
        }
    }

    //jadikan data JSON
    $json = json_encode($response, JSON_PRETTY_PRINT);

    //Print JSON
    echo $json;
}