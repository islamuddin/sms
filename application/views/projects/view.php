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
                    <h2>Projects Detail 
						<span class="pull-right">
							<a onclick="history.back()" class="btn btn-info mr-2 text-light">Go Back</a>
							<a class="btn btn-primary" href="<?php echo base_url();?>projects/edit?id=<?= $record->id ?>"> <i class="fa fa-pencil"></i> Edit</a>
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
								Project Detail</th>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong><br><?= $record->name ?></td>
                            </tr>
                            <tr>
                                <td><strong>API Key:</strong><br><?= $record->api_key ?></td>
                            </tr>
                            <tr>
                                <td><strong>Mask:</strong><br><?= $record->mask ?></td>
                            </tr>
                            <tr>
                                <td><strong>API Setup For Developers To Receive OTP:</strong><br><li>POST: <?php echo base_url(); ?>api/getOTP </li><li>FORM Data: api_key: <?= $record->api_key ?>, number:< OTP User Mobile Number Here ></li></td>
                            </tr>
                        </tbody>
                    </table>
					
					<div class="card">
						<div class="card-header">
							<h3><i class="lnr lnr-envelope"></i> OTPs Sent</h3>
						</div>
						<div class="card-body">
							<div class="timeline">
								<?php if($messages){ foreach ($messages as $message): ?>
									<div class="timeline-item">
										<div class="timeline-badge"><i class="fa fa-check"></i></div>
										<div class="timeline-panel">
											<div class="timeline-heading">
												<h4 class="timeline-title"><a href="<?php echo base_url(); ?>projects/view?id=<?= $message->project_id ?>">OTP for <?php echo $message->project_name; ?> login</a></h4>
												<p><p><small class="text-muted"><?= time_ago($message->created_date) ?></small></p></p>
											</div>
											<div class="timeline-body">
												<p><strong>Number:</strong> <a href="<?php echo base_url();?>otp/view?number=<?= $message->number ?>"><?= $message->number ?></a></p>
												<p><strong>Code:</strong> <a href="<?php echo base_url(); ?>otp/view?id=<?= $message->id ?>"><?= $message->otp ?></a></p>
												<p><strong>Message</strong>: <?= $message->message ?></p>
												<p><strong>URL</strong>: <?= $message->url ?></p>
												<p><strong>Response</strong>: <?= $message->response ?></p>
												<p><strong>Status</strong>: <?php if($message->status==='1'){ echo "Sent"; }else{ echo "Failed";} ?></p>
												<p><strong>IP</strong>: <?= $message->ip ?></p>
		</div>
										</div>
									</div>
									<div class="timeline-vertical-line"></div>
								<?php endforeach; }else{ echo "No message sent"; } ?>
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
