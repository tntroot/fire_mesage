<?php
try{
    $pdo = new PDO(
        'mysql:host=localhost:3308;dbname=fire_management;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (Exception $e) {
    $data['status'] = 'fail';
    $data['data'] = '0';
    echo json_encode($data);
}
