console.log('Payments loaded.....');

if (document.body.classList.contains("mobile")) {

    console.log("móvil.");

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

} else if (document.body.classList.contains("desktop")) {

    console.log("Desktop.");

    // Función para manejar la detección de cambios en el cuerpo del documento
    function handleBodyChanges(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            // Verificar si se añadieron o eliminaron nodos
            if (mutation.type === 'childList') {
                // Verificar si se añadió el div accountModal accountModal--desktop

                try{
                    if (window.location.search.includes('?accounts=%2A&wallet=%2A&deposit=%2A') ||
                        window.location.search.includes('?accounts=%2A&wallet=%2A&deposit-methods=%2A') ||
                        window.location.search.includes('?accounts=*&wallet=*&deposit=*') ||
                        window.location.search.includes('?accounts=*&wallet=*&deposit-methods=*')){
                            if ($('div.v3-modal-root').length > 0) {

                                var modalContentDiv = $('div.v3-modal-root');
                                // modalContentDiv.style.borderRadius = '0px'
                                // modalContentDiv.querySelector('.v3-modal-body').style.display = 'none';
                                modalContentDiv.css('border-radius', '0px');
                                modalContentDiv.find('.v3-modal-body').css('display', 'none');

                                var modalElement =modalContentDiv.find('.v3-modal');
                                if(modalElement){
                                    modalElement.style.margin = '0';
                                    //modal.style.width = 'auto';
                                    //width: auto;
                                    console.log('modalElement v3-modal FOUND');
                                }else{
                                    console.log('modalElement v3-modal NOT FOUND');
                                }

                                var authData = localStorage.getItem("x__ACCOUNT__auth_data");
                                if(authData){
                                    // Parsear los datos JSON almacenados en el Local Storage
                                    var authDataObj = JSON.parse(authData);
                                    // Acceder a los valores necesarios (auth_token y user_id)
                                    var auth_token = authDataObj.auth_token;
                                    var user_id = authDataObj.user_id;
                                    var metodo_tb = '';
                                    //console.log('authData Found');
                                } else {
                                    //console.log('authData not Found');
                                }


                            } else {

                            }

                    } else {
                        console.log('No accounts-wallet-deposit: ', error);
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

