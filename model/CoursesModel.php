<?php
require "database/database.php"; // Tìm và thực thi đến tập lệnh được chỉ định 

function updateCourseById(
    $name,
    $slug,
    $status,
    $description,
    $idDepartment,
    $id
) {
    // Here is update to database
    date_default_timezone_set('Asia/Ho_Chi_Minh');// cap nhat lai mui gio vietnamese
    $db = connectionDb();
    $checkUpdate = false;
    $sql = "UPDATE `courses` SET `name` = :nameCourse, `slug` = :slug, `status` = :statusCourse, `department_id` = :idDepartment,`description` = :descriptionCourse,`updated_at` = :updated_at WHERE `id` = :id AND `deleted_at` IS NULL";
    $updateTime = date('Y-m-d H:i:s');
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bindParam(':nameCourse', $name, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindParam(':statusCourse', $status, PDO::PARAM_INT);
        $stmt->bindParam(':idDepartment', $idDepartment, PDO::PARAM_INT);
        $stmt->bindParam(':descriptionCourse', $description, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updateTime, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $checkUpdate = true;
        }
    }
    disconnectionDb($db);
    return $checkUpdate;
}

function getDetailCourseById($id = 0)
{
    $db = connectionDb();
    $sql = "SELECT * FROM courses WHERE id = :id AND deleted_at IS NULL";
    $stmt = $db->prepare($sql);
    $infoCourse = [];
    if ($stmt) {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $infoCourse = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectionDb($db);
    return $infoCourse;
}

function deleteCourseById($id = 0)
{
    // Dữ liệu trên giao diện bị xóa nhưng vẫn còn trong database
    date_default_timezone_set('Asia/Ho_Chi_Minh');// cap nhat lai mui gio vietnamese

    $db = connectionDb();
    $sql = "UPDATE courses SET deleted_at = :deleted_at WHERE id = :id";
    $deletedAt = date("Y-m-d H:i:s");
    $stmt = $db->prepare($sql);
    $checkDelete = false;
    if ($stmt) {
        $stmt->bindParam(':deleted_at', $deletedAt, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $checkDelete = true;
        }
    }
    disconnectionDb($db);
    return $checkDelete;
}

function getAllDataCourses($keyword = null)
{
    $db = connectionDb();
    $key = "%{$keyword}%";
    $sql = "SELECT departments.name AS department_name, courses.*
            FROM departments
            INNER JOIN courses ON departments.id = courses.department_id 
            WHERE (courses.name LIKE :keywordName OR courses.slug LIKE :keywordSlug OR departments.name LIKE :keywordNameDepartment) 
            AND courses.deleted_at IS NULL
            AND departments.deleted_at IS NULL";
    $stmt = $db->prepare($sql);
    $data = [];
    if ($stmt) {$stmt->bindParam(':keywordName', $key, PDO::PARAM_STR);
        $stmt->bindParam(':keywordSlug', $key, PDO::PARAM_STR);
        $stmt->bindParam(':keywordNameDepartment',$key,PDO::PARAM_STR);
        // $stmt->bindParam(':startData', $start, PDO::PARAM_INT);
        // $stmt->bindParam(':limitData', $limit, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //fetchall: là câu lệnh lấy tất cả dữ liệu của một câu lệnh sql đã được thực thi. câu lệnh này trả về một mảng chứa tất cả các hàng trong tập kết quả
            }
        }
        // print_r($data);
        // die;
    }
    disconnectionDb($db);
    return $data;
}

function getAllDataCoursesByPage($keyword = null, $start = 0, $limit = LIMIT_ITEM_PAGE)
{
    $db = connectionDb();
    $key = "%{$keyword}%";
    $sql = "SELECT departments.name AS department_name, courses.*
            FROM departments
            INNER JOIN courses ON departments.id = courses.department_id 
            WHERE (courses.name LIKE :keywordName OR courses.slug LIKE :keywordSlug OR departments.name LIKE :keywordNameDepartment) 
            AND courses.deleted_at IS NULL
            AND departments.deleted_at IS NULL 
            LIMIT :startData, :limitData";
    $stmt = $db->prepare($sql);
    $dataCourses = [];
    if ($stmt) {
        $stmt->bindParam(':keywordName', $key, PDO::PARAM_STR);
        $stmt->bindParam(':keywordSlug', $key, PDO::PARAM_STR);
        $stmt->bindParam(':keywordNameDepartment', $key, PDO::PARAM_STR);
        $stmt->bindParam(':startData', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limitData', $limit, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $dataCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
    // print_r($dataCourses);
    // die;
    disconnectionDb($db);
    return $dataCourses;
}
// Here is insert to database
function insertCourse(
    $name,
    $slug,
    $status,
    $description,
    $idDepartment
) {
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $db = connectionDb();
    $flagInsert = false;
    $sqlInsert = "INSERT INTO `courses`(`name`, `slug`, `status`, `description`, `department_id`, `created_at`) VALUES(:nameCourse, :slug, :statusCourse, :descriptionCourse, :idDepartment, :created_at)";
    $stmt = $db->prepare($sqlInsert);
    $currentDate = date('Y-m-d H:i:s');
    if ($stmt) {
        $stmt->bindParam(':nameCourse', $name, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindParam(':statusCourse', $status, PDO::PARAM_INT);
        $stmt->bindParam(':descriptionCourse', $description, PDO::PARAM_STR);
        $stmt->bindParam(':idDepartment', $idDepartment, PDO::PARAM_INT);
        $stmt->bindParam(':created_at', $currentDate, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $flagInsert = true;
        }
        disconnectionDb($db); // ngat ket noi database
    }
    // $flagInsert la true : insert thanh cong va nguoc lai
    return $flagInsert;
}

function detailNameDepartment()
{
    $db = connectionDb();
    $sql = "SELECT * FROM `departments` where `deleted_at` is null";
    $stmt = $db->prepare($sql);
    $infoName = [];
    if ($stmt) {
        if($stmt->execute()){
            if ($stmt->rowCount() > 0) {
                $infoName = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
    
    disconnectionDb($db);
    return $infoName;
}