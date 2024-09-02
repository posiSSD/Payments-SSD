//direccion de las imagenes
var prometeodesktopImagen = 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/logo-prometeo-200x26-17092176683099.png?1725292290290';
var payphonedesktopImagen = 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphone158x21-17105213796592.png?1725292726790';
var prometeomobilImagen = 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/logo-prometeo-200x26-17092176683099.png?1725292290290'; ///
var payphonemobilImagen = 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphone85x24-17105219288429.png?1725303738260';

if (document.body.classList.contains("mobile")) {

    console.log("móvil Payments Logos");
    var imagesChangedPrometeo = false;
    var imagesChangedPayphone = false;

    function handleBodyChanges(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            
            if (mutation.type === 'childList') {
                
                try{
                    if($('div.accountModal.accountModal--mobile').length > 0) {

                        var accountModal = $('div.accountModal.accountModal--mobile');

                        if(accountModal){
                            /*                                                                              
                            var prometeoContainer = accountModal.find('div:contains("Prometeo")').closest('div.style__HeroFallbackContainer-sc-swzx38-5');
                            if (prometeoContainer) {
                                var prometeoImage = prometeoContainer.find('img');
                                prometeoImage.on('load', function() {
                                    if (prometeoImage.attr('src') !== prometeomobilImagen) {
                                        prometeoImage.attr('src', prometeomobilImagen);
                                        imagesChangedPrometeo = true;
                                    }
                                });
                            }
                            */
                                                                                                             //style__HeroFallbackContainer-sc-swzx38-5
                            var payphoneContainer = accountModal.find('div:contains("Payphone")').closest('div.style__HeroFallbackContainer-sc-swzx38-5');
                            if (payphoneContainer) {
                                var payphoneImage = payphoneContainer.find('img');
                                payphoneImage.on('load', function() {
                                    if (payphoneImage.attr('src') !== payphonemobilImagen) {
                                        payphoneImage.attr('src', payphonemobilImagen);
                                        imagesChangedPayphone = true;
                                    }
                                });
                            } 

                        }
                    }
                    /*
                    if ($('div.style__HeroFallbackContainer-sc-swzx38-5.ldbuIk').length > 0) {
                        var style__HeroFall = $('div.style__HeroFallbackContainer-sc-swzx38-5.ldbuIk');
                        var text = style__HeroFall.find('.style__HeroFallbackText-sc-swzx38-1').text();
                        //console.log('Text div style__HeroFallbackText-sc-swzx38-1: ', text,' ');

                        if(text == 'Payphone'){

                            var imagenPayphone = style__HeroFall.find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPayphone.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphone142x40-17105221848106.png?1710522189795');
                            //console.log('Payphone');

                        } 
                        if(text == 'Prometeo') {

                            var imagenPrometeo = style__HeroFall.find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPrometeo.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeo-house-nobg-170915368925.png?1709153842929');
                            //console.log('Prometeo');

                        } 
                    } 
                    */                  
                } catch (error) {
                    console.error('Error: ', error);
                }
        
            }
        });
    }

    var bodyObserver = new MutationObserver(handleBodyChanges);

    bodyObserver.observe(document.body, { childList: true, subtree: true });


} else if (document.body.classList.contains("desktop")) {

    console.log("Desktop Payments Logos");
    var imagesChangedPrometeo = false;
    var imagesChangedPayphone = false;
        
    // Función para manejar la detección de cambios en el cuerpo del documento
    function handleBodyChanges(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            // Verificar si se añadieron o eliminaron nodos
            if (mutation.type === 'childList') {
                // Verificar si se añadió el div accountModal accountModal--desktop
                try{         
                    if ($('div.accountModal.accountModal--desktop').length > 0) {
                        //console.log('div accountModal accountModal--desktop: True');
        
                        var accountModal = $('div.accountModal.accountModal--desktop');

                        if (accountModal){
                            
                            // Seleccionar el contenedor de Prometeo y cambiar la imagen                      
                            var prometeoContainer = accountModal.find('div:contains("Prometeo")').closest('div.style__HeroFallbackContainer-sc-swzx38-5');
                            if (prometeoContainer) {
                                var prometeoImage = prometeoContainer.find('img');
                                prometeoImage.on('load', function() {
                                    if (prometeoImage.attr('src') !== prometeodesktopImagen) {
                                        prometeoImage.attr('src', prometeodesktopImagen);
                                        imagesChangedPrometeo = true;
                                        //console.log('URL Prometeo:', prometeoImage.attr('src'));
                                    }
                                });
                            }

                            var payphoneContainer = accountModal.find('div:contains("Payphone")').closest('div.style__HeroFallbackContainer-sc-swzx38-5');
                            if (payphoneContainer) {
                                var payphoneImage = payphoneContainer.find('img');
                                payphoneImage.on('load', function() {
                                    if (payphoneImage.attr('src') !== payphonedesktopImagen) {
                                        payphoneImage.attr('src', payphonedesktopImagen);
                                        imagesChangedPayphone = true;
                                        //console.log('URL Payphone:', payphoneImage.attr('src'));
                                    }
                                });
                            } 
                            /*
                            if (imagesChangedPrometeo && imagesChangedPayphone) {
                                observer.disconnect(); // Detener la observación
                                console.log("Desktop Payments Logos Observer BYE BYE");
                            }             
                            */
                        }else{
                            console.log('div accountModal accountModal--desktop: False');
                        }
                        
                    } else {
                        //console.log('El div accountModal accountModal--desktop se ha ocultado.');
                        // Realizar acciones necesarias cuando el div se oculta
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

