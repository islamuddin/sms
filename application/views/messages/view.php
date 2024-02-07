<!-- Tabular Single Record View -->
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

?>


<?php if ($record) : ?>
    <div class="main-content">
        <div class="container-fluid content-top-gap">
            <div class="card">
                <div class="card-header">
                    <h2>Message Detail 
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
								Message Detail</th>
                            </tr>
                            <tr>
                                <td><strong>Message:</strong><br><?= $record->message ?></td>
                            </tr>
                            <tr>
                                <td><strong>Sent On:</strong><br><?= $record->created_date ?> <small class="text-muted"> (<?= time_ago($record->created_date) ?>)</small></td>
                            </tr>
                        </tbody>
                    </table>
					<strong>Message Sent To:</strong>
					<table id="records-table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>📞Contact #</th>
				<th>Status</th>


				<th>Contact Details</th> 
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $record): ?>
                <tr>
                	<td><?= $record->name ?></td>
                    <td><?= $record->contact_no ?></td>
					<td>Sent</td>

                    <td>
						<a href="<?php echo base_url();?>contacts/view?id=<?= $record->id ?>">View</a>
					</td> 
                </tr>
            <?php endforeach; ?>
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
                    return 'Contact Number';
                } else {
                    return data;
                }
            }
        },
        columns: [0,1, 2]
    }
},

{
    extend: 'pdfHtml5',
    title: 'Contact Details',
    exportOptions: {
        columns: [0,1, 2]

    },
    // exportOrientation: "landscape",
    orientation: 'landscape',
},

{
    extend: 'print',
    title: 'Contact Details',
    exportOptions: {
        columns: [0,1, 2]
    },
    exportOrientation: "landscape",
},

]
                               
        });
    });


</script>
