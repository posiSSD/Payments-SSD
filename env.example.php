<?php
function env($constant, $default=null) {
	return @constant($constant) ?? $default;
}
DEFINE('APP_URL', 'payments.apuestatotal.app');

DEFINE('DB_HOST', 'db-gestion.cyxi90mjli4a.us-east-1.rds.amazonaws.com');
DEFINE('DB_PORT', '3306');

DEFINE('DB_DATABASE', 'at_payments_prueba');
DEFINE('DB_USERNAME', 'admin');
DEFINE('DB_PASSWORD', 'CxxW34GOV9fgw0Aj3f1G');

DEFINE('DB_DATABASE_KUSHKI', 'at_kushki');
DEFINE('DB_USERNAME_KUSHKI', 'admin');
DEFINE('DB_PASSWORD_KUSHKI', 'CxxW34GOV9fgw0Aj3f1G');

DEFINE('DB_DATABASE_KUSHKIPAYMENT', 'bc_kushkipayment');
DEFINE('DB_USERNAME_KUSHKIPAYMENT', 'admin');
DEFINE('DB_PASSWORD_KUSHKIPAYMENT', 'CxxW34GOV9fgw0Aj3f1G');

DEFINE('BC_KUSHKI_SECRET_KEY', 'wwaw4TbqSrO24gH22');

DEFINE('API_V2_TOKEN','');
DEFINE('KUSHKI_MERCHANT_ID', '');
DEFINE('KUSHKI_WEBHOOK_SIGNATURE', '');

///prometeo
DEFINE('API_KEY_PROMETEO','SKEyYnMt1OGIoMX0gpAy0xPJLrgh2e5p8jp3vGrZyjqO1wbuIJDKPuSHKxpIFynA');
DEFINE('WIDGET_PROMTEO','aa2b08c8-b9e1-4fb2-a971-c3ec850c5692');

///payphone TotalbetEC - LOCALHOST
DEFINE('TOKEN_PAYPHONE','OEcjRT2DJZiPFB7M5_w54ZDvp5P9VxKMuXQdzregqH1HtrZqoVUqqCYaDL0TNEfteI_kxMfd_uNZaAvR1y5L799hrAbgGmZCZkoOsMEN-GHNL4JsdQeG3-V3VBWwaeD1ytydsrpSBh7GF8ZPsgMxUOjZB0PNwbyRxMRIL6Qt3RPHLOsjSqoBD94DN-6Gps5yxOwIbz-DLuDofgMAIj8SeHUqQPxMwu71pFkziQJFY3wPcZOvJnyCAXnnUK_vgipHB9jGjR8vJouA_I-yUOzPFf3MHoFE9N_1OnjZgLnGwJKfdjMIS_QzCg5NwoJAtRugqBMF_g');
//DEFINE('RESPONSEURL_PAYPHONE', 'http://localhost:8081/Payments-SSD/payphonenots.php');
///payphone ECTotalBet - NUBE
//DEFINE('TOKEN_PAYPHONE','-qOXX4AGMi23QfQN7cW-pmzUA0kCFC5xB7yYmIlldgfJoLYe06e8DUXzZPmM2G4h92WM_hQ7U4HoiGrse9H3ltikneuhPfiXXDigUdUTF4BxyrqQA7ZUUv6nUxcc0sH4YYjKIiokLPRkSGpH16ZAFApUM4SAGt8Blb_bWwwEWrhypeXanBQ-EyzCSxNzfnpxM3CKjVNDyTjQkGBl26In8a75qmvpQ8xfn9bfBAKTNywSMUnG7yxTKira98amaOnI6c2YrziizJBKirmERSJd_1DuDZlK0ylKju_MdD_r3sWK4xoAYjOHr1FzUIAl2fxtCafzxA');
DEFINE('RESPONSEURL_PAYPHONE', 'https://payments.totalbet.com/Payments-SSD/payphonenots.php');

///limite de pago 
DEFINE('DEPOSIT_LIMITS', '1,500');
/// Direccion del proyecto
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

?>