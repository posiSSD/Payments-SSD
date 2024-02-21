console.log('Payments Logos loaded.....');
// Verificar si el body tiene la clase "mobile"
if (document.body.classList.contains("mobile")) {
    console.log("móvil.");

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