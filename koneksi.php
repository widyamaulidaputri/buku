<?php

    header('Access-Control-Allow-Origin:https://buku-frontend-git-master-widyamaulidaputri.vercel.app');
    header('Access-Control-Allow-Header: Content-Type');
    header('Access-Control-Allow-Method: GET, POST, OPTION');

    function getConnection() {
        $host = 'localhost';
        $dbname = 'uaspweb';
        $username = 'root';
        $password = '';

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
?>
