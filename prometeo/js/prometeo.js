var dataresponse = null;
var datarespuesta = null;
var dataintent_id = null;
var dataexternal_id = null;
var respuesta = null;
var apiKey = "SKEyYnMt1OGIoMX0gpAy0xPJLrgh2e5p8jp3vGrZyjqO1wbuIJDKPuSHKxpIFynA";
var apiUrl = "https://payment.prometeoapi.net/api/v1/payment-link/";
var auth_token = "FAE2579BC8325A2F60B432173CEF4D77";

//crear nro RND
const randomNumber = Math.floor(Math.random() * (Math.pow(10, 9)));
console.log("numero RND");
console.log(randomNumber);

var requestData = {
  "product_id": "aa2b08c8-b9e1-4fb2-a971-c3ec850c5692",
  "external_id": randomNumber.toString(),
  "concept": "1234",
  "currency": "PEN",
  "amount": "1",
  "expires_at": "2023-12-21T21:25:37.311Z",
  "email": "test@prometeoapi.com",
  "reusable": false
};

// Configuración de la solicitud
var requestOptions = {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-API-Key': apiKey,
    //'Authorization': "Bearer " + auth_token
  },
  body: JSON.stringify(requestData)
  
};


// Realizar la solicitud POST
fetch(apiUrl, requestOptions)
  .then(response => response.json())
  .then(data => {
    console.log("Respuesta de la API:");
    console.log(data);
    dataresponse = data;
    save_response_to_bd(dataresponse);
    details_payment_link(dataresponse);
    //

    var url = data.url; // La URL proporcionada en la respuesta
    var iframe = document.createElement('iframe');
    iframe.src = url;
    iframe.width = "800"; // Ancho del iframe
    iframe.height = "600"; // Alto del iframe
    document.body.appendChild(iframe);
    
    
    
  })
  .catch(error => {
    console.error("Error en la solicitud:", error);
  });

// Realizar consulta adicional a la API de detalles de enlace de pago
function details_payment_link(data) {
  var linkId = data.id; // Usar data.id para obtener el ID del enlace
  var apiUrl = "https://payment.prometeoapi.net/api/v1/payment-link/" + linkId;

  var requestOptions = {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-API-Key': apiKey,
      'Authorization': "Bearer " + auth_token
    }
  };

  console.log("Lo que se envia: ")
  console.log(requestOptions);

  // Realizar la solicitud GET
  fetch(apiUrl, requestOptions)
    .then(response => response.json())
    .then(data => {
      console.log("Detalles del enlace de pago:")
      console.log(data);
      datarespuesta = data;
      edit_response_to_bd(datarespuesta);

      //consulta a la bd por el intent
      // Captura el intent_id y external_id de payment_data
      console.log("Intent ID:")
      console.log(data.payment_data.intent_id);
      console.log("External ID:")
      console.log(data.payment_data.external_id);

      dataintent_id = data.payment_data.intent_id;
      dataexternal_id = data.payment_data.external_id;


      select_responde_to_bd(dataintent_id, dataexternal_id)
      // Puedes manejar la respuesta de la API de detalles aquí
    })
    .catch(error => {
      console.error("Error en la solicitud de detalles:", error);
    });

    
}


function save_response_to_bd(apiResponseData) {
  console.log("save_response_to_bd data :");
  console.log(apiResponseData);

  // Realiza la solicitud POST a tu archivo PHP /prometeo/bd_prometeo.php
  $.post('/prometeo/bd_prometeo.php', {"save_prometeo": JSON.stringify(apiResponseData)}, function(response) {
    // Parsea la respuesta JSON
    var result = JSON.parse(response);
    console.log('Respuesta save desde PHP:');
    console.log(result);
  });
}

function edit_response_to_bd(apiRespuestaData) {
  console.log("edit_response_to_bd data :");
  console.log(apiRespuestaData);

  // Realiza la solicitud POST a tu archivo PHP /prometeo/bd_prometeo.php
  $.post('/prometeo/bd_prometeo.php', {"edit_prometeo": JSON.stringify(apiRespuestaData)}, function(response) {
    // Parsea la respuesta JSON
    var result = JSON.parse(response);
    console.log('Respuesta edit desde PHP:');
    console.log(result);
  });
}

// Función para realizar la solicitud y verificar la respuesta
function select_responde_to_bd(intent_id, external_id) {

  // Crear un objeto JSON con intent_id y external_id
  var requestData = {
    intent_id: intent_id,
    external_id: external_id
  };

  // Realizar la solicitud POST a tu archivo PHP /prometeo/bd_prometeo.php
  $.post('/prometeo/bd_prometeo.php', {"select_prometeo": JSON.stringify(requestData)}, function(response) {
    // Parsea la respuesta JSON
    var result = JSON.parse(response);
    

    // Verificar si la respuesta es 'true'
    if (result.success === true) {
      // Realizar acciones adicionales aquí si es necesario
      console.log('Respuesta desde PHP:');
      console.log(result);
    } else {
      // Si la respuesta no es 'true', ejecutar la solicitud nuevamente después de un cierto período de tiempo (por ejemplo, 1 segundo)
      setTimeout(function() {
        console.log('Respuesta select desde PHP:');
        console.log(result);
        select_responde_to_bd(intent_id, external_id);
      }, 10000); // Espera 1 segundo antes de ejecutar la próxima solicitud
    }
  });
}

