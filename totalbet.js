console.log('Payments loaded.....');

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

                    if((window.location.pathname === '/' && window.location.search === '?accounts=%2A&wallet=%2A&deposit=%2A') ||
                        (window.location.pathname === '/' && window.location.search === '?accounts=%2A&wallet=%2A&deposit-methods=%2A')){
                        console.log('Payments ?accounts=%2A&wallet=%2A&deposit=%2A loaded.');

                        //$(node).find('.v3-modal-content').hide();
                        var modalContentDiv = node.querySelector('.v3-modal-content');
                        if (modalContentDiv){
                            // Ocultar el contenido existente del modal
                            modalContentDiv.querySelector('.v3-modal-body').style.display = 'none';

                            // Obtener los datos del Local Storage
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
                                console.log('Error authData not Found');
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
                            modalContentDiv.style.cssText = modalAndIframeStyles;

                            // Crear un objeto con los datos de autenticación
                            var array_authData = {
                                auth_token: auth_token,
                                user_id: user_id,
                                metodo: metodo_tb,
                                amount: inputCantidad.value
                            };

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




/*
// Crea una instancia de MutationObserver
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

                    

                    //$(node).find('.v3-modal-content').hide();
                    var modalContentDiv = node.querySelector('.v3-modal-content');
                    if (modalContentDiv){
                        // Ocultar el contenido existente del modal
                        modalContentDiv.querySelector('.v3-modal-body').style.display = 'none';

                        // Obtener los datos del Local Storage
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
                            console.log('Error authData not Found');
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
                                        metodo_tb = opcionSeleccionada.trim().toLowerCase();
                                    } else {
                                        metodo_tb = "vacio";
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
                        modalContentDiv.style.cssText = modalAndIframeStyles;

                        // Crear un objeto con los datos de autenticación
                        var array_authData = {
                            auth_token: auth_token,
                            user_id: user_id,
                            metodo: metodo_tb,
                            amount: inputCantidad.value
                        };

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

                    } else {
                        console.log('modalContentDiv Not Found: ');
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




