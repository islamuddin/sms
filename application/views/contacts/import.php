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
			<h1>Import CSV File</h1>

		</div>

<div class="card">
    <div class="card-body mb-2">
		<!-- <form action="<?= base_url('records/importAction') ?>" method="post"  enctype="multipart/form-data">
		</form> -->
		<div class="form-group">
			<label for="polling_name">Upload CSV File</label>
			<input type="file"  name="csv_file" id="csv_file" class="form-control" required>		
		</div>
		<button type="submit" id="process" onclick="processCSV()" class="btn btn-primary pull-right">Process CSV</button>


</div>
</div>

		<!-- end form -->

	</div>
	<!-- //content -->
</div>
<!-- main content end-->

<!-- Script -->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/import.js?v=1"></script>
