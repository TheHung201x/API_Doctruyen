<?php
$objConn = null; // Tạo biến kết nối
$db_host = 'localhost';
$db_name = 'doc_truyen'; // Tên database
$db_user = 'root'; // mặc định là root đối với win
$db_pass = ''; // mặc định là rỗng đối với win

try {
   // Khai báo kết nối tới tên miền, tên databse, user, passwd
    $objConn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

    // (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) chế độ báo lỗi của PDO
    // -> truy cập thuộc tính và phương thức của môt đối tượng (Khỏi tạo)
    $objConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo 'Ket noi CSDL thanh cong';
} catch (Exception $e) {
    
    die('Loi ket noi CSDL: '. $e->getMessage() );
}