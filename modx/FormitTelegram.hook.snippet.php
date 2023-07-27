<?php
/* @var modX $modx*/

$values = $hook->getValues();

require_once MODX_ASSETS_PATH.'components/tgbot/tgBot.class.php';
$tg_botToken = $modx->getOption('tg_botToken');
$tg_webhookurl = $modx->getOption('site_url')."assets/components/tgbot/input.php";

if(!$tg_botToken) return;
$tgBot = new tgBot($tg_botToken);
$tg_botUsers = $modx->getObject('cgSetting', ['key'=>'tg_botUsers']);
$tg_botUsers = json_decode($tg_botUsers->value);
//first init
//$tgBot->enableWebhook($tg_webhookurl);

$car = $values["car"]??false;
$name = $values["name"]??false;
$phone = $values["phone"]??false;
$pageId = $values["pageId"]??false;
$typeEvent = $values["typeEvent"]??false;
$ip = $_SERVER['HTTP_X_FORWARDED_FOR']??$_SERVER['REMOTE_ADDR'];

$msg = "";
if ($phone && !$name){
    $msg = "<b>ОСТАВЬТЕ СВОЙ НОМЕР </b> <code>$phone</code>";
}

if ($phone && $car && $name){
    $msg = "<b>ЗАЯВКА НА АРЕНДУ</b>
Авто: <code>$car</code>
Имя: <code>$name</code>
Телефон: <code>$phone</code>
";
}

if($car || $phone || $name ){
    foreach ($tg_botUsers as $chatID){
       // $msg .= $modx->getOption('site_url')."\n";
        $msg .= "\nIP: <code>{$ip}</code>";
        $tgBot->call('sendMessage', ['chat_id' => $chatID, 'text' => $msg,'parse_mode'=>'HTML']);
    }

}

return true;