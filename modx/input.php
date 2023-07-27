<?php
//tg_botUsers
define('MODX_API_MODE', true);
require '../../../index.php';
/* @var modX $modx*/
$modx->getService('error','error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_FATAL);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');


require_once MODX_ASSETS_PATH.'components/tgbot/tgBot.class.php';
$tg_botToken = $modx->getOption('tg_botToken');
$tg_webhookurl = $modx->getOption('site_url')."assets/components/tgbot/input.php";

if(!$tg_botToken) return;
$tgBot = new tgBot($tg_botToken);
$data = json_decode($tgBot->content);

/* @var cgSetting $tg_botUsers*/
$tg_botUsers = $modx->getObject('cgSetting', ['key'=>'tg_botUsers']);

$tg_botUsers_arr = json_decode($tg_botUsers->value);

$message = $data->message;
if($message){

    $chatID = $message->chat->id;
    $first_name = $message->chat->first_name;
    $text = $message->text;

    if (!in_array($chatID,$tg_botUsers_arr)){
        $tg_botUsers_arr[] = $chatID;

        $tg_botUsers->set('value',json_encode($tg_botUsers_arr));
        $tg_botUsers->save();

        $tgBot->call('sendMessage', ['chat_id' => $chatID, 'text' => 'Привет, теперь ты мой раб)!']);

    }

}