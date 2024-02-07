<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SMS Management System | Login</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/libs/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css"> -->


    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/logo.png?v=1">
    <!-- Template CSS -->
    <link rel="stylesheet" href="<?php base_url(); ?>assets/css/style-starter.css">

    <!-- google fonts -->
    <link href="//fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900&display=swap" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
        }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card login">

            <div class="card-header text-center"><a href="<?php base_url() ?>"><img class="logo-img" src="assets/images/logo_in.png?v=1" alt="logo" alt="logo" width="115px"></a>
                <h2>SMS Management System</h2>
            </div>
            <div class="card-body">
                <?php $this->load->helper('form'); ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if ($error) {
                ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>
                <form method="post" action="loginMe">
                    <div class="form-group">
                        <input class="form-control form-control-lg" id="user_name" name="user_name" type="text" placeholder="Username" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg" id="password" name="password" type="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox"><span class="custom-control-label">Remember Me</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-unlock mr-2"></i>Sign in</button>
                </form>
            </div>
            <!-- <div class="card-footer bg-white p-0  ">
                <div class="col-md-12">
                    <div class="row">
                        <div class="card-footer-item card-footer-item-bordered col-md-6">
                            <a href="#" class="footer-link">Create An Account</a>
                        </div>
                        <div class="card-footer-item card-footer-item-bordered col-md-6">
                            <a href="#" class="footer-link">Forgot Password</a>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>

        <div class="col-md-12 text-center footer mt-2">©<?= date("Y") ?> All Rights Reserved.<br></div>
    </div>


    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.js"></script>
</body>

</html>
