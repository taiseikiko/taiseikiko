<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  include("header1.php");
?>
<!-- <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>How to Create a PHP Event Calendar with FullCalendar JS Library</title>
</head> -->
<main>

	<?php 
	$currentData = date('Y-m-d');
	?>

	<!-- Calendar Container -->
	<div id='calendar-container'>
    	<div id='calendar'></div>
  	</div>

  	<!-- jQuery -->
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

  	<!-- Fullcalendar  -->
	<script type="text/javascript" src="fullcalendar/dist/index.global.min.js"></script>

	<!-- Sweetalert -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="assets/js/public_office_ent.js"></script>
	<script src="assets/js/customer_ent.js"></script>
	<script src="assets/js/fwt_check.js"></script>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
	    var calendarEl = document.getElementById('calendar');

	    var calendar = new FullCalendar.Calendar(calendarEl, {
			locale: 'ja',
	      	initialDate: '<?= $currentData ?>',
	      	height: '600px',
	      	selectable: true,
	      	editable: true,
			businessHours: true,
	      	dayMaxEvents: true, // allow "more" link when too many events
	      	events: 'fetchevents.php', // Fetch all events
			eventSourceSuccess: function(content) {
				// content.forEach(event => {
				// 	if (event.stop !== null) {
				// 		event.backgroundColor = 'red';
				// 	} else {
				// 		// event.backgroundColor = 'blue';
				// 	}
				// })
			},
			weekends: true, // Display weekends
			selectConstraint: {
			dow: [1, 2, 3, 4, 5] // Only allow selecting on weekdays
			},
			eventConstraint: {
			dow: [1, 2, 3, 4, 5] // Only allow dragging and resizing on weekdays
			},
			selectAllow: function(selectInfo) {
			return selectInfo.start.getUTCDay() !== 5 && selectInfo.start.getUTCDay() !== 6;
			},
			eventAllow: function(event, delta, revertFunc, jsEvent, ui, view) {
			return event.start.getUTCDay() !== 5 && event.start.getUTCDay() !== 6;
			},
	      	select: function(arg) { // Create Event

				//Fetch HTML content using ajax
				$.ajax({
					url: 'fwt_m_input3_calendar.php',
					type: 'POST',
					data: {
						set_date : arg.startStr
					},
					success: function(response) {
						// Alert box to add event
						function openSwalWithContent(response) {
							Swal.fire({
								title: '予約不可、解除',
								showCancelButton: true,
								cancelButtonText: '前の画面に戻る',
								confirmButtonText: '予約不可登録',
								html: response,
								customClass: 'swal-style',
								focusConfirm: false,
								preConfirm: () => {
									const values = [];
									const ids = ['stop_note', 'stop_date', 'stop_time', 'stop_name'];

									ids.forEach(element => {
										var value = getValuesByName(element);
										values.push(value);
									});

									let errMsg = '';
									// errMsg = checkValidation2();

									if (errMsg !== '') {
										Swal.showValidationMessage(errMsg);
									}

									return values;
								}
							}).then((result) => {
								if (result.isConfirmed) {
									const form_values = {};
									const keys = ['stop_note', 'stop_date', 'stop_time', 'stop_name'];

									keys.forEach((key, index) => {
										form_values[key] = result.value[index];
									});							

									var start_date = arg.startStr;
									var end_date = arg.endStr;
            
									// AJAX - Add to fwt_m_tr
									$.ajax({
										url: 'fwt_m_update_calendar.php',
										type: 'post',
										data: {
											request: 'add_to_fwt_stop',
											form_values: form_values,
											start_date: start_date,
											end_date: end_date},
										dataType: 'json',
										success: function(response){
											if(response.status == 1){
												// Add event
												calendar.addEvent({
													// eventid: form_values['fwt_m_no'],
													// title: form_values['class'],
													// description: form_values['status'],
													// start: arg.start,
													// end: arg.end,
													// allDay: arg.allDay
												})

												// Alert message
												Swal.fire(response.message,'','success');

											}else{
												// Alert message
												Swal.fire(response.message,'','error');
											}
											
										},
										error: function(xhr, status, error) {
											// Handle errors
											console.log(xhr);
											console.error('Error fetching HTML content:', error);
											Swal.fire({
												title: 'Error',
												text: 'Failed to load content.',
												icon: 'error'
											});
										}
									});								
								}
							})
						}

						// Initial open of SweetAlert with fetched content
            			openSwalWithContent(response);

						calendar.unselect()
						/*----------------------------------------------------------------------------------------------- */

						function getValuesByName(name) {
							const elements = document.getElementsByName(name);
							let values = '';
							let stop_notes = [];

							elements.forEach(element => {
								if (element.type === 'radio') {
									if (element.checked) {
										values = element.value;
									}
								} else if (element.type === 'checkbox') {
									if (element.name == 'stop_note') {
										if (element.checked) {
											stop_notes.push(element.value);
										}
									} else {
										if (element.checked) {
											values = element.value;
										}
									}
								} else {
									values = element.value;
								}
							})
							if (stop_notes.length > 0) {
								values = stop_notes.join(',');
							}
							return values;
						}

						/*----------------------------------------------------------------------------------------------- */
					},
					error: function(xhr, status, error) {
						// Handle errors
						console.error('Error fetching HTML content:', error);
						Swal.fire({
							title: 'Error',
							text: 'Failed to load content.',
							icon: 'error'
						});
					}
				});	        	
	        	
	      	},		
	    });

		var callPF = document

	    calendar.render();
	});

	</script>
</body>
</html>
<style>
.swal-style {
	width:100% !important;
	height: 100vh !important;
}
a {
    color: #0b0b0c;
}
</style>