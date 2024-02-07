<!-- main content start -->
<div class="main-content">

  <!-- content -->
  <div class="container-fluid content-top-gap">


    <div class="welcome-msg pt-3 pb-4">
      <h1>Setting</h1>

    </div>



    <!-- modals -->
    <section class="template-cards">
      <div class="card card_border">
        <div class="cards__heading">
          <h3> <i class="fa fa-gear"></i> Profile Setting</h3>
        </div>
        <div class="card-body pb-0">




          <div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="card card-primary card-outline mb-2">
                <div class="card-body box-profile ">
                  <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="assets/images/user.png" alt="User profile picture">
                  </div>

                  <h3 class="profile-username text-center"><?php echo $name; ?> </h3>

                  <p class="text-dark text-center"><strong>Role</strong> <br><?php echo $role_text; ?></p>

                  <ul class="list-group list-group-unbordered  border-bottom">
                    <li class="list-group-item">
                      <b>Status</b> <span class="float-right badge badge-success">Active</span>
                    </li>
                    <!-- <li class="list-group-item">
                    <b>Employee No</b> <a class="float-right">8098877</a>
                  </li> -->
                    <li class="list-group-item">
                      <b>User Since</b> <a class="float-right"><?php echo $createdDtm; ?></a>
                    </li>
                  </ul>





                  <!-- <a href="#" class="btn btn-primary btn-block"><b>Approve</b></a> -->

                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->


            </div>
            <!-- /.col -->
            <div class="col-md-9">
              <div class="card">
                <div class="card-header p-2">
                  <h3 class="mgbt-xs-15 mgtp-10 pt-10 font-semibold"><i class=" fa fa-user"></i> Change Password</h3>
                </div><!-- /.card-header -->
                <div class="card-body">

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
                  <?php
                  $success = $this->session->flashdata('success');
                  if ($success) {
                  ?>
                    <div class="alert alert-success alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <?php echo $this->session->flashdata('success'); ?>
                    </div>
                  <?php } ?>

                  <?php
                  $noMatch = $this->session->flashdata('nomatch');
                  if ($noMatch) {
                  ?>
                    <div class="alert alert-warning alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <?php echo $this->session->flashdata('nomatch'); ?>
                    </div>
                  <?php } ?>

                  <div class="row">
                    <div class="col-md-12">
                      <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                    <!-- form start -->
                    <form role="form" class="form-horizontal col-md-12" action="<?php echo base_url() ?>changePassword" method="post">

                      <div class="form-group">
                        <label class="col-md-4 col-xs-12 control-label" for="inputPassword1">Old Password</label>
                        <div class="col-md-8 col-xs-12">
                          <div class="input-group">
                            <!-- <span class="input-group-addon"><span class="fa fa-lock"></span></span> -->
                            <input type="password" class="form-control input-style" id="inputOldPassword" placeholder="Old password" name="oldPassword" maxlength="10" required>
                          </div>
                        </div>
                      </div>




                      <div class="form-group">
                        <label class="col-md-4 col-xs-12 control-label" for="inputPassword1">New Password</label>
                        <div class="col-md-8 col-xs-12">
                          <div class="input-group">
                            <!-- <span class="input-group-addon"><span class="fa fa-unlock"></span></span> -->
                            <input type="password" class="form-control input-style" id="inputPassword1" placeholder="New password" name="newPassword" maxlength="10" required>

                          </div>
                        </div>
                      </div>




                      <div class="form-group">
                        <label class="col-md-4 col-xs-12 control-label" for="inputPassword2">Confirm New Password</label>
                        <div class="col-md-8 col-xs-12">
                          <div class="input-group">
                            <!-- <span class="input-group-addon"><span class="fa fa-unlock"></span></span> -->
                            <input type="password" class="form-control input-style" id="inputPassword2" placeholder="Confirm new password" name="cNewPassword" maxlength="10" required>
                          </div>
                        </div>
                      </div>





                      <div class="box-footer pull-right">
                        <input type="reset" class="btn btn-danger" value="Reset" />
                        <input type="submit" class="btn btn-primary" value="Submit" />
                      </div>
                    </form>


                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
    <!-- //modals -->

  </div>
  <!-- //content -->
</div>
<!-- main content end-->