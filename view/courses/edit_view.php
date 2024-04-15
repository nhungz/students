<?php
if (!defined('APP_ROOT_PATH')) {
    die('Can not access');
}
$namePage = 'Update Courses';
$errorAdd = $_SESSION['error_course'] ?? null;
?>
<link rel="shortcut icon" href="public/img/images (1).png" type="x-icon">
<!-- load header view -->
<?php require APP_PATH_VIEW . "partials/header_view.php"; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Update Courses</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a style="color: green;" href="index.php?c=courses">Course</a></li>
                        <li class="breadcrumb-item active">Form update Courses</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="card-title">
                                Update Courses
                            </h5>
                        </div>
                        <div class="card-body">
                            <form enctype="multipart/form-data" method="post" action="index.php?c=courses&m=handle-update&id=<?= $infoDetail['id']; ?>">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Courses</label>
                                            <input type="text" class="form-control" name="name" value="<?= $infoDetail['name']; ?>" />
                                            <?php if(!empty($errorAdd['name'])): ?>
                                                <span class="text-danger"><?= $errorAdd['name'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Teacher</label>
                                            <input type="text" class="form-control" name="slug" value="<?= $infoDetail['slug']; ?>" />
                                            <?php if(!empty($errorAdd['slug'])): ?>
                                                <span class="text-danger"><?= $errorAdd['slug'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                    
                                        <div class="form-group mb-3">
                                            <label> Status </label>
                                            <select class="form-control" name="status">
                                                <option
                                                    value="1"
                                                    <?= $infoDetail['status'] == 1 ? 'selected' : null; ?>
                                                >Active</option>
                                                <option
                                                    value="0"
                                                    <?= $infoDetail['status'] == 0 ? 'selected' : null; ?>
                                                >Deactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="">Department</label>
                                            <select name="Department_ID" id="" class="form-control">
                                                <?php foreach($detailName as $key => $item): ?>
                                                    <option value="<?php echo $item['ID']; ?>"
                                                    <?= $infoDetail['department_id'] == $item['ID'] ? 'selected' : null; ?>>
                                                        <?php echo $item['Name']; ?> 
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <button class="btn btn-primary btn-lg" type="submit" name="btnUpdate"> Update </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- load footer view -->
<?php require APP_PATH_VIEW . "partials/footer_view.php"; ?>