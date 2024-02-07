<!-- Edit mode view -->
<!-- main content start -->
<?php 
if(!$record){
    echo '<div class="main-content"><div class="container-fluid content-top-gap"><br><h3>No record found</h3></div></div>';
}else {
?>
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
            <h1>Edit</h1>
        </div>
<div class="card col-md-12">
    <div class="card-body">
        <!-- Form in edit mode -->
        <form action="<?= base_url('contacts/update') ?>" method="post">
            <input type="hidden" name="id" value="<?= $record->id ?>">
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= $record->name ?>" required>
            </div>

			<div class="form-group">
			<label for="contact_no">Contact NO</label>
			<input type="text" name="contact_no" id="contact_no" class="form-control" value="<?= $record->contact_no ?>"  required>		
		</div>
		

            <button type="submit" class="btn btn-primary">Update</button>
            <a onclick="history.back()" class="btn btn-info mr-2 text-light">Go Back</a>
        </form>
        </div>
        </div>
    </div>
    <!-- //content -->
</div>
<!-- main content end -->
<?php } ?>
