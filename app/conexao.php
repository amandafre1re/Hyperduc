<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "hyperduc";
    $conn = new mysqli($host, $user, $password, $db);
     
    if($conn->connect_error){
        die("Falha na conexão");
    }
    ?>