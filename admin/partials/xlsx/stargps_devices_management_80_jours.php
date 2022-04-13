<div class="">
    <h4><span class="dashicons dashicons-calendar-alt"></span> 80 jours passés de la date de  recharge</h4>

    <div  class="devicesForm">
        <div class="column">
            <?php stargps_device_management_get_table_select_menu_80(); ?>
        </div>
        <div class="column">
            <label>Customer Name: </label>
            <input  type="text" name="customer_name_80_jours" id="customer_name_80_jours"/>
        </div>
        <div class="column">
            <label>Tel CTL: </label>
            <input type="text" name="tel_clt_80_jours" id="tel_clt_80_jours"/>
        </div>             
    </div><!-- .devicesForm -->
    <div  class="devicesForm"> 
        <div class="column">
            <label>SIM no: </label>
            <input type="text" name="sim_no_80_jours" id="sim_no_80_jours"/>
        </div>            
        <div class="column">
            <label>IMEI: </label>
            <input type="text" name="imei_80_jours" id="imei_80_jours"/>
        </div>
        <div class="column">
            <label>Type Device: </label>
            <input type="text" name="type_device_80_jours" id="type_device_80_jours"/>
        </div> 
    </div><!-- .devicesForm -->
    <div  class="devicesForm">
        <div class="column">
            <label>Date recharge: </label>
            <input type="text" name="DateOfRecharge_80_jours" class="form-control date_recharge date_picker" value="" />
        </div>
        <div class="column">
            <label>Next recharge: </label>
            <input type="text" name="NextOfRecharge_80_jours" class="form-control next_recharge date_picker" value="" />
        </div>
        <div class="column">
            <label>Expiry date: </label>
            <input type="text" name="ExpiryDate_80_jours" class="form-control next_recharge date_picker" value="" />
        </div>        
    </div><!-- .devicesForm -->  
    
    <div class="devicesForm">        
        <div class="column">
            <label>Order by: </label>
            <select name="order-by-80-jours" id="order-by-80-jours">
                <option value="id" selected="">ID</option>
                <option value="customer-name">Customer Name</option>
                <option value="#">Groupe</option>
                <option value="expiry">Expiry date</option>
                <option value="date-recharge">Date Recharge</option>
                <option value="next-recharge">Next Recharge</option>
            </select>
            <select name="order-80-jours" id="order-80-jours">
                <option value="ASC">ASC</option>
                <option value="DESC">DESC</option>
            </select>
        </div>            
    </div>    

    <div class="submit-wrapper">   
        <a id="stargps_device_management_date_recharge_80_jours" class="stargps-devices-management-btn">Submit</a>
    </div>
    <div class="listDevices80">
        <span class="stargps-spinner"></span>
        <div class="resultDevices80"></div>
    </div>
</div>