<form id="form" name="form" method="post" action="index.html">
    <h1>Devices</h1>
    <div id="stylized" class="devicesForm">
        <div class="column">
                <?php stargps_device_management_get_table_select_menu();?>
        </div>
        <div class="column customer_search">
            <label>Customer Name: </label>
            <input  type="text" name="customer_name" id="customer_name"/>
            <ul id="customer_name_after_search" class="customer_name_after_search"></ul>
        </div>
        <div class="column">
            <label>Tel CTL: </label>
            <input type="text" name="tel_clt" id="tel_clt"/>
        </div>            
    </div>
    <div id="stylized" class="devicesForm">

        <div class="column">
            <label>SIM no: </label>
            <input type="text" name="sim_no" id="sim_no"/>
        </div>            
        <div class="column">
            <label>IMEI: </label>
            <input type="text" name="imei" id="imei"/>
        </div>
        <div class="column">
            <label>Type Device: </label>
            <input type="text" name="type_device" id="type_device"/>
        </div>            
    </div>        
    <div class="devicesForm">        
        <div class="column">
            <label>Date recharge: </label>
            <input type="text" name="DateOfRecharge" class="form-control date_recharge date_picker" value="" />
        </div>
        <div class="column">
            <label>Next recharge: </label>
            <input type="text" name="NextOfRecharge" class="form-control next_recharge date_picker" value="" />
        </div> 
        <div class="column">
            <label>Expiry date: </label>
            <input type="text" name="ExpiryDate" class="form-control next_recharge date_picker" value="" />
        </div>            
    </div>
    <div class="devicesForm">        
        <div class="column">
            <label>Order by: </label>
            <select name="order-by" id="order-by">
                <option value="id" selected="">ID</option>
                <option value="customer-name">Customer Name</option>
                <option value="#">Groupe</option>
                <option value="expiry">Expiry date</option>
                <option value="date-recharge">Date Recharge</option>
                <option value="next-recharge">Next Recharge</option>
            </select>
            <select name="order" id="order">
                <option value="ASC">ASC</option>
                <option value="DESC">DESC</option>
            </select>
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
<div id="update-devices-dialog" class="hidden" style="max-width:900px">
    <form action="" id="update-dialog-form">
        <input type="text" name="device_ids" id="device_ids" value="">
        <input type="text" name="update_app" id="update_app" value="">

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="customer">Customer</label></th>
                    <td><input name="customer" type="text" value="0" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="login">Login</label></th>
                    <td><input name="login" type="text" value="0" class="regular-text"></td>
                </tr> 
                <tr>
                    <th scope="row"><label for="num-tel">Num tel</label></th>
                    <td><input name="num-tel" type="text" value="0" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="target">Target</label></th>
                    <td><input name="target" type="text" value="0" class="regular-text"></td>
                </tr> 
                <tr>
                    <th scope="row"><label for="type-device">Type device</label></th>
                    <td><input name="type-device" type="text" value="0" class="regular-text"></td>
                </tr> 
                <tr>
                    <th scope="row"><label for="expiry">Expiry</label></th>
                    <td><input name="expiry" type="text" value="0" class="regular-text"></td>
                </tr> 
                <tr>
                    <th scope="row"><label for="operator">Operator</label></th>
                    <td><input name="Operator" type="text" value="0" class="regular-text"></td>
                </tr>  
                <tr>
                    <th scope="row"><label for="remarks">Remarks</label></th>
                    <td><input name="remarks" type="text" value="0" class="regular-text"></td>
                </tr>        
            </tbody>
        </table>
        <p class="submit">
            <input type="button" class="button button-primary" id="run-update" value="<?php _e('Update') ?>">
            <span class="updating-message"></span>
        </p>
    </form>
</div>