<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
//   include("header1.php");
?>
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
	      	select: function(arg) { // Create Event

				//Fetch HTML content using ajax
				$.ajax({
					url: 'fwt_m_input3_calendar.php',
					type: 'POST',
					data: $('#input2').serialize(),
					success: function(response) {
						// Alert box to add event
						function openSwalWithContent(response) {
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

									ids.forEach(element => {
										var value = getValuesByName(element);
										values.push(value);
									});

									let errMsg = '';
									errMsg = checkValidation();

									if (errMsg !== '') {
										Swal.showValidationMessage(errMsg);
									}

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
										url: 'fwt_m_update_calendar.php',
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
													eventid: form_values['fwt_m_no'],
													title: form_values['class'],
													description: form_values['status'],
													start: arg.start,
													end: arg.end,
													allDay: arg.allDay
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
							const element1 = document.getElementById('class1');
							const element2 = document.getElementById('class2');
							const element3 = document.getElementById('class3');
							
							//立会検査の場合
							if (this.value == '2') {
								element3.classList.add('hide');
								element2.classList.remove('hide');
								element1.classList.remove('hide');
							}
							//技術研修の場合
							else if (this.value == '3') {
								element1.classList.add('hide');
								element2.classList.add('hide');
								element3.classList.remove('hide');
							} else {
								element1.classList.remove('hide');
								element2.classList.add('hide');
								element3.classList.add('hide');
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
							$('#hid_dvd').val(dvd);
							
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
								$("#confirm_okBtn").attr("name", "upload");
								//fwt_m_attach_upload1.phpへ移動する
								uploadFile("fwt_m_attach_upload1.php", response);
							}
						});

						/*----------------------------------------------------------------------------------------------- */
						$(document).ready(function() {
							console.log('loading');
  						});

						const formData = JSON.parse(localStorage.getItem('input2'));

						if (formData) {
							var myForm = document.getElementById('input2');

							Object.keys(formData).forEach(key => {
								const exceptId = ['uploaded_file'];
								if (!exceptId.includes(key)) {
								myForm.elements[key].value = formData[key];
								}
							})

							//フォームにセット後、クリアする
							localStorage.removeItem('input2');
						}

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
							let values = '';
							let inspections = [];

							elements.forEach(element => {
								if (element.type === 'radio') {
									if (element.checked) {
										values = element.value;
									}
								} else if (element.type === 'checkbox') {
									if (element.name == 'inspection') {
										if (element.checked) {
											inspections.push(element.value);
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
							if (inspections.length > 0) {
								values = inspections.join(',');
							}
							return values;
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

						function uploadFile(url, response) {
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
									//フォームデータを保存する
        							saveFormData();

									$.ajax({
										url: 'fwt_m_input3_calendar.php',
										type: 'POST',									
										success: function(response) {
											openSwalWithContent(response);
										}
									});
								},
									error: function(xhr, status, error) {
								}
							})

						}

						function saveFormData() {
							var myForm = document.getElementById('input2');
							const formData = new FormData(myForm);
							const jsonData = JSON.stringify(Object.fromEntries(formData));
							localStorage.setItem('input2', jsonData);
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
	        	
	      	},
	      	
	      	eventClick: function(arg) { // Edit/Delete event
				var fwt_m_no = arg.event._def.extendedProps.eventid;
				$.ajax({
					url: 'fwt_m_input3_calendar.php',
					type: 'POST',									
					data: {fwt_m_no: fwt_m_no},
					success: function(response) {
						// Alert box to add event
						Swal.fire({
							title: '見学、立会、研修仮予約、本予約入力',
							showCancelButton: true,
							cancelButtonText: '前の画面に戻る',
							confirmButtonText: '更新',
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

								ids.forEach(element => {
									var value = getValuesByName(element);
									values.push(value);
								});

								let errMsg = '';
								errMsg = checkValidation();

								if (errMsg !== '') {
									Swal.showValidationMessage(errMsg);
								}

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
									url: 'fwt_m_update_calendar.php',
									type: 'post',
									data: {
										request: 'edit_to_fwt',
										form_values: form_values,
										start_date: start_date,
										end_date: end_date},
									dataType: 'json',
									success: function(response){
										if(response.status == 1){
											// Refetch all events
											calendar.refetchEvents();

											// Alert message
											Swal.fire(response.message,'','success');

										}else{
											// Alert message
											Swal.fire(response.message,'','error');
										}
							
									},
									error: function(xhr, status, error) {
										// Handle errors
										console.log(error);
										// console.error('Error fetching HTML content:', error);
										Swal.fire({
											title: 'Error',
											text: 'Failed to load content.',
											icon: 'error'
										});
									}
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
						var class_val = $('#class').val();
						handleHideShow(class_val);

						//種類がCHANGEされた場合
						$(document).on('change', '#class', function(event) {
							handleHideShow(this.value);
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
							$('#hid_dvd').val(dvd);
							
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
								$("#confirm_okBtn").attr("name", "upload");
								//fwt_m_attach_upload1.phpへ移動する
								uploadFile("fwt_m_attach_upload1.php");
								$('#confirm_okBtn').attr('data-dismiss', 'modal');
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

						function handleHideShow(class_val) {
							const element1 = document.getElementById('class1');
							const element2 = document.getElementById('class2');
							const element3 = document.getElementById('class3');
							
							//立会検査の場合
							if (class_val == '2') {
								element3.classList.add('hide');
								element2.classList.remove('hide');
								element1.classList.remove('hide');
							}
							//技術研修の場合
							else if (class_val == '3') {
								element1.classList.add('hide');
								element2.classList.add('hide');
								element3.classList.remove('hide');
							} else {
								element1.classList.remove('hide');
								element2.classList.add('hide');
								element3.classList.add('hide');
							}
						}

						/*----------------------------------------------------------------------------------------------- */

						function getValuesByName(name) {
							const elements = document.getElementsByName(name);
							let values = '';
							let inspections = [];

							elements.forEach(element => {
								if (element.type === 'radio') {
									if (element.checked) {
										values = element.value;
									}
								} else if (element.type === 'checkbox') {
									if (element.name == 'inspection') {
										if (element.checked) {
											inspections.push(element.value);
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
							if (inspections.length > 0) {
								values = inspections.join(',');
							}
							return values;
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