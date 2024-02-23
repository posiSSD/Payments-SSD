console.log('Payments Logos loaded.....');


// Función para manejar el evento popstate
function handlePopState(event) {
    console.log('Se ha producido un cambio en el historial de navegación:', event.state);
    // Obtener la URL actual
    var currentUrl = window.location.href;
    console.log('URL actual:', currentUrl);
}

// Agregar un listener para el evento popstate
window.addEventListener('popstate', handlePopState);



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

