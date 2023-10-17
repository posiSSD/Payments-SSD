var connecting,site_id,sws,ws_url,message_queue,swsid,ws_session,usr_active;
var prueba ={};

$(document).ready(function() {
	console.log("document.ready k.js");
	set_events();
	set_vars();
	ws_connect();
});

function set_events(){
	$(document).on('sw_login_error', function(e,data) {
		$('#kushki_payment_form').remove();
		$('#kushki_payment_holder').show();
		$('#kushki_payment_holder').html('Ocurrio un error, refresca la pagina y vuelve a intentar.');
		
	});
	$(document).on('ws_onclose', function(e,data) {
		$('#kushki_payment_form').remove();
		//$('#kushki_payment_holder').remove();
		$('#kushki_payment_holder').show();
		$('#kushki_payment_holder').html('Ocurrio un error, refresca la pagina y vuelve a intentar.');
	});
	$(document).on('sw_login_ok', function(e,data) {
		//console.log(data);
		build_form();
	});
	
}
function set_vars(){
	console.log("set_vars");
	connecting = false;
	//site_id = 279;
	site_id = 18751709;
	sws;
	//ws_url = "wss://websocketbc.glitch.me/";
	ws_url = 'wss://eu-swarm-springre.trexname.com/';
	//ws_url= "ws://localhost:8086"
	//ws_url = "wss://eu-swarm-springre.betconstruct.com/";
	//ws_url = "wss://payments.totalbet.com/";
	message_queue = [];
	swsid;
	ws_session = false;
	usr_active = true;
}
function build_form(rs){
	console.log('build_form');
	let form = $('#kushki_payment_form');
	let btn = form.find('button');
	let input = form.find('input');
	let sms = $('#sms_alert');

	console.log(input);
		// btn.addClass('ready');
		btn.html('Generar');
		btn.addClass('ready');
		btn.click(function(event) {
			console.log("btn.click");
			if($.isNumeric(input.val())){
				if(Number(input.val()) > Number(input.data('max'))){
					input.addClass('is-invalid');
					input.attr('title','El monto debe ser menor a '+Number(input.data('max'))+' PEN');
					sms.addClass('color');
					sms.html('El monto debe ser menor a '+Number(input.data('max'))+' PEN');
				}
				else if(Number(input.val()) < Number(input.data('min'))){
					input.addClass('is-invalid');
					input.attr('title','El monto debe ser más de '+Number(input.data('min'))+' PEN');
					sms.addClass('color');
					sms.html('El monto debe ser más de '+Number(input.data('min'))+' PEN');
				}
				else{		
					input.attr('disabled', true);
					input.removeClass('alert');
					btn.off();
					btn.removeClass('ready');
					btn.html('Generando...');
					form.hide();

					prueba.kushki_value = Number(input.val());
					//console.log("valor de usr_active es: "+usr_active+" y el value es: "+ prueba.kushki_value);

					kushki_create_payment_button();
				}
			}else{
				input.addClass('is-invalid');
				input.attr('title','Este campo es requerido');
			}
		});
		// btn.delay(500).click(); //test
}
function kushki_create_payment_button(){
	console.log("kushki_create_payment_button");
	
	
	
	usr_active.this_url = this_url;
	usr_active.kushki_value = prueba.kushki_value;

	//////////////////////////////////////////
	let holder = $('#kushki_payment_holder');
	let holderdetails = $('#kushki_details');
	let holderbutton = $('#kushki_btn')
	let form = $('#kushki_payment_form');
	let btn = form.find('button');
	//let input = form.find('input');
	let inputtext = $("#inputtext");
	let prodiv = $("#prometeoembeded");
	let proframe = $("#prometeoframe");
	let btncerrar = $("#cerrarIframe");
	let texto = $("#texto");
	//////////////////////////////////////////

	//$("#kushki_payment_holder").show();
	holder.show();
	//$("#kushki_details").html('Recarga: $/'+prueba.kushki_value);
	holderdetails.html('Recarga: $/'+prueba.kushki_value);

	

	$.post(this_url+'sys/', 
	{
		kushki_create_payment_button:usr_active,
	}, 
	function(r, textStatus, xhr) {
		//console.log("r : ");
		//console.log(r);
		try {
			let rs = jQuery.parseJSON(r);
			usr_active.order_id = rs.id;
			
			if(rs.status==201){
				
				holder.hide();
				//find("iframe").attr("src", rs.url);
				prodiv.show(); // Esto muestra el div con id "prometeoembeded"
				proframe.attr("src", rs.url);
				proframe.show();
				btncerrar.click(function(event) {
					console.log("Close cerrarIframe ");
					//form.show();
					//inputtext.hide();
					//texto.hide();
					//btn.html('Salir');
					//btn.addClass('ready');
					//btn.html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span><span class="sr-only">Vamos!!!</span>');
					//btn.off();
					prodiv.hide();
					holderbutton.html('Salir');
					holder.show();
					
				});
				
				//select_responde_to_bd(usr_active);
				
			}else{
				$('#kushki_payment_form').remove();
				$('#kushki_payment_holder').html(rs.error);
			}

		}
		catch(err) {
			console.log(usr_active);
			console.log(r);
			console.log(err);
		}
	});
}
function validar(){
	let form = $('#kushki_payment_form');
	let input = form.find('input');
	
	var only_number = document.getElementById('basic-url');
	only_number.addEventListener('input', onlyNumbers);
	only_number.addEventListener('keypress', onlyEnter);

	if(Number(input.val()) > Number(input.data('max'))){
		input.removeClass('is-valid');
		input.addClass('is-invalid');
		input.attr('title','El monto debe ser menor a '+Number(input.data('max'))+' PEN');
	}

	else if(Number(input.val()) < Number(input.data('min'))){
		input.removeClass('is-valid');
		input.addClass('is-invalid');
		input.attr('title','El monto debe ser más de '+Number(input.data('min'))+' PEN');
	}
	else{
		input.removeClass('is-invalid');
		input.addClass('is-valid');
		input.attr('title','');
	}
}
function onlyNumbers(e) {
	e.target.value = e.target.value.replace(/[^0-9,.]/g, '').replace(/,/g, '.');
	if (e.target.value.length > 9) {
		e.target.value = e.target.value.slice(0, 9);
	}
}

function onlyEnter(e){
	if (e.keyCode == 13) {
		e.preventDefault();
	  }
}
function select_responde_to_bd(usr_active) {
	console.log("select_responde_to_bd");
	$.post(usr_active.this_url+'sys/', 
	{
		prometeo_select_transactions:usr_active,
	}, 
 
	function(response) {
	  // Parsea la respuesta JSON
	  var result = JSON.parse(response);
  
	  // Verificar si la respuesta es 'true'
	  if (result.success === true) {
		// Realizar acciones adicionales aquí si es necesario
		console.log('Respuesta True desde PHP:');
		console.log(result);
	  } else {
		// Si la respuesta no es 'true', ejecutar la solicitud nuevamente después de un cierto período de tiempo (por ejemplo, 1 segundo)
		setTimeout(function() {
		  console.log('Respuesta False select desde PHP:');
		  console.log(result);
		  select_responde_to_bd(usr_active);
		}, 10000); // Espera 10 segundo antes de ejecutar la próxima solicitud
	  }
	});
}