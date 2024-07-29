<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>How to Create a PHP Event Calendar with FullCalendar JS Library</title>
</head>
<body>

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
	      	select: function(arg) { // Create Event

				//Fetch HTML content using ajax
				$.ajax({
					url: 'fwt_m_input2.php',
					type: 'POST',
					data: $('#input2').serialize(),
					success: function(response) {
						// Alert box to add event
						Swal.fire({
							title: '見学、立会、研修仮予約、本予約入力',
							showCancelButton: true,
							cancelButtonText: '前の画面に戻る',
							confirmButtonText: '仮予約登録',
							html: response,
							customClass: 'swal-style',
							focusConfirm: false,
							preConfirm: () => {
								const values = [];
								const ids = ['class', 'candidate1_date', 'candidate1_start', 'candidate1_end', 'candidate2_date', 'candidate2_start', 'candidate2_end', 
											'candidate3_date', 'candidate3_start', 'candidate3_end', 'pf_code', 'cust_code', 'post_name', 'p_number', 'companion', 'purpose',
											'qm_visit', 'fb_visit', 'er_visit', 'p_demo', 'p_demo_note', 'dvd_gd', 'dvd_gd_note', 'd_document', 'd_document_note', 'ht_visit',
											'lunch', 'other_req', 'note', 'name', 'size', 'quantity', 'card_no', 'inspection', 'inspection_note', 'training_plan', 'lecture', 'demonstration',
											'experience', 'hid_dvd'];

								const mul_values = ['d_document', 'ht_visit', 'lunch', 'inspection'];

								ids.forEach(element => {
									var value = getValuesByName(element);

									if (!mul_values.includes(element)) {
										values.push(value[0]);
									} else {
										values.push(value);
									}
								});
								return values;
							}
						}).then((result) => {
						
							if (result.isConfirmed) {
								const form_values = {};
								const keys = ['class', 'candidate1_date', 'candidate1_start', 'candidate1_end', 'candidate2_date', 'candidate2_start', 'candidate2_end', 
											'candidate3_date', 'candidate3_start', 'candidate3_end', 'pf_code', 'cust_code', 'post_name', 'p_number', 'companion', 'purpose',
											'qm_visit', 'fb_visit', 'er_visit', 'p_demo', 'p_demo_note', 'dvd_gd', 'dvd_gd_note', 'd_document', 'd_document_note', 'ht_visit',
											'lunch', 'other_req', 'note', 'name', 'size', 'quantity', 'card_no', 'inspection', 'inspection_note', 'training_plan', 'lecture', 'demonstration',
											'experience', 'hid_dvd'];

								keys.forEach((key, index) => {
									form_values[key] = result.value[index];
								});							

								var start_date = arg.startStr;
								var end_date = arg.endStr;

								// AJAX - Add to fwt_m_tr
								$.ajax({
									url: 'fwt_m_update.php',
									type: 'post',
									data: {
										request: 'add_to_fwt',
										form_values: form_values,
										start_date: start_date,
										end_date: end_date},
									dataType: 'json',
									success: function(response){
										if(response.status == 1){

											// Add event
											calendar.addEvent({
												eventid: candidate1_date,
												title: candidate2_date,
												description: candidate3_date,
												start: arg.start,
												end: arg.end,
												// allDay: arg.allDay
											}) 

											// Alert message
											Swal.fire(response.message,'','success');

										}else{
											// Alert message
											Swal.fire(response.message,'','error');
										}
										
									},
								});
								
							}
						})

						/*----------------------------------------------------------------------------------------------- */

						//官庁検索ボタンを押下する場合
						$(document).on('click', '#search_pf', function(event) {
							public_office_open(event);
						})

						/*----------------------------------------------------------------------------------------------- */

						//社員検索ボタンを押下する場合
						$(document).on('click', '#search_cus', function(event) {
							customer_open(event);
						})

						/*----------------------------------------------------------------------------------------------- */

						//種類がCHANGEされた場合
						$(document).on('change', '#class', function(event) {
							//立会検査の場合
							if (this.value == '2') {
								const element = document.getElementById('class3');
								element.classList.add('hide');
							}
							//技術研修の場合
							if (this.value == '3') {
								const element = document.getElementById('class2');
								element.classList.add('hide');
							}
						})

						/*----------------------------------------------------------------------------------------------- */

						//研修内容項目がCHANGEされた場合
						$(document).on('change', '#training_plan', function(event) {
							//選択されたOPTIONを取得する
							const selectedOption = this.options[this.selectedIndex];
							const selectedValue = selectedOption.value;

							if (selectedValue == '') {
								var lecture = '0';	//座学
								var demonstration = '0';	//実演
								var experience = '0';	//体験
								var dvd = '';	//	DVD
							} else {
								var lecture = selectedOption.dataset.lecture;	//座学
								var demonstration = selectedOption.dataset.demonstration;	//実演
								var experience = selectedOption.dataset.experience;	//体験
								var dvd = selectedOption.dataset.dvd;	//	DVD
							}	
							$('#lecture').val(lecture);
							$('#demonstration').val(demonstration);
							$('#experience').val(experience);
							$('#dvd').html(dvd);
							$('#hid_dvd').html(dvd);
							
							handleCheckbox(lecture, 'lecture');
							handleCheckbox(demonstration, 'demonstration');
							handleCheckbox(experience, 'experience');
						})

						/*----------------------------------------------------------------------------------------------- */

						//アップロードボタンを押下する場合
						$(document).on('click' ,'#upload', function(event) {
							//何の処理かを書く
							var process = "upload";
							//エラーメッセージを書く
							var msg = "アプロードします。よろしいですか？";
							//確認Dialogを呼ぶ
							openConfirmModal(msg, process);
						})

						/*----------------------------------------------------------------------------------------------- */

						//確認BOXに"はい"ボタンを押下する場合
						$(document).on('click' ,'#confirm_okBtn', function(event) {
							var process = $("#btnProcess").val();
							//アプロード処理の場合
							if (process == "upload"){
								//submitしたいボタン名をセットする
								$("#confirm_okBtn").attr("name", "submit");
								//fwt_m_attach_upload1.phpへ移動する
								uploadFile("fwt_m_attach_upload1.php");
							}
						});

						/*----------------------------------------------------------------------------------------------- */

						function handleCheckbox(val, id) {
							if (val == '1') {
								$('#' + id).attr('checked', true);
							} else {
								$('#' + id).attr('checked', false);
							}
						}

						/*----------------------------------------------------------------------------------------------- */

						function getValuesByName(name) {
							const elements = document.getElementsByName(name);
							return Array.from(elements).map(element => element.value);
						}

						/*----------------------------------------------------------------------------------------------- */

						function openConfirmModal(msg, process) {
							event.preventDefault();
							//何の処理かをセットする
							$("#btnProcess").val(process);
							//確認メッセージをセットする
							$("#confirm-message").text(msg);
							//確認Dialogを呼ぶ
							$("#confirm").modal({backdrop: false});
						}

						/*----------------------------------------------------------------------------------------------- */

						function uploadFile(url, filename) {
							event.preventDefault();
							var fwt_m_no = document.getElementById('fwt_m_no').value;
							var uploaded_file = document.getElementById('uploaded_file').files[0];

							var formData = new FormData();
							formData.append('fwt_m_no', fwt_m_no);
							formData.append('uploaded_file', uploaded_file);

							$.ajax({
								type: "POST",
								url: url,
								data: formData,
								processData: false, // Important: prevent jQuery from processing the data
								contentType: false, // Important: ensure jQuery does not add a content-type header
								success: function(response) {
									console.log(response);
									//フォームデータを保存する
									// saveFormData();
									//reload page
									// location.reload();
								},
									error: function(xhr, status, error) {
								}
							})

						}
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
	        	calendar.unselect()
	        	
	      	},
	      	// eventDrop: function (event, delta) { // Move event

	      	// 	// Event details
	      	// 	var eventid = event.event.extendedProps.eventid;
	      	// 	var newStart_date = event.event.startStr;
	      	// 	var newEnd_date = event.event.endStr;
	           	
	        //    	// AJAX request
	        //    	$.ajax({
			// 		url: 'ajaxfile.php',
			// 		type: 'post',
			// 		data: {request: 'moveEvent',eventid: eventid,start_date: newStart_date, end_date: newEnd_date},
			// 		dataType: 'json',
			// 		async: false,
			// 		success: function(response){

			// 			console.log(response);
									
			// 		}
			// 	}); 

	        // },
	      	eventClick: function(arg) { // Edit/Delete event

	      		
	      		// Event details
	      		// var eventid = arg.event._def.extendedProps.eventid;
	      		// var description = arg.event._def.extendedProps.description;
	      		// var title = arg.event._def.title;

	      		// // Alert box to edit and delete event
	      		// Swal.fire({
				//   	title: 'Edit Event',
				//   	showDenyButton: true,
				// 	showCancelButton: true,
				// 	confirmButtonText: 'Update',
				// 	denyButtonText: 'Delete',
				//   	html:
				//     '<input id="eventtitle" class="swal2-input" placeholder="Event name" style="width: 84%;" value="'+ title +'" >' +
				//     '<textarea id="eventdescription" class="swal2-input" placeholder="Event description" style="width: 84%; height: 100px;">' + description + '</textarea>',
				//   	focusConfirm: false,
				//   	preConfirm: () => {
				// 	    return [
				// 	      	document.getElementById('eventtitle').value,
				// 	      	document.getElementById('eventdescription').value
				// 	    ]
				//   	}
				// }).then((result) => {
				  
				//   	if (result.isConfirmed) { // Update
				    	
				//     	var newTitle = result.value[0];
				//     	var newDescription = result.value[1];

				//     	if(newTitle != '' && newDescription != ''){

				//     		// AJAX - Edit event
				//     		$.ajax({
				// 				url: 'ajaxfile.php',
				// 				type: 'post',
				// 				data: {request: 'editEvent',eventid: eventid,title: newTitle, description: newDescription},
				// 				dataType: 'json',
				// 				async: false,
				// 				success: function(response){

				// 					if(response.status == 1){
										
				// 						// Refetch all events
				// 						calendar.refetchEvents();

				// 						// Alert message
				// 						Swal.fire(response.message, '', 'success');
				// 					}else{

				// 						// Alert message
				// 						Swal.fire(response.message, '', 'error');
				// 					}
										
				// 				}
				// 			}); 
				//     	}
				    	
				//   	} 
				// })

				var fwt_m_no = arg.event._def.extendedProps.eventid;
				console.log(fwt_m_no);
				$.ajax({
					url: 'fwt_m_input2.php',
					type: 'POST',									
					data: {fwt_m_no: fwt_m_no},
					success: function(response) {
						// Alert box to add event
						Swal.fire({
							title: '見学、立会、研修仮予約、本予約入力',
							showCancelButton: true,
							cancelButtonText: '前の画面に戻る',
							showDenyButton: true,
							confirmButtonText: 'Update',
							denyButtonText: 'Delete',
							html: response,
							customClass: 'swal-style',
							focusConfirm: false,
							preConfirm: () => {
								const values = [];
								const ids = ['fwt_m_no', 'class', 'candidate1_date', 'candidate1_start', 'candidate1_end', 'candidate2_date', 'candidate2_start', 'candidate2_end', 
											'candidate3_date', 'candidate3_start', 'candidate3_end', 'pf_code', 'cust_code', 'post_name', 'p_number', 'companion', 'purpose',
											'qm_visit', 'fb_visit', 'er_visit', 'p_demo', 'p_demo_note', 'dvd_gd', 'dvd_gd_note', 'd_document', 'd_document_note', 'ht_visit',
											'lunch', 'other_req', 'note', 'name', 'size', 'quantity', 'card_no', 'inspection', 'inspection_note', 'training_plan', 'lecture', 'demonstration',
											'experience', 'hid_dvd'];

								const mul_values = ['d_document', 'ht_visit', 'lunch', 'inspection'];

								ids.forEach(element => {
									var value = getValuesByName(element);

									if (!mul_values.includes(element)) {
										values.push(value[0]);
									} else {
										values.push(value);
									}
								});
								return values;
							}
						}).then((result) => {
						
							if (result.isConfirmed) {
								const form_values = {};
								const keys = ['fwt_m_no', 'class', 'candidate1_date', 'candidate1_start', 'candidate1_end', 'candidate2_date', 'candidate2_start', 'candidate2_end', 
											'candidate3_date', 'candidate3_start', 'candidate3_end', 'pf_code', 'cust_code', 'post_name', 'p_number', 'companion', 'purpose',
											'qm_visit', 'fb_visit', 'er_visit', 'p_demo', 'p_demo_note', 'dvd_gd', 'dvd_gd_note', 'd_document', 'd_document_note', 'ht_visit',
											'lunch', 'other_req', 'note', 'name', 'size', 'quantity', 'card_no', 'inspection', 'inspection_note', 'training_plan', 'lecture', 'demonstration',
											'experience', 'hid_dvd'];

								keys.forEach((key, index) => {
									form_values[key] = result.value[index];
								});							

								var start_date = arg.startStr;
								var end_date = arg.endStr;

								// AJAX - Add to fwt_m_tr
								$.ajax({
									url: 'fwt_m_update.php',
									type: 'post',
									data: {
										request: 'edit_to_fwt',
										form_values: form_values,
										start_date: start_date,
										end_date: end_date},
									dataType: 'json',
									success: function(response){
										console.log(response);
										if(response.status == 1){

											// Add event
											calendar.addEvent({
												eventid: candidate1_date,
												title: candidate2_date,
												description: candidate3_date,
												start: arg.start,
												end: arg.end,
												// allDay: arg.allDay
											}) 

											// Alert message
											Swal.fire(response.message,'','success');

										}else{
											// Alert message
											Swal.fire(response.message,'','error');
										}
										
									},
								});
								
							}
						})

						/*----------------------------------------------------------------------------------------------- */

						//官庁検索ボタンを押下する場合
						$(document).on('click', '#search_pf', function(event) {
							public_office_open(event);
						})

						/*----------------------------------------------------------------------------------------------- */

						//社員検索ボタンを押下する場合
						$(document).on('click', '#search_cus', function(event) {
							customer_open(event);
						})

						/*----------------------------------------------------------------------------------------------- */

						//種類がCHANGEされた場合
						$(document).on('change', '#class', function(event) {
							//立会検査の場合
							if (this.value == '2') {
								const element = document.getElementById('class3');
								element.classList.add('hide');
							}
							//技術研修の場合
							if (this.value == '3') {
								const element = document.getElementById('class2');
								element.classList.add('hide');
							}
						})

						/*----------------------------------------------------------------------------------------------- */

						//研修内容項目がCHANGEされた場合
						$(document).on('change', '#training_plan', function(event) {
							//選択されたOPTIONを取得する
							const selectedOption = this.options[this.selectedIndex];
							const selectedValue = selectedOption.value;

							if (selectedValue == '') {
								var lecture = '0';	//座学
								var demonstration = '0';	//実演
								var experience = '0';	//体験
								var dvd = '';	//	DVD
							} else {
								var lecture = selectedOption.dataset.lecture;	//座学
								var demonstration = selectedOption.dataset.demonstration;	//実演
								var experience = selectedOption.dataset.experience;	//体験
								var dvd = selectedOption.dataset.dvd;	//	DVD
							}	
							$('#lecture').val(lecture);
							$('#demonstration').val(demonstration);
							$('#experience').val(experience);
							$('#dvd').html(dvd);
							$('#hid_dvd').html(dvd);
							
							handleCheckbox(lecture, 'lecture');
							handleCheckbox(demonstration, 'demonstration');
							handleCheckbox(experience, 'experience');
						})

						/*----------------------------------------------------------------------------------------------- */

						//アップロードボタンを押下する場合
						$(document).on('click' ,'#upload', function(event) {
							//何の処理かを書く
							var process = "upload";
							//エラーメッセージを書く
							var msg = "アプロードします。よろしいですか？";
							//確認Dialogを呼ぶ
							openConfirmModal(msg, process);
						})

						/*----------------------------------------------------------------------------------------------- */

						//確認BOXに"はい"ボタンを押下する場合
						$(document).on('click' ,'#confirm_okBtn', function(event) {
							var process = $("#btnProcess").val();
							//アプロード処理の場合
							if (process == "upload"){
								//submitしたいボタン名をセットする
								$("#confirm_okBtn").attr("name", "submit");
								//fwt_m_attach_upload1.phpへ移動する
								uploadFile("fwt_m_attach_upload1.php");
							}
						});

						/*----------------------------------------------------------------------------------------------- */

						function handleCheckbox(val, id) {
							if (val == '1') {
								$('#' + id).attr('checked', true);
							} else {
								$('#' + id).attr('checked', false);
							}
						}

						/*----------------------------------------------------------------------------------------------- */

						function getValuesByName(name) {
							const elements = document.getElementsByName(name);
							return Array.from(elements).map(element => element.value);
						}

						/*----------------------------------------------------------------------------------------------- */

						function openConfirmModal(msg, process) {
							event.preventDefault();
							//何の処理かをセットする
							$("#btnProcess").val(process);
							//確認メッセージをセットする
							$("#confirm-message").text(msg);
							//確認Dialogを呼ぶ
							$("#confirm").modal({backdrop: false});
						}

						/*----------------------------------------------------------------------------------------------- */

						function uploadFile(url, filename) {
							event.preventDefault();
							var fwt_m_no = document.getElementById('fwt_m_no').value;
							var uploaded_file = document.getElementById('uploaded_file').files[0];

							var formData = new FormData();
							formData.append('fwt_m_no', fwt_m_no);
							formData.append('uploaded_file', uploaded_file);

							$.ajax({
								type: "POST",
								url: url,
								data: formData,
								processData: false, // Important: prevent jQuery from processing the data
								contentType: false, // Important: ensure jQuery does not add a content-type header
								success: function(response) {
									console.log(response);
									//フォームデータを保存する
									// saveFormData();
									//reload page
									// location.reload();
								},
									error: function(xhr, status, error) {
								}
							})

						}
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
	      		
	      	}

			
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
</style>