<?php
// noi xu ly - truy van du lieu
require "database\database.php";

// viet ham kiem tra tai khoan dang nhap cua nguoi dung co ton tai trong database khong?
function checkLoginUser($username, $password){
    // $username va $password : du lieu nguoi dung nhap tu form login
    $db = connectionDb(); // bien ket noi toi CSDL
    $userInfo = []; // mang rong dung de chua thong tin tai khoan guoi dung
    $sql = "SELECT a.*, u.`Full_Name`, u.`Email`, u.Phone, u.`Extra_Code` FROM `account` AS a INNER JOIN `Users` AS u ON a.`User_ID` = u.`ID` WHERE a.`UserName` = :user AND a.`Password` = :pass AND a.`Status` = 1 LIMIT 1 ";
    $statement = $db->prepare($sql); // kiem tra cau lenh sql(chuoi)
    if($statement){
        // kiem tra tham so truyen cau lenh sql
        $statement->bindParam(':user', $username, PDO::PARAM_STR);
        $statement->bindParam(':pass', $password, PDO::PARAM_STR);
        // thuc thi sql
        if($statement->execute()){
            // kiem tra xem co du lieu duoc tra ve khong?
            if($statement->rowCount() . 0){
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
    // ngat ket noi toi CSDL
    disconnectionDb($db);
    return $userInfo;
    // $userInfo : tra ve la mang rong thi tai khoan dang nhap khong ton tai va nguoc lai
}