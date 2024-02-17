<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->

    <title>SMS Management System</title>

    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/images/logo.png?v=4">
    <!-- Template CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style-starter.css">
    <link href="<?php echo base_url(); ?>assets/css/print.css" rel="stylesheet" media="print">

    <link href="<?php echo base_url(); ?>assets/normalize-css/normalize.css" rel="stylesheet" media="print">

    <!-- Datatables -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/DataTables/css/jquery.dataTables.min.css">
    <link href="<?php echo base_url() ?>assets/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">

    <!-- google fonts -->
    <link href="//fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900&display=swap" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery-1.10.2.min.js"></script>
    <!-- Bootstrap DatePicker -->
    <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/jquery.ui.all.css"> -->
    <link rel="stylesheet" integrity="" href="<?php echo base_url() ?>assets/css/jquery.ui.datepicker.css">


    <!-- Bootstrap DatePicker -->
    <script type="text/javascript">
        //         function mFunction()

        //   {

        //     alert("Hello World!");
        //   }

        //     jQuery(function($) {
        //     var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
        //     $('ul li a').each(function() {
        //     if (this.href === path) {
        //     $(this).addClass('active');
        //     }
        //     });
        //     });     
        // 
    </script>

    <style type="text/css" media="print">
        @page {
            size: landscape;
        }
    </style>




</head>

<body class="" onload="myfunction()">
    <div class="se-pre-con"></div>
    <section>
        <!-- sidebar menu start -->
        <div class="sidebar-menu sticky-sidebar-menu no-print">

            <!-- logo start -->
            <div class="logo">
                <!-- <a href="#" title="logo"> </a> -->
                <h1><a href="<?php echo base_url('dashboard'); ?>"><img class="pr-2" src="<?php echo base_url(); ?>assets/images/logo.png?v=4" alt="logo-icon" style="margin-left:1px;margin-top: -3px;">SMS Management System</a></h1>
            </div>

            <!-- if logo is image enable this -->
            <!-- image logo --
    <div class="logo">
      <a href="index.html">
        <img src="image-path" alt="Your logo" title="Your logo" class="img-fluid" style="height:35px;" />
      </a>
    </div>
    <!-- //image logo -->

            <div class="logo-icon text-center no-print">
                <a href="<?php echo base_url('dashboard'); ?>" title="logo"><img src="<?php echo base_url(); ?>assets/images/logo.png?v=1" alt="logo-icon"> </a>
            </div>
            <!-- //logo end -->

            <div class="sidebar-menu-inner no-print">

                <!-- sidebar nav start -->
                <ul class="nav nav-pills nav-stacked custom-nav no-print">

                    <li class="<?php if ($this->uri->uri_string() == 'dashboard') {
                                    echo 'active';
                                } ?>"><a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-tachometer"></i><span>
                                Dashboard </span></a>
                    </li>
                    <!-- place -->
                    
                    <li><a href="<?php echo base_url(); ?>projects/all"><i class="lnr lnr-apartment"></i> <span>Projects</span></a>
                    <li><a href="<?php echo base_url(); ?>messages/all"><i class="lnr lnr-envelope"></i> <span>Messages</span></a>
                    <li><a href="<?php echo base_url(); ?>contacts/all"><i class="lnr lnr-phone-handset"></i> <span>Contacts</span></a>
                    <li><a href="<?php // echo base_url('logout') 
                                    ?>" data-toggle="modal" data-target="#logout"><i class="lnr lnr-power-switch"></i> <span>Logout</span></a>
									</li>
                </ul>
                <!-- //sidebar nav end -->
                <!-- toggle button start -->
                <a class="toggle-btn">
                    <i class="fa fa-angle-double-left menu-collapsed__left"><span>Collapse Sidebar</span></i>
                    <i class="fa fa-angle-double-right menu-collapsed__right"></i>
                </a>
                <!-- //toggle button end -->
            </div>
        </div>
        <!-- //sidebar menu end -->
        <!-- header-starts -->
        <div class="header sticky-header no-print col-md-12">
            <div class="row">
                <div class="col-md-7">
                    <div class="search-box mt-3">
                        <div class="title" style="margin-left:249px; margin-top:-4px; font-size:1.50rem; font-weight:800px;"><b></b></div>
                    </div>

                </div>

                <!-- notification menu start -->
                <div class="col-md-5">
                    <div class="navbar user-panel-top ">

                        <div class="user-dropdown-details d-flex">
                            <div class="profile_details_left">
                               

                                <?php
                                //include('notification.php');
                                ?>
                            </div>
                            <div class="profile_details">
                                <ul>
                                    <li class="dropdown profile_details_drop">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenu3" aria-haspopup="true" aria-expanded="false">
                                            <div class="profile_img">
                                                <img src="<?php echo base_url(); ?>assets/images/user.jpg" class="rounded-circle" alt="" />
                                                <div class="user-active">
                                                    <span></span>
                                                </div>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu drp-mnu" aria-labelledby="dropdownMenu3">
                                            <li class="user-info">
                                                <h5 class="user-name"><?php echo $name; ?></h5>
                                                <span class="status ml-2"><?php echo $role_text; ?></span>
                                            </li>
                                            <li> <a href="#"><i class="lnr lnr-user"></i>My Profile</a> </li>

                                            <li> <a href="<?php echo base_url('loadChangePass') ?>"><i class="lnr lnr-cog"></i>Setting</a> </li>

                                            <li class="logout"> <a href="" data-toggle="modal" data-target="#logout"><i class="fa fa-power-off"></i>
                                                    Logout</a> </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--notification menu end -->

            </div>
        </div>
        <!-- //header-ends -->
