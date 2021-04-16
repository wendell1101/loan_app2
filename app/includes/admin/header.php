<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo TITLE ?> | Dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!--fontawesome5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/admin/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../assets/admin/css/OverlayScrollbars.min.css">
    <!--bootstrap select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../assets/admin/css/admin.css?id=<?php time(); ?>">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" </head>


<body class="hold-transition sidebar-mini layout-fixed">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0&appId=749751495614026&autoLogAppEvents=1" nonce="JUf8uuB4"></script>
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'navbar.php' ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="../index.php" class="brand-link d-flex align-items-center">
                <img src="../assets/img/logo_light.png" alt="FEA" class="brand-image">
                <span class="brand-text">FEA</span>
            </a>
            <?php include 'sidebar.php' ?>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <?php if ($_SESSION['position_id'] === 1) : ?>
                                <h1 class="m-0 text-white" style="text-shadow: 1px 3px 5px #333;">Administration</h1>
                            <?php elseif ($_SESSION['position_id'] === 3) : ?>
                                <h1 class="m-0 text-white" style="text-shadow: 1px 3px 5px #333;">President Administration</h1>
                            <?php elseif ($_SESSION['position_id'] === 4) : ?>
                                <h1 class="m-0 text-white" style="text-shadow: 1px 3px 5px #333;">Treasurer Administration</h1>
                            <?php elseif ($_SESSION['position_id'] === 5) : ?>
                                <h1 class="m-0 text-white" style="text-shadow: 1px 3px 5px #333;">Assistant Treasurer Administration</h1>
                            <?php elseif ($_SESSION['position_id'] === 6) : ?>
                                <h1 class="m-0 text-white" style="text-shadow: 1px 3px 5px #333;">Membership Commitee Administration</h1>
                            <?php elseif ($_SESSION['position_id'] === 7) : ?>
                                <h1 class="m-0 text-white" style="text-shadow: 1px 3px 5px #333;">Financial Commitee Administration</h1>
                            <?php endif ?>
                        </div><!-- /.col -->
                        <div class="col-sm-6">

                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->