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
/*
function create_payment_button(){
	console.log("create_payment_button");
		
	usr_active.this_url = this_url;
	//usr_active.this_url = 'https://payments.totalbet.com/payphone/';
	usr_active.kushki_value = prueba.kushki_value * 100;
	//usr_active.kushki_value = 5* 100;
	let prodiv = $("#pp-button");
	console.log(usr_active);
	//////////////////////////////////////////
	//let prodiv = $("#prometeoembeded");
	//////////////////////////////////////////
	prodiv.show();
	try {
		console.log("ingresando a payphone");
		document.addEventListener("DOMContentLoaded", () => {
			console.log("ingresando a paddEventListener");

            var ppb = new PPaymentButtonBox({
                // Configuraciones de pago

                // Token obtenido desde la consola de desarrollador que identifica la empresa
                token: '3e_lfs3syayUBEpyx1FD09A4K66scfjmDLvBBuirB0iGsNvndfcaAxbX3O0bSfoXl86aH87G6hKQ2nMJhB9dP7k1tqAnA5LDymAmBmE0fgQr8dwr7DNXa_vVN6LJH1US4i7yxia08TA_wUPYSPwn3mecajkX5abz6w-k9-Yo5SAnBlP6AInSOSo_maCuv88q_G68JjLhEJKhBrp_7aeVdgwLalLbGfY81NbIepdTEMOkP_iNjHaJNT2bQABfktMzZ007Orin5CqaD3CVJcJpe9SAucxQswwrTGIEenH11mKHDX15jWe5tH_GEl0M4yga6X9JAQ',

                // Monto a cobrar: Debe cumplir la siguiente regla
                // Amount = amountWithoutTax + AmountWithTax + AmountWithTax + Tax + service + tip
                // Todos los valores se multiplican por 100, es decir $1 = 100, $15.67 = 1567
                amount: 180, // monto total de venta
                amountWithoutTax: 180, // monto total que no cobra IVA
                amountWithTax: 0, // monto total que sí cobra IVA
                tax: 0, // monto del IVA
                service: 0, // Si existe monto por servicio
                tip: 0, // Si existe monto por propina

                // storeId: "", Identificador de la sucursal que cobra. Puedes obtener este campo desde la consola de Payphone Developer. Si lo envías se cobra con la sucursal indicada, si no lo envías se cobra con la sucursal matriz.

                reference: "Prueba Cajita de Pagos Payphone", // Referencia de pago
                clientTransactionId: generateUniqueId(), // Id único. Debe cambiar para cada transacción


            });
			console.log("PPaymentButtonBox creado:", ppb);
			ppb.render('pp-button');

        });
	} catch (error) {
		console.error("Error al inicializar PPaymentButtonBox:", error);
	}
	
	
	
	//response_to_payphone(usr_active);

}*/

function create_payment_button(){
	console.log("create_payment_button");
		
	//usr_active.this_url = this_url;
	usr_active.this_url = 'https://payments.totalbet.com/payphone/';
	//usr_active.kushki_value = prueba.kushki_value * 100;
	usr_active.kushki_value = 5* 100;

	console.log(usr_active);
	//////////////////////////////////////////
	//let prodiv = $("#prometeoembeded");
	//////////////////////////////////////////
	//prodiv.show();

	$.post(this_url+'sys/', 
	{
		create_payment_button:usr_active,
	}, 
	function(r, textStatus, xhr) {

		try {
			let rs = jQuery.parseJSON(r);
        	usr_active.order_id = rs.id;
			console.log("respuesta de la BD");
			console.log(rs);
			
			if(rs.status==201){
				
				//prodiv.show(); // Esto muestra el div con id "prometeoembeded"
				//proframe.attr("src", rs.url);
				//proframe.show(); // iframe"	
				try {
					document.addEventListener("DOMContentLoaded", function () {
						ppb = new PPaymentButtonBox({
							token: '...tu_token_aqui...',
							amount: usr_active.kushki_value,
							amountWithoutTax: usr_active.kushki_value,
							amountWithTax: 0,
							tax: 0,
							service: 0,
							tip: 0,
							reference: "Prueba Cajita de Pagos Payphone",
							clientTransactionId: uniqueId,
						}).render('pp-button');
					});
				} catch (error) {
					console.error("Error al inicializar PPaymentButtonBox:", error);
				}
				
				
				
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

	$.post(this_url+'sys/', {

		status_payment_button:usr_active,

	}, 
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
				console.log("pending deposit : "+rs.status);
				showStatusMessage('Recargando: $/' + prueba.kushki_value);
                holderbutton.html('Espere un momento...');
                setTimeout(function () {
                    response_to_payphone(usr_active);
                }, 3000);	
			} else if ( rs.status ==  6 ){ 
				console.log("new  : "+rs.status);
                holderbutton.html('Espere un momento...');
                setTimeout(function () {
                    response_to_payphone(usr_active);
                }, 3000);	
			} else if ( rs.status ==  8 ){
				console.log("pending payment : "+rs.status);
                holderbutton.html('Espere un momento...');
                setTimeout(function () {
                    response_to_payphone(usr_active);
                }, 3000);	
			} else if ( rs.status ==  7 ){
				console.log("paid : "+rs.status);
				showStatusMessage('Recarga Realizada: $/' + prueba.kushki_value);
                holderbutton.html('Salir');
				holderbutton[0].style.cursor = 'default';	
			} else if ( rs.status ==  10 ){
				console.log("declined payment : "+rs.status);
				showStatusMessage('Recarga Declinada: $/' + prueba.kushki_value);
                holderbutton.html('Salir');
				holderbutton[0].style.cursor = 'default';
			} else if ( rs.status ==  11 ){
				console.log("failed deposit : "+rs.status);
				showStatusMessage('Recarga Fallida: $/' + prueba.kushki_value);
				holderbutton[0].style.cursor = 'default';
                holderbutton.html('Salir');	
			} else {
				console.log("Error deposit: "+rs.status);
				showStatusMessage('Algo salio mal: $/' + prueba.kushki_value);
				holderbutton[0].style.cursor = 'default';
                holderbutton.html('Contacta con nosotros');
			}		
		}
		catch(err) {

			console.log(usr_active);
			console.log(r);
			console.log(err);

		}
	});
	
}
function generateUniqueId() {
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		var r = Math.random() * 16 | 0,
			v = c === 'x' ? r : (r & 0x3 | 0x8);
		return v.toString(16);
	});
}