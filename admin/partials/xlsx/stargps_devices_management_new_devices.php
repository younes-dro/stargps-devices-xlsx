<div class="">
<h4><span class="dashicons dashicons-database-add"></span> Add new devices to database</h4>
<div class="newRows">
<?php stargps_device_management_get_table_select_menu_new_devices();?>

    <input type="number" name="number_rows" min="1" id="number_rows">
    <span class="generate-form-rows dashicons dashicons-table-row-before"></span>
</div>

<div class="newRows">
    <span class="stargps-spinner"></span>
    <div class="resultNewRows"></div>
</div>
</div>

