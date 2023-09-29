<?php
function env($constant, $default=null) {
	return @constant($constant) ?? $default;
}
DEFINE('APP_URL', 'payments.apuestatotal.app');

DEFINE('DB_HOST', '');
DEFINE('DB_PORT', '');

DEFINE('DB_DATABASE', '');
DEFINE('DB_USERNAME', '');
DEFINE('DB_PASSWORD', '');

DEFINE('API_V2_TOKEN','');
DEFINE('KUSHKI_MERCHANT_ID', '');
DEFINE('KUSHKI_WEBHOOK_SIGNATURE', '');


DEFINE('DEPOSIT_LIMITS', '1,2');
?>
