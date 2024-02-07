<style>
   g rect{border: 1px solid red !important;}
</style>
<!-- main content start -->
<div class="main-content">
    <!-- content -->
    <div class="container-fluid content-top-gap">
        <div class="welcome-msg p-2">
            <h1>Dashboard</h1>
        </div>
        <!-- statistics data -->
        <div class="statistics">
            <div class="row">
                <div class="col-xl-6 pr-xl-2">
                    <div class="row">
                        <div class="col-sm-4 pr-sm-2 statistics-grid">
                            <a href="<?php echo base_url('contacts/all'); ?>">
                                <div class="card card_border border-primary-top p-3" id="blue">
                                    <i class="lnr lnr-phone-handset"> </i>
                                    <h3 class="text-light number"><?= $contacts_total ?></h3>
                                    <p class="stat-text text-light">Total</p>
                                    <div class="row text-white">
                                        <span class="col-md-8">Contacts</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Display statistics for male records -->
                        <div class="col-sm-4 pr-sm-2 statistics-grid">
                            <a href="<?php echo base_url('messages/all'); ?>">
                                <div class="card card_border border-primary-top p-3" id="blue">
                                    <i class="lnr lnr-envelope"> </i>
                                    <h3 class="text-light number"><?= $messages_total ?></h3>
                                    <p class="stat-text text-light">Total</p>
                                    <div class="row text-white">
                                        <span class="col-md-8">Messages</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Display statistics for female records -->
                        <div class="col-sm-4 pr-sm-2 statistics-grid">
                            <a href="<?php echo base_url('messages/all'); ?>">
                                <div class="card card_border border-primary-top p-3" id="blue">
                                    <i class="lnr lnr-envelope"> </i>
                                    <h3 class="text-light number"><?= $sms_sent ?></h3>
                                    <p class="stat-text text-light">Sent</p>
                                    <div class="row text-white">
                                        <span class="col-md-8">SMS<i class="fas fa-key-skeleton    "></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- //statistics data -->
    </div>
</div>
<!-- main content end -->


<!-- chart js -->
<!-- <script src="assets/js/Chart.min.js"></script> -->
<script src="assets/js/utils.js"></script>
<!-- //chart js -->
