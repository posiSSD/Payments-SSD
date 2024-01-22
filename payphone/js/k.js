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

					create_payment_button();
				}
			}else{
				input.addClass('is-invalid');
				input.attr('title','Este campo es requerido');
			}
		});
		// btn.delay(500).click(); //test
}
function create_payment_button(){
	console.log("create_payment_button");
		
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

	//holder.show();
	//holderdetails.html('Recargando: $/'+prueba.kushki_value);

	$.post(this_url+'sys/', 
	{
		create_payment_button:usr_active,
	}, 
	function(r, textStatus, xhr) {

		try {
			let rs = jQuery.parseJSON(r);
        	usr_active.order_id = rs.id;
			
			if(rs.status==201){
				holder.hide();
				prodiv.show(); // Esto muestra el div con id "prometeoembeded"
				proframe.attr("src", rs.url);
				proframe.show();

				/*
				btncerrar.click(function(event) {
					prodiv.hide();
					holderbutton.html('Salir');
					holder.show();	
				});
				*/
				
				response_to_payphone(usr_active);
							
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
function response_to_payphone(usr_active){
	let holder = $('#kushki_payment_holder');
	let holderdetails = $('#kushki_details');
	let holderbutton = $('#kushki_btn');
	let form = $('#kushki_payment_form');
	let btn = form.find('button');
	let inputtext = $("#inputtext");
	let prodiv = $("#prometeoembeded");
	let proframe = $("#prometeoframe");
	let btncerrar = $("#cerrarIframe");
	let texto = $("#texto");

	//holder.show();
	//holderdetails.html('Recargando: $/'+prueba.kushki_value);
	//holderbutton.html('Espere un momento...');

	$.post(this_url+'sys/', {

		status_payment_button:usr_active,

	}, 
	function(r, textStatus, xhr) {
		try {

			let rs = jQuery.parseJSON(r);
			//console.log(rs);

			if ( rs.status == 9 ) {
				prodiv.hide();
				holder.show();
				holderbutton.show();
				holderdetails.html('Recargando: $/'+prueba.kushki_value);
				holderbutton.html('Espere un momento...');
				console.log("El status es : "+rs.status);
			} else if ( rs.status ==  7 ){
				prodiv.hide();
				holder.show();
				holderbutton.show();
				holderdetails.html('Recarga Realizada: $/'+prueba.kushki_value);
				holderbutton[0].style.cursor = 'default';
				holderbutton.html('Salir');
				console.log("El status es : "+rs.status);
			} else if ( rs.status ==  10 ){
				prodiv.hide();
				holder.show();
				holderbutton.show();
				holderdetails.html('Recarga Declinada: $/'+prueba.kushki_value);
				holderbutton[0].style.cursor = 'default';
				holderbutton.html('Salir');
				console.log("El status es : "+rs.status);
			} else if ( rs.status ==  11 ){
				prodiv.hide();
				holder.show();
				holderbutton.show();
				holderdetails.html('Recarga Fallida: $/'+prueba.kushki_value);
				holderbutton[0].style.cursor = 'default';
				holderbutton.html('Salir');
				console.log("El status es : "+rs.status);
			} else {
				console.log("El status es : "+rs.status);
				setTimeout(function () {
				response_to_payphone(usr_active); //temporizador de 5 seg
				}, 1000);
			}		
		}
		catch(err) {

			console.log(usr_active);
			console.log(r);
			console.log(err);

		}
	});
	
}		