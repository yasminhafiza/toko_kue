<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "toko_kue";

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}


?>
