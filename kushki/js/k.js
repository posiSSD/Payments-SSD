var connecting,site_id,sws,ws_url,message_queue,swsid,ws_session,usr_active;

$(document).ready(function() {
	console.log("document.ready");
	set_events();
	set_vars();
	ws_connect();
});

function set_events(){
	$(document).on('sw_login_error', function(e,data) {
		$('#kushki_payment_form').remove();
		$('#kushki_payment_holder').remove();
	});
	$(document).on('ws_onclose', function(e,data) {
		$('#kushki_payment_form').remove();
		$('#kushki_payment_holder').remove();
	});
	$(document).on('sw_login_ok', function(e,data) {
		console.log(data);
		build_form();
	});
	
}
function set_vars(){
	console.log("set_vars");
	connecting = false;
	site_id = 279;
	sws;
	ws_url = "wss://eu-swarm-springre.betconstruct.com/";
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
				}else if(Number(input.val()) < Number(input.data('min'))){
					input.addClass('is-invalid');
					input.attr('title','El monto debe ser más de '+Number(input.data('min'))+' PEN');
					sms.addClass('color');
					sms.html('El monto debe ser más de '+Number(input.data('min'))+' PEN');
				}else{			
					input.attr('disabled', true);
					input.removeClass('alert');
					btn.off();
					btn.removeClass('ready');
					btn.html('Generando...');
					form.hide();
					usr_active.kushki_value = Number(input.val());
					console.log(usr_active);
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
	$("#kushki_payment_holder").show();
	$("#kushki_details").html('Recarga: S/'+usr_active.kushki_value);
	usr_active.this_url = this_url;
	// build_form();
	// $("#msg").html('Esperando Kushki...');
	$.post(this_url+'/sys/', 
	{
		kushki_create_payment_button:usr_active,
	}, 
	function(r, textStatus, xhr) {
		console.log(r);
		try {
			let rs = jQuery.parseJSON(r);
			console.log(rs);
			// $("#msg").html("");
			if(rs.status==201){
				$("#kushki_btn").addClass('ready');
				$("#kushki_btn").html('Ir a Kushki!');
				$("#kushki_btn").attr('href', rs.url);
				$("#kushki_btn").click(function(event) {
					$("#kushki_btn").off();
					$("#kushki_btn").removeClass('ready');
					$("#kushki_btn").html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span><span class="sr-only">Vamos</span>');
				});
				// build_form(rs);
				// $("#kushki_details").html('Recarga: S/'+usr_active.kushki_value);
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
	e.target.value = e.target.value.replace(/[^0-9]/g, '');
	if (e.target.value.length > 9) {
		e.target.value = e.target.value.slice(0, 9);
	}
}
