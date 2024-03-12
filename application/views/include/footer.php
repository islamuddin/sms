</section>
<!-- Logout Modal -->
<div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-lock"></i> Logout !</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center">
				Are you sure! Do you want to logout?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">NO</button>
				<a href="<?php echo base_url('logout') ?>" class="btn btn-success">YES</a>
			</div>
		</div>
	</div>
</div>
<!-- // Logout Modal -->
<!--footer section start-->
<footer class="dashboard no-print">
</footer>
<!--footer section end-->
<!-- move top -->
<button onclick="topFunction()" id="movetop" class="bg-primary" title="Go to top">
	<span class="fa fa-angle-up"></span>
</button>
<script>
	// When the user scrolls down 20px from the top of the document, show the button
	window.onscroll = function() {
		scrollFunction()
	};

	function scrollFunction() {
		if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
			document.getElementById("movetop").style.display = "block";
		} else {
			document.getElementById("movetop").style.display = "none";
		}
	}

	// When the user clicks on the button, scroll to the top of the document
	function topFunction() {
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	}
</script>
<!-- /move top -->


<!-- <script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-1.10.2.min.js"></script> -->




<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.nicescroll.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/scripts.js"></script>



<!-- close script -->
<script>
	var closebtns = document.getElementsByClassName("close-grid");
	var i;

	for (i = 0; i < closebtns.length; i++) {
		closebtns[i].addEventListener("click", function() {
			this.parentElement.style.display = 'none';
		});
	}
</script>
<!-- //close script -->

<!-- disable body scroll when navbar is in active -->
<script type="text/javascript">
	$(function() {
		$('.sidebar-menu-collapsed').click(function() {
			$('body').toggleClass('noscroll');
		})
	});
</script>
<!-- disable body scroll when navbar is in active -->

<!-- loading-gif Js -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/modernizr.js"></script>
<script type="text/javascript">
	$(window).load(function() {
		// Animate loader off screen
		$(".se-pre-con").fadeOut("fast");;
	});

	//  go back 
	function goBack() {
		window.history.back();
	}
</script>
<!--// loading-gif Js -->

<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/repeater.js" type="text/javascript"></script>
<!-- <script src="<?php base_url(); ?>assets/js/inputmask.js" type="text/javascript"></script> -->
<script src="<?php echo base_url(); ?>assets/input-mask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>


<!-- datatable -->
<script src="<?php echo base_url() ?>assets/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="<?php echo base_url() ?>assets/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<script src="<?php echo base_url() ?>assets/jszip/dist/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/pdfmake/build/vfs_fonts.js"></script>

<!--  -->

<!-- bootstrap-daterangepicker -->
<script src="<?php echo base_url() ?>assets/moment/min/moment.min.js"></script>
<!-- <script src="<?php echo base_url() ?>vendors/bootstrap-daterangepicker/daterangepicker.js"></script>  -->
<!-- bootstrap-datetimepicker -->
<script src="<?php echo base_url() ?>assets/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>



<script src="<?php echo base_url() ?>assets/js/jquery-ui.min.js"></script>

<!-- ======//== -->


<script>
	// $(document).ready(function() {
	//     $('#datatable').DataTable({
	//         responsive: true
	//     });
	// });

	// $('.date-input').datetimepicker({
		
	$('.date-input').datepicker({
		// format : "YYYY-MM-DD"
		// format : "yyyy-mm-dd"
		dateFormat: 'yy-mm-dd',
	});

	/* Create Repeater */
	$("#repeater").createRepeater({
		showFirstItemToDefault: false,
	});

	/* equipment Repeater */
	$("#equipment_repeater").createRepeater({
		showFirstItemToDefault: true,
	});
	// Initialize InputMask
	$('input').inputmask();
	// $(":input").inputmask();
	$(document).delegate('.person_cnic', 'focus', function() {
		$(".person_cnic").inputmask('99999-9999999-9');
	});

	$(document).delegate('.veh_no', 'focus', function() {
		$(".veh_no").inputmask('ABC-9999');
		// $(".veh_no").inputmask('a{1,3}-a{1,3}9{1,3}');

	});

	function myfunction() {

		if ($('#status').val() == 0 || $('#status').val() == '') {
			// alert("OH!----");
			document.getElementById('noCheck').checked = true;
		} else {
			// document.getElementById('yesCheck').checked = true;
			//document.getElementById('ifYes').style.display = 'block';
			// document.getElementById('letter_no').required = 'false';
			//document.getElementById('appoin_date').required = 'true';
			//document.getElementById('TextBox1').required = 'true';
			// document.getElementById('TextBox2').required = 'true';

		}
	}


	function yesnoCheck() {
		// if (document.getElementById('yesCheck').checked) {
		// 	document.getElementById('ifYes').style.visibility = 'visible';
		// } else document.getElementById('ifYes').style.visibility = 'hidden';


		if (document.getElementById('yesCheck').checked) {
			document.getElementById('ifYes').style.display = 'block';
			document.getElementById('letter_no').required = false;
			document.getElementById('appoin_date').required = 'true';
			document.getElementById('TextBox1').required = 'true';
			document.getElementById('TextBox2').required = 'true';

			// document.getElementById('serial_no').required = 'true';

		} else {
			document.getElementById('ifYes').style.display = 'none';
			document.getElementById('letter_no').required = false;
		}


	}

	$(".alert").delay(4000).slideUp(200, function() {
		$(this).alert('close');
	});



	// --------------------------  HRMIS API ------------------------------
	// $('#cnic').blur(function() {
	$('#verify').click(function() {
		$("#myDiv").show();

		if ($('#cnic').val().length == 15 && $('#cnic').val() != '_____-_______-_') {
			// $.getJSON('<?php echo base_url(); ?>Visitor_Controller/pullofficerfromhrms/'+$('#cnic').val(),function(data){
			// setTimeout(function(){ p.abort(); }, 100);


		}






		if ($('#cnic').val() === '_____-_______-_' || $('#cnic').val() === '') {
			// alert('This CNIC Record not found');
			$("#myDiv").hide();
			$("#verify_danger").hide();
			$("#verify_success").hide();
			$('#cnic_error').text('Please Enter CNIC No');
			$('#ofc_id').val('');
			$('#hrm_verified').val('');
			$('#nadra_verified').val('');
			$('#cro_verified').val('');
			$('#read_status').val('');
			$('#name').val('');
			$('#rank').val('');
			$('#fname').val('');
			$('#current_posting').val('');

			$('#caste').val('');
			$('#contact_no').val('');

			$('#profile_image').val('');
			$('#nadra_image').val('');
			//   var $profile_image= data['output'][0].picture_new;

			document.getElementById('profile_pic').src = '<?php echo base_url(); ?>assets/images/user.png';
			//   $('#visible_mark').val(data['output'][0].ofc_fathername);

			$('#perm_address').val('');
			$('#address').val('');

			$('#visitor_name').text('Name');
			$('#visitor_address').text('Address');
			$('#visitor_cnic').text('CNIC');
			$('#emp_posting').text('CPO');
			$('#hrm_verified').val(0);
			document.getElementById("cnic_verified").style.background = "#d6d6d7";
			document.getElementById("cro_verified_ico").style.background = "#d6d6d7";
			document.getElementById("unverified").style.background = "#d6d6d7";



		}





	});

	$('#cnic').blur(function() {
		if ($('#cnic').val() === '_____-_______-_' || $('#cnic').val() === '') {
			// alert('This CNIC Record not found');
			$("#myDiv").hide();
			$("#verify_danger").hide();
			$("#verify_success").hide();
			$('#cnic_error').text('');
			$('#ofc_id').val('');
			$('#hrm_verified').val('');
			$('#nadra_verified').val('');
			$('#cro_verified').val('');
			$('#read_status').val(2);
			$('#name').val('');
			$('#rank').val('');
			$('#fname').val('');
			$('#current_posting').val('');

			$('#caste').val('');
			$('#contact_no').val('');

			$('#profile_image').val('');
			$('#nadra_image').val('');
			//   var $profile_image= data['output'][0].picture_new;

			document.getElementById('profile_pic').src = '<?php echo base_url(); ?>assets/images/user.png';
			//   $('#visible_mark').val(data['output'][0].ofc_fathername);

			$('#perm_address').val('');
			$('#address').val('');

			$('#visitor_name').text('Name');
			$('#visitor_address').text('Address');
			$('#visitor_cnic').text('CNIC');
			$('#emp_posting').text('CPO');
			$('#hrm_verified').val(0);
			document.getElementById("cnic_verified").style.background = "#d6d6d7";
			document.getElementById("cro_verified_ico").style.background = "#d6d6d7";
			document.getElementById("unverified").style.background = "#d6d6d7";



		}
	});
</script>
<script type="text/javascript">
	$(function() {
		$('#myDatepicker').datetimepicker();
	});

	$('#myDatepicker2').datetimepicker({
		format: 'DD.MM.YYYY'
	});

	$('#joining_date_').datetimepicker({
		format: 'DD.MM.YYYY'
	});

	$('#myDatepicker3').datetimepicker({
		format: 'hh:mm A'
	});

	$('#myDatepicker4').datetimepicker({
		ignoreReadonly: true,
		allowInputToggle: true
	});

	$('#datetimepicker6').datetimepicker();

	$('#datetimepicker7').datetimepicker({
		useCurrent: false
	});

	$("#datetimepicker6").on("dp.change", function(e) {
		$('#datetimepicker7').data("DateTimePicker").minDate(e.date);
	});

	$("#datetimepicker7").on("dp.change", function(e) {
		$('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
	});

	// ============== Date range ==============
	// $("#TextBox1").datepicker({
	// 	dateFormat: 'dd-mm-yy',
	// 	// minDate: '-2Y-6M',
	// 	minDate: '-0Y-0M-3D',
	// 	maxDate: '+2Y+6M',
	// 	onSelect: function(dateStr) {
	// 		var min = $(this).datepicker('getDate'); // Get selected date
	// 		$("#TextBox2").datepicker('option', 'minDate', min || '0'); // Set other min, default to today
	// 	}
	// });

	// $("#TextBox2").datepicker({
	// 	dateFormat: 'dd-mm-yy',
	// 	// minDate: '-2Y-6M',
	// 	minDate: '-0Y-0M-3D',
	// 	maxDate: '+2Y+6M',
	// 	onSelect: function(dateStr) {
	// 		var max = $(this).datepicker('getDate'); // Get selected date
	// 		$('#datepicker').datepicker('option', 'maxDate', max || '+2Y+6M'); // Set other max, default to +18 months
	// 		var start = $("#TextBox1").datepicker("getDate");
	// 		var end = $("#TextBox2").datepicker("getDate");
	// 		var days = (end - start) / (1000 * 60 * 60 * 24) + 1;
	// 		// $("#TextBox3").val(days);
	// 	}
	// });

	// ============== report range ==============
	// $("#searchdatefrom").datepicker({
	//     dateFormat: 'yy-mm-dd',
	//     // minDate: '-2Y-6M',
	//     minDate: '-0Y-0M-3D',
	//     maxDate: '+2Y+6M',
	//     onSelect: function (dateStr) {
	//         var min = $(this).datepicker('getDate'); // Get selected date
	//         $("#searchdateto").datepicker('option', 'minDate', min || '0'); // Set other min, default to today
	//     }
	// });

	// $("#searchdateto").datepicker({
	//     dateFormat: 'yy-mm-dd',
	//     // minDate: '-2Y-6M',
	//     minDate: '-0Y-0M-3D',
	//     maxDate: '+2Y+6M',
	//     onSelect: function (dateStr) {
	//         var max = $(this).datepicker('getDate'); // Get selected date
	//         $('#datepicker').datepicker('option', 'maxDate', max || '+2Y+6M'); // Set other max, default to +18 months
	//         var start = $("#searchdatefrom").datepicker("getDate");
	//         var end = $("#searchdateto").datepicker("getDate");
	//         var days = (end - start) / (1000 * 60 * 60 * 24)+1;
	//         // $("#TextBox3").val(days);
	//     }
	// });

	// $("#appoin_date").datepicker({
	// 	dateFormat: 'dd-mm-yy',
	// 	minDate: '-0Y-0M-3D',
	// 	maxDate: '+2Y+6M',
	// 	onSelect: function(dateStr) {
	// 		var min = $(this).datepicker('getDate'); // Get selected date
	// 		// $("#appoin_date").datepicker('option', 'minDate', min || '0'); // Set other min, default to today
	// 	}
	// });

	// $("#v_date").datepicker({
	// 	dateFormat: 'yy-mm-dd',
	// 	minDate: '-1Y-0M-3D',
	// 	maxDate: '+0Y+0M+0D',
	// 	onSelect: function(dateStr) {
	// 		var min = $(this).datepicker('getDate'); // Get selected date
	// 		// $("#appoin_date").datepicker('option', 'minDate', min || '0'); // Set other min, default to today
	// 	}
	// });


	// $("#expiry_date").datepicker({
	// 	dateFormat: 'dd-mm-yy',
	// 	minDate: '-0Y-0M-3D',
	// 	maxDate: '+2Y+6M',
	// 	onSelect: function(dateStr) {
	// 		var min = $(this).datepicker('getDate'); // Get selected date
	// 		// $("#appoin_date").datepicker('option', 'minDate', min || '0'); // Set other min, default to today
	// 	}
	// });
	// --------- tooltip enabled--------------
	$(function() {
		$('[data-toggle="tooltip"]').tooltip()
	})
</script>
</body>

</html>
