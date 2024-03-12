<?php
function time_ago($timestamp)
{
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes      = round($seconds / 60);           // value 60 is seconds
    $hours        = round($seconds / 3600);         // value 3600 is 60 minutes * 60 sec
    $days          = round($seconds / 86400);        // value 86400 is 24 hours * 60 minutes * 60 sec
    $weeks         = round($seconds / 604800);       // value 604800 is 7 days * 24 hours * 60 minutes * 60 sec
    $months       = round($seconds / 2629440);      // value 2629440 is ((365+365+365+365+366)/5/12) days * 24 hours * 60 minutes * 60 sec
    $years          = round($seconds / 31553280);     // value 31553280 is ((365+365+365+365+366)/5) days * 24 hours * 60 minutes * 60 sec

    if ($seconds <= 60) {
        return "Few seconds ago";
    } else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "1 minute ago";
        } else {
            return "$minutes minutes ago";
        }
    } else if ($hours <= 24) {
        if ($hours == 1) {
            return "1 hour ago";
        } else {
            return "$hours hours ago";
        }
    } else if ($days <= 7) {
        if ($days == 1) {
            return "yesterday";
        } else {
            return "$days days ago";
        }
    } else if ($weeks <= 4.3) {  // 4.3 == 30/7
        if ($weeks == 1) {
            return "1 week ago";
        } else {
            return "$weeks weeks ago";
        }
    } else if ($months <= 12) {
        if ($months == 1) {
            return "1 month ago";
        } else {
            return "$months months ago";
        }
    } else {
        if ($years == 1) {
            return "1 year ago";
        } else {
            return "$years years ago";
        }
    }
}
?>

<!-- main content start -->
<div class="main-content">

    <!-- content -->
    <div class="container-fluid content-top-gap">

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
        $success = $this->session->flashdata('inert_message');
        if ($success) {
        ?>
            <div class="alert ui-pnotify-container alert-success ui-pnotify-shadow">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $this->session->flashdata('inert_message'); ?>
            </div>
        <?php } ?>
        <div class="welcome-msg pt-3 pb-4">
            <h1>All OTP Log</h1>

        </div>


        <div class="card">
            <div class="card-header">
                <span class="pull-left">
                    <h2>OTPs</h2>
                </span>
                <span class="pull-right">
                </span>

            </div>
            <div class="card-body mb-3">
            <div class="row mb-2">
                        
                        <div class="col-md-3 mb-2">
                            <!-- dept -->
                            <select id='searchstatus' class="form-control input-style">
                                <option value=''>-- Select Status --</option>
                                <?php
                                // foreach ($floor as $flr) {
                                //     echo "<option value='" . $flr . "'>" . $flr . "</option>";
                                // }
                                ?>
                                <option value="0">Failed</option>
                                <option value="1">Sent</option>
                            </select>

                        </div>



                        <div class="col-md-3 mb-2">

                            <!-- Name -->
                            <div class="input-group">
                                <input type="text" id="searchdatefrom" class="form-control input-style date-input" placeholder="Date from" data-inputmask="'mask': '9999-99-99'">
                                <label class="input-group-btn" for="searchdatefrom">
                                    <span class="btn btn-primary pb-3">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </label>
                            </div>

                        </div>

                        <div class="col-md-3 mb-2">
                            <div class="input-group">
                                <!-- Name -->
                                <input type="text" id="searchdateto" class="form-control input-style date-input" placeholder="Date to" data-inputmask="'mask': '9999-99-99'">
                                <label class="input-group-btn" for="searchdateto">
                                    <span class="btn btn-primary pb-3">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                       
                        <div class="col-md-3 mb-2">
                            <!-- Name -->
                            <input type="text" id="searchcontact_no" class="form-control input-style" placeholder="Contact no" data-inputmask="'mask': '99999999999'" maxlenth="11">
                        </div>


                        <div class="col-md-3 mb-2">
                            <input type="text" id="searchname" class="form-control input-style" placeholder="Search Name">
                        </div>
                    </div>
                <!-- <button class="btn btn-danger" id="deleteSelected">Delete Selected</button> -->

                <table id="records-table" class="table table-striped table-bordered ">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>OTP</th>
                            <th>Number</th>
                            <th>IP</th>
                            <th>Sent On</th>
                            <th>Status</th>
                            <th>Response</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                   
                </table>

            </div>
        </div>
        <script>
            $(document).ready(function() {
                var currentUrl = window.location.href;
                var urlParams = new URLSearchParams(currentUrl.split('?')[1]);
                 var typeValue = urlParams.get('type');

                var userDataTable = $('#records-table').DataTable({
                    dom: 'lBfrtip',

                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'

                    ],

                    'processing': true,
                    'serverSide': true,
                    'responsive': true,
                    'serverMethod': 'post',

                    'searching': false, // Remove default Search Control
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    "pagingType": "full_numbers",
                    // "pageLength": 25,
                    buttons: [

                        {
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: [0, ':visible']


                            }

                        },
                        {
                            extend: 'excel',
                            title: 'All Visitors ',
                            // text:'<i class="fa fa-table fainfo" aria-hidden="true" ></i>',
                            titleAttr: 'Export Excel',
                            "oSelectorOpts": {
                                filter: 'applied',
                                order: 'current'
                            },
                            exportOptions: {
                                modifier: {
                                    page: 'all'
                                },

                                format: {
                                    header: function(data, columnIdx) {
                                        if (columnIdx == 1) {
                                            return 'column_1_header';
                                        } else {
                                            return data;
                                        }
                                    }
                                },
                                columns: [0, 1, 2, 3, 4, 5, ]
                            }
                        },

                        {
                            extend: 'pdfHtml5',
                            title: 'All Visitors - exported:<?php echo date("m-d-Y"); ?> | <?php echo $pageTitle ?> <br><?php echo $branch ?>',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]

                            },
                            // exportOrientation: "landscape",
                            orientation: 'landscape',
                        },

                        {
                            extend: 'print',
                            title: '<i class="text-center pull-left fa fa-id-badge fainfo" style="margin-top:10px; "  > All Visitors  Dated: <?php echo date("m-d-Y"); ?>   <?php // echo date("m-d-Y"); 
                                                                                                                                                                            ?> | <?php echo $pageTitle ?> <br><?php echo $branch ?></i>  <img class="pull-right" style="width:100px;position:relative; margin-bottom:5px;" src="<?php echo base_url() ?>assets/images/print_logo.png" alt="--"/><span ></span>',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,]
                            },
                            exportOrientation: "landscape",
                        },

                    ],


                    'ajax': {
                        'url': '<?php echo base_url(); ?>Otp_Controller/all_otps_ajax',
                        'data': function(data) {
                            data.range_id = $('#range_id').val();
                            data.place_id = $('#place_id').val();
                            data.visitor_type = $('#visitor_type').val();

                            data.searchdept = $('#searchdept').val();
                            data.searchstatus = $('#searchstatus').val();
                            data.searchcontact_no = $('#searchcontact_no').val();
                            data.searchcnic = $('#searchcnic').val();
                            data.searchname = $('#searchname').val();
                            data.searchpurpose = $('#searchpurpose').val();
                            data.searchfloor = $('#searchfloor').val();
                            data.searchdatefrom = $('#searchdatefrom').val();
                            data.searchdateto = $('#searchdateto').val();

                            data.type = 'all';
                            data.url= typeValue;
                        }


                    },
                    'columns': [
                        // {
                        //     data: 'serial_no'
                        // },
                        {
                            data: 'project_name'
                        },
                        {
                            data: 'otp'
                        },
                        {
                            data: 'number'
                        },
                        {
                            data: 'ip'
                        },
                        {
                            data: 'created_date'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'response'
                        },
                        //{
                        //    data: 'response'
                        //},
                                              
                        {
                            data: 'actions'
                        }


                    ]
                });

                $('#searchstatus,#place_id,#searchdept,#searchsubb,#searchpurpose,#searchfloor,#searchdatefrom,#searchdateto').change(function() {
                    userDataTable.draw();

                    $('#report').text($('#searchdept').val());
                });
                $('#searchname,#searchcontact_no,#searchcnic').keyup(function() {
                    userDataTable.draw();
                });
            });


            // $('#checkAll').click(function() {
            //     $('.record-checkbox').prop('checked', this.checked);
            // });

            // $('.record-checkbox').change(function() {
            //     if ($('.record-checkbox:checked').length === $('.record-checkbox').length) {
            //         $('#checkAll').prop('checked', true);
            //     } else {
            //         $('#checkAll').prop('checked', false);
            //     }
            // });

            // $('#deleteSelected').click(function() {
            //     var selectedIds = [];
            //     $('.record-checkbox:checked').each(function() {
            //         selectedIds.push($(this).data('record-id'));
            //     });

            //     if (selectedIds.length > 0) {
            //         // Send an AJAX request to delete selected records
            //         $.ajax({
            //             url: '<?php echo base_url(); ?>otp/deleteSelected',
            //             type: 'POST',
            //             data: {
            //                 ids: selectedIds
            //             },
            //             success: function(response) {
            //                 // Handle success response
            //                 alert('Selected records deleted successfully.');
            //                 location.reload();
            //             },
            //             error: function(xhr, status, error) {
            //                 // Handle error response
            //                 alert('Error deleting selected records.');
            //             }
            //         });
            //     } else {
            //         alert('Please select at least one record to delete.');
            //     }
            // });
        </script>


        <!-- end form -->

    </div>
    <!-- //content -->
</div>
<!-- main content end-->

<!-- Script -->