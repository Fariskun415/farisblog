<?php
$servername = "localhost"; 
$username = "id22319554_farisblog"; 
$password = "Ponselanda@2"; 
$database = "id22319554_blog_db"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi ke MySQL gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8");

