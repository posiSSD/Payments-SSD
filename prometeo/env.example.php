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

DEFINE('API_KEY_PROMETEO','SKEyYnMt1OGIoMX0gpAy0xPJLrgh2e5p8jp3vGrZyjqO1wbuIJDKPuSHKxpIFynA');
DEFINE('WIDGET_PROMTEO','aa2b08c8-b9e1-4fb2-a971-c3ec850c5692');


DEFINE('DEPOSIT_LIMITS', '1,50');
?>