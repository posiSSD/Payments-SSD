console.log('Payments Logos loaded.....');

if (document.body.classList.contains("mobile")) {

    console.log("móvil.");

    
    function handleBodyChanges(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            
            if (mutation.type === 'childList') {
                
                try{

                    /*

                    if ($('div.accountModal.accountModal--mobile').length > 0) {
                        console.log('Se encontró el div con la clase "accountModal accountModal--mobile".');

                        var data_testid = $('div.accountModal.accountModal--mobile');

                        var style__HeroFall = data_testid.find('div.style__HeroFallbackContainer-sc-swzx38-5.ldbuIk');

                        if (style__HeroFall.length > 0){

                            var text = style__HeroFall.find('.style__HeroFallbackText-sc-swzx38-1').text();

                            if(text == 'Payphone'){

                                var imagenPayphone = style__HeroFall.find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                                imagenPayphone.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphoneremovebg-preview-17060223265677.png?1708961570165');
                                console.log('Payphone: ');

                            } 
                            if(text == 'Prometeo') {

                                var imagenPrometeo = style__HeroFall.find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                                imagenPrometeo.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeo-e1610717447192-16971452504121.png?1708961642956');
                                console.log('Prometeo: ');

                            } 

                        } else {
                            console.log('Se encontró el div con la clase "style__HeroFall".');
                        }

                        
                    } else {
                        console.log('No se encontró el div con la clase "accountModal accountModal--mobile".');
                    }

                    */

                    if ($('div[data-testid="payment-methods-list"]').length > 0) {

                        var data_testid = $('div[data-testid="payment-methods-list"]');
                        //console.log('div[data-testid="payment-methods-list"]: True.');
                        if (data_testid.length > 0){

                            var imagenPayphone = data_testid.find('div[data-testid="payment-methods-list-item"]').eq(0).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPayphone.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphoneremovebg-preview-17060223265677.png?1708961570165');
                            // https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphoneremovebg-preview-17060223265677.png?1708961570165 - payphone
                            // https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeo-e1610717447192-16971452504121.png?1708961642956 - prometeo
                            
                            var imagenPrometeo = data_testid.find('div[data-testid="payment-methods-list-item"]').eq(1).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPrometeo.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeo-e1610717447192-16971452504121.png?1708961642956');

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
                            imagenPayphone.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphoneremovebg-preview-17060223265677.png?1708961570165');
                            //console.log('Payphone');

                        } 
                        if(text == 'Prometeo') {

                            var imagenPrometeo = style__HeroFall.find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPrometeo.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeo-e1610717447192-16971452504121.png?1708961642956');
                            //console.log('Prometeo');

                        } 
                    } else {
                        //console.log('div style__HeroFallbackContainer-sc-swzx38-5.ldbuIk: ocultado.'); 
                    }    
                                   
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
                    if ($('div.accountModal.accountModal--desktop').length > 0) {
                        //console.log('div accountModal accountModal--desktop: True');
        
                        var accountModal = $('div.accountModal.accountModal--desktop');
                        var carouselWrapper = accountModal.find('div.carousel__wrapper'); // Aquí es donde debes usar .find() en lugar de .$()
                        if (carouselWrapper.length > 0){
                            //console.log('div carousel__wrapper: True', carouselWrapper);
        
                            /*
                            var payphoneImage = carouselWrapper.find('div.payment__item-box:first').find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            payphoneImage.first().attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphonenobg-17086151185001.png?1708705781603');
                            */

                            var imagenPayphone = carouselWrapper.find('div.payment__item-box').eq(0).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPayphone.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphonenobg-17086151185001.png?1708705781603');

                            var imagenPrometeo = carouselWrapper.find('div.payment__item-box').eq(1).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPrometeo.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeonobg-17086149510158.png?1708707330596');

                            
        
                        } else {
                            //console.log('div carousel__wrapper: False');
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

