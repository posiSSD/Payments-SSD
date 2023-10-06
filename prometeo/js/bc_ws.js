function ws_has(){
	console.log("document.ready fuction ws_has");
	if(!("WebSocket" in window)){
		console.log("WebSocket in window F");
		return false;
	}else{
		console.log("WebSocket in window T");
		return true;
	}
}
function ws_connect(){
	$(document).trigger("ws_connect", []);
	console.log("ws_connect");
	// $("#msg").html('Conectando con BC...');
	if(!connecting){
		connecting = true;
		if(ws_has()){
			try{
				sws = new WebSocket(ws_url); // se cae aqui
				sws.onopen = function(){
					// $("#msg").html('Conectando ws...');
					console.log("Conectando ws...");
					sw_open_session();
					connecting=false;
				};
				sws.onmessage = function(msg){
					// console.log("onmessage");
					var obj = jQuery.parseJSON(msg.data);
					console.log(obj);
					// console.log(swsid);
					if(obj.rid && obj.rid === "sw_open_session"){
						$(document).trigger("sw_open_session", obj);
						console.log("--------------> sw_open_session");
						// console.log(obj);
						swsid = obj.data.sid;
						sw_restore_login();
						// balance_history();
						//prueba posi
						//$(document).trigger("sw_login_ok", obj);
					}
					
					else if(obj.rid === "restore_login")
					{
						$(document).trigger("restore_login", obj);
						// $("#msg").append('<br>Validando credenciales...');
						console.log("--------------> restore_login");
						console.log(obj.code);
						if(obj.code == 12){
							$(document).trigger("sw_login_error", obj);
							// $("#msg").html('Sesión inválida!');
							console.log("BAD LOGIN, BYE BYE");
						}else{
							// console.log("LOGIN OK");
							// console.log(obj);
							// console.log(user_id);
							// console.log(auth_token);

							// $("#msg").html('Obteniendo usuario...');
							var next = false;
							if(obj.data.auth_token === auth_token){
								// console.log("auth_token es igual");
								next=true;
							}else{
								// console.log("auth_token NO es igual");
								next = false;
							}
							if(obj.data.user_id === user_id){
								next=true;
								// console.log("user_id es igual");
							}else{
								// console.log("user_id NO es igual");
								next = false;
							}
							if(next){
								console.log("restore_login OK");
								// $("#msg").html('Obteniendo usuario...');
								sw_get_user_balance();
							}else{
								$(document).trigger("sw_login_error", obj);
							}
						}
					}
					else if(obj.rid === "sw_get_user_balance"){
						$(document).trigger("sw_get_user_balance", obj);
						console.log("--------------> sw_get_user_balance");
						// console.log(obj);
						if(obj.code == 12){
							console.log("BAD LOGIN sw_get_user_balance, BYE BYE");
							// $(document).trigger("clearBetData");
							// $(document).trigger("sw_login_error", obj);
							// $(document).trigger("invalidSession", [data]);
						}else{
							console.log("LOGIN sw_get_user_balance OK");
							console.log(obj);
							usr_active = {};
							usr_active.auth_token = obj.data.auth_token;
							usr_active.name = obj.data.name;
							usr_active.email = obj.data.email;
							usr_active.balance = obj.data.balance;
							usr_active.client_id = obj.data.user_id;
							// $("#msg").html('');
							// $("#msg").html('Hola <b>'+obj.data.first_name+'</b>, tu saldo actual es S/'+obj.data.balance);
							$(document).trigger("sw_login_ok", obj);
							// $(document).trigger("sw_get_user_balance_event", obj);
							// return "sw_login_ok";
							// $(document).trigger("updatesw_get_user_balance", [data.data]);
						}
					}
					
					// console.log(swsid);
				};
				sws.onclose = function(){
					$(document).trigger("ws_onclose", []);
					// swsid ='';
					connecting = false;
					console.log('Disconnected. Socket Status: '+sws.readyState+' (Closed)');
				};
			}
			catch(exception){
				// $(document).trigger("ws_error", exception);
				console.log("Disconnected!");
				console.log(exception);
				connecting = false;
			}
		}else{
			// $(document).trigger("ws_nows", []);
			console.log("NO WS, BYE");
		}
	}
}
function sw_restore_login(){
	// $("#msg").html('Validando credenciales...');
	if(typeof user_id != 'undefined' && user_id && typeof auth_token != 'undefined' && auth_token){
		var _msg = '{"command": "restore_login","rid":"restore_login","params": {"user_id": '+user_id+',"auth_token": "'+auth_token+'"}}';
		console.log("sw_restore_login: ");
		sw_queue_msg(_msg);
	}
}
function sw_get_user_balance(){
	if(!usr_active){ return false; }
	// $("#msg").html('Obteniendo usuario...');
	//console.log("sw_get_user_balance");
	var _msg = '{"command":"get_user", "rid":"sw_get_user_balance"}';
	console.log("sw_get_user_balance: ");
	sw_queue_msg(_msg);
	return "sw_login_ok";
}
function sw_open_session(){
	console.log("sw_open_session");
	// $("#msg").html('Creando sesión...');
	sw_send_msg('{"command":"request_session","rid":"sw_open_session","params":{"site_id": '+site_id+',"language":"spa"}}')
}
function sw_send_msg(msg){
	if(sws && sws.readyState == 1){
		console.log("sw_send_msg");
		sws.send(msg);
	}
}
function sw_queue_msg(msg){
	if(sws && sws.readyState == 1 && swsid){
		sw_send_msg(msg);
	}else{
		message_queue.push(msg);
		ws_connect();
	}
	console.log("sw_queue_msg");
}