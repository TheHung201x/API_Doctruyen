<?php

// Lấy tất cả các bình luận theo id truyện
function getListComment($id_truyen){
    global $objConn;
    try{
        $selectAllComment = "SELECT tb_binh_luan.*, tb_truyen.ten_truyen, tb_user.username
        FROM tb_binh_luan 
        INNER JOIN tb_truyen ON tb_binh_luan.id_truyen = tb_truyen.id
        INNER JOIN tb_user ON tb_binh_luan.id_user = tb_user.id
        WHERE tb_binh_luan.id_truyen = $id_truyen
        ";

        // prepare(chuẩn bị) cho cú pháp
        // stmt(Prepared Statement): à một câu truy vấn SQL có chứa các tham số giữ chỗ thay vì các giá trị thực tế
        $stmt = $objConn->prepare( $selectAllComment);

        // excute(Thực thi) câu lệnh
        $stmt->execute();

        //thiết lập chế độ lấy dữ liệu
        $stmt->setFetchMode(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOC: trả về 1 mảng

        // lấy dữ liệu:
        $danh_sach_binh_luan = $stmt->fetchAll();

        $dataRes = [
            'status'=> 1,
            'msg'=> 'Thành công',
            'data'=> $danh_sach_binh_luan
        ];
        
         die(   json_encode($dataRes) );  
        } catch (Exception $e) {
            die( 'Lỗi thực hiện truy vấn CSLD ' . $e->getMessage()  );
       }
}

// Thêm bình uận
function addComment($id_truyen, $id_user){
    global $objConn;

    $noi_dung = $_POST['noi_dung'];

    $ngay_gio = date('Y-m-d');

            try {
                $addNewComment =  "INSERT INTO tb_binh_luan(id_truyen, id_user, noi_dung, ngay_gio) VALUES (:id_truyen,:id_user,:noi_dung,:ngay_gio)";
                $stmt =  $objConn->prepare($addNewComment);
                    
                // gán tham số cho câu lệnh
                $stmt->bindParam(":id_truyen", $id_truyen );
                $stmt->bindParam(":id_user", $id_user );
                $stmt->bindParam(":noi_dung", $noi_dung );
                $stmt->bindParam(":ngay_gio", $ngay_gio );

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

        die(json_encode ($dataRes ));
}

// Xoá bình luận
function deleteComment($id){
    global $objConn;
    try {
        $deleteUser = "DELETE FROM `tb_binh_luan` WHERE id = $id";
        $stmt = $objConn->prepare(  $deleteUser );
        $stmt->execute(); 

        $dataRes = [
            'status'=> 1,
            'msg'=> 'Xoá thành công',
        ];
        
         die(   json_encode($dataRes) );  
         
    } catch (PDOException $e) {
         die( 'Lỗi thực hiện truy vấn CSLD ' . $e->getMessage()  );
        
    }
}

// Sửa bình luận
function updateComment($id){
    global $objConn;

    parse_str(file_get_contents('php://input'), $_PUT);

    $noidungUpdate = $_PUT['noi_dung'];
    $ngaygioUpdate = date('Y-m-d');

        try {
            $updateUser =  "UPDATE `tb_binh_luan` SET `noi_dung`=:noidungUpdate,`ngay_gio`=:ngaygioUpdate WHERE id = $id";
                    
            $stmt =  $objConn->prepare($updateUser);
    
            $stmt->bindParam(":noidungUpdate", $noidungUpdate );
            $stmt->bindParam(":ngaygioUpdate", $ngaygioUpdate );
    
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

if($method =='POST'){
    addComment($_GET['id_truyen'], $_GET['id_user']);
}

if($method =='DELETE'){
    deleteComment($_GET['id']);
}

if($method =='PUT'){
    updateComment($_GET['id']);
}

if( $method == 'GET'){
        getListComment($_GET['id_truyen']);
}