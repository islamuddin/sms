
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
                <th>IP</th>
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
                    <td><?= $record->ip ?></td>
                    <td><?= $record->created_date ?></td>
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
</script>


		<!-- end form -->

	</div>
	<!-- //content -->
</div>
<!-- main content end-->

<!-- Script -->



