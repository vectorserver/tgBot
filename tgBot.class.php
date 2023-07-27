<?php
/*
 * @description by vectorserver
 */
class tgBot
{


    protected $botToken;

    public function __construct($botToken)
    {
        $this->botToken = $botToken;
        $this->content = file_get_contents("php://input");

        if ($this->content) file_put_contents(__DIR__ . '/log.json', $this->content);
    }

    public function enableWebhook($url)
    {
        $data = [];
        $data[] = $this->deleteWebhook();
        sleep(2);
        $data[] = $this->call('setWebhook', ['url' => $url]);
        return $data;
    }

    public function deleteWebhook()
    {
        return $this->call('deleteWebhook');
    }

    public function getWebhookInfo()
    {
        return $this->call('getWebhookInfo');
    }


    public function getUpdates()
    {
        return $this->call('getUpdates');
    }

    public function getMe()
    {
        return $this->call('getMe');
    }

    public function call($method, $params = [], $m = 'POST')
    {
        // Формируем запрос к Telegram API для получения информации о группе
        $dataPArams = http_build_query($params);
        $url = "https://api.telegram.org/bot{$this->botToken}/{$method}";


        // Отправляем запрос и получаем ответ в формате JSON
        if ($m == 'POST') {
            $options = [
                'http' => [
                    'method' => $m,
                    'header' => 'Content-type: application/x-www-form-urlencoded\r\n',
                    'content' => $dataPArams,
                ],
            ];

            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);

        } else {
            $response = @file_get_contents($url."?".$dataPArams);
        }

        $error = error_get_last();
        if ($error) {
            $error['error'] = 'error';
            //$error['headers'] = $http_response_header;
            //$error['response'] = $response;
            $response = json_encode($error);
        }

        return json_decode($response);
    }

}
