<?php
require "model/LoginModel.php"; // import model
// http://localhost/students/index.php?c=login&m=index
// m : ten ham nam trong controller
// vd : index
$m = trim($_GET['m'] ?? 'index');
$m = strtolower($m); // chuyen ve chu thuong

switch($m){
    case 'index':
        index();
        break;
    case 'handle':
        handleLogin();
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        echo 'Not found request';
        break;
}
function handleLogout(){
    if(isset($_POST['btnLogout'])){
        // nguoi dung thuc su muon logout ra ngoai
        // xoa het cac session da tao ra o login
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        unset($_SESSION['phone']);
        unset($_SESSION['idAccount']);
        unset($_SESSION['idUser']);
        unset($_SESSION['idRole']);
        // quay ve lai trang dang nhap
        header("Location:index.php");
    }
}
function handleLogin(){
 // kiem tra nguoi dung bam submit login chua?
 if(isset($_POST['btnLogin'])){
   // lay thong tin username
   $username = trim($_POST['username'] ?? null);
   $username = strip_tags($username);

   // lay thong tin password
   $password = trim($_POST['password'] ?? null);
   $password = strip_tags($password);

   if(empty($username) || empty($password)){
    // nguoi dung ko nhap du thong tin
    // quay lai trang dang nhap
    header("Location:index.php?state=error");
   }else {
    // nguoi dung co nhap du thong tin
    $userInfo = checkLoginUser($username, $password);
    if(empty($userInfo)){
        // tai khoan khong ton tai trong db
        header("Location:index.php?state=fail");
    }else{
        // tai khoan co ton tai trong db
        // luu thong tinnn nguoi dung vao mang session
        $_SESSION['username'] = $userInfo['UserName'];
        $_SESSION['email'] = $userInfo['Email'];
        $_SESSION['phone'] = $userInfo['Phone'];
        $_SESSION['idAccount'] = $userInfo['ID'];
        $_SESSION['idUser'] = $userInfo['User_ID'];
        $_SESSION['idRole'] = $userInfo['Role_ID'];
        // cho vao trang quan tri
        header("Location:index.php?c=dashboard");
    }
   }
 }
}
function index()
{
    if(isLoginUser()){
        header("Location:index.php?c=dashboard");
        exit();
    }
    // load view
    require APP_PATH_VIEW . 'login/index_view.php';
}
