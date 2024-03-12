<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SMS Management System</title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url() ?>assets/images/logo.png?v=4">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style-starter.css">
    <link href="<?= base_url() ?>assets/css/print.css" rel="stylesheet" media="print">
    <link href="<?= base_url() ?>assets/normalize-css/normalize.css" rel="stylesheet" media="print">
    <link rel="stylesheet" href="<?= base_url() ?>assets/DataTables/css/jquery.dataTables.min.css">
    <link href="<?= base_url() ?>assets/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    
    <link href="//fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900&display=swap" rel="stylesheet">
    <script src="<?= base_url() ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery-1.10.2.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/jquery.ui.datepicker.css">
    <link rel="stylesheet" href="https://vms.sindhpolice.gov.pk/assets/css/jquery.ui.datepicker.css">
    <style type="text/css" media="print">
        @page { size: landscape; }
    </style>
</head>

<body class="" onload="myfunction()">
<?php
function activeNav($url, ...$routes){
	foreach($routes as $route)		if ($url == $route ) { echo 'nav-active'; break;}
}
function activeItem($url, $route){
	if ($url == $route) { echo 'active'; } 
}
?>
    <div class="se-pre-con"></div>
    <section>
        <div class="sidebar-menu sticky-sidebar-menu no-print">
            <div class="logo">
                <h1><a href="<?= base_url('dashboard'); ?>"><img class="pr-2" src="<?= base_url() ?>assets/images/logo.png?v=4" alt="logo-icon" style="margin-left:1px;margin-top: -3px;">SMS Management System</a></h1>
            </div>
            <div class="logo-icon text-center no-print">
                <a href="<?= base_url('dashboard'); ?>" title="logo"><img src="<?= base_url() ?>assets/images/logo.png?v=1" alt="logo-icon"> </a>
            </div>
            <div class="sidebar-menu-inner no-print">
                <ul class="nav nav-pills nav-stacked custom-nav no-print">
                    <li class="<?= ($this->uri->uri_string() == 'dashboard') ? 'active' : '' ?>"><a href="<?= base_url(); ?>dashboard"><i class="fa fa-tachometer"></i><span>Dashboard </span></a></li>
                    <li><a href="<?= base_url(); ?>projects/all"><i class="lnr lnr-apartment"></i> <span>Projects</span></a></li>
					<li class="menu-list <?php activeNav($this->uri->uri_string(), 'otp/all?type=all', 'otp/all?type=today', 'otp/all?type=sent') ?>">
                            <a href="#"><i class="lnr lnr-history"></i>
                                <span>OTP Log<i class="lnr lnr-chevron-right"></i></span>
							</a>
                            <ul class="sub-menu-list">
                                <li class="<?=activeItem($this->uri->uri_string(), 'otp/all?type=all');?>">
									<a href="<?= base_url(); ?>otp/all?type=all"><i class="lnr lnr-list"></i> <span>All</span></a> 
								</li>
                                <li class="<?=activeItem($this->uri->uri_string(), 'otp/all?type=today');?>">
									<a href="<?= base_url(); ?>otp/all?type=today"><i class="lnr lnr-clock"></i> <span>Today</span></a> 
								</li>
                                <li class="<?=activeItem($this->uri->uri_string(), 'otp/all?type=sent');?>">
									<a href="<?= base_url(); ?>otp/all?type=sent">✅ <span>Sent</span></a> 
								</li>
                                <li class="<?=activeItem($this->uri->uri_string(), 'otp/all?type=failed');?>">
									<a href="<?= base_url(); ?>otp/all?type=failed"><i class="lnr lnr-cross-circle"></i> <span>Failed</span></a> 
								</li>
                                <li class="<?=activeItem($this->uri->uri_string(), 'otp/invalidRequests');?>">
									<a href="<?= base_url(); ?>otp/invalidRequests"><i class="lnr lnr-cross-circle"></i> <span>Invalid Requests</span></a> 
								</li>
                                <li class="<?=activeItem($this->uri->uri_string(), 'otp/all?type=failed');?>">
									<a href="<?= base_url(); ?>otp/iplocations"><i class="lnr lnr-cross-circle"></i> <span>OTP Request Locations</span></a> 
								</li>
                            </ul>
					</li>					
					<li class="menu-list <?php activeNav($this->uri->uri_string(), 'otp/summaryReport', 'otp/datewiseReport', 'otp/monthwiseReport') ?>">
                            <a href="#"><i class="lnr lnr-database"></i>
                                <span>OTP Reports<i class="lnr lnr-chevron-right"></i></span>
							</a>
                            <ul class="sub-menu-list">

                                <li class="<?=activeItem($this->uri->uri_string(), 'otp/summaryReport');?>">
									<a href="<?= base_url(); ?>otp/summaryReport"><i class="lnr lnr-apartment"></i> <span>Project-wise Summary</span></a> 
								</li>
                                <li class="<?=activeItem($this->uri->uri_string(), 'otp/datewiseReport');?>">
									<a href="<?= base_url(); ?>otp/datewiseReport"><i class="lnr lnr-calendar-full"></i> <span>Date-wise Summary</span></a> 
								</li>
                                <li class="<?=activeItem($this->uri->uri_string(), 'otp/monthwiseReport');?>">
									<a href="<?= base_url(); ?>otp/monthwiseReport"><i class="lnr lnr-calendar-full"></i> <span>Month-wise Summary</span></a> 
								</li>
                            </ul>
					</li>
					<li class="menu-list <?php activeNav($this->uri->uri_string(), 'messages/all', 'contacts/all') ?>">
                            <a href="#"><i class="lnr lnr-layers"></i>
                                <span>Bulk SMS<i class="lnr lnr-chevron-right"></i></span>
							</a>
                            <ul class="sub-menu-list">

                                <li class="<?=activeItem($this->uri->uri_string(), 'messages/all');?>">
									<a href="<?= base_url(); ?>messages/all"><i class="lnr lnr-envelope"></i> <span>Messages</span></a> 
								</li>
                                <li class="<?=activeItem($this->uri->uri_string(), 'contacts/all');?>">
									<a href="<?= base_url(); ?>contacts/all"><i class="lnr lnr-phone-handset"></i> <span>Contacts</span></a> 
								</li>
                            </ul>
					</li>



                    <li><a href="#" data-toggle="modal" data-target="#logout"><i class="lnr lnr-power-switch"></i> <span>Logout</span></a></li>
                </ul>
                <a class="toggle-btn"><i class="fa fa-angle-double-left menu-collapsed__left"><span>Collapse Sidebar</span></i><i class="fa fa-angle-double-right menu-collapsed__right"></i></a>
            </div>
        </div>
        <div class="header sticky-header no-print col-md-12">
            <div class="row">
                <div class="col-md-7">
                    <div class="search-box mt-3">
                        <div class="title" style="margin-left:249px; margin-top:-4px; font-size:1.50rem; font-weight:800px;"><b></b></div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="navbar user-panel-top ">
                        <div class="user-dropdown-details d-flex">                          
                            <div class="profile_details">
                                <ul>
                                    <li class="dropdown profile_details_drop">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenu3" aria-haspopup="true" aria-expanded="false">
                                            <div class="profile_img">
                                                <img src="<?= base_url() ?>assets/images/user.jpg" class="rounded-circle" alt="" />
                                                <div class="user-active"><span></span></div>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu drp-mnu" aria-labelledby="dropdownMenu3">
                                            <li class="user-info">
                                                <h5 class="user-name"><?= $name; ?></h5>
                                                <span class="status ml-2"><?= $role_text; ?></span>
                                            </li>
                                            <li> <a href="#"><i class="lnr lnr-user"></i>My Profile</a> </li>
                                            <li> <a href="<?= base_url('loadChangePass') ?>"><i class="lnr lnr-cog"></i>Setting</a> </li>
                                            <li class="logout"> <a href="" data-toggle="modal" data-target="#logout"><i class="fa fa-power-off"></i> Logout</a> </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
