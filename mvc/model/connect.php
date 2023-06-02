<?php
    try {
    $db = new PDO("mysql:host=localhost;dbname=monitoring_test",
    "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Підключення не вдалося:' . $e->getMessage();
    }
?>