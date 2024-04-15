<?php
require "database/database.php";

function updateDepartmentByID(
    $name,
    $slug,
    $leader, 
    $status, 
    $beginDate, 
    $logo, 
    $id
){
    // cap nhat lai mui gio vietnamese
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $db = connectionDb();
    $checkUpdate = false;
    $sql = "UPDATE `departments` SET `Name` = :nameDepartment, `Slug` = :slug, `Leader` = :leader,  `Beginning_Date` = :beginning_date, `Status` = :statusDepartment, `logo` = :logo, `Updated_at` = :update_at WHERE `ID` = :id AND `Deleted_at` IS NULL";
    $updateTime = date('Y-m-d H:i:s');
    $stmt = $db->prepare($sql);

    if($stmt){
        $stmt->bindParam(':nameDepartment', $name, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindParam(':leader', $leader, PDO::PARAM_STR);
        $stmt->bindParam(':beginning_date', $beginDate, PDO::PARAM_STR);
        $stmt->bindParam(':statusDepartment', $status, PDO::PARAM_INT);
        $stmt->bindParam(':logo', $logo, PDO::PARAM_STR);
        $stmt->bindParam(':update_at', $updateTime, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if($stmt->execute()){
            $checkUpdate = true;
        }
    }
    disconnectionDb($db);
    return $checkUpdate;

}

function getDetailDepartmentByID($id = 0){
    $db = connectionDb();
    $sql = "SELECT * FROM `departments` WHERE `ID` = :id AND `Deleted_at` IS NULL";
    $stmt = $db->prepare($sql);
    $infoDepartment = [];
    if($stmt){
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if($stmt->execute()){
            if($stmt->rowCount() >0){
                $infoDepartment = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectionDb($db);
    return $infoDepartment;
}

function deleteDepartmentByID($id = 0){
    // cap nhat lai mui gio vietnamese
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $db = connectionDb();
    $sql = "UPDATE `departments` SET `Deleted_at` = :deleted_at WHERE `ID` = :id";
    $deleteAt = date("Y-m-d H:i:s");
    $stmt = $db->prepare($sql);
    $checkDelete = false;
    if($stmt){
        $stmt->bindParam(':deleted_at', $deleteAt, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if($stmt->execute()){
            $checkDelete = true;
        }
    }
    disconnectionDb($db);
    return $checkDelete;
}

function getAllDataDepartments($keyword = null){
    $db = connectionDb();
    $sql = "SELECT * FROM `departments` WHERE (`Name` LIKE :keyword OR `Leader` LIKE :leader) AND `Deleted_at` IS NULL";
    $stmt = $db->prepare($sql);
    $key = "%{$keyword}%";
    $data = [];
    if($stmt){
        $stmt->bindParam(':keyword', $key, PDO::PARAM_STR);
        $stmt->bindParam(':leader', $key, PDO::PARAM_STR);
        if($stmt->execute()){
            if($stmt->rowCount() >0){
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectionDb($db);
    return $data;
}

function getAllDataDepartmentsByPage($keyword = null, $start = 0, $limit = LIMIT_ITEM_PAGE){
    $db = connectionDb();
    $key = "%{$keyword}%";
    $sql = "SELECT * FROM `departments` WHERE (`Name` LIKE :keyword OR `Leader` LIKE :leader) AND `Deleted_at` IS NULL LIMIT :startData, :limitData";
    $stmt = $db->prepare($sql);
    $dataDepartments = [];
    if($stmt){
        $stmt->bindParam(':keyword', $key, PDO::PARAM_STR);
        $stmt->bindParam(':leader', $key, PDO::PARAM_STR);
        $stmt->bindParam(':startData', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limitData', $limit, PDO::PARAM_INT);
        if($stmt->execute()){
            if($stmt->rowCount() > 0){
                $dataDepartments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } 
        }
    }
    disconnectionDb($db);
    return $dataDepartments;
}

function insertDepartment($name, $leader, $status, $beginDate, $logo = null){
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $db = connectionDb();
    $flagInsert = false;
    $sqlInsert = "INSERT INTO `departments`(`Name`, `Slug`, `Leader`, `Beginning_Date`, `Status`, `logo`, `Created_at`) VALUES(:NameDepartment, :Slug, :leader, :beginning_date, :statusDepartment, :logo, :created_at)";
    $stmt = $db->prepare($sqlInsert);
    $currentDate = date('Y-m-d H:i:s');
    if($stmt){
        $stmt->bindParam(':NameDepartment', $name, PDO::PARAM_STR);
        $stmt->bindParam(':Slug', $name, PDO::PARAM_STR);
        $stmt->bindParam(':leader', $leader, PDO::PARAM_STR);
        $stmt->bindParam(':beginning_date', $beginDate, PDO::PARAM_STR);
        $stmt->bindParam(':statusDepartment', $status, PDO::PARAM_INT);
        $stmt->bindParam(':logo', $logo, PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $currentDate, PDO::PARAM_STR);
        if($stmt->execute()){
            $flagInsert = true;
        }
        disconnectionDb($db); // ngat ket noi database
    }
    // $flagInsert la true: insert thanh cong va nguoc lai
    return $flagInsert;
}