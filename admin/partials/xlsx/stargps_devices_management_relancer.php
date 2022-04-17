<div class="">
    <h4><span class="dashicons dashicons-image-filter"></span> Filtrer les Devices à relancer :</h4>

    <div  class="devicesForm">
        <div class="column">  
            <?php stargps_device_management_get_table_select_menu_relancer(); ?>
        </div>
        <div class="column">
            <select name="relancer" id="relancer">
                <option value="">-</option>
                <option value="mois_en_cours">Mois en cours</option>
                <option value="mois_prochain">Le mois prochain</option>
                <option value="mois_dernier">Le mois dernier</option>
            </select>
        </div>
        <div class="column">
            <label>Customer Name: </label>
            <input  type="text" name="customer_name_relancer" id="customer_name_relancer"/>
        </div>
        <div class="column">
            <label>Tel CTL: </label>
            <input type="text" name="tel_clt_relancer" id="tel_clt_relancer"/>
        </div>         
    </div><!-- .devicesForm -->
    <div  class="devicesForm">
        <div class="column">
            <label>SIM no: </label>
            <input type="text" name="sim_no_relancer" id="sim_no_relancer"/>
        </div>            
        <div class="column">
            <label>IMEI: </label>
            <input type="text" name="imei_relancer" id="imei_relancer"/>
        </div>
        <div class="column">
            <label>Type Device: </label>
            <input type="text" name="type_device_relancer" id="type_device_relancer"/>
        </div> 
    </div><!-- .devicesForm -->
    <div  class="devicesForm">
        <div class="column">
            <label>Date recharge: </label>
            <input type="text" name="DateOfRecharge_relancer" class="form-control date_recharge date_picker" value="" />
        </div>
        <div class="column">
            <label>Next recharge: </label>
            <input type="text" name="NextOfRecharge_relancer" class="form-control next_recharge date_picker" value="" />
        </div>
        <div class="column">
            <label>Expiry date: </label>
            <input type="text" name="ExpiryDate_relancer" class="form-control next_recharge date_picker" value="" />
        </div>        
    </div><!-- .devicesForm --> 
    <div class="devicesForm">        
        <div class="column">
            <label>Order by: </label>
            <select name="order-by-relancer" id="order-by-relancer">
                <option value="id" selected="">ID</option>
                <option value="customer-name">Customer Name</option>
                <option value="#">Groupe</option>
                <option value="expiry">Expiry date</option>
                <option value="date-recharge">Date Recharge</option>
                <option value="next-recharge">Next Recharge</option>
            </select>
            <select name="order-relancer" id="order-relancer">
                <option value="ASC">ASC</option>
                <option value="DESC">DESC</option>
            </select>
        </div> 
        <div class="column">
               <select name="status-devices-relancer" id="status-devices-relancer">
                   <option value="all" selected>Tout</option>
                    <option value="active">Activé</option>
                    <option value="disabled">Désactivé</option>
                    <option value="expired">Expiré</option>
                    <option value="removed">Supprimé</option>
               </select>
            
        </div>        
    </div> <!-- .devicesForm -->      
    <div class="submit-wrapper">
        <a id="stargps_device_management_date_recharge_relancer" class="stargps-devices-management-btn">Submit</a>
    </div>


    <div class="listDevicesRelancer">
        <span class="stargps-spinner"></span>
        <div class="resultDevicesRelancer"></div>
    </div>


</div>