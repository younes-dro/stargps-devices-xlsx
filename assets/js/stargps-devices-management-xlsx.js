(function($){
    
    if ( starGPSDevicesManagementXlsxParams.is_admin){
        		$('.date_picker').datepicker({
			dateFormat : 'dd-mm-yy'
		});
        
//        console.log(starGPSDevicesManagementXlsxParams);
    $('body').on('change', '#importxlsx', function() {
        
        $this = $(this);
        file_data = $(this).prop('files')[0];
        form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('action', 'upload_xlsx');
        form_data.append('security', starGPSDevicesManagementXlsxParams.security);
  
        $.ajax({
            url: starGPSDevicesManagementXlsxParams.admin_ajax,
            type: 'POST',
            contentType: false,
            processData: false,
            data: form_data,
            beforeSend: function() {
                $(".stargps-spinner-xlsx").addClass("stargps-is-active").show();
                $('div#result_upload_xlsx').html('');
            },            
            success: function (response) {
                $this.val('');
                var result = $.parseJSON(response);
                if( result.re === 'f' ){
                    var c =  confirm("Fichier existe . confirmer l'upload ?");
                    if (c === false ){
                        cancel_upload( result.file_tmp_name );
                    }else{
                        confirm_upload( result.file_tmp_name );
                    }   
                    return;
                }
                $this.val('');
                if( result.re === '1' ){
                    $('div#result_upload_xlsx').html('<div class="notice notice-success is-dismissible"><p>Fichier uploadé avec succès</p></div>');
                    refresh_list_file_xlsx();
                }else if( result.re === '0' ){
                     $('div#result_upload_xlsx').html('<div class="notice notice-error is-dismissible"><p>Server Error</p></div>');
                }else{
                    // Ext not supported
                    $('div#result_upload_xlsx').html( '<div class="notice notice-warning is-dismissible"><p>l\'extension du fichier non acceptée</p></div>' );
                }
                
            },
            error: function (response, textStatus, errorThrown ) {
                console.log( textStatus + " :  " + response.status + " : " + errorThrown );
            },
            complete: function () {
                 $(".stargps-spinner-xlsx").removeClass("stargps-is-active").hide();
            }            
        });
    });
    function cancel_upload (file_name){
        $.ajax({
            url: starGPSDevicesManagementXlsxParams.admin_ajax,
            type: 'POST',
            data: {'action' : 'cancel_upload', 'file_name': file_name },
            beforeSend: function() {
                $(".stargps-spinner-xlsx").addClass("stargps-is-active").show();
                $('div#result_upload_xlsx').html('');
            },            
            success: function (response) {
                var result = $.parseJSON(response);
                if( result.re === '1' ){
                    $('div#result_upload_xlsx').html('<div class="notice notice-success is-dismissible"><p>Uploade annulé</p></div>');
                    refresh_list_file_xlsx();
                }else if( result.re === '0' ){
                     $('div#result_upload_xlsx').html('<div class="notice notice-error is-dismissible"><p>Server Error</p></div>');
                }else{
                    // Server Error
                    $('div#result_upload_xlsx').html( '<div class="notice notice-warning is-dismissible"><p>Server Error</p></div>' );
                }
                
            },
            error: function (response, textStatus, errorThrown ) {
                console.log( textStatus + " :  " + response.status + " : " + errorThrown );
            },
            complete: function () {
                 $(".stargps-spinner-xlsx").removeClass("stargps-is-active").hide();
            }            
        });        
    }
    function confirm_upload (file_name) {
        $.ajax({
            url: starGPSDevicesManagementXlsxParams.admin_ajax,
            type: 'POST',
            data: {'action' : 'confirm_upload', 'file_name': file_name },
            beforeSend: function() {
                $(".stargps-spinner-xlsx").addClass("stargps-is-active").show();
                $('div#result_upload_xlsx').html('');
            },            
            success: function (response) {
                var result = $.parseJSON(response);
                if( result.re === '1' ){
                    $('div#result_upload_xlsx').html('<div class="notice notice-success is-dismissible"><p>Fichier uploadé avec succès</p></div>');
                    refresh_list_file_xlsx();
                }else if( result.re === '0' ){
                     $('div#result_upload_xlsx').html('<div class="notice notice-error is-dismissible"><p>Server Error</p></div>');
                }else{
                    // Server Error
                    $('div#result_upload_xlsx').html( '<div class="notice notice-warning is-dismissible"><p>Server Error</p></div>' );
                }
                
            },
            error: function (response, textStatus, errorThrown ) {
                console.log( textStatus + " :  " + response.status + " : " + errorThrown );
            },
            complete: function () {
                 $(".stargps-spinner-xlsx").removeClass("stargps-is-active").hide();
            }            
        });
    }
    function refresh_list_file_xlsx(){
        $.ajax({
            url: starGPSDevicesManagementXlsxParams.admin_ajax,
            type: 'POST',
            data: {'action' : 'refresh_xlsx'},
            beforeSend: function() {
               // $(".stargps-spinner-xlsx").addClass("stargps-is-active").show();
                $('div#list_file_xlsx').html('');
            },            
            success: function (response) {
                
                $('div#list_file_xlsx').html(response);
                
            },
            error: function (response, textStatus, errorThrown ) {
                console.log( textStatus + " :  " + response.status + " : " + errorThrown );
            },
            complete: function () {
                 //$(".stargps-spinner-xlsx").removeClass("stargps-is-active").hide();
            }            
        });        
    }
    
        $('div#list_file_xlsx').on('click','.delete-xlxs',function(){
            
            var $button = $(this);
            var c =  confirm("confirmer la suppression ?");
            if (c === false) return;
            
            $.ajax({
                url: starGPSDevicesManagementXlsxParams.admin_ajax,
                type: 'POST',
                context: this,
                data: {'action' : 'delete_xlxs', 'file_name': $(this).data('name') },
                beforeSend: function() {
                    $(this).prop( "disabled", true );
                    $(this).siblings("span.spinner-small").addClass("stargps-is-active").show();
                    $(this).parent().parent().css('opacity', '0.3');
                },            
                success: function (data) {
                    //console.log(data);
                    if( data === 'ok'){
                        $(this).parent().parent().fadeOut();
                    }else{
                        console.log('Error');
                        $(this).parent().parent().css('opacity', '1');
                        $(this).siblings("span.spinner-small").removeClass("stargps-is-active").addClass("stargps-is-done").show();
                        $(this).parent().parent().css('background-color', '#f00');
                    }
                        
                },
                error: function (response, textStatus, errorThrown ) {
                     $('div#result_import_xlsx').html( textStatus + " :  " + response.status + " : " + errorThrown );
                },
                complete: function () {
                    $(this).siblings("span.spinner-small").removeClass("stargps-is-active").addClass("stargps-is-done").show();                                 
                }            
            });            
        }); 
        $('div#list_file_xlsx').on('click','.import-xlxs',function(){
            
           // var $button = $(this);
            $.ajax({
                url: starGPSDevicesManagementXlsxParams.admin_ajax,
                type: 'POST',
                context: this,
                data: {'action' : 'import_xlsx', 'file_name': $(this).data('name') },
                beforeSend: function() {
                    $(this).prop( "disabled", true );
                    $('div#result_import_xlsx').html('Loading ....');
                },            
                success: function (response) {
//                    console.log(response);
                    $('div#result_import_xlsx').html(response);

                },
                error: function (response, textStatus, errorThrown ) {
                     $('div#result_import_xlsx').html( textStatus + " :  " + response.status + " : " + errorThrown );
//                    console.log( textStatus + " :  " + response.status + " : " + errorThrown );
                },
                complete: function () {
                    $(this).prop( "disabled", false );
                }            
            });            
        });        
    
		$('#stargps_device_management_date_recharge').click(function (e) {
                    e.preventDefault();
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_devices_date_recharge_xlsx' ,'date_recharge': $('input[name=DateOfRecharge]').val(),'next_recharge': $('input[name=NextOfRecharge]').val(), 'customer_name': $('#customer_name').val(), 'type_device': $('#type_device').val(), 'app': $('#app').val(), 'imei': $('#imei').val(), 'tel_clt': $('#tel_clt').val(), 'sim_no': $('#sim_no').val(), 'stargps_device_management_nonce': starGPSDevicesManagementXlsxParams.stargps_device_management_nonce },
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
		$('div.resultDevices , div.resultDevices80').on('click','.send-sim-recharge',function(){
                   // console.log($(this).data('sim-no'));
                    
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_send_recharge_sim_xlsx' , 'device_id': $(this).data('id'), 'table': $(this).data('table'), 'nmsms': $(this).data('sim-no'), 'stargps_device_management_nonce': starGPSDevicesManagementXlsxParams.stargps_device_management_nonce },
				beforeSend: function () {
                                    $(this).prop( "disabled", true );
                                    //$('.result').html('');
                                   $(this).siblings("span.spinner-small").addClass("stargps-is-active").show();
                                   
				},
				success: function (data) {
                                    var result = $.parseJSON(data);  
                                    if( result.re === '1' ){
					var c =  confirm("La date Next Recharge est moins de 80 jours . confirmer ou annuler ?");
					if (c === false ){
						$(this).siblings("span.spinner-small").removeClass("stargps-is-active");                                                
                                                $(this).prop( "disabled", false );                                       
						return;
					}else{
						confirm_send_recharge( $(this),  $(this).data('sim-no') );
                                                return;
					}                                        
                                        return;
                                    }
					$(this).siblings("span.spinner-small").removeClass("stargps-is-active").addClass("stargps-is-done").show();
					$(this).siblings("span.spinner-small").html('sent!');
					$(this).siblings("button.send-sim-valider").addClass("stargps-is-active").show();
					$(this).siblings("button.send-sim-reload").addClass("stargps-is-active").show();                                     

				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function ( data) {
                                 
                                    
				}
			}); 
                        
		}); 
                function confirm_send_recharge(obj, sim_no){
                    
                    
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_confirm_send_recharge_sim_xlsx' ,  'nmsms': sim_no, 'stargps_device_management_nonce': starGPSDevicesManagementXlsxParams.stargps_device_management_nonce },
				beforeSend: function () {
                                    obj.prop( "disabled", true );
                                    //$('.result').html('');
                                   obj.siblings("span.spinner-small").addClass("stargps-is-active").show();
                                   
				},
				success: function (data) { 
                                    var result = $.parseJSON(data);
                                    console.log(result);
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function ( data) {
					obj.siblings("span.spinner-small").removeClass("stargps-is-active").addClass("stargps-is-done").show();
					obj.siblings("span.spinner-small").html('sent!');
					obj.siblings("button.send-sim-valider").addClass("stargps-is-active").show();
					obj.siblings("button.send-sim-reload").addClass("stargps-is-active").show();                                   
                                    
				}
			});                     
                    
                }
		$('div.resultDevices, div.resultDevices80').on('click','button.send-sim-valider',function(){
                   // console.log($(this).data('sim-no'));
                    
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_send_valider_recharge_sim_xlsx' , 'device_id': $(this).data('id'), 'table': $(this).data('table'), 'nmsms': $(this).data('sim-no'), 'stargps_device_management_nonce': starGPSDevicesManagementXlsxParams.stargps_device_management_nonce },
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
					$(this).siblings("span.spinner-small").html('Valider!');                                   
                                    
				}
			}); 
                        
                }); 
                
		$('div.resultDevices, div.resultDevices80').on('click','button.send-sim-reload',function(){
//                    console.log();
                    $(this).siblings("button.send-sim-recharge").prop( "disabled", false );
                    $(this).siblings("button.send-sim-valider").prop( "disabled", false );
		});
                
                $('div.remove-table').on('click', '.delete-table', function(){
                   
                    var c =  confirm("confirmer la suppression ?");
                    if (c === false) return;
    
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
                                context: this,
				data: { 'action': 'stargps_device_management_remove_table' , 'table': $(this).data('name')},
				beforeSend: function () {
                                    $(this).prop( "disabled", true );
                                    $(this).siblings("span.spinner-small").addClass("stargps-is-active").show();
				},
				success: function (data) {         
					if (data.error) {
						
						alert(data.error.msg);
					} else {
                                            if( data === 'ok'){
                                                $('#table_'+$(this).data('name')).fadeOut();
                                            }
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
					$(this).siblings("span.spinner-small").removeClass("stargps-is-active").addClass("stargps-is-done").show();                                 
				}
			});                    
                    
                });
                
		$('#stargps_device_management_date_recharge_80_jours').click(function (e) {
                    e.preventDefault();
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_date_recharge_80_jours' , 'app': $('#app_80').val(), 'recharge_80_j' : '1'},
				beforeSend: function () {
                                    $("div.listDevices80 span.stargps-spinner").addClass("stargps-is-active").show();
                                    $('div.resultDevices80').html('');
				},
				success: function (data) {         
					if (data.error) {
						
						alert(data.error.msg);
					} else {
						
                                                $('div.resultDevices80').html( data );
						//console.log(data);
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    $("div.listDevices80 span.stargps-spinner").removeClass("stargps-is-active").hide();
				}
			});
		});
        $('#run_recharge_manuelle').on('click',function(){
                    //console.log($('#recharge_manuelle').val());
                    //return;
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_devices_recharge_manuelle_xlsx' , 'nmsms' : $('#recharge_manuelle').val(), 'stargps_device_management_nonce': starGPSDevicesManagementXlsxParams.stargps_device_management_nonce },
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
                $('span.generate-form-rows').on('click', function(){
                    var app = $('#app_new_devices').val();
                    var num_rows = $('#number_rows').val();
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'generate_form_rows' , 'app' : app, 'num_rows' : num_rows, 'stargps_device_management_nonce': starGPSDevicesManagementXlsxParams.stargps_device_management_nonce },
				beforeSend: function () {
                                    $(this).prop( "display", "none" );
                                    $('div.resultNewRows').html('');
                                    $("div.newRows span.stargps-spinner").addClass("stargps-is-active").show();
                                   
				},
				success: function (data) {  
                                    //console.log(data);
                                    //return;
					if (data.error) {
						
						alert(data.error.msg);
					} else {
						
                                                $('.resultNewRows').html(data);
						//console.log(data);
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    $("div.newRows span.stargps-spinner").removeClass("stargps-is-active").hide();
                                    $(this).prop( "display", 'inline-block' );
				}
			});                   
                });                    
                $('div.newRows').on( 'click', 'a#add_new_devices', function(e){
                    e.preventDefault();
                    //$(this).css('cursor','progress');
                    $(this).css('display','none');
                    var forms = $('form[name="new_devices"]');
                    
                    forms.each(function(selindex) {
                        
                        //console.log($(this).serializeArray());
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: {'action': 'add_new_devices', 'new_devices': $(this).serializeArray()}, 
				beforeSend: function () {
                                    //$(this).prop( "disabled", true );
                                    $(this).find("span.stargps-spinner").addClass("stargps-is-active").show();
                                   
				},
				success: function (data) {
                                    //console.log(data);
                                    //return;
                                    var result = $.parseJSON(data);
					if (result.re === 'yes') {
						$(this).find("span.stargps-spinner").removeClass("stargps-is-active stargps-spinner").addClass("dashicons dashicons-saved");
                                                return;
					}
                                        if(result.re === 'no'){
                                            $(this).find("span.stargps-spinner").removeClass("stargps-is-active stargps-spinner").addClass("dashicons dashicons-dismiss");
                                            return;
                                        }
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
				}
			});                        
                    });                   
                });
		$('#stargps_device_management_date_recharge_relancer').click(function (e) {
                    e.preventDefault();
                    //console.log('relancer');
                    //return;
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_relancer' , 'app': $('#app_relancer').val(), 'mois' : $('#relancer').val()},
				beforeSend: function () {
                                    $("div.listDevicesRelancer span.stargps-spinner").addClass("stargps-is-active").show();
                                    $('div.resultDevicesRelancer').html('');
				},
				success: function (data) {         
					if (data.error) {
						
						alert(data.error.msg);
					} else {
						
                                                $('div.resultDevicesRelancer').html( data );
						//console.log(data);
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    $("div.listDevicesRelancer span.stargps-spinner").removeClass("stargps-is-active").hide();
				}
			});
		});                
                
    }
    $.skeletabs.setDefaults({
        keyboard: false,
    }); 
})(jQuery);


