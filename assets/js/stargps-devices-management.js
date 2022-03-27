jQuery(function($){

if ( starGPSDevicesManagementParams.is_admin){
		$('.date_picker').datepicker({
			dateFormat : 'yy-mm-dd'
		});
//        console.log(starGPSDevicesManagementParams);
		$('#stargps_device_management_date_recharge').click(function (e) {
                    e.preventDefault();
			$.ajax({
				url: starGPSDevicesManagementParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_devices_date_recharge' ,'date_recharge': $('input[name=DateOfRecharge]').val(), 'target_name': $('#target_name').val(), 'type_device': $('#type_device').val(), 'app': $('#app').val(), 'imei': $('#imei').val(), 'stargps_device_management_nonce': starGPSDevicesManagementParams.stargps_device_management_nonce },
				beforeSend: function () {
                                    $(".stargps-spinner").addClass("stargps-is-active").show();
                                    $('div.resultDevices').html('');
				},
				success: function (data) {         
					if (data.error) {
						
						alert(data.error.msg);
					} else {
						
                                                $('div.resultDevices').html( data );
						//console.log(data);
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    $(".stargps-spinner").removeClass("stargps-is-active").hide();
				}
			});
		}); 
                
                $('.run_wstargpsma').on('click',function(){
                   // <button data-type="<?php echo $value['type'] ?>" data-url="<?php echo $value['url'] ?>" data-password="<?php echo $value['password'] ?>" data-email="<?php echo $value['email'] ?>" data-api-hash="<?php echo $value['token'] ?>"  type="button" title="Lancer"  class="run_wstargpsma dashicons dashicons-controls-play"></button>   
                   // var api_data =   'type':  $(this).data('type') ,  'url': + $(this).data('url') + ',' + 'email:' + $(this).data('email') + ',' + 'password:' + $(this).data('password') + ',' + 'api-hash:' + $(this).data('api-hash');  
//                    console.log(api_data);
//                    return;
			$.ajax({
				url: starGPSDevicesManagementParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_devices_run_wstargpsma' , 'type':  $(this).data('type'), 'app': $(this).data('app') , 'url': $(this).data('url') , 'email': $(this).data('email') , 'password': $(this).data('password') , 'api-hash' : $(this).data('api-hash')  ,  'stargps_device_management_nonce': starGPSDevicesManagementParams.stargps_device_management_nonce },
				beforeSend: function () {
                                    $(this).prop( "disabled", true );
                                    $('.result').html('');
                                    $(".stargps-spinner").addClass("stargps-is-active").show();
                                   
				},
				success: function (data) {         
					if (data.error) {
						
						alert(data.error.msg);
					} else {
						
                                                $('.result').html(data);
						console.log(data);
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    $(".stargps-spinner").removeClass("stargps-is-active").hide();
                                    $(this).prop( "disabled", false );
				}
			});                   
                });
                $('.result').on('click','.update-sim-no',function(){
                    console.log($(this).data('id'));
			$.ajax({
				url: starGPSDevicesManagementParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_update_sim_no' , 'device_id': $(this).data('id'), 'new_sim_no': $(this).data('new-sim-no'), 'stargps_device_management_nonce': starGPSDevicesManagementParams.stargps_device_management_nonce },
				beforeSend: function () {
                                    $(this).prop( "disabled", true );
                                    //$('.result').html('');
                                   $(this).siblings("span.spinner-small").addClass("stargps-is-active").show();
                                   
				},
				success: function (data) {         
					if (data.error) {
						
						alert(data.error.msg);
					} else {
						
                                                //$('.result').html(data);
						console.log(data);
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    $(this).siblings("span.spinner-small").removeClass("stargps-is-active").addClass("stargps-is-done").show();
                                    $(this).siblings("span.spinner-small").html('Updated!');
                                    
				}
			});                   
                }); 
                
                $('div.resultDevices').on('click', '.select-all-recharge',function(){
                    
                    //console.log('selec all');
                    $(this).toggleClass('clicked');
                    if ($(this).hasClass('clicked')){
                    $("button.send-sim-recharge").prop( "disabled", true );    
                    }else{
                        $("button.send-sim-recharge").prop( "disabled", false );
                    }
                    
                    var devices = []
//                    var sim_no = [];
//                    var devices_id  =[];
                    $.each($("button.send-sim-recharge"), function () {
                        //sim_no.push($(this).data('sim-no'));
                        devices[$(this).data('id')] = $(this).data('sim-no') ;
                        
                    });                    
                    console.log(devices);
//			$.ajax({
//				url: starGPSDevicesManagementParams.admin_ajax,
//				type: "POST",
//				context: this,
//				data: { 'action': 'stargps_device_management_send_recharge_sim' , 'nmsms': devices, 'stargps_device_management_nonce': starGPSDevicesManagementParams.stargps_device_management_nonce },
//				beforeSend: function () {
//                                    $(this).prop( "disabled", true );
//                                    //$('.result').html('');
//                                   $(this).siblings("span.spinner-small").addClass("stargps-is-active").show();
//                                   
//				},
//				success: function (data) {         
//					if (data.error) {
//						
//						alert(data.error.msg);
//					} else {
//						
//                                                //$('.result').html(data);
//						console.log(data);
//					}
//				},
//				error: function (response, textStatus, errorThrown ) {
//					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
//				},
//				complete: function () {
//                                    
//                                    $(this).siblings("span.spinner-small").removeClass("stargps-is-active").addClass("stargps-is-done").show();
//                                    $(this).siblings("span.spinner-small").html('sent!');
//                                    
//				}
//			});                    
                    
                });

                $('div.resultDevices').on('click','.send-sim-recharge',function(){
                    console.log($(this).data('id'));
			$.ajax({
				url: starGPSDevicesManagementParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_send_recharge_sim' , 'device_id': $(this).data('id'), 'nmsms': $(this).data('sim-no'), 'stargps_device_management_nonce': starGPSDevicesManagementParams.stargps_device_management_nonce },
				beforeSend: function () {
                                    $(this).prop( "disabled", true );
                                    //$('.result').html('');
                                   $(this).siblings("span.spinner-small").addClass("stargps-is-active").show();
                                   
				},
				success: function (data) {         
					if (data.error) {
						
						alert(data.error.msg);
					} else {
						
                                                //$('.result').html(data);
						console.log(data);
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    $(this).siblings("span.spinner-small").removeClass("stargps-is-active").addClass("stargps-is-done").show();
                                    $(this).siblings("span.spinner-small").html('sent!');
                                    
				}
			});                   
                }); 
                
                 $('.connect-api').on('click',function(){
                    
                    var type_gps = $(this).data('type');
                    var url = $('#url-'+type_gps).val();
                    var email = $('#email-'+type_gps).val();
                    var password = $('#password-'+type_gps).val();
//                    console.log(type_gps + '- ' + email + ' - ' + password );
                
                   
			$.ajax({
				url: starGPSDevicesManagementParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_devices_connect_api' , 'url': url , 'type_gps': type_gps, 'email': email , 'password':password, 'stargps_device_management_nonce': starGPSDevicesManagementParams.stargps_device_management_nonce },
				beforeSend: function () {
                                    $(this).prop( "disabled", true );
                                    $('.responseAPI').html('');
                                    $(".responseAPI-wrapper .stargps-spinner").addClass("stargps-is-active").show();
                                   
				},
				success: function (data) {         
					if (data.error) {
						
						alert(data.error.msg);
					} else {
						
                                                $('.responseAPI').html(data);
						//console.log(data);
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    $(".responseAPI-wrapper .stargps-spinner").removeClass("stargps-is-active").hide();
                                    $(this).prop( "disabled", false );
				}
			});                   
                });               
  
        $('#run_recharge_manuelle').on('click',function(){
                    
			$.ajax({
				url: starGPSDevicesManagementParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_devices_recharge_manuelle' , 'nmsms' : $('#recharge_manuelle').val(), 'stargps_device_management_nonce': starGPSDevicesManagementParams.stargps_device_management_nonce },
				beforeSend: function () {
                                    $(this).prop( "disabled", true );
                                    $('.result_manuelle').html('');
                                    $(".stargps-spinner").addClass("stargps-is-active").show();
                                   
				},
				success: function (data) {         
					if (data.error) {
						
						alert(data.error.msg);
					} else {
						
                                                $('.result_manuelle').html(data);
						//console.log(data);
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    $(".stargps-spinner").removeClass("stargps-is-active").hide();
                                    $(this).prop( "disabled", false );
				}
			});                   
                });                
        
    }  
});

if ( starGPSDevicesManagementParams.is_admin ) {
	/*Tab options*/

	jQuery.skeletabs.setDefaults({
		keyboard: false
	});

}

