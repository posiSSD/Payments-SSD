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
		$('#prometeoembeded').remove(); 
		$('#kushki_payment_holder').show();
		$('#kushki_payment_holder').html('Ocurrio un error, refresca la pagina y vuelve a intentar.');
		
	});
	$(document).on('ws_onclose', function(e,data) {
		$('#kushki_payment_form').remove();
		$('#prometeoembeded').remove();
		$('#kushki_payment_holder').show();
		$('#kushki_payment_holder').html('Ocurrio un error, refresca la pagina y vuelve a intentar.');
	});
	$(document).on('sw_login_ok', function(e,data) {
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

	////////////////New - start////////////////////////
	let holderdetails = $('#kushki_details');
	let holderbutton = $('#kushki_btn');
	holderdetails.html('Espere un momento...');
	holderbutton.html('Cargando Payphone');
	////////////////New - End////////////////////////

		// btn.addClass('ready');
		btn.html('Generar');
		btn.addClass('ready');

		//console.log("btn.click");
		if ($.isNumeric(input.val())) {
			if (Number(input.val()) > Number(input.data('max'))) {
				input.addClass('is-invalid');
				input.attr('title', 'El monto debe ser menor a ' + Number(input.data('max')) + ' PEN');
				sms.addClass('color');
				sms.html('El monto debe ser menor a ' + Number(input.data('max')) + ' PEN');
			} else if (Number(input.val()) < Number(input.data('min'))) {
				input.addClass('is-invalid');
				input.attr('title', 'El monto debe ser más de ' + Number(input.data('min')) + ' PEN');
				sms.addClass('color');
				sms.html('El monto debe ser más de ' + Number(input.data('min')) + ' PEN');
			} else {
				input.attr('disabled', true);
				input.removeClass('alert');
				btn.off();
				btn.removeClass('ready');
				btn.html('Generando...');
				form.hide();

				prueba.kushki_value = Number(input.val());

				create_payment_button();
			}
		} else {
			input.addClass('is-invalid');
			input.attr('title', 'Este campo es requerido');
		}

		/*
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

					create_payment_button();
				}
			}else{
				input.addClass('is-invalid');
				input.attr('title','Este campo es requerido');
			}
		});
		*/
}

function create_payment_button(){
	console.log("create_payment_button");
		
	usr_active.this_url = this_url;
	usr_active.kushki_value = prueba.kushki_value ;
	let prodiv = $("#prometeoembeded");
	let proframe = $("#prometeoframe");

	let holder = $('#kushki_payment_holder');
	
	//console.log(usr_active);
	let iframeurl = "";
	let data = "";
	
	$.post(this_url+'sys/', 
	{
		create_payment_button: usr_active,
	}, 
	function(r, textStatus, xhr) {

		try {
			console.log('Loading Payphone');
			let rs = jQuery.parseJSON(r);
			//console.log(rs);
        	usr_active.unique_id = rs.unique_id;
			if (rs.status == 201) {
				data = {
					value: rs.value,
					unique_id: rs.unique_id
				};
				
				holder.hide();				
				iframeurl = this_url + 'sys/' + 'payphonebox.php?' + 'data=' + encodeURIComponent(JSON.stringify(data));
				prodiv.show(); // Esto muestra el div con id "prometeoembeded"
				proframe.attr("src", iframeurl);
				proframe.show(); // iframe"				
				response_to_payphone(usr_active);				
			} else {
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
function response_to_payphone(usr_active){
	
	let holder = $('#kushki_payment_holder');
	let holderdetails = $('#kushki_details');
	let holderbutton = $('#kushki_btn');
	let prodiv = $("#prometeoembeded");
	let iframeBody = document.body;
	

	$.post(this_url+'sys/', { status_payment_button:usr_active }, 
	function(r, textStatus, xhr) {
		try {

			let rs = jQuery.parseJSON(r);
			//console.log(rs);
			function showStatusMessage(message) {
                prodiv.hide();
                holder.show();
                holderbutton.show();
                holderdetails.html(message);
            }
			if ( rs.status == 9 ) {
				console.log("response_to_payphone : "+rs.status);
				showStatusMessage('Recargando: $/' + prueba.kushki_value);
                holderbutton.html('Espere un momento...');
                setTimeout(function () {
                    response_to_payphone(usr_active);
                }, 3000);	
			} else if ( rs.status ==  8 ){
				console.log("response_to_payphone : "+rs.status);
                holderbutton.html('Espere un momento...');
                setTimeout(function () {
                    response_to_payphone(usr_active);
                }, 3000);	
			}
			////////////////////////////////////////////////////////
			 	else if ( rs.status ==  7 ){
				console.log("response_to_payphone : "+rs.status);
				showStatusMessage('Recarga Realizada: $/' + prueba.kushki_value);
                holderbutton.html('Salir');
				
				holder.hide();
				
				// Creamos la variable
				var estadoPago = rs.status; //
				// Enviar el estado del pago al documento principal
				window.parent.postMessage(estadoPago, '*'); 
	
			} else if ( rs.status ==  10 ){
				console.log("response_to_payphone : "+rs.status);
				showStatusMessage('Recarga Declinada: $/' + prueba.kushki_value);
                holderbutton.html('Salir');

				holder.hide();

				// Creamos la variable
				var estadoPago = rs.status; //
				// Enviar el estado del pago al documento principal
				window.parent.postMessage(estadoPago, '*');

				
			} else if ( rs.status ==  11 ){
				console.log("response_to_payphone : "+rs.status);
				showStatusMessage('Recarga Fallida: $/' + prueba.kushki_value);
                holderbutton.html('Salir');	

				holder.hide();

				// Creamos la variable
				var estadoPago = rs.status; //
				// Enviar el estado del pago al documento principal
				window.parent.postMessage(estadoPago, '*');

				/*
				iframeBody.style.backgroundImage = "url('/imagenes/problema1.png')";
				iframeBody.style.backgroundSize = "100% auto";
				iframeBody.style.backgroundRepeat = "no-repeat";
				iframeBody.style.backgroundPosition = "center";
				holderbutton[0].style.cursor = 'default';
				*/
			} else {
				console.log("response_to_payphone: Error deposit "+rs.status);
				showStatusMessage('Algo salio mal: $/' + prueba.kushki_value);
				holderbutton.html('Contacta con nosotros');

				holder.hide();

				// Creamos la variable
				var estadoPago = ""; //
				// Enviar el estado del pago al documento principal
				window.parent.postMessage(estadoPago, '*');

				/*
				iframeBody.style.backgroundImage = "url('/imagenes/problema1.png')";
				iframeBody.style.backgroundSize = "100% auto";
				iframeBody.style.backgroundRepeat = "no-repeat";
				iframeBody.style.backgroundPosition = "center";
				holderbutton[0].style.cursor = 'default';
				*/    
			}		
		}
		catch(err) {

			console.log(usr_active);
			console.log(r);
			console.log(err);

		}
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log("Error en la solicitud POST:", errorThrown);
    });
}



