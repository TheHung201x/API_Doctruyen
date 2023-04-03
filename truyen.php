<?php

// Lấy tất cả các truyện
function getListTruyen(){
    global $objConn;
    try{
        $selectAllTruyen = "SELECT * FROM `tb_truyen`";

        // prepare(chuẩn bị) cho cú pháp
        // stmt(Prepared Statement): à một câu truy vấn SQL có chứa các tham số giữ chỗ thay vì các giá trị thực tế
        $stmt = $objConn->prepare( $selectAllTruyen);

        // excute(Thực thi) câu lệnh
        $stmt->execute();

        //thiết lập chế độ lấy dữ liệu
        $stmt->setFetchMode(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOC: trả về 1 mảng

        // lấy dữ liệu:
        $danh_sach_truyen = $stmt->fetchAll();

        $dataRes = [
            'status'=> 1,
            'msg'=> 'Thành công',
            'data'=> $danh_sach_truyen
        ];
        
         die(   json_encode($dataRes) );  
        } catch (Exception $e) {
            die( 'Lỗi thực hiện truy vấn CSLD ' . $e->getMessage()  );
       }
}

// Lấy ra truyên theo id
function getIDTruyen($id){
    global $objConn;
    try{
        $selectOneTruyen = "SELECT * FROM `tb_truyen` WHERE id = $id";

        $stmt = $objConn->prepare( $selectOneTruyen);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $id_truyen = $stmt->fetchAll();

        $dataRes = [
            'status'=> 1,
            'msg'=> 'Thành công',
            'data'=> $id_truyen
        ];
        
         die(   json_encode($dataRes) );  
        } catch (Exception $e) {
            die( 'Lỗi thực hiện truy vấn CSLD ' . $e->getMessage()  );
       }
}

// Thêm truyện
function addTruyen(){
    global $objConn;

    $ten_truyen = $_POST['ten_truyen'];
    $tac_gia = $_POST['tac_gia'];
    $nam_xb = $_POST['nam_xb'];
    $anh_bia = $_POST['anh_bia'];
        if(empty ($ten_truyen) ){ // bắt buộc phải có tên truyện
            $dataRes =[
                'status'=>0,
                'msg'=> 'Chưa nhập tên truyện'
            ];
  
        }else{
            try {
                $addNewTruyen =  "INSERT INTO tb_truyen(ten_truyen, tac_gia, nam_xb, anh_bia) VALUES (:ten,:tacgia,:namxb,:anhbia)";
                $stmt =  $objConn->prepare($addNewTruyen);
                    
                // gán tham số cho câu lệnh
                $stmt->bindParam(":ten", $ten_truyen );
                $stmt->bindParam(":tacgia", $tac_gia );
                $stmt->bindParam(":namxb", $nam_xb );
                $stmt->bindParam(":anhbia", $anh_bia );

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

// Xoá truyện
function deleteTruyen($id){
    global $objConn;
    try {
        $deleteTruyen = "DELETE FROM `tb_truyen` WHERE id = $id";
        $stmt = $objConn->prepare(  $deleteTruyen );
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

// Sửa truyện
function updateTruyen($id){
    global $objConn;

    parse_str(file_get_contents('php://input'), $_PUT);
    
    $tacgiaUpdate = $_PUT['tac_gia'];
    $namxbUpdate = $_PUT['nam_xb'];
    $anhbiaUpdate = $_PUT['anh_bia'];

        try {
            $updateUser =  "UPDATE `tb_truyen` SET `tac_gia`=:tacgiaUpdate, `nam_xb`=:namxbUpdate, `anh_bia`=:anhbiaUpdate WHERE id = $id";
                    
            $stmt =  $objConn->prepare($updateUser);
    
            $stmt->bindParam(":tacgiaUpdate", $tacgiaUpdate );
            $stmt->bindParam(":namxbUpdate", $namxbUpdate );
            $stmt->bindParam(":anhbiaUpdate", $anhbiaUpdate );
    
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
        getListTruyen();
    else{
        getIDTruyen($_GET['id']);
    }
}

if($method =='POST'){
    addTruyen();
}

if($method =='DELETE'){
    deleteTruyen($_GET['id']);
}

if($method =='PUT'){
    updateTruyen($_GET['id']);
}