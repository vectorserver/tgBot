<?php
define('MODX_API_MODE', true);
require_once '../../../../index.php';
require_once 'tgBot.class.php';

/* @var modX $modx*/
$modx->getService('error','error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_FATAL);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');


//http://t.me/helpmysite_bot
$bot = new tgBot('xxx:xxx');
$hook_url = $modx->getOption('site_url',['context_key'=>'helpmysite'])."assets/helpmysite/api/tg/bot.php";

$admins = explode(",",$modx->getOption("tg_admins",['context_key'=>'helpmysite']));


//$bot->enableWebhook($hook_url)
$request = $_REQUEST;

if(!$request['cmd'] && $bot->content){
    $data = json_decode($bot->content);

    //Сообщения от бота
    $user_message = $data->message->text;
    $chatID = $data->message->chat->id;

    $bot->call('sendMessage', ['chat_id' => $chatID, 'text' => "Нахуй это туда.... , только я могу писать тебе!",'parse_mode'=>'HTML']);
} else{
	
    // для отправки сообщений админам
    $send = false;
    switch ($request['cmd']){
        case 'test':
            $send = true;
            $msg = rawurldecode($request['msg']);
            break;
    }

    if ($send && $msg){
        $st = [
            'st'=>'error'
        ];
        foreach ($admins as $chatID){
            $bot->call('sendMessage', ['chat_id' => $chatID, 'text' => $msg,'parse_mode'=>'HTML']);
            $st['st'] = 'OK';
            $st['chat_id'][] = $chatID;
            $st['msg'] = $msg;
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($st,JSON_UNESCAPED_UNICODE);
    }
}




