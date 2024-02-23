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
                    <h2>OTP Detail 
						<span class="pull-right">
							<a onclick="history.back()" class="btn btn-info mr-2 text-light">Go Back</a>
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
								OTP Detail</th>
                            </tr>
                            <tr>
                                <td><strong>Project:</strong><br><?= $record->project_id ?></td>
                            </tr>
                            <tr>
                                <td><strong>OTP:</strong><br><?= $record->otp ?></td>
                            </tr>

							<tr>
                                <td><strong>Number :</strong><br><?= $record->number ?></td>
                            </tr>
							<tr>
                                <td><strong>Message :</strong><br><?= $record->message ?></td>
                            </tr>
                        </tbody>
                    </table>
					


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
