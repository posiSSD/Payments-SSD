console.log('Payments Logos loaded.....');
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

    var urls = [
        'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphonenobg-17086151185001.png?1708631320587',
        'https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/prometeonobg-17086149510158.png?1708631340258',
        'https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg',
        'https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg',
        'https://static.springbuilder.site/widgets-x/images/payment-default-icon.svg'
    ];

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
                                var imagen = style_hero.find('.style__HeroFallbackImg-sc-swzx38-2')
                                var text = style_hero.find('.payment__item-box-text')

                                imagen.attr('src','https://static.springbuilder.site/fs/userFiles-v2/totalbet-18751709/media/payphonenobg-17086151185001.png?1708631320587')

                                
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