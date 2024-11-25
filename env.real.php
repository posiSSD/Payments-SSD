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

//DEFINE('BC_PAYPHONE_SECRET_KEY', 'wwaw4TbqSrO24gH22'); //antiguo
DEFINE('BC_PAYPHONE_SECRET_KEY', 'QuDz2$9IAG#$oprHR*sR'); //NUevo

DEFINE('API_V2_TOKEN','');
DEFINE('KUSHKI_MERCHANT_ID', '');
DEFINE('KUSHKI_WEBHOOK_SIGNATURE', '');

///prometeo
DEFINE('API_KEY_PROMETEO','SKEyYnMt1OGIoMX0gpAy0xPJLrgh2e5p8jp3vGrZyjqO1wbuIJDKPuSHKxpIFynA');
DEFINE('WIDGET_PROMTEO','aa2b08c8-b9e1-4fb2-a971-c3ec850c5692');

///payphone TotalbetEC - LOCALHOST
//DEFINE('TOKEN_PAYPHONE','OEcjRT2DJZiPFB7M5_w54ZDvp5P9VxKMuXQdzregqH1HtrZqoVUqqCYaDL0TNEfteI_kxMfd_uNZaAvR1y5L799hrAbgGmZCZkoOsMEN-GHNL4JsdQeG3-V3VBWwaeD1ytydsrpSBh7GF8ZPsgMxUOjZB0PNwbyRxMRIL6Qt3RPHLOsjSqoBD94DN-6Gps5yxOwIbz-DLuDofgMAIj8SeHUqQPxMwu71pFkziQJFY3wPcZOvJnyCAXnnUK_vgipHB9jGjR8vJouA_I-yUOzPFf3MHoFE9N_1OnjZgLnGwJKfdjMIS_QzCg5NwoJAtRugqBMF_g');
//DEFINE('RESPONSEURL_PAYPHONE', 'http://localhost:8081/Payments-SSD/payphonenots.php');

///payphone ECTotalBet - NUBE
DEFINE('TOKEN_PAYPHONE','3e_lfs3syayUBEpyx1FD09A4K66scfjmDLvBBuirB0iGsNvndfcaAxbX3O0bSfoXl86aH87G6hKQ2nMJhB9dP7k1tqAnA5LDymAmBmE0fgQr8dwr7DNXa_vVN6LJH1US4i7yxia08TA_wUPYSPwn3mecajkX5abz6w-k9-Yo5SAnBlP6AInSOSo_maCuv88q_G68JjLhEJKhBrp_7aeVdgwLalLbGfY81NbIepdTEMOkP_iNjHaJNT2bQABfktMzZ007Orin5CqaD3CVJcJpe9SAucxQswwrTGIEenH11mKHDX15jWe5tH_GEl0M4yga6X9JAQ');						 
DEFINE('RESPONSEURL_PAYPHONE', 'https://payments.totalbet.com/Payments-SSD/payphonenots.php');

//payphone ECTotalBet - NUBE - API
//DEFINE('TOKEN_PAYPHONE','av5a2_p1Yp8ftbESUdCt6-mJrxjjnCBqwIPNPKkzzgIh1YBV3mbvNRbpzW-AAvb65v9-CwjLgWE-FEdtPvKc7tJOCdjOJmOw4si-OI7OceY5FgTnWIqugcl-RLu7X69AMp5IRBGIxvbU-BDbKBaZASb5ITmIi7GMZ9FmS4qU7AXIimEDKOhUEKZr9qwEJogoC51HWrZaFFG2IdObKDiDJjBH7ctvWVx9fLUmahVrP6ok4nhnyr4XJrqo9GREJZNLZVRKuu0X0tWdsUnLI_2XQG4l8Fx6kCnaK3ar52ybTe3PUmDX-vSNSU0K2f8N1GQrb1SYDw');						 
//DEFINE('RESPONSEURL_PAYPHONE', 'https://payments.totalbet.com/Payments-SSD/payphonenots.php');

//Prontopaga
DEFINE('TOKEN_PRONTOPAGA','835081294cce75ca0050e57736a4b535488f4e33459d6f833d2afc70f121ebb4');	 
DEFINE('RESPONSEURL_PRONTOPAGA', 'https://payments.totalbet.com/Payments-SSD/prontopaganots.php');
DEFINE('SECRETKEY_PRONTOPAGA', '01J2C7RGXETGVF67C07HS94VC7');

///limite de pago 
DEFINE('DEPOSIT_LIMITS', '1,500');
/// Direccion del proyecto
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

?>