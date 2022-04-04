<div class="">
<h4><span class="dashicons dashicons-image-filter"></span> Filtrer les Devices à date expirer :</h4>
<div class="column">
<?php stargps_device_management_get_table_select_menu_relancer();?>
    <select name="relancer" id="relancer">
        <option value="">-</option>
        <option value="mois_en_cours">Mois en cours</option>
        <option value="mois_prochain">Le mois prochain</option>
        <option value="mois_dernier">Le mois dernier</option>
    </select>    
<a id="stargps_device_management_date_recharge_relancer" class="stargps-devices-management-btn">Submit</a>
</div>
<div class="listDevicesRelancer">
    <span class="stargps-spinner"></span>
    <div class="resultDevicesRelancer"></div>
</div>
</div>