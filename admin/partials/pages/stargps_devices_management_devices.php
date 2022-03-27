    <form id="form" name="form" method="post" action="index.html">
        <h1>Devices</h1>
        <div id="stylized" class="devicesForm">
        <div class="column">
<?php stargps_device_management_get_api_select_menu();?>
        </div>
        <div class="column">
            <label>Target Name: </label>
            <input type="text" name="target_name" id="target_name"/>
        </div>            
        <div class="column">
            <label>IMEI: </label>
            <input type="text" name="imei" id="imei"/>
        </div>             
         
<!--        <div class="column">
            <label>Next recharge: </label>
            <input type="text" name="DateNextRecharge" class="form-control next_recharge date_picker"/>
        </div>-->
             
        </div>
 <div class="devicesForm">        
        <div class="column">
            <label>Type Device: </label>
            <input type="text" name="type_device" id="type_device"/>
        </div>
        <div class="column">
            <label>Date recharge: </label>
            <input type="text" name="DateOfRecharge" class="form-control date_recharge date_picker" value="" />
        </div>      
 </div>
        <div class="submit-wrapper">
            <a id="stargps_device_management_date_recharge" class="stargps-devices-management-btn">Submit</a>
        </div>
</form>
<div class="listDevices">
    <span class="stargps-spinner"></span>
    <div class="resultDevices"></div>
</div>