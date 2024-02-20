console.log('Payments loaded.....');

var observer = new MutationObserver(function(mutationsList, observer) {
    mutationsList.forEach(function(mutation) {
        //console.log('Tipo de mutación:', mutation.type);
        // Verifica si se añadieron nuevos nodos
        if (mutation.type === 'childList') {
            //console.log('Nuevos nodos añadidos:', mutation.addedNodes);
            mutation.addedNodes.forEach(function(node) {

                try {

                    // Verifica si el nodo añadido es el modal
                    if ($(node).hasClass('v3-modal-root')) {
                        // Realiza las modificaciones necesarias en el contenido del modal
                        console.log('v3-modal-root FOUND.');

                        if (window.location.search.includes('?accounts=%2A&wallet=%2A&deposit=%2A') || window.location.search.includes('?accounts=%2A&wallet=%2A&deposit-methods=%2A')){
                            console.log('Payments ?accounts=%2A&wallet=%2A&deposit=%2A loaded.');

                            //$(node).find('.v3-modal-content').hide();
                            var modalContentDiv = node.querySelector('.v3-modal-content');
                            if (modalContentDiv){
                                console.log('.v3-modal-content FOUND');

                                modalContentDiv.style.borderRadius = '0px'
                                modalContentDiv.querySelector('.v3-modal-body').style.display = 'none';

                                try{
                                    modalContentDiv.querySelector('.v3-modal.style__ModalWrapper-sc-xfnom2-0.fxHqfr').style.margin = '0';
                                } catch (error){
                                    console.error('Error: ', error);
                                    var modalElement = document.querySelector('.v3-modal');
                                    if(modalElement){
                                        modalElement.style.margin = '0';
                                        //modal.style.width = 'auto';
                                        //width: auto;
                                        console.log('modalElement v3-modal FOUND');
                                    }else{
                                        console.log('modalElement v3-modal NOT FOUND');
                                    }
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

                                // Seleccionar el elemento del carrusel activo (para la versión web)
                                var carruselActivo = document.querySelector('.payment__item-box-active');
                                if (carruselActivo) {
                                    var opcionSeleccionada = carruselActivo.querySelector('.payment__item-box-text').textContent;
                                    metodo_tb = opcionSeleccionada.trim().toLowerCase();
                                    metodo_tb = 'prometeo';   //// probando con prometeo
                                    console.log('Web:', metodo_tb);
                                } else {
                                    // Selecciona el elemento con la clase style__HeroFallbackText-sc-swzx38-1 (para la versión celular)
                                    var paymentMethodElement = $('.style__HeroFallbackText-sc-swzx38-1');
                                    var opcionSeleccionada = paymentMethodElement.text();
                                    metodo_tb = opcionSeleccionada.trim().toLowerCase();
                                    metodo_tb = 'prometeo';   //// probando con prometeo
                                    console.log('Cell:', metodo_tb);
                                }
                                
                                 
                                // Seleccionar el elemento del input de cantidad por su ID
                                var inputCantidad = document.getElementById('amount');
                                console.log('inputCantidad value: ', inputCantidad.value);
                                                            
                                var max_width, max_height;

                                if ( metodo_tb === 'payphone' ) {
                                    max_width = '447px';
                                    max_height = '846px';
                                    /*
                                    max_width = '450px';
                                    max_height = '902px';
                                    */
                                } else if ( metodo_tb === 'prometeo' ) {
                                    max_width = '399px';
                                    max_height = '650px';
                                } else {
                                    max_width = '600px';
                                    max_height = '800px';
                                }

                                // Aplicar estilos al modal y al iframe
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
                                    amount: inputCantidad.value
                                };

                                //console.log('authData: ', array_authData);
                                // Convertir el objeto en una cadena JSON y codificarla
                                var encoded_auth_data = encodeURIComponent(JSON.stringify(array_authData));

                                // Crear el iframe
                                var iframe = document.createElement('iframe');
                                iframe.id = 'paymentsframe';
                                iframe.style.cssText = modalAndIframeStyles;

                                // Construir la URL de redirección con los parámetros
                                var redirectUrl = "https://payments.totalbet.com/index.php?auth_data=" + encoded_auth_data;
                                iframe.src = redirectUrl;

                                // Agregar el iframe al contenido del modal
                                modalContentDiv.appendChild(iframe);

                                ///////////////////////////////////////////////////////////////////////////
                                var iframe_element = document.getElementById('paymentsframe'); 
                                window.addEventListener('message', function(event) {
                                    // Verifica si el mensaje proviene del iframe esperado
                                    if (event.source === iframe_element.contentWindow) {
                                        // Maneja el mensaje recibido
                                        var estadoPago = event.data;
                                        // Realiza acciones basadas en el estado del pago recibido
                                        console.log('Estado del pago recibido:', estadoPago);
                                        if( estadoPago == 7) {

                                            iframe.remove();
                                            modalContentDiv.querySelector('.v3-modal-body').style.display = 'block';

                                            var successMessageSpan = modalContentDiv.querySelector('.payment-success-modal-message');
                                            // Verificar si se encontró el elemento
                                            if (successMessageSpan) {
                                                // Modificar el texto del span
                                                successMessageSpan.textContent = 'Tu Recarga $/'+inputCantidad.value+' se ha realizado con éxito'; 
                                            } else {
                                                console.log('Elemento span no encontrado');
                                            }

                                        } else if( estadoPago == 10 ){

                                            iframe.remove();
                                            modalContentDiv.querySelector('.v3-modal-body').style.display = 'block';

                                            var modalElement = document.querySelector('.style__ImageWrapper-sc-xfnom2-1');
                                            if (modalElement){
                                                modalElement.querySelector('.v3-icon').style.display = 'none';

                                                // Crear un elemento SVG
                                                var svgElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                                                // Establecer el tamaño del SVG
                                                svgElement.setAttribute('width', '72');
                                                svgElement.setAttribute('height', '72');
                                                // Crear el círculo rojo
                                                var circleElement = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                                                circleElement.setAttribute('cx', '36');
                                                circleElement.setAttribute('cy', '36');
                                                circleElement.setAttribute('r', '30'); // Radio del círculo
                                                circleElement.setAttribute('fill', 'red');
                                                // Crear la X blanca
                                                var line1 = document.createElementNS("http://www.w3.org/2000/svg", "line");
                                                line1.setAttribute('x1', '20');
                                                line1.setAttribute('y1', '20');
                                                line1.setAttribute('x2', '52');
                                                line1.setAttribute('y2', '52');
                                                line1.setAttribute('stroke', 'white');
                                                line1.setAttribute('stroke-width', '5');
                                                var line2 = document.createElementNS("http://www.w3.org/2000/svg", "line");
                                                line2.setAttribute('x1', '52');
                                                line2.setAttribute('y1', '20');
                                                line2.setAttribute('x2', '20');
                                                line2.setAttribute('y2', '52');
                                                line2.setAttribute('stroke', 'white');
                                                line2.setAttribute('stroke-width', '5');
                                                // Agregar los elementos al SVG
                                                svgElement.appendChild(circleElement);
                                                svgElement.appendChild(line1);
                                                svgElement.appendChild(line2);
                                                // Agregar el SVG al elemento modal
                                                modalElement.appendChild(svgElement);

                                            } else {
                                                console.log('Icon no encontrado');
                                            }

                                            var titleElement = document.querySelector('.style__Title-sc-xfnom2-2');
                                            // Verificar si se encontró el elemento
                                            if (titleElement) {
                                                // Cambiar el texto del elemento
                                                titleElement.textContent = '¡Declinado!';
                                            } else {
                                                console.error('Titulo no encontrado');
                                            }

                                            var successMessageSpan = modalContentDiv.querySelector('.payment-success-modal-message');
                                            // Verificar si se encontró el elemento
                                            if (successMessageSpan) {
                                                // Modificar el texto del span
                                                successMessageSpan.textContent = 'Tu Recarga $/'+inputCantidad.value+' se ha declinado'; 
                                            } else {
                                                console.log('Elemento span no encontrado');
                                            }

                                        } else if( estadoPago == 11 ){

                                            iframe.remove();
                                            modalContentDiv.querySelector('.v3-modal-body').style.display = 'block';

                                            var modalElement = document.querySelector('.style__ImageWrapper-sc-xfnom2-1');
                                            if (modalElement){
                                                modalElement.querySelector('.v3-icon').style.display = 'none';

                                                // Crear un elemento SVG
                                                var svgElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                                                // Establecer el tamaño del SVG
                                                svgElement.setAttribute('width', '72');
                                                svgElement.setAttribute('height', '72');
                                                // Crear el círculo rojo
                                                var circleElement = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                                                circleElement.setAttribute('cx', '36');
                                                circleElement.setAttribute('cy', '36');
                                                circleElement.setAttribute('r', '30'); // Radio del círculo
                                                circleElement.setAttribute('fill', 'red');
                                                // Crear la X blanca
                                                var line1 = document.createElementNS("http://www.w3.org/2000/svg", "line");
                                                line1.setAttribute('x1', '20');
                                                line1.setAttribute('y1', '20');
                                                line1.setAttribute('x2', '52');
                                                line1.setAttribute('y2', '52');
                                                line1.setAttribute('stroke', 'white');
                                                line1.setAttribute('stroke-width', '5');
                                                var line2 = document.createElementNS("http://www.w3.org/2000/svg", "line");
                                                line2.setAttribute('x1', '52');
                                                line2.setAttribute('y1', '20');
                                                line2.setAttribute('x2', '20');
                                                line2.setAttribute('y2', '52');
                                                line2.setAttribute('stroke', 'white');
                                                line2.setAttribute('stroke-width', '5');
                                                // Agregar los elementos al SVG
                                                svgElement.appendChild(circleElement);
                                                svgElement.appendChild(line1);
                                                svgElement.appendChild(line2);
                                                // Agregar el SVG al elemento modal
                                                modalElement.appendChild(svgElement);

                                            } else {
                                                console.log('Icon no encontrado');
                                            }

                                            var titleElement = document.querySelector('.style__Title-sc-xfnom2-2');
                                            // Verificar si se encontró el elemento
                                            if (titleElement) {
                                                // Cambiar el texto del elemento
                                                titleElement.textContent = 'Fallida!';
                                            } else {
                                                console.error('Titulo no encontrado');
                                            }

                                            var successMessageSpan = modalContentDiv.querySelector('.payment-success-modal-message');
                                            // Verificar si se encontró el elemento
                                            if (successMessageSpan) {
                                                // Modificar el texto del span
                                                successMessageSpan.textContent = 'Tu Recarga $/'+inputCantidad.value+' fallida'; 
                                            } else {
                                                console.log('Elemento span no encontrado');
                                            }

                                        } else {

                                        }
                                    }
                                });
                                
                                ///////////////////////////////////////////////////////////////////////////

                            } else {
                                console.log('.v3-modal-content Found: ');
                            }

                        } else {
                            console.log('Payments ?accounts=%2A&wallet=%2A&deposit=%2A not loaded.');
                        }
                    }

                } catch (error) {
                    console.error('Error: ', error);
                }

            });
        }
    });
});

// Observa los cambios en el cuerpo del documento y en sus descendientes
console.log('Watching changes on DOM...');
observer.observe(document.body, { childList: true, subtree: true });




/*

var observer = new MutationObserver(function(mutationsList, observer) {
    mutationsList.forEach(function(mutation) {
        //console.log('Tipo de mutación:', mutation.type);
        // Verifica si se añadieron nuevos nodos
        if (mutation.type === 'childList') {
            //console.log('Nuevos nodos añadidos:', mutation.addedNodes);
            mutation.addedNodes.forEach(function(node) {


                // Verifica si el nodo añadido es el modal
                if ($(node).hasClass('v3-modal-root')) {
                    // Realiza las modificaciones necesarias en el contenido del modal
                    console.log('v3-modal-root FOUND.');

                    if (window.location.search.includes('?accounts=%2A&wallet=%2A&deposit=%2A') || window.location.search.includes('?accounts=%2A&wallet=%2A&deposit-methods=%2A')){
                        console.log('Payments ?accounts=%2A&wallet=%2A&deposit=%2A loaded.');

                        //$(node).find('.v3-modal-content').hide();
                        var modalContentDiv = node.querySelector('.v3-modal-content');
                        if (modalContentDiv){
                            
                            // poner margin 0 !important
                            //modalContentDiv.querySelector('.v3-modal.style__ModalWrapper-sc-xfnom2-0.fxHqfr').style.margin = '0';
                            modalContentDiv.querySelector('.v3-modal.style__ModalWrapper-sc-xfnom2-0.fxHqfr').style.setProperty('margin', '0', 'important');
                            // Ocultar el contenido existente del modal
                            modalContentDiv.querySelector('.v3-modal-body').style.display = 'none';
                            
                            var authData = localStorage.getItem("x__ACCOUNT__auth_data");
                            if(authData){
                                // Parsear los datos JSON almacenados en el Local Storage
                                var authDataObj = JSON.parse(authData);
                                // Acceder a los valores necesarios (auth_token y user_id)
                                var auth_token = authDataObj.auth_token;
                                var user_id = authDataObj.user_id;
                                //var metodo_tb = 'prometeo';
                                var metodo_tb = '';

                                console.log('authData Found');

                            } else {
                                console.log('authData not Found');
                            }

                            try {
                                // Seleccionar el elemento del carrusel activo (para la versión web)
                                var carruselActivo = document.querySelector('.payment__item-box-active');
                                if (carruselActivo) {
                                    var opcionSeleccionada = carruselActivo.querySelector('.payment__item-box-text').textContent;
                                    if (opcionSeleccionada === "ProntoPaga") {
                                        metodo_tb = "prometeo";
                                    } else {
                                        metodo_tb = opcionSeleccionada.trim().toLowerCase();
                                    }
                                    console.log('Web:', metodo_tb);
                                } else {
                                    // Selecciona el elemento con la clase style__HeroFallbackText-sc-swzx38-1 (para la versión celular)
                                    var paymentMethodElement = $('.style__HeroFallbackText-sc-swzx38-1');
                                    var opcionSeleccionada = paymentMethodElement.text();
                                    if(opcionSeleccionada){
                                        if(opcionSeleccionada === "ProntoPaga"){
                                            metodo_tb = "prometeo";
                                        } else if (opcionSeleccionada === "Payphone") {
                                            metodo_tb = opcionSeleccionada.trim().toLowerCase();window.location.search
                                        }
                                    } else {
                                        console.log('CELL - opcionSeleccionada empty', metodo_tb);
                                    }
        
                                    console.log('Cell:', metodo_tb);
                                }
                            } catch (error) {
                                console.error('Error: ', error);
                            }
                            

                            // Seleccionar el elemento del input de cantidad por su ID
                            var inputCantidad = document.getElementById('amount');

                            console.log('inputCantidad value: ', inputCantidad.value);


                            var max_width, max_height;

                            if ( metodo_tb === 'payphone' ) {
                                max_width = '450px';
                                max_height = '902px';
                            } else if ( metodo_tb === 'prometeo' ) {
                                max_width = '399px';
                                max_height = '650px';
                            } else {
                                max_width = '600px';
                                max_height = '800px';
                            }

                            // Aplicar estilos al modal y al iframe
                            var modalAndIframeStyles = `
                                width: 100%;
                                height: 100vh;
                                max-width: ${max_width};
                                max-height: ${max_height};
                                border: none;
                                overflow: hidden;
                                text-align: center;
                                justify-content: center;
                            `;
                            // Aplicar las propiedades CSS al modal
                            //modalContentDiv.style.cssText = modalAndIframeStyles;

                            // Crear un objeto con los datos de autenticación
                            var array_authData = {
                                auth_token: auth_token,
                                user_id: user_id,
                                metodo: metodo_tb,
                                amount: inputCantidad.value
                            };

                            console.log('authData: ', array_authData);
                            // Convertir el objeto en una cadena JSON y codificarla
                            var encoded_auth_data = encodeURIComponent(JSON.stringify(array_authData));

                            // Crear el iframe
                            var iframe = document.createElement('iframe');
                            iframe.id = 'paymentsframe';
                            iframe.style.cssText = modalAndIframeStyles;

                            // Construir la URL de redirección con los parámetros
                            var redirectUrl = "https://payments.totalbet.com/index.php?auth_data=" + encoded_auth_data;
                            iframe.src = redirectUrl;

                            // Agregar el iframe al contenido del modal
                            modalContentDiv.appendChild(iframe);


                            ///////////////////////////////////////////////////////////////////////////

                            var iframe_element = document.getElementById('paymentsframe'); 
                            // Configura un event listener para escuchar mensajes del iframe
                            window.addEventListener('message', function(event) {
                                // Verifica si el mensaje proviene del iframe esperado
                                if (event.source === iframe_element.contentWindow) {
                                    // Maneja el mensaje recibido
                                    var estadoPago = event.data;
                                    // Realiza acciones basadas en el estado del pago recibido
                                    console.log('Estado del pago recibido:', estadoPago);
                                }
                            });

                            ///////////////////////////////////////////////////////////////////////////



                        } else {
                            console.log('modalContentDiv Not Found: ');
                        }

                    } else {
                        console.log('Payments ?accounts=%2A&wallet=%2A&deposit=%2A not loaded.');
                    }
                }

                
            });
        }
    });
});

// Observa los cambios en el cuerpo del documento y en sus descendientes
console.log('Watching changes on DOM...');
observer.observe(document.body, { childList: true, subtree: true });




*/


//////////////////////////JQUERY//////////////////////////////////////////
/*

$(document).ready(function() {
    console.log('Payments loaded.....');

    var observer = new MutationObserver(function(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    try {
                        // Verifica si el nodo añadido es el modal
                        if ($(node).hasClass('v3-modal-root')) {
                            console.log('v3-modal-root FOUND.');

                            if (window.location.search.includes('?accounts=%2A&wallet=%2A&deposit=%2A') || window.location.search.includes('?accounts=%2A&wallet=%2A&deposit-methods=%2A')){
                                console.log('Payments ?accounts=%2A&wallet=%2A&deposit=%2A loaded.');

                                var modalContentDiv = $(node).find('.v3-modal-content');
                                if (modalContentDiv.length > 0){
                                    modalContentDiv.find('.v3-modal-body').hide();
                                    
                                    var authData = localStorage.getItem("x__ACCOUNT__auth_data");
                                    if(authData){
                                        var authDataObj = JSON.parse(authData);
                                        var auth_token = authDataObj.auth_token;
                                        var user_id = authDataObj.user_id;
                                        var metodo_tb = '';

                                        console.log('authData Found');

                                    } else {
                                        console.log('authData not Found');
                                    }

                                    var carruselActivo = $('.payment__item-box-active');
                                    if (carruselActivo.length > 0) {
                                        var opcionSeleccionada = carruselActivo.find('.payment__item-box-text').text();
                                        metodo_tb = opcionSeleccionada.trim().toLowerCase();
                                        console.log('Web:', metodo_tb);
                                    } else {
                                        var paymentMethodElement = $('.style__HeroFallbackText-sc-swzx38-1');
                                        var opcionSeleccionada = paymentMethodElement.text();
                                        metodo_tb = opcionSeleccionada.trim().toLowerCase();
                                        console.log('Cell:', metodo_tb);
                                    }

                                    var inputCantidad = $('#amount');
                                    if (carruselActivo.length > 0){
                                        console.log('inputCantidad value: ', inputCantidad.val());
                                    } else {
                                        console.log('inputCantidad value: Not found');
                                    }

                                    var max_width, max_height;

                                    if ( metodo_tb === 'payphone' ) {
                                        max_width = '450px';
                                        max_height = '902px';
                                    } else if ( metodo_tb === 'prometeo' ) {
                                        max_width = '399px';
                                        max_height = '650px';
                                    } else {
                                        max_width = '600px';
                                        max_height = '800px';
                                    }

                                    var modalAndIframeStyles = {
                                        width: '100%',
                                        height: '100vh',
                                        'max-width': max_width,
                                        'max-height': max_height,
                                        border: 'none',
                                        overflow: 'hidden',
                                        'text-align': 'center',
                                        'justify-content': 'center'
                                    };

                                    var array_authData = {
                                        auth_token: auth_token,
                                        user_id: user_id,
                                        metodo: metodo_tb,
                                        amount: inputCantidad.val()
                                    };

                                    console.log('authData: ', array_authData);
                                    var encoded_auth_data = encodeURIComponent(JSON.stringify(array_authData));

                                    var iframe = $('<iframe>', {
                                        id: 'paymentsframe',
                                        css: modalAndIframeStyles,
                                        src: "https://payments.totalbet.com/index.php?auth_data=" + encoded_auth_data
                                    });

                                    modalContentDiv.append(iframe);

                                    var iframe_element = $('#paymentsframe');
                                    window.addEventListener('message', function(event) {
                                        if (event.source === iframe_element[0].contentWindow) {
                                            var estadoPago = event.data;
                                            console.log('Estado del pago recibido:', estadoPago);
                                        }
                                    });

                                } else {
                                    console.log('modalContentDiv Not Found: ');
                                }

                            } else {
                                console.log('Payments ?accounts=%2A&wallet=%2A&deposit=%2A not loaded.');
                            }
                        }

                    } catch (error) {
                        console.error('Error: ', error);
                    }

                });
            }
        });
    });

    console.log('Watching changes on DOM...');
    observer.observe(document.body, { childList: true, subtree: true });
});


*/
///////////////////////////////////////JS/////////////////////////////////////////
/*

console.log('Payments loaded.....');

document.addEventListener("DOMContentLoaded", function() {
    var observer = new MutationObserver(function(mutationsList, observer) {
        mutationsList.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    try {
                        // Verifica si el nodo añadido es el modal
                        if (node.classList.contains('v3-modal-root')) {
                            console.log('v3-modal-root FOUND.');

                            if (window.location.search.includes('?accounts=%2A&wallet=%2A&deposit=%2A') || window.location.search.includes('?accounts=%2A&wallet=%2A&deposit-methods=%2A')){
                                console.log('Payments ?accounts=%2A&wallet=%2A&deposit=%2A loaded.');

                                var modalContentDiv = node.querySelector('.v3-modal-content');
                                if (modalContentDiv){
                                    modalContentDiv.querySelector('.v3-modal-body').style.display = 'none';
                                    
                                    var authData = localStorage.getItem("x__ACCOUNT__auth_data");
                                    if(authData){
                                        var authDataObj = JSON.parse(authData);
                                        var auth_token = authDataObj.auth_token;
                                        var user_id = authDataObj.user_id;
                                        var metodo_tb = '';

                                        console.log('authData Found');

                                    } else {
                                        console.log('authData not Found');
                                    }

                                    var carruselActivo = document.querySelector('.payment__item-box-active');
                                    if (carruselActivo) {
                                        var opcionSeleccionada = carruselActivo.querySelector('.payment__item-box-text').textContent;
                                        metodo_tb = opcionSeleccionada.trim().toLowerCase();
                                        console.log('Web:', metodo_tb);
                                    } else {
                                        var paymentMethodElement = document.querySelector('.style__HeroFallbackText-sc-swzx38-1');
                                        var opcionSeleccionada = paymentMethodElement.textContent;
                                        metodo_tb = opcionSeleccionada.trim().toLowerCase();
                                        console.log('Cell:', metodo_tb);
                                    }

                                    var inputCantidad = document.getElementById('amount');
                                    if (carruselActivo){
                                        console.log('inputCantidad value: ', inputCantidad.value);
                                    } else {
                                        console.log('inputCantidad value: Not found');
                                    }

                                    var max_width, max_height;

                                    if ( metodo_tb === 'payphone' ) {
                                        max_width = '450px';
                                        max_height = '902px';
                                    } else if ( metodo_tb === 'prometeo' ) {
                                        max_width = '399px';
                                        max_height = '650px';
                                    } else {
                                        max_width = '600px';
                                        max_height = '800px';
                                    }

                                    var modalAndIframeStyles = `
                                        width: 100%;
                                        height: 100vh;
                                        max-width: ${max_width};
                                        max-height: ${max_height};
                                        border: none;
                                        overflow: hidden;
                                        text-align: center;
                                        justify-content: center;
                                    `;

                                    var array_authData = {
                                        auth_token: auth_token,
                                        user_id: user_id,
                                        metodo: metodo_tb,
                                        amount: inputCantidad.value
                                    };

                                    console.log('authData: ', array_authData);
                                    var encoded_auth_data = encodeURIComponent(JSON.stringify(array_authData));

                                    var iframe = document.createElement('iframe');
                                    iframe.id = 'paymentsframe';
                                    iframe.style.cssText = modalAndIframeStyles;
                                    iframe.src = "https://payments.totalbet.com/index.php?auth_data=" + encoded_auth_data;

                                    modalContentDiv.appendChild(iframe);

                                    var iframe_element = document.getElementById('paymentsframe');
                                    window.addEventListener('message', function(event) {
                                        if (event.source === iframe_element.contentWindow) {
                                            var estadoPago = event.data;
                                            console.log('Estado del pago recibido:', estadoPago);
                                        }
                                    });

                                } else {
                                    console.log('modalContentDiv Not Found: ');
                                }

                            } else {
                                console.log('Payments ?accounts=%2A&wallet=%2A&deposit=%2A not loaded.');
                            }
                        }

                    } catch (error) {
                        console.error('Error: ', error);
                    }

                });
            }
        });
    });

    console.log('Watching changes on DOM...');
    observer.observe(document.body, { childList: true, subtree: true });
});


*/