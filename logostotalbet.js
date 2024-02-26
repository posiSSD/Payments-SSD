console.log('Payments Logos loaded.....');

if (document.body.classList.contains("mobile")) {

    console.log("móvil.");

    
    function handleBodyChanges(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            
            if (mutation.type === 'childList') {
                
                try{

                    if ($('div.accountModal.accountModal--mobile').length > 0) {

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

                        }

                    } else {

                    }

                    /*

                    if ($('div[data-testid="payment-methods-list"]').length > 0) {

                        var data_testid = $('div[data-testid="payment-methods-list"]');
                        console.log('div data-testid: ', data_testid);
                        if (data_testid.length > 0){

                            var imagenPayphone = data_testid.find('div[data-testid="payment-methods-list-item"]').eq(0).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPayphone.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphoneremovebg-preview-17060223265677.png?1708961570165');
                            // https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphoneremovebg-preview-17060223265677.png?1708961570165 - payphone
                            // https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeo-e1610717447192-16971452504121.png?1708961642956 - prometeo
                            
                            var imagenPrometeo = data_testid.find('div[data-testid="payment-methods-list-item"]').eq(1).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPrometeo.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeo-e1610717447192-16971452504121.png?1708961642956');

                        }
                        
                    } 
                    */

                    /*
                    if ($('div.style__HeroFallbackContainer-sc-swzx38-5.ldbuIk').length > 0) {
                        var style__HeroFall = $('div.style__HeroFallbackContainer-sc-swzx38-5.ldbuIk');
                        var text = style__HeroFall.find('.style__HeroFallbackText-sc-swzx38-1').text();
                        console.log('Texto del div style__HeroFallbackText-sc-swzx38-1: ', text,' ');
                        if(text == 'Payphone'){

                            var imagenPayphone = style__HeroFall.find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPayphone.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphoneremovebg-preview-17060223265677.png?1708961570165');
                            console.log('Payphone: ', imagenPayphone);

                        } 
                        if(text == 'Prometeo') {

                            var imagenPrometeo = style__HeroFall.find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPrometeo.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeo-e1610717447192-16971452504121.png?1708961642956');
                            console.log('Prometeo: ', imagenPayphone);

                        } 
                    } else {
                        console.log('div style__HeroFallbackContainer-sc-swzx38-5.ldbuIk: ocultado.'); 
                    }    
                    */

                    /*
                        <div class="style__HeroBoxContainer-sc-swzx38-3 iUYPsD">
                            <div class="style__HeroBox-sc-swzx38-4 hNRfBY payment__item-box-hero">
                                <div class="style__HeroFallbackContainer-sc-swzx38-5 ldbuIk">
                                    <img src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg" loading="lazy" class="style__HeroFallbackImg-sc-swzx38-2 FxEuk">
                                    <div class="style__HeroFallbackText-sc-swzx38-1 erQcHc payment__item-box-text">Payphone</div>
                                </div>
                            </div>
                        </div>
                        style__HeroBoxContainer-sc-swzx38-3 iUYPsD
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

    console.log("Desktop.");

    // Función para manejar la detección de cambios en el cuerpo del documento
    function handleBodyChanges(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            // Verificar si se añadieron o eliminaron nodos
            if (mutation.type === 'childList') {
                // Verificar si se añadió el div accountModal accountModal--desktop
                try{
                    if ($('div.accountModal.accountModal--desktop').length > 0) {
                        console.log('div accountModal accountModal--desktop: True');
        
                        var accountModal = $('div.accountModal.accountModal--desktop');
                        var carouselWrapper = accountModal.find('div.carousel__wrapper'); // Aquí es donde debes usar .find() en lugar de .$()
                        if (carouselWrapper.length > 0){
                            console.log('div carousel__wrapper: True', carouselWrapper);
        
                            /*
                            var payphoneImage = carouselWrapper.find('div.payment__item-box:first').find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            payphoneImage.first().attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphonenobg-17086151185001.png?1708705781603');
                            */

                            var imagenPayphone = carouselWrapper.find('div.payment__item-box').eq(0).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPayphone.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphonenobg-17086151185001.png?1708705781603');

                            var imagenPrometeo = carouselWrapper.find('div.payment__item-box').eq(1).find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
                            imagenPrometeo.attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeonobg-17086149510158.png?1708707330596');

                            
        
                        } else {
                            console.log('div carousel__wrapper: False');
                        }
                        
                    } else {
                        console.log('El div accountModal accountModal--desktop se ha ocultado.');
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




/*
// Cambiar la imagen de Payphone solo en el primer div.payment__item-box
var payphoneImage = $('div.payment__item-box:first').find('img[src="https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg"]');
payphoneImage.first().attr('src', 'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeonobg-17086149510158.png?1708704142798');
*/


/*
var observer = new MutationObserver(function(mutationsList, observer) {
    for(var mutation of mutationsList) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'href') {
            console.log('La URL ha cambiado:', window.location.href);
            // Realizar acciones necesarias cuando cambia la URL
        }
    }
});

// Observar cambios en el atributo href de todos los elementos <a>
observer.observe(document.body, { attributes: true, subtree: true, attributeFilter: ['href'] });

*/


/*
// Función que se ejecuta cuando se detecta un cambio en el cuerpo del documento
function onBodyClassChange(mutationsList, observer) {
    mutationsList.forEach(function(mutation) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
            // Acceder a la nueva lista de clases del body
            var newClassList = mutation.target.classList;
            console.log('Se detectó un cambio en las clases del body:', newClassList);
            // Aquí puedes realizar las acciones necesarias en función de las clases agregadas o eliminadas
        }
    });
}

// Crear una instancia de MutationObserver
var bodyObserver = new MutationObserver(onBodyClassChange);

// Observar cambios en los atributos del body, específicamente en el atributo class
bodyObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });
*/


/*

// Verificar si el body tiene la clase "mobile"
if (document.body.classList.contains("mobile")) {
    console.log("móvil.");

    // Función que se ejecuta cuando se detecta un cambio en las clases del div deseado
    function onClassChange(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            // Verificar si se modificaron los atributos del div deseado
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                var targetNode = mutation.target;
                // Verificar si el div deseado tiene las clases requeridas
                if (targetNode.classList.contains('style__Container-sc-g7ftgu-1') && 
                    targetNode.classList.contains('kYpkWH') && 
                    targetNode.classList.contains('account-popup') && 
                    targetNode.classList.contains('account-popup-open')) {
                    console.log('Se detectó el cambio de clases en el div deseado:', targetNode);
                    // Agrega aquí tu código para cargar el script o realizar otras acciones necesarias.
                }
            }
        });
    }

    // Crear una instancia de MutationObserver para observar cambios en el DOM
    var observer = new MutationObserver(onClassChange);

    // Observar cambios en el DOM, incluidos los descendientes del body
    observer.observe(document.body, { attributes: true, subtree: true });
    
} 
// Verificar si el body tiene la clase "desktop"
else if (document.body.classList.contains("desktop")) {
    console.log("desktop.");

    // Función que se ejecuta cuando se detecta la aparición del div deseado
    function onDivAppear(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            // Verificar si se añadió un nuevo nodo al DOM
            if (mutation.type === 'childList') {
                // Verificar si el nuevo nodo es el div deseado
                mutation.addedNodes.forEach(function(node) {
                    // Buscar hacia arriba en el árbol DOM hasta encontrar el div "payment__carousel-wrapper"

                    var decodedURL = decodeURIComponent(window.location.search);
                    if (decodedURL.includes('?accounts=*&wallet=*&deposit=*') ||
                        decodedURL.includes('?accounts=*&wallet=*&deposit-methods=*')) {   

                        console.log('?accounts=*&wallet=*&deposit-methods=* Go');

                        var carouselWrapper = $(node).closest('.carousel__wrapper');
                        if (carouselWrapper.length > 0) {
                            console.log('Se encontró el div carousel__wrapperr:', carouselWrapper);
                            
                            var paymentItemBoxes = carouselWrapper.find('.payment__item-box');
                            paymentItemBoxes.each(function() {
                                var paymentItem = $(this);
                                console.log('Se encontró el div paymentItem:', paymentItem);
                                var image = paymentItem.find('img');
                                if (image.length > 0) {
                                    console.log('Se encontró la etiqueta <img>:', image);
                                    // Realizar las operaciones necesarias con la imagen
                                } else {
                                    console.log('No se encontró la etiqueta <img> dentro del paymentItem.');
                                }
                            });
                        } else {
                            console.log('No se encontró el div payment__carousel-wrapper.');
                        }

                    } else {
                        console.log('?accounts=*&wallet=*&deposit-methods=* No go');
                    }



                   
                });
            }
        });
    }

    // Crear una instancia de MutationObserver
    var observer = new MutationObserver(onDivAppear);
    // Observar cambios en el DOM, incluidos los descendientes del body
    observer.observe(document.body, { childList: true, subtree: true });

*/    
    
    /*

    // Función que se ejecuta cuando se detecta la aparición del div deseado
    function onDivAppear(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            // Verificar si se añadió un nuevo nodo al DOM
            if (mutation.type === 'childList') {
                // Verificar si el nuevo nodo es el div deseado
                mutation.addedNodes.forEach(function(node) {
                    if (node.classList && node.classList.contains('accountModal') && node.classList.contains('accountModal--desktop')) {
                        console.log('Se detectó la aparición del div deseado:', node);

                        var carouselWrapper = $(node).find('.carousel__wrapper');
                        if (carouselWrapper.length > 0) {
                            console.log('Se encontró el div carousel__wrapper:', carouselWrapper);
                            carouselWrapper.find('.payment__item-box').each(function(index) {
                                var paymentItem = $(this);
                                console.log('Se encontró el div payment__item:', paymentItem);
                                
                                var style_hero = paymentItem.find('.style__HeroFallbackContainer-sc-swzx38-5');
                                if(style_hero){
                                    console.log('Se encontró el div style__HeroF:', style_hero);

                                    var imagen = style_hero.find('.style__HeroFallbackImg-sc-swzx38-2');
                                    var text = style_hero.find('.payment__item-box-text')
                                    if(imagen){
                                        console.log('Elemento <img>:', imagen[0]);
                                    } else  {

                                    }
                                } else{

                                }                                                  
                            });
                        } else {
                            console.log('No se encontró el div carousel__wrapper dentro de accountModal.');
                        }
                    }
                });
            }
        });
    }

    // Crear una instancia de MutationObserver
    var observer = new MutationObserver(onDivAppear);
    // Observar cambios en el DOM, incluidos los descendientes del body
    observer.observe(document.body, { childList: true, subtree: true });
      
    

} 
// En caso de que no tenga ninguna de las clases especificadas
else {
    console.log("none.");
}

*/




/*
// Función que se ejecuta cuando se detecta la aparición del div deseado
function onDivAppear(mutationsList, observer) {
    mutationsList.forEach(function(mutation) {
        // Verificar si se añadió un nuevo nodo al DOM
        if (mutation.type === 'childList') {
            // Verificar si el nuevo nodo es el div deseado
            mutation.addedNodes.forEach(function(node) {
                if (node.classList && node.classList.contains('accountModal') && node.classList.contains('accountModal--desktop')) {
                    console.log('Se detectó la aparición del div deseado:', node);
                    // Agrega aquí tu código para cargar el script o realizar otras acciones necesarias.
                }
            });
        }
    });
}

// Crear una instancia de MutationObserver
var observer = new MutationObserver(onDivAppear);

// Observar cambios en el DOM, incluidos los descendientes del body
observer.observe(document.body, { childList: true, subtree: true });


// Función que se ejecuta cuando se detecta el cambio en las clases del div deseado
function onClassChange(mutationsList, observer) {
    mutationsList.forEach(function(mutation) {
        // Verificar si se modificaron los atributos del div deseado
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
            var targetNode = mutation.target;
            // Verificar si el div deseado tiene las clases requeridas
            if (targetNode.classList.contains('style__Container-sc-g7ftgu-1') && 
                targetNode.classList.contains('kYpkWH') && 
                targetNode.classList.contains('account-popup') && 
                targetNode.classList.contains('account-popup-open')) {
                console.log('Se detectó el cambio de clases en el div deseado:', targetNode);
                // Obtener el elemento div deseado
                var targetNode = document.querySelector('.style__Container-sc-g7ftgu-1.account-popup');
            }
        }
    });
}

// Crear una instancia de MutationObserver
var observer = new MutationObserver(onClassChange);

// Observar cambios en los atributos del elemento div deseado
observer.observe(targetNode, { attributes: true });






// Decodificar la cadena de consulta de la URL
var decodedURL = decodeURIComponent(window.location.search);
if (decodedURL.includes('?accounts=*&wallet=*&deposit=*') || decodedURL.includes('?accounts=*&wallet=*&deposit-methods=*')) {
    console.log("Loading Logos Payments");
    // Crear un elemento script
    var scriptElement = document.createElement('script');
    // Establecer el atributo src del script
    scriptElement.src = 'https://payments.totalbet.com/logostotalbet.js';
    // Insertar el elemento script en el body
    document.body.appendChild(scriptElement);
}
else{
    console.log("Not Loading Logos Payments");
}
*/

/*
// Función que se ejecuta cuando se detecta la aparición del div deseado
function onDivAppear(mutationsList, observer) {
    mutationsList.forEach(function(mutation) {
        // Verificar si se añadió un nuevo nodo al DOM
        if (mutation.type === 'childList') {
            // Verificar si el nuevo nodo es el div deseado
            mutation.addedNodes.forEach(function(node) {
                if (node.classList && node.classList.contains('accountModal') && node.classList.contains('accountModal--desktop')) {
                    console.log('Se detectó la aparición del div deseado:', node);
                    // Agrega aquí tu código para cargar el script o realizar otras acciones necesarias.
                }
            });
        }
    });
}

// Crear una instancia de MutationObserver
var observer = new MutationObserver(onDivAppear);

// Observar cambios en el DOM, incluidos los descendientes del body
observer.observe(document.body, { childList: true, subtree: true });


// Función que se ejecuta cuando se detecta el cambio en las clases del div deseado
function onClassChange(mutationsList, observer) {
    mutationsList.forEach(function(mutation) {
        // Verificar si se modificaron los atributos del div deseado
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
            var targetNode = mutation.target;
            // Verificar si el div deseado tiene las clases requeridas
            if (targetNode.classList.contains('style__Container-sc-g7ftgu-1') && 
                targetNode.classList.contains('kYpkWH') && 
                targetNode.classList.contains('account-popup') && 
                targetNode.classList.contains('account-popup-open')) {
                console.log('Se detectó el cambio de clases en el div deseado:', targetNode);
                // Agrega aquí tu código para cargar el script o realizar otras acciones necesarias.
                // Obtener el elemento div deseado
                var targetNode = document.querySelector('.style__Container-sc-g7ftgu-1.account-popup');
            }
        }
    });
}

// Crear una instancia de MutationObserver
var observer = new MutationObserver(onClassChange);

// Observar cambios en los atributos del elemento div deseado
observer.observe(targetNode, { attributes: true });
*/

/*

<body class="desktop  txlive " style="background:#FFFFFF;">

<body class="mobile txlive" style="background: rgb(255, 255, 255); overflow: visible;">

*/
/*
<html class="mobile v3-embedded uc-scrollbar mouse" prefix="og: http://ogp.me/ns#" lang="es" data-theme="v3-light" dir="ltr" itemscope="" itemtype="http://schema.org/WebSite" data-page-type="pageBlank" data-device-type="mobile" style="overflow: visible;">
<html class="mobile v3-embedded uc-scrollbar mouse accountModal--html--overflow--hidden" prefix="og: http://ogp.me/ns#" lang="es" data-theme="v3-light" dir="ltr" itemscope="" itemtype="http://schema.org/WebSite" data-page-type="pageBlank" data-device-type="mobile" style="overflow: visible;">
<html class="desktop v3-embedded uc-scrollbar mouse" prefix="og: http://ogp.me/ns#" lang="es" data-theme="v3-light" dir="ltr" itemscope="" itemtype="http://schema.org/WebSite" data-page-type="pageBlank" data-device-type="desktop" style=""><head><meta http-equiv="origin-trial" content="A89JPrWYXvEpNQ/xE+PjjlGJiBu/L2GfQcplC/QkDJOS1fBoX5Q4/HLfT1dXpD1td7C2peXE3bSCJiYdwoFcNgQAAACSeyJvcmlnaW4iOiJodHRwczovL3JlY2FwdGNoYS5uZXQ6NDQzIiwiZmVhdHVyZSI6IkRpc2FibGVUaGlyZFBhcnR5U3RvcmFnZVBhcnRpdGlvbmluZyIsImV4cGlyeSI6MTcyNTQwNzk5OSwiaXNTdWJkb21haW4iOnRydWUsImlzVGhpcmRQYXJ0eSI6dHJ1ZX0=">
<html class="desktop v3-embedded uc-scrollbar mouse" prefix="og: http://ogp.me/ns#" lang="es" data-theme="v3-light" dir="ltr" itemscope="" itemtype="http://schema.org/WebSite" data-page-type="pageBlank" data-device-type="desktop" style="overflow: hidden;"><head><meta http-equiv="origin-trial" content="A89JPrWYXvEpNQ/xE+PjjlGJiBu/L2GfQcplC/QkDJOS1fBoX5Q4/HLfT1dXpD1td7C2peXE3bSCJiYdwoFcNgQAAACSeyJvcmlnaW4iOiJodHRwczovL3JlY2FwdGNoYS5uZXQ6NDQzIiwiZmVhdHVyZSI6IkRpc2FibGVUaGlyZFBhcnR5U3RvcmFnZVBhcnRpdGlvbmluZyIsImV4cGlyeSI6MTcyNTQwNzk5OSwiaXNTdWJkb21haW4iOnRydWUsImlzVGhpcmRQYXJ0eSI6dHJ1ZX0=">

*/

