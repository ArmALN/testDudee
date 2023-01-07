<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Test Dudee</title>

	<script type="text/javascript"src="asset/jquery/dist/jquery.min.js"></script>
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="bg-gradient-primary">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-sm my-5">
					<div class="card-header">
						<h3>Laundromat Company</h3>
					</div>
                    <div class="card-body ">
                        <div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="">Input your LineNotify Token</label>
									<input type="text" class="form-control" placeholder="Your LineNotify Token" id="line_token">
								</div>
							</div>
						</div>
                        <div class="row" id="div_wash">

							
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
<!-- <body class="bg-gradient-primary">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body> -->
</html>

<script>
	$(document).ready(function () {
		all_click();
		
		add_washing_machine();
	});

	function all_click() {  
		// $('#add').click(function (e) { 
		// 	e.preventDefault();
			
		// 	// $('#tbody_washing').find('tr').each({ 
		// 	// 	// $($this).find(class).text('number');
		// 	// });
			
		// 	add_washing_machine();
		// });

	}

	function timerstart(i) {  
		var timer2 = "1:20";
		var status = 'washing';
		var interval = setInterval(function() {
			var timer = timer2.split(':');
			//by parsing integer, I avoid all extra string processing
			var minutes = parseInt(timer[0], 10);
			var seconds = parseInt(timer[1], 10);
			--seconds;
			if (minutes == 0 && seconds == -1) {
			
				status = 'success';
			}else if(minutes == 1 && seconds == -1){
				console.log('เกือบเสร็จ');
				line_notify(i);
			}

			minutes = (seconds < 0) ? --minutes : minutes;
			if (minutes < 0) clearInterval(interval);
			seconds = (seconds < 0) ? 59 : seconds;
			seconds = (seconds < 10) ? '0' + seconds : seconds;
			//minutes = (minutes < 10) ?  minutes : minutes;

			if (status == 'success') {
				washing_success(i);
			}else{
				$('#countdown'+i).html(minutes + ':' + seconds);
			}
			timer2 = minutes + ':' + seconds;
			// console.log(timer2);
			
		}, 1000);
	}

	function add_washing_machine() {  

		for (let i = 1; i < 7; i++) {
			$('#div_wash').append(
				'<div class="col-xl-4 col-lg-6" style="padding:3px;">'+
					'<div class="card">'+
						'<div class="card-header">'+
							'Washing Machine Number '+i+
						'</div>'+
						'<div class="card-body">'+
							'<div class="row">'+
								'<div class="col-md-12">'+
									'<div class="form-group">'+
										'<button class="btn btn-block btn-primary btn-sm" id="put_coin'+i+'">10฿</button>'+
									'</div>'+
								'</div>'+
								'<div class="col-md-12">'+
									'<div class="form-group">'+
										'<small>put your coin 30฿ for washing</small>'+
									'</div>'+
								'</div>'+
								'<div class="col-md-12">'+
									'<div class="form-group">'+
										'<span>money <span  id="coin_qty'+i+'">0</span> ฿</span>'+
									'</div>'+
								'</div>'+
								'<div class="col-md-12" id="div_satus'+i+'">'+
									'<span class="bg-secondary" ><small style="color:#FFF">Empty</small></span>'+
								'</div>'+
								'<div class="col-md-12">'+
									'<p id="countdown'+i+'">0:00</p>'+
								'</div>'+
							'</div>'+
						'</div>'+
						'<div class="card-footer" id="div_start'+i+'">'+
						'</div>'+
					'</div>'+
				'</div>'
			);

			$('#div_start'+i).append(
				'<div class="form-group">'+
					'<button class="btn btn-success btn-sm" id="start_washing'+i+'">start washing</button>'+
				'</div>'
			);

			if ($('#coin_qty'+i).text() != '30') {
				$('#start_washing'+i).attr('disabled', true);
			}else{
				$('#start_washing'+i).attr('disabled', false);
			}

			$('#put_coin'+i).click(function (e) { 
				e.preventDefault();
				cal_coin(i);
			});

			$('#start_washing'+i).click(function (e) { 
				e.preventDefault();

				var token_status = check_line_token();

				$('#line_token').addClass('is-warning');

				if (token_status == 'not_start') {

				}else{
					timerstart(i);
					$('#start_washing'+i).attr('disabled', true);

					$('#div_satus'+i).empty();
					$('#div_satus'+i).append(
						'<span class="bg-warning" ><small style="color:#000">Waiting...</small></span>'
					);
				}

			});
		}
	}
	
	function check_line_token() {  
		$('#line_token').css('border','');

		if ($('#line_token').val() == '') {
			$("#line_token").css('border','rgb(243, 156, 18) 2px solid');
			return  'not_start';
		}else{
			return  'start';
		}
	}

	function cal_coin(i) {  
		var coin = parseInt($('#coin_qty'+i).text());
		coin = 10 + coin;
		$('#coin_qty'+i).text(coin)

		if ($('#coin_qty'+i).text() < 30) {
			$('#start_washing'+i).attr('disabled', true);
			$('#put_coin'+i).attr('disabled', false);
		}else{
			$('#start_washing'+i).attr('disabled', false);
			$('#put_coin'+i).attr('disabled', true);
		}
	}

	function washing_success(i) {  
		$('#start_washing'+i).attr('disabled', true);
		$('#put_coin'+i).attr('disabled', false);

		$('#coin_qty'+i).text(0)

		$('#div_satus'+i).empty();
		$('#div_satus'+i).append(
			'<span class="bg-secondary" ><small style="color:#FFF">Empty</small></span>'
		);
	}

	function line_notify(i) {  
		$.ajax({
			type: "post",
			url: "<?= site_url('Main/line_notify')?>",
			data: {
				line_token : '9PcsALwysGeWlDrzVMk12kancDFQM0GXhRLofFiGCHg',
				washing_machine : 'Washing_Machine Number'+i
			},
			dataType: "json",
			success: function (data) {
				console.log('less 1 minute');
			}
		});
	}

</script>
