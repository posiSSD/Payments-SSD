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
		// kushki_create_payment_button(data);
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

	// var ws_url = "wss://us-swarm-ws.betconstruct.com/";
	// var ws_url = "wss://soe-swarm-apuesta.betconstruct.com/";
	// var fw_user = [];
	// var fw_active = true;
	// var fw_active = false;
	// var interval_fw_get_balance = false;
	// var interval_sw_get_user_balance = false;
}
function build_form(rs){
	console.log('build_form');
	let form = $('#kushki_payment_form');
	let btn = form.find('button');
	let input = form.find('input');

	console.log(input);
	// console.log(btn);


	// let form = $('<form>');
	// let input = $('<input>');
	// let minmax_txt
	// let btn = $('<a>').html('Ir a Kushki');
		btn.html('Generar');
		// btn.attr('href', rs.result);
		btn.addClass('ready');
		btn.click(function(event) {
			console.log("btn.click");
			if($.isNumeric(input.val())){
				input.attr('disabled', true);
				input.removeClass('alert');
				btn.off();
				btn.removeClass('ready');
				btn.html('Generando...');
				// $("#msg").html("Generando orden Kushki...");
				form.hide();
				usr_active.kushki_value = Number(input.val());
				console.log(usr_active);
				kushki_create_payment_button();
			}else{
				input.addClass('alert');
				input.focus();
			}
		});
		// btn.delay(500).click();
	// form.append(input);
	// form.append(btn);
	// $("#msg").html(form);
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

			$("#kushki_btn").addClass('ready');
			$("#kushki_btn").html('Ir a Kushki!');
			$("#kushki_btn").attr('href', rs.url);
			$("#kushki_btn").click(function(event) {
				$("#kushki_btn").off();
				$("#kushki_btn").removeClass('ready');
				$("#kushki_btn").html('Vamos...');
			});
			// build_form(rs);
			// $("#kushki_details").html('Recarga: S/'+usr_active.kushki_value);
		}
		catch(err) {
			console.log(usr_active);
			console.log(r);
			console.log(err);
		}
	});
}