<?php

// Lấy tất cả các user
function getListUsers(){
    global $objConn;
    try{
        $selectAllUsers = "SELECT * FROM `tb_user`";

        // prepare(chuẩn bị) cho cú pháp
        // stmt(Prepared Statement): à một câu truy vấn SQL có chứa các tham số giữ chỗ thay vì các giá trị thực tế
        $stmt = $objConn->prepare( $selectAllUsers);

        // excute(Thực thi) câu lệnh
        $stmt->execute();

        //thiết lập chế độ lấy dữ liệu
        $stmt->setFetchMode(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOC: trả về 1 mảng

        // lấy dữ liệu:
        $danh_sach_users = $stmt->fetchAll();

        $dataRes = [
            'status'=> 1,
            'msg'=> 'Thành công',
            'data'=> $danh_sach_users
        ];
        
         die(   json_encode($dataRes) );  
        } catch (Exception $e) {
            die( 'Lỗi thực hiện truy vấn CSLD ' . $e->getMessage()  );
       }
}

// Lấy ra user theo id
function getIDUser($id){
    global $objConn;
    try{
        $selectOneUser = "SELECT * FROM `tb_user` WHERE id = $id";

        $stmt = $objConn->prepare( $selectOneUser);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $id_users = $stmt->fetchAll();

        $dataRes = [
            'status'=> 1,
            'msg'=> 'Thành công',
            'data'=> $id_users
        ];
        
         die(   json_encode($dataRes) );  
        } catch (Exception $e) {
            die( 'Lỗi thực hiện truy vấn CSLD ' . $e->getMessage()  );
       }
}

// Thêm user
function addUser(){
    global $objConn;

    $username = $_POST['username'];
    $passwd = $_POST['passwd'];
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];
        if(empty ($username) ){ // bắt buộc phải có username
            $dataRes =[
                'status'=>0,
                'msg'=> 'Chưa nhập username'
            ];
  
        }else{
            // đã nhập username rồi ==> lưu vào CSDL
            try {
                $addNewUSer =  "INSERT INTO tb_user(username, passwd, email, fullname) VALUES (:user,:pass,:email,:fullname)";
                $stmt =  $objConn->prepare($addNewUSer);
                    
                // gán tham số cho câu lệnh
                $stmt->bindParam(":user", $username );
                $stmt->bindParam(":pass", $passwd );
                $stmt->bindParam(":email", $email );
                $stmt->bindParam(":fullname", $fullname );

                // thực thi
                $stmt->execute();
 
                $dataRes =[
                    'status'=>1,
                    'msg'=>  'Đã thêm thành công'
                ];
            
            } catch (PDOException $e) {
                 
                $dataRes =[
                    'status'=>0,
                    'msg'=> 'Lỗi '. $e->getMessage()
                ];
            }
        }

        die(json_encode ($dataRes ));
}

// Xoá user
function deleteUser($id){
    global $objConn;
    try {
        $deleteUser = "DELETE FROM `tb_user` WHERE id = $id";
        $stmt = $objConn->prepare(  $deleteUser );
        $stmt->execute(); 

        $dataRes = [
            'status'=> 1,
            'msg'=> 'Xoá thành công',
        ];
        
         die(   json_encode($dataRes) );  
         
    } catch (PDOException $e) {
        //  die( 'Lỗi thực hiện truy vấn CSLD ' . $e->getMessage()  );
         $dataRes = [
            'status'=> 0,
            'msg'=> 'Thất bại',
        ];
    }
}

// Sửa user
function updateUser($id){
    global $objConn;

    parse_str(file_get_contents('php://input'), $_PUT);
    
    $passwdUpdate = $_PUT['passwd'];
    $emailUpdate = $_PUT['email'];
    $fullnameUpdate = $_PUT['fullname'];

        try {
            $updateUser =  "UPDATE `tb_user` SET `passwd`=:passUpdate, `email`=:emailUpdate, `fullname`=:fullnameUpdate WHERE id = $id";
                    
            $stmt =  $objConn->prepare($updateUser);
    
            $stmt->bindParam(":passUpdate", $passwdUpdate );
            $stmt->bindParam(":emailUpdate", $emailUpdate );
            $stmt->bindParam(":fullnameUpdate", $fullnameUpdate );
    
            $stmt->execute();
     
            $dataRes =[
                'status'=>1,
                'msg'=>  'Đã sửa thành công'
            ];
        }catch (PDOException $e) {
                     
            $dataRes =[
                'status'=>0,
                'msg'=> 'Lỗi '. $e->getMessage()
            ];
        }
    

    die(json_encode ($dataRes ));
}


//---- xử lý gọi hàm 

$method = $_SERVER['REQUEST_METHOD']; // yêu cầu kiểu request
if( $method == 'GET'){
    if(empty($_GET['id'])) // không có id là trang danh sách, có id là chi tiết
        getListUsers();
    else{
        getIDUser($_GET['id']);
    }
}

if($method =='POST'){
    addUser();
}

if($method =='DELETE'){
    deleteUser($_GET['id']);
}

if($method =='PUT'){
    updateUser($_GET['id']);
}