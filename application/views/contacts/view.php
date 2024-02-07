<!-- Tabular Single Record View -->
<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .timeline-badge {
        background-color: #00ff72;
        color: #fff;
        border-radius: 50%;
        font-size: 18px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .timeline-panel {
        width: calc(100% - 45px); /* Adjust as needed */
    }

    .timeline-vertical-line {
        width: 2px;
        background-color: #00ff72;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 15px;
        margin-left: -1px;
    }
</style>



<?php if ($record) : ?>
    <div class="main-content">
        <div class="container-fluid content-top-gap">
            <div class="card">
                <div class="card-header">
                    <h2>Contact Detail 
						<span class="pull-right">
							<a onclick="history.back()" class="btn btn-info mr-2 text-light">Go Back</a>
							<a class="btn btn-primary" href="<?php echo base_url();?>records/edit?id=<?= $record->id ?>"> <i class="fa fa-pencil"></i> Edit</a>
							<a onclick="window.print();" class="btn btn-info mr-2 text-light">Print</a>
						</span>
					</h2>
                    
                </div>
                <div class="card-body">
                    <table class="table ">
                        <tbody>
                            <tr>
                                <th colspan="1">
								<img class="pr-2" src="<?php echo base_url(); ?>assets/images/logo_in.png" alt="logo-icon" style="    width: 10%;">	
								Contact Detail</th>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong><br><?= $record->name ?></td>
                            </tr>
                            <tr>
                                <td><strong>Contact No:</strong><br><?= $record->contact_no ?></td>
                            </tr>
                        </tbody>
                    </table>
					
					<div class="card">
						<div class="card-header">
							<h3><i class="lnr lnr-envelope"></i> Messages Sent</h3>
						</div>
						<div class="card-body">
							<div class="timeline">
								<?php if($messages){ foreach ($messages as $message): ?>
									<div class="timeline-item">
										<div class="timeline-badge"><i class="fa fa-check"></i></div>
										<div class="timeline-panel">
											<div class="timeline-heading">
												<h4 class="timeline-title"><a href="<?php echo base_url(); ?>messages/view?id=<?= $message->id ?>">Message</a></h4>
												<p><small class="text-muted"><?= $message->created_date ?></small></p>
											</div>
											<div class="timeline-body">
												<p><?= $message->message ?></p>
											</div>
										</div>
									</div>
									<div class="timeline-vertical-line"></div>
								<?php endforeach; }else{ echo "No messages sent"; } ?>
							</div>
						</div>
					</div>
					<!-- End of Bootstrap Timeline Card -->

                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="main-content">
        <div class="container-fluid content-top-gap">
            <br>
            <h3>No record found</h3>
        </div>
    </div>
<?php endif; ?>
