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
	<!-- <button class="btn btn-danger" id="deleteSelected">Delete Selected</button> -->

    <table id="records-table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Project</th>
                <th>OTP</th>
                <th>Number</th>
                <th>Sent On</th>
                <th>Status</th>
                <th>Response</th>
				<th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $record): ?>
                <tr>
                	<td><a href="<?php echo base_url(); ?>projects/view?id=<?= $record->project_id ?>"><?= $record->project_name ?></a></td>
                    <td><a href="<?php echo base_url();?>otp/view?id=<?= $record->id ?>"><?= $record->otp ?></a></td>
                    <td><a href="<?php echo base_url();?>otp/view?number=<?= $record->number ?>"><?= $record->number ?></a></td>
                    <td><?= time_ago($record->created_date) ?></td>
                    <td><?php if($record->status==='1'){ echo "Sent"; }else{ echo "Failed";} ?></td>
                    <td><?= $record->response ?></td>
            
                    <td>
						<a href="<?php echo base_url();?>otp/view?id=<?= $record->id ?>">View</a>
					</td> 
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
</div>
<script>
    $(document).ready(function() {
        $('#records-table').DataTable({
            dom: 'lBfrtip',

                                buttons: [
                                    'excel', 'pdf', 'print'
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'

                                ],

                                'processing': true,
                                // 'serverSide': true,
                                'responsive': true,
                                // 'serverMethod': 'post',                               
                                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                                buttons: [
{
    extend: 'copyHtml5',
    exportOptions: {
        columns: [0, ':visible']
    }

},
{
    extend: 'excel',
    title: 'SMS Management System',
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
        columns: [1, 2]
    }
},

{
    extend: 'pdfHtml5',
    title: 'Contact Details',
    exportOptions: {
        columns: [1, 2]

    },
    // exportOrientation: "landscape",
    orientation: 'landscape',
},

{
    extend: 'print',
    title: 'Contact Details',
    exportOptions: {
        columns: [1, 2]
    },
    exportOrientation: "landscape",
},

]
                               
        });
    });


	$('#checkAll').click(function() {
		$('.record-checkbox').prop('checked', this.checked);
	});

	$('.record-checkbox').change(function() {
		if ($('.record-checkbox:checked').length === $('.record-checkbox').length) {
			$('#checkAll').prop('checked', true);
		} else {
			$('#checkAll').prop('checked', false);
		}
	});

	$('#deleteSelected').click(function() {
            var selectedIds = [];
            $('.record-checkbox:checked').each(function() {
                selectedIds.push($(this).data('record-id'));
            });

            if (selectedIds.length > 0) {
                // Send an AJAX request to delete selected records
                $.ajax({
                    url: '<?php echo base_url();?>otp/deleteSelected',
                    type: 'POST',
                    data: { ids: selectedIds },
                    success: function(response) {
                        // Handle success response
                        alert('Selected records deleted successfully.');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        alert('Error deleting selected records.');
                    }
                });
            } else {
                alert('Please select at least one record to delete.');
            }
        });
</script>


		<!-- end form -->

	</div>
	<!-- //content -->
</div>
<!-- main content end-->

<!-- Script -->



