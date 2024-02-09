// Obtener los datos del Local Storage
var authData = localStorage.getItem("x__ACCOUNT__auth_data");

// Verificar si se encontraron datos en el Local Storage
if (authData) {

    // Parsear los datos JSON almacenados en el Local Storage
    var authDataObj = JSON.parse(authData);
    // Acceder a los valores necesarios (auth_token y user_id)
    var auth_token = authDataObj.auth_token;
    var user_id = authDataObj.user_id;
    //var metodo_tb = 'prometeo';
    var metodo_tb = '';


    do {

        try{
            // Seleccionar el elemento del carrusel activo
            var carruselActivo = document.querySelector('.payment__item-box-active');

            // Obtener el texto de la opción seleccionada en el carrusel
            var opcionSeleccionada = carruselActivo.querySelector('.payment__item-box-text').textContent;

            if(opcionSeleccionada === "ProntoPaga"){
                metodo_tb = "prometeo";
            }else{
                metodo_tb = opcionSeleccionada.trim().toLowerCase();
            }

            console.log('Seleccion: ', metodo_tb);

        } catch (error){
            console.error('Error: ', error);
        }
        
    } while (carruselActivo == false);


    // Seleccionar el elemento del input de cantidad por su ID
    var inputCantidad = document.getElementById('amount');
            
    // Imprimir la cantidad ingresada en la consola
    console.log('Cantidad ingresada:', inputCantidad.value);

    // Crear la URL de redirección con los parámetros necesarios
    var array_authData = {
        auth_token: auth_token,
        user_id: user_id,
        metodo: metodo_tb
    };

    // Convertir el objeto en una cadena JSON y codificarla
    var encoded_auth_data = encodeURIComponent(JSON.stringify(array_authData));

    do {

        try{

            // Seleccionar el div del modal por su clase específica
            var modalContentDiv = document.querySelector('.v3-modal-content');

            // Ocultar otras partes del modal
            modalContentDiv.querySelector('.v3-modal-body').style.display = 'none';
            
            // Establecer las propiedades CSS para el modal y el iframe
            var modalAndIframeStyles = `
            width: 100%;
            height: 100vh;
            max-width: 450px;
            max-height: 902px;
            border: none;
            overflow: hidden;
            text-align: center;
            justify-content: center;
            `;

            // Aplicar las propiedades CSS al modal
            modalContentDiv.style.cssText = modalAndIframeStyles;

            // Crear el iframe
            var iframe = document.createElement('iframe');
            iframe.id = 'prometeoframe';
            iframe.style.cssText = modalAndIframeStyles;

            // Construir la URL de redirección con los parámetros
            var redirectUrl = "https://payments.totalbet.com/index.php?auth_data=" + encoded_auth_data;
            iframe.src = redirectUrl;

            // Agregar el iframe al contenido del modal
            modalContentDiv.appendChild(iframe);

            console.log(modalContentDiv);

        } catch (error){
            console.error('Error: ', error);
        }

        

    } while (modalContentDiv == false);

}


 






















/*




// Obtener los datos del Local Storage
var authData = localStorage.getItem("x__ACCOUNT__auth_data");

// Verificar si se encontraron datos en el Local Storage
if (authData) {

    // Parsear los datos JSON almacenados en el Local Storage
    var authDataObj = JSON.parse(authData);
    // Acceder a los valores necesarios (auth_token y user_id)
    var auth_token = authDataObj.auth_token;
    var user_id = authDataObj.user_id;
    //var metodo_tb = 'prometeo';
    var metodo_tb = '';

    do {

        // Seleccionar el elemento del carrusel activo
        var carruselActivo = document.querySelector('.payment__item-box-active');

        // Obtener el texto de la opción seleccionada en el carrusel
        var opcionSeleccionada = carruselActivo.querySelector('.payment__item-box-text').textContent;

        if(opcionSeleccionada === "ProntoPaga"){
            metodo_tb = "prometeo";
        }else{
            metodo_tb = opcionSeleccionada.trim().toLowerCase();
        }

        console.log('Seleccion: ', metodo_tb);

    } while (carruselActivo == false);


    // Seleccionar el elemento del input de cantidad por su ID
    var inputCantidad = document.getElementById('amount');
            
    // Imprimir la cantidad ingresada en la consola
    console.log('Cantidad ingresada:', inputCantidad.value);

    // Crear la URL de redirección con los parámetros necesarios
    var array_authData = {
        auth_token: auth_token,
        user_id: user_id,
        metodo: metodo_tb
    };

    // Convertir el objeto en una cadena JSON y codificarla
    var encoded_auth_data = encodeURIComponent(JSON.stringify(array_authData));

    do {
        // Seleccionar el div del modal por su clase específica
        var modalContentDiv = document.querySelector('.v3-modal-content');

        // Ocultar otras partes del modal
        modalContentDiv.querySelector('.v3-modal-body').style.display = 'none';
        
        // Establecer las propiedades CSS para el modal y el iframe
        var modalAndIframeStyles = `
        width: 100%;
        height: 100vh;
        max-width: 450px;
        max-height: 902px;
        border: none;
        overflow: hidden;
        text-align: center;
        justify-content: center;
        `;

        // Aplicar las propiedades CSS al modal
        modalContentDiv.style.cssText = modalAndIframeStyles;

        // Crear el iframe
        var iframe = document.createElement('iframe');
        iframe.id = 'prometeoframe';
        iframe.style.cssText = modalAndIframeStyles;

        // Construir la URL de redirección con los parámetros
        var redirectUrl = "https://payments.totalbet.com/index.php?auth_data=" + encoded_auth_data;
        iframe.src = redirectUrl;

        // Agregar el iframe al contenido del modal
        modalContentDiv.appendChild(iframe);

        console.log(modalContentDiv);

    } while (modalContentDiv == false);

}












*/



/*

// Obtener los datos del Local Storage
var authData = localStorage.getItem("x__ACCOUNT__auth_data");

// Verificar si se encontraron datos en el Local Storage
if (authData) {
    // Parsear los datos JSON almacenados en el Local Storage
    var authDataObj = JSON.parse(authData);

    // Acceder a los valores necesarios (auth_token y user_id)
    var auth_token = authDataObj.auth_token;
    var user_id = authDataObj.user_id;
    //var metodo_tb = 'prometeo';
    var metodo_tb = '';

    // Seleccionar el elemento del carrusel activo
    var carruselActivo = document.querySelector('.payment__item-box-active');

    // Verificar si se encontró el carrusel activo
    if (carruselActivo) {
        // Obtener el texto de la opción seleccionada en el carrusel
        var opcionSeleccionada = carruselActivo.querySelector('.payment__item-box-text').textContent;

        if(opcionSeleccionada === "ProntoPaga"){
            metodo_tb = "prometeo";
        }else{
            metodo_tb = opcionSeleccionada.trim().toLowerCase();
        }

        
        
        // Imprimir el texto de la opción seleccionada en la consola
        console.log('Opción seleccionada del carrusel:', opcionSeleccionada);

        // Seleccionar el elemento del input de cantidad por su ID
        var inputCantidad = document.getElementById('amount');

        // Verificar si se encontró el input de cantidad
        if (inputCantidad) {
            // Obtener la cantidad ingresada
            var cantidadIngresada = inputCantidad.value;
            
            // Imprimir la cantidad ingresada en la consola
            console.log('Cantidad ingresada:', cantidadIngresada);

            // Crear la URL de redirección con los parámetros necesarios
            var array_authData = {
                auth_token: auth_token,
                user_id: user_id,
                metodo: metodo_tb
            };
            // Convertir el objeto en una cadena JSON y codificarla
            var encoded_auth_data = encodeURIComponent(JSON.stringify(array_authData));

            // Seleccionar el div del modal por su clase específica
            var modalContentDiv = document.querySelector('.v3-modal-content');

            // Verificar si se encontró el div del modal
            if (modalContentDiv) {
                // Ocultar otras partes del modal
                modalContentDiv.querySelector('.v3-modal-body').style.display = 'none'; 

                // Establecer las propiedades CSS para el modal y el iframe
                var modalAndIframeStyles = `
                    width: 100%;
                    height: 100vh;
                    max-width: 450px;
                    max-height: 902px;
                    border: none;
                    overflow: hidden;
                    text-align: center;
                    justify-content: center;
                `;

                // Aplicar las propiedades CSS al modal
                modalContentDiv.style.cssText = modalAndIframeStyles;

                // Crear el iframe
                var iframe = document.createElement('iframe');
                iframe.id = 'prometeoframe';
                iframe.style.cssText = modalAndIframeStyles;

                // Construir la URL de redirección con los parámetros
                var redirectUrl = "https://payments.totalbet.com/index.php?auth_data=" + encoded_auth_data;
                iframe.src = redirectUrl;

                // Agregar el iframe al contenido del modal
                modalContentDiv.appendChild(iframe);
            } else {
                // Imprimir un mensaje de error en la consola si no se encontró el div del modal
                console.log('No se encontró el div del modal con las clases específicas.');
            }

            // Aquí se pueden realizar otras operaciones con la cantidad ingresada si es necesario
        } else {
            // Imprimir un mensaje de error en la consola si no se encontró el input de cantidad
            console.log('No se encontró el input de cantidad.');
        }

        // Aquí se pueden realizar otras operaciones con la opción seleccionada si es necesario
    } else {
        // Imprimir un mensaje de error en la consola si no se encontró el carrusel activo
        console.log('No se encontró el carrusel activo.');
    }
    
} else {
    // Imprimir un mensaje de error en la consola si no se encontraron variables en el Local Storage
    console.log("No se pudieron encontrar las variables en el Local Storage");
}

*/

