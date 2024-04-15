<?php
require 'model/DepartmentModel.php';

$m = trim($_GET['m'] ?? 'index'); // trim : xoa hoang trang 2 dau
$m = strtolower($m); // chuyen ve chu thuog

switch($m){
    case 'index':
        index();
        break;
    case 'add':
        Add();
        break;
    case 'handle-add';
        handleAdd();
        break;
    case 'delete';
        handleDelete();
        break;
    case 'edit';
        handleEdit();
        break;
    case 'handle-update';
        handleUpdate();
    default:
        index();
        break;
}
function handleUpdate(){
    if(isset($_POST['btnUpdate'])){
        $id = $_GET['id'] ?? null;
        $id = is_numeric($id) ? $id : 0;

        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);

        $leader = trim($_POST['leader'] ?? null);
        $leader = strip_tags($leader);

        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;

        $beginningDate = trim($_POST['beginning_date'] ?? null);
        $beginningDate = date('Y-m-d', strtotime($beginningDate));

        $info = getDetailDepartmentByID($id);

        // check du lieu
        $_SESSION['error_department'] = [];

        // tien hanh upload logo cua khoa
        $logo = $info['logo'] ?? null;
        $_SESSION['error_department']['logo'] = null;
        if(!empty($_FILES['logo']['tmp_name'])){
            // thuc su nguoi dung muon upload logo
            $logo = uploadFile($_FILES['logo'], 'public/uploads/images/', ['image/png', 'image/jpeg', 'image/jpg', 'image/svg'], 3*1024*1024);

            if(empty($logo)){
                $_SESSION['error_department']['logo'] = 'Type files is allow .png, .jpg, .jpeg, .svg and size file <= 3Mb';
            } else{
                $_SESSION['error_department']['logo'] = null;
            }
        }

        if(empty($name)){
            $_SESSION['error_department']['name'] = 'Enter name, please!';
        }
        else{
            $_SESSION['error_department']['name'] = null;
        }

        if(empty($leader)){
            $_SESSION['error_department']['leader'] = "Enter name's leader, please!";
        }
        else{
            $_SESSION['error_department']['leader'] = null;
        }

        $checkerror = false;
        foreach($_SESSION['error_department'] as $error){
            if(!empty($error)){
                $checkerror = true;
                break;
            }
        }

        if($checkerror){
            // co loi xay ra
            // quay lai form update
            header("Location:index.php?c=department&m=edit&id={$id}&state=error");
        } else{
            // khong co loi gi ca
            // tien hanh update vao database
            if(isset($_SESSION['error_department'])){
                unset($_SESSION['error_department']);
            }
            $slug = slugify($name);
            $update = updateDepartmentByID(
                $name,
                $slug,
                $leader,
                $status,
                $beginningDate,
                $logo,
                $id
            );

            if($update){
                //Thanh cong
                header("Location:index.php?c=department&state=success");
            } else{
                // that bai
                header("Location:index.php?c=department&m=edit&id={$id}&state=failure");
            }
        }

    }
}
function handleEdit(){
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0; // is_numeric : kiem tra gia tri co phai la so hay khong?
    $infoDetail = getDetailDepartmentByID($id);
    if(!empty($infoDetail)){
        // co du lieu trong database
        // hien thi thong tin du lieu chi tiet
        require APP_PATH_VIEW. 'departments/edit_view.php';
    }else{
        // khong co du lieu trong database
        // thong bao loi
        require APP_PATH_VIEW. 'error_view.php';
    }

}
function handleDelete(){
    $id = trim($_GET['id'] ?? null);
    $delete = deleteDepartmentByID($id);
    if($delete){
        header("Location:index.php?c=department&state=delete_success");
    } else{
        header("Location:index.php?c=department&state=delete_failure");
    }
    
}

function handleAdd(){
    if(isset($_POST['btnSave'])){
        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);

        $leader = trim($_POST['leader'] ?? null);
        $leader = strip_tags($leader);

        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;

        $beginningDate = trim($_POST['beginning_date'] ?? null);
        $beginningDate = date('Y-m-d', strtotime($beginningDate));

        // check du lieu
         $_SESSION['error_department'] = [];
        // tien hanh upload logo cua khoa
        $logo = null;
        $_SESSION['error_department']['logo'] = null;
        if(!empty($_FILES['logo']['tmp_name'])){
            // thuc su nguoi dung muon upload logo
            $logo = uploadFile($_FILES['logo'], 'public/uploads/images/', ['image/png', 'image/jpeg', 'image/jpg', 'image/svg'], 3*1024*1024);

            if(empty($logo)){
                $_SESSION['error_department']['logo'] = 'Type files is allow .png, .jpg, .jpeg, .svg and size file <= 3Mb';
            } else{
                $_SESSION['error_department']['logo'] = null;
            }
        }

        if(empty($name)){
            $_SESSION['error_department']['name'] = 'Enter name, please!';
        }
        else{
            $_SESSION['error_department']['name'] = null;
        }

        if(empty($leader)){
            $_SESSION['error_department']['leader'] = "Enter name's leader, please!";
        }
        else{
            $_SESSION['error_department']['leader'] = null;
        }

        if( !empty($_SESSION['error_department']['name'])
            ||
            !empty($_SESSION['error_department']['leader'])
            ||
            !empty($_SESSION['error_department']['logo'])
        ){
            // co loi - thong bao ve lai form
            header("Location:index.php?c=department&m=add&state=fail");
        }
        else{
            // insert vao database
            if(isset($_SESSION['error_department'])){
                unset($_SESSION['error_department']);
            }
            $insert = insertDepartment($name, $leader, $status, $beginningDate, $logo);
            if($insert){
                header("Location:index.php?c=department");
            } else{
                header("Location:index.php?c=department&m=add&state=error");
            }
        }
    }
}
function Add(){
    require APP_PATH_VIEW . 'departments/add_view.php';
}
function index(){
    $keyword = trim($_GET['search'] ?? null);
    $keyword = strip_tags($keyword);
    $page    = trim($_GET['page'] ?? null);
    $page    = (is_numeric($page) && $page > 0) ? $page : 1;
    $linkPage = createLink([
        'c' => 'department',
        'm' => 'index',
        'page' => '{page}',
        'search' => $keyword
    ]);
    $itemDepartment = getAllDataDepartments($keyword);
    $itemDepartment = count($itemDepartment);
    $pagination = pagination($linkPage, $itemDepartment, $page, LIMIT_ITEM_PAGE);
    $start = $pagination['start'] ?? 0;
    $departments = getAllDataDepartmentsByPage($keyword, $start, LIMIT_ITEM_PAGE);
    $htmlPage = $pagination['htmlPage'] ?? null;
    require APP_PATH_VIEW . 'departments/index_view.php';
}