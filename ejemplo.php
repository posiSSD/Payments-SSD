<div class="popup-container">
    <!-- Contenido de tu popup -->
    <iframe id="payphoneframe" src="https://payments.totalbet.com/index.php?auth_data=%7B%22auth_token%22%3A%2277FF481AD74D0B925BDE9D0F16135142%22%2C%22user_id%22%3A1674627753%2C%22metodo%22%3A%22payphone%22%7D">
        <body>
            <div id="msg" style="font-style: italic;"></div>
            
            <form action="#" id="kushki_payment_form">
                <p class="text-muted text-start write-text" id="texto">Escriba el valor aquí: *</p>
                <div class="input-group mb-3" id="inputtext">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon3">USD</span>
                    </div>
        
                    <input autocomplete="off" data-bs-toggle="tooltip" data-bs-placement="top" type="text" placeholder="Min 1 | Max 500" data-min="1" data-max="500" class="form-control" id="basic-url" aria-describedby="basic-addon3" required="" onkeyup="validar()">
                </div>
                <p id="sms_alert"></p>
                <div>
                    <button type="button" class="btn btn-secondary ready" style="font-size: 14px; width: 150px;">Generar</button>
                </div>
        
            </form>
            <div id="prometeoembeded">
                <iframe id="prometeoframe" frameborder="0" allowfullscreen=""></iframe>  
            </div>
            <div id="kushki_payment_holder">
                <div id="kushki_details"></div>
                <br>
                <div>
                    <a id="kushki_btn" target="_top">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Cargando payphone</span>
                    </a>
                </div>
            </div>
        </body>
    </iframe>
</div>