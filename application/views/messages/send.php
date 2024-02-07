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
			<h1>Send Message To All Contacts</h1>

		</div>

<div class="card">
    <div class="card-body mb-2">
		<form action="<?= base_url('messages/save') ?>" method="post">
		<div class="form-group">
			<label for="message">Message:</label>
			<textarea name="message" id="message" rows="5" class="form-control" required></textarea>
		</div>

    <button type="submit" class="btn btn-primary pull-right">Send Message To All Contacts</button>
</form>

</div>
</div>

		<!-- end form -->

	</div>
	<!-- //content -->
</div>
<!-- main content end-->

<!-- Script -->



