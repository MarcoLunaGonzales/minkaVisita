<?
// Enter your email address here
$adminaddress = "76200970@tigo.com.bo";

// Enter the address of your website here MUST include http://www.
$siteaddress ="http://www.websbolivia.com";

// Enter your company name or site name here
$sitename = "WebsBolivia";

// Gets the date and time from your server
$date = date("m/d/Y H:i:s");


//** IF ($action != "")://

//This sends a confirmation to your visitor
mail("76200970@tigo.com.bo","Murio Nodo", "Murio el nodo
Saludos.
","FROM:Nodo Chacaltaya");

//Confirmation is sent back to the Flash form that the process is complete
$sendresult = "Muchas_gracias_en_breve_nos_comunicaremos_con_usted";
$send_answer = "Respuesta=";
$send_answer .= rawurlencode($sendresult);
echo "$send_answer";

//ENDIF;
?>