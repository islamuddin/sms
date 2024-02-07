<!-- Tabular Single Record View -->



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
                                <td><strong>Sent On:</strong><br><?= $record->created_date ?></td>
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
