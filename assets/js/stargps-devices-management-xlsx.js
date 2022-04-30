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
				data: { 'action': 'stargps_device_management_devices_date_recharge_xlsx' ,'date_recharge': $('input[name=DateOfRecharge]').val(),'next_recharge': $('input[name=NextOfRecharge]').val(), 'expiry_date': $('input[name=ExpiryDate]').val(), 'customer_name': $('#customer_name').val(), 'type_device': $('#type_device').val(), 'app': $('#app').val(), 'imei': $('#imei').val(), 'tel_clt': $('#tel_clt').val(), 'sim_no': $('#sim_no').val(), 'order_by': $('#order-by').val(), 'order': $('#order').val(), 'status': $('#status-devices').val(), 'stargps_device_management_nonce': starGPSDevicesManagementXlsxParams.stargps_device_management_nonce },
				beforeSend: function () {
                                    $(".stargps-spinner").addClass("stargps-is-active").show();
                                    $('div.resultDevices').html('');
				},
				success: function (data) {  
//                                    console.log(data);
					if (data.error) {
						
						alert(data.error.msg);
					} else {
						
                                                $('div.resultDevices').html( data );
                                                $('.date_picker').datepicker({
                                                    dateFormat : 'dd-mm-yy'
                                                });
                                                fill_customer_name();
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
                function fill_customer_name(){
                    var li = "";
                    $('ul.customer_name_after_search').html("");
                    $('td.customer-name').each(function(index){
                        //console.log($(this).text());
                        li += '<li><a href="#">'+$(this).text() +'</a></li>';
                    });
                    $('ul.customer_name_after_search').html(li);
                    var liText = '', liList = $('ul.customer_name_after_search li'), listForRemove = [];
                    $(liList).each(function () {
                        var text = $(this).text();
                        if (liText.indexOf('|'+ text + '|') == -1){
                            liText += '|'+ text + '|';
                        }else{
                            listForRemove.push($(this));
                        }
                    }); 
                    $(listForRemove).each(function () { $(this).remove(); });                    
                    
                    
                }
                $('#customer_name').on("keyup", function(e) {
                    $('ul.customer_name_after_search').css('display', 'block');
                    var input, filter, ul, li, a, i, txtValue;
                    input = document.getElementById("customer_name");
                    filter = input.value.toUpperCase();
                    ul = document.getElementById("customer_name_after_search");
                    li = ul.getElementsByTagName("li");
                    for (i = 0; i < li.length; i++) {
                        a = li[i].getElementsByTagName("a")[0];
                        txtValue = a.textContent || a.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            li[i].style.display = "";
                        } else {
                            li[i].style.display = "none";
                        }
                    }                    
                });
                $("#customer_name").focus(function(){
                    $('ul.customer_name_after_search').css('display', 'block');
                    return;
                                         
                });                
                $(document).on("click", 'ul.customer_name_after_search > li > a',function(e) {
                    e.preventDefault();
                    $('ul.customer_name_after_search').css('display', 'none');   
                });                 
                $(document).on("click", function(e) {
                    if(e.target.id == "customer_name"){
                        return;
                    }
                    if ( e.target.id != 'ok' ) {
                       $('ul.customer_name_after_search').css('display', 'none');   
                   } 
                });
                $(document).on('click','ul.customer_name_after_search > li > a', function(e){
                    $('#customer_name').val($(this).text());
//                    console.log($(this).text());
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
				data: { 'action': 'stargps_device_management_date_recharge_80_jours' , 'app': $('#app_80').val(), 'recharge_80_j' : '1','date_recharge': $('input[name=DateOfRecharge_80_jours]').val(),'next_recharge': $('input[name=NextOfRecharge_80_jours]').val(), 'expiry_date': $('input[name=ExpiryDate_80_jours]').val(), 'customer_name': $('#customer_name_80_jours').val(), 'type_device': $('#type_device_80_jours').val(), 'imei': $('#imei_80_jours').val(), 'tel_clt': $('#tel_clt_80_jours').val(), 'sim_no': $('#sim_no_80_jours').val(), 'order_by': $('#order-by-80-jours').val(), 'order': $('#order-80-jours').val(), 'status': $('#status-devices-80-jours').val(),},
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
//                                    console.log(data);
//                                    return;
                                    var result = $.parseJSON(data);
					if (result.re === 'duplicate_sim_no') {
						$(this).find("span.stargps-spinner").removeClass("stargps-is-active stargps-spinner").addClass("dashicons dashicons-no").text('Duplicate SIM NO !'); 
                                                return;
					}
					if (result.re === 'duplicate_idimei') {
						$(this).find("span.stargps-spinner").removeClass("stargps-is-active stargps-spinner").addClass("dashicons dashicons-no").text('Duplicate idimei !'); 
                                                return;
					}                                        
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
				data: { 'action': 'stargps_device_management_relancer' , 'app': $('#app_relancer').val(), 'mois' : $('#relancer').val(), 'date_recharge': $('input[name=DateOfRecharge_relancer]').val(),'next_recharge': $('input[name=NextOfRecharge_relancer]').val(), 'expiry_date': $('input[name=ExpiryDate_relancer]').val(), 'customer_name': $('#customer_name_relancer').val(), 'type_device': $('#type_device_relancer').val(), 'imei': $('#imei_relancer').val(), 'tel_clt': $('#tel_clt_relancer').val(), 'sim_no': $('#sim_no_relancer').val(),'order_by': $('#order-by-relancer').val(), 'order': $('#order-relancer').val(), 'status': $('#status-devices-relancer').val()},
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
                $(document).on('click' , 'span.modification_rapide', function(e){
                    
                    var device_id = $(this).data('id');
                    $('tr.edit-'+device_id).css('display', 'table-row');
                });
                $(document).on('click', 'button.annuler' , function(){
                    var device_id = $(this).data('id');
                    $('tr.edit-'+device_id).css('display', 'none');
                });
                $(document).on('click', 'button.update-rapide', function(){
                    var device_id = $(this).data('id');
                    var data_form = $('#form-'+device_id).serializeArray();
                    console.log(data_form);
                    //return;
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_update_rapide' , 'data_form': data_form},
				beforeSend: function () {
                                    $("span.spinner-"+device_id).addClass("stargps-is-active").show();
                                   
				},
				success: function (data) {
//                                    console.log(data);
//                                    return;
                                    var result = $.parseJSON(data); 
					if (result.re === 'duplicate_sim_no') {
						$("span.spinner-"+device_id).removeClass("stargps-is-active");
                                                $("span.confirm-"+device_id).html("Duplicate SIM NO !");
                                                return;
					}                                    
					if (result.re === 'yes') {
						$("span.spinner-"+device_id).removeClass("stargps-is-active");
                                                $("span.confirm-"+device_id).html("Updated!");
                                                return;
					}
                                        if(result.re === 'no'){
                                            $("span.spinner-"+device_id).removeClass("stargps-is-active");
                                            $("span.confirm-"+device_id).html("Nothing changed!");
                                            return;
                                        }
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    //$(this).prev("span.stargps-spinner").removeClass("stargps-is-active").hide();
				}
			});                    
                });
                $(document).on('click', '#checkAll', function(){
                    $('.elementDevice').not(this).prop('checked', this.checked);
                    
                });

                
    }
    $.skeletabs.setDefaults({
        keyboard: false,
    }); 
})(jQuery);

(function ($) {
  $('#update-devices-dialog').dialog({
    title: 'Update selected devices',
    dialogClass: 'wp-dialog',
    autoOpen: false,
    draggable: false,
    width: 'auto',
    modal: true,
    resizable: false,
    closeOnEscape: true,
    position: {
      my: "center",
      at: "center",
      of: window
    },
    open: function () {
      $('.ui-widget-overlay').bind('click', function(){
        $('#update-devices-dialog').dialog('close');
      })
    },
    create: function () {
      $('.ui-dialog-titlebar-close').addClass('ui-button');
    },
  });

  
$(document).on('click', '#update_selected_devices', function(){
    var checked = $('input[name="elementDevice"]:checked').length > 0;
    if (!checked){
        alert("Please check at least one checkbox");
        return false;
    }  
    var device_ids = '';
    $('input[name="elementDevice"]:checked').each(function() {
        device_ids = device_ids  + this.value + '-'; 
    });
    $('div#update-devices-dialog #device_ids').val(device_ids);
    $('div#update-devices-dialog #update_app').val($('#app').val());
    
    $('#update-devices-dialog').dialog('open');
             
});
$(document).on('click', '#run-update', function(){

			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_update_dialog_form' , 'data_form': $('#update-dialog-form').serializeArray()},
				beforeSend: function () {
                                    $(this).next("span.updating-message").text('Updating ...');
                                   
				},
				success: function (data) {
//                                   console.log(data);
//                                   return;
                                    var result = $.parseJSON(data);
                                    
					if (result.re === 'yes') {
                                            $(this).next("span.updating-message").text("Updated!");
                                            $('.elementDevice').prop('checked', false);
                                            return;
					}
					if (result.re === 'no') {
                                            $(this).next("span.updating-message").text("Failed!");
                                            return;
					}                                        
                                        if(result.re === 'no-change'){
                                            $(this).next("span.updating-message").text("Nothing changed!");
                                            return;
                                        }
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    //$(this).prev("span.stargps-spinner").removeClass("stargps-is-active").hide();
				}
			});    
});
	$(document).on('click', '#delete_selected_devices', function(){
		var checked = $('input[name="elementDevice"]:checked').length > 0;
		if (!checked){
			alert("Please check at least one checkbox");
			return false;
		}  
		var device_ids = '';
		tr_line_to_fade_out = [];
		$('input[name="elementDevice"]:checked').each(function() {
			device_ids = device_ids  + this.value + '-'; 
			tr_line_to_fade_out.push(this.value);
		});
    
		var c =  confirm("Change Device status to 'Removed'. If it is in Status 'Removed', it will be permanently deleted?");
		if ( c === false ){
			$('.elementDevice').prop('checked', false);
			return;
		}

			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_delete_devices' , 'device_ids': device_ids, 'app': $('#delete-devices-app').val()},
				beforeSend: function () {
                                    $(this).next("span.deleting-message").text('Deleting ...');
                                   
				},
				success: function (data) {
//                                   console.log(data);
//                                   return;
                                    var result = $.parseJSON(data);
                                    
					if (result.re === 'yes') {
                                            $(this).next("span.deleting-message").text( result.n + " devices" + " Deleted!");
                                             $('.elementDevice').prop('checked', false);
                                                 $.each(tr_line_to_fade_out, function( i , v ){
                                                    // $('tr.line-'+v).fadeOut("slow");
                                                     //$('tr.edit-'+v).fadeOut("slow");
                                                 });
                                            return;
					}
					if (result.re === 'no') {
                                            $(this).next("span.deleting-message").text("Failed!");
                                            return;
					}                                        
                                        if(result.re === 'no-change'){
                                            $(this).next("span.deleting-message").text("Nothing changed!");
                                            return;
                                        }
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    
                                    //$(this).prev("span.stargps-spinner").removeClass("stargps-is-active").hide();
				}
			}); 
                    });
                    $(document).on('click', 'button.download', function(e){
                            
//                       console.log($(this).data('sql'));
			$.ajax({
				url: starGPSDevicesManagementXlsxParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'stargps_device_management_download' , 'sql': $(this).data('sql') },
				beforeSend: function () {
                                    $(this).prop( "disabled", true );
                                    $(this).next("span.download_xlsx").text('Generating file xlsx ... ');
                                   
				},
				success: function (data) {
//                                   console.log(data);
                                   $(this).next("span.download_xlsx").html(data);
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
                                    $(this).prop( "disabled", false );
                                    
                                    //$(this).prev("span.stargps-spinner").removeClass("stargps-is-active").hide();
				}
			});                            
                        });


})(jQuery);



