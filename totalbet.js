console.log('Payments loaded.....');

if (document.body.classList.contains("mobile")) {

    console.log("Movil Payments");

    /*
    function handleBodyChanges(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            
            if (mutation.type === 'childList') {
                
                try{

                    
                                   
                } catch (error) {
                    console.error('Error: ', error);
                }
        
            }
        });
    }

    var bodyObserver = new MutationObserver(handleBodyChanges);

    bodyObserver.observe(document.body, { childList: true, subtree: true });
    */
} else if (document.body.classList.contains("desktop")) {

    console.log("Desktop Payments");

    // Función para manejar la detección de cambios en el cuerpo del documento
    function handleBodyChanges(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            // Verificar si se añadieron o eliminaron nodos
            if (mutation.type === 'childList') {
                // Verificar si se añadió el div accountModal accountModal--desktop

                try{

                    var modal_root = $('body > div > div.v3-modal-root');
                    if  ( (modal_root.length > 0) &&
                        (window.location.search.includes('?accounts=%2A&wallet=%2A&deposit=%2A') ||
                         window.location.search.includes('?accounts=*&wallet=*&deposit=*'))) {

                        var modalElement = modal_root.find('.v3-modal');
                        if(modalElement){
                            modalElement.css('margin','0');
                            modalElement.css('width','460px');
                            console.log('v3-modal FOUND');

                            var modalContentDiv = modalElement.find('.v3-modal-content')
                            if (modalContentDiv) {
                                modalContentDiv.css('border-radius','0px');
                                console.log('v3-modal-content v3-modal FOUND');

                                var modal_body = modalContentDiv.find('.v3-modal-body');
                                if ( modal_body) {
                                    modal_body.css('display','none');
                                    console.log('v3-modal-body FOUND');

                                } else {
                                    console.log('v3-modal-body Not Found');
                                }

                                var authData = localStorage.getItem("x__ACCOUNT__auth_data");
                                if(authData){
                                    // Parsear los datos JSON almacenados en el Local Storage
                                    var authDataObj = JSON.parse(authData);
                                    // Acceder a los valores necesarios (auth_token y user_id)
                                    var auth_token = authDataObj.auth_token;
                                    var user_id = authDataObj.user_id;
                                    var metodo_tb = '';
                                    console.log('authData Found');
                                } else {
                                    console.log('authData not Found');
                                }

                                var carruselActivo = $('.payment__item-box-active');
                                if (carruselActivo.length > 0) {
                                    var opcionSeleccionada = carruselActivo.find('.payment__item-box-text').text().trim().toLowerCase();
                                    var metodo_tb = opcionSeleccionada;
                                    console.log('payment__item-box-active Found ', metodo_tb);
                                } else {
                                    console.log('payment__item-box-active Not Found');
                                }


                                var valorInput = $('#amount').val();
                                if (valorInput) {
                                    console.log('#amount Found: ', valorInput);
                                } else {
                                    console.log('#amount Not Found');
                                }
                                
                                var max_width, max_height;
                                if ( metodo_tb === 'payphone' ) {
                                    max_width = '447px';
                                    max_height = '846px';
                                       
                                } else if ( metodo_tb === 'prometeo' ) {
                                    max_width = '399px';
                                    max_height = '650px';

                                } else {
                                    max_width = '600px';
                                    max_height = '800px';

                                }

                                var modalAndIframeStyles = `
                                    width: 99%;
                                    height: 96vh;
                                    max-width: ${max_width};
                                    max-height: ${max_height};
                                    border: none;
                                    overflow: hidden;
                                    text-align: center;
                                    justify-content: center;
                                `;

                                // Crear un objeto con los datos de autenticación
                                var array_authData = {
                                    auth_token: auth_token,
                                    user_id: user_id,
                                    metodo: metodo_tb,
                                    amount: valorInput
                                };
                                
                                console.log('array_authData : ', array_authData);
                                console.log('modalContentDiv : ', modalContentDiv); // Se corrigió el log, se agregó la coma que faltaba

                                /*
                                var encoded_auth_data = encodeURIComponent(JSON.stringify(array_authData));

                                // Crear el iframe usando jQuery
                                var iframe = $('<iframe>', {
                                    id: 'paymentsframe',
                                    css: modalAndIframeStyles,
                                    src: "https://payments.totalbet.com/index.php?auth_data=" + encoded_auth_data
                                });

                                // Agregar el iframe al contenido del modal usando jQuery
                                $('#modalContentDiv').append(iframe);
                                */
                                



                            } else {
                                console.log('v3-modal-content v3-modal NOT FOUND');
                            }
                        } else {
                            console.log('v3-modal Not Found');
                        
                        }        

                    } else {
                        console.log('div.v3-modal-root && ?accounts=%2A&wallet=%2A&deposit=%2A Not Found');
                    }


                   
                } catch (error) {
                    console.error('Error: ', error);
                }
            }
        });
    }

    // Crear una instancia de MutationObserver para observar cambios en el cuerpo del documento
    var bodyObserver = new MutationObserver(handleBodyChanges);

    // Observar cambios en el cuerpo del documento, incluidos los descendientes
    bodyObserver.observe(document.body, { childList: true, subtree: true });

} else {

    console.log("Pi pi pi pi pi.");

}

