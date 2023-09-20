<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "transporte";

    # Conectar com o banco de dados.
    $conn = mysqli_connect($host, $username, $password, $dbname);
    # Se o $conn for diferente de True
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>