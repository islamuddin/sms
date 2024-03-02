
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
			<h1>Project-wise Summary</h1>

		</div>


<div class="card">
    <div class="card-header">
		<span class="pull-left">			
		<h2>Project-wise Total Summary</h2>		
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
                <th>OTP Count</th>
                <th>Number Used</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $record): ?>
                <tr>
                	<td><?= $record->project_name ?></td>
                    <td><?= $record->otp_sent ?></td>
                    <td><?= $record->unique_numbers_used ?></td>
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
                                'responsive': false,
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



