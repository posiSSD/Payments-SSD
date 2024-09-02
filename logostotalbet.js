//direccion de las imagenes
var prometeodesktopImagen = 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/logo-prometeo-200x26-17092176683099.png?1725292290290';
var payphonedesktopImagen = 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphone158x21-17105213796592.png?1725292726790';




if (document.body.classList.contains("mobile")) {

    console.log("móvil Payments Logos");

    /*

    function handleBodyChanges(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            
            if (mutation.type === 'childList') {
                
                try{

                    if ($('div[data-testid="payment-methods-list"]').length > 0) {

                        var data_testid = $('div[data-testid="payment-methods-list"]');
                        //console.log('div[data-testid="payment-methods-list"]: True.');
                        if (data_testid.length > 0){

                            var imagenPayphone = data_testid.find('div[data-testid="payment-methods-list-item"]').eq(0).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPayphone.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphone85x24-17105219288429.png?1710521941186');
                            
                            // https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphoneremovebg-preview-17060223265677.png?1708961570165 - payphone
                            // https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeo-e1610717447192-16971452504121.png?1708961642956 - prometeo 
                            // https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeo-house-nobg-170915368925.png?1709153842929 prometeo - house 
                            // https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphone-house-nobg-17091548133654.png?1709154819859 payphone - house
                            // https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphone85x24-17105219288429.png?1710521941186
                            
                            var imagenPrometeo = data_testid.find('div[data-testid="payment-methods-list-item"]').eq(1).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPrometeo.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeo-house-nobg-170915368925.png?1709153842929');

                        }
                        
                    } else {
                        //console.log('div[data-testid="payment-methods-list"]: False.'); 
                    }  
                    
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
                    } else {
                        //console.log('div style__HeroFallbackContainer-sc-swzx38-5.ldbuIk: ocultado.'); 
                    }
                    
                    //observer.disconnect();
                                   
                } catch (error) {
                    //console.error('Error: ', error);
                }
        
            }
        });
    }

    var bodyObserver = new MutationObserver(handleBodyChanges);

    bodyObserver.observe(document.body, { childList: true, subtree: true });

   */

} else if (document.body.classList.contains("desktop")) {

    console.log("Desktop Payments Logos");
    var changeImageAttemptsPrometeo = 0;
    var changeImageAttemptsPayphone = 0;
    // Función para manejar la detección de cambios en el cuerpo del documento
    function handleBodyChanges(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            // Verificar si se añadieron o eliminaron nodos
            if (mutation.type === 'childList') {
                // Verificar si se añadió el div accountModal accountModal--desktop
                try{         //accountModal accountModal--desktop
                    if ($('div.accountModal.accountModal--desktop').length > 0) {
                        //console.log('div accountModal accountModal--desktop: True');
        
                        var accountModal = $('div.accountModal.accountModal--desktop');

                        if (accountModal){
                            
                            // Seleccionar el contenedor de Prometeo y cambiar la imagen
                            var prometeoContainer = accountModal.find('div:contains("Prometeo")').closest('div.style__HeroFallbackContainer-sc-swzx38-5');
                            if(prometeoContainer){

                                var prometeoImage = prometeoContainer.find('img');
                                prometeoImage.attr('src', prometeodesktopImagen);

                                changeImageAttemptsPrometeo++;
                                console.log(`Prometeo TRUE #${changeImageAttemptsPrometeo}`);

                            }else{

                                changeImageAttemptsPrometeo++;
                                console.log(`Prometeo #${changeImageAttemptsPrometeo}`);

                            }

                            var payphoneContainer = accountModal.find('div:contains("Payphone")').closest('div.style__HeroFallbackContainer-sc-swzx38-5');
                            if(payphoneContainer){

                                var payphoneImage = payphoneContainer.find('img');
                                payphoneImage.attr('src', payphonedesktopImagen);

                                changeImageAttemptsPayphone++;
                                console.log(`Payphone TRUE #${changeImageAttemptsPayphone}`);

                            }else{

                                changeImageAttemptsPayphone++;
                                console.log(`Payphone #${changeImageAttemptsPayphone}`);

                            }                        
                            

                        }else{
                            console.log('div accountModal accountModal--desktop: False');
                        }
                        
                    } else {
                        //console.log('El div accountModal accountModal--desktop se ha ocultado.');
                        // Realizar acciones necesarias cuando el div se oculta
                    }

                } catch (error) {
                    //console.error('Error: ', error);
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


                        /*
                        var carouselWrapper = accountModal.find('div.carousel__wrapper'); // Aquí es donde debes usar .find() en lugar de .$()
                        if (carouselWrapper.length > 0){
                            //console.log('div carousel__wrapper: True', carouselWrapper);

                            var imagenPayphone = carouselWrapper.find('div.payment__item-box').eq(0).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPayphone.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphone158x21-17105213796592.png?1710521407681');
                            /// https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/logo-payphone-200x26-17092174679023.png?1709217477178
                            /// https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphone158x21-17105213796592.png?1710521407681

                            var imagenPrometeo = carouselWrapper.find('div.payment__item-box').eq(1).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPrometeo.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/logo-prometeo-200x26-17092176683099.png?1709217671152');
                            /// https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/logo-prometeo-200x26-17092176683099.png?1709217671152

                        } else {
                            //console.log('div carousel__wrapper: False');
                        }
                        */
                        //observer.disconnect();

}

