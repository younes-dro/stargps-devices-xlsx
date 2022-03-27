<h3><span class="dashicons dashicons-rest-api"></span> <span>APIs</span></h3>

<div class="postbox">

    <div class="postbox-header">
        <h4 class="">Liste des APIs enregistrées</h4>
    </div>
    <div class="inside">
        <?php stargps_device_management_get__saved_api_list(); ?>
    </div>

    <div class="postbox-header">
        <h4 class="">Ajout nouveau API</h4>
    </div>
    <div class="inside">
        <form class="form-new-api">

            <div class="type-api">
                <h4>Traccar</h4>
                <div class="type-gps">
                    <span>( <b>Pas de / à la fin </b>)</span>
                    <input type="text" id="url-traccar" placeholder="https://exemple.com:{PORT}" >
                    <input type="email" id="email-traccar" placeholder="E-mail" >
                    <input type="text" id="password-traccar" placeholder="Password" >
                    
                    <button data-type="traccar"  type="button" title="Lancer"  class="connect-api dashicons dashicons-editor-spellcheck"></button>
                 
                    
                </div>
                <h4>GPWWOX</h4>
                <div class="type-gps">
                    <span>( <b>Pas de / à la fin </b>)</span>
                    <input type="text" id="url-gpswox" placeholder="https://exemple.com" >
                    <input type="email" id="email-gpswox" placeholder="E-mail" >
                    <input type="text" id="password-gpswox" placeholder="Password" >                    
                    
                    <button data-type="gpswox"  type="button" title="Lancer"  class="connect-api dashicons dashicons-editor-spellcheck"></button>
                    
                </div> 
                 
            </div>
            <div class="responseAPI-wrapper">
                <span class="stargps-spinner spinner-small"></span>
            <p class="responseAPI"></p>
            </div>

        </form>
    </div> 

</div>