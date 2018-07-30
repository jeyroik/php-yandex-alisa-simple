<?php
/**
 * Получаем запрос пользователя
 */
$dataRow = file_get_contents('php://input');
header('Content-Type: application/json');

/**
 * Здесь будет ответ
 */
$response = '';

/**
 * Впишите сюда своё активационное имя
 */
$mySkillName = 'навык';

try{
    if (!empty($dataRow)) {
        /**
         * Простейший лог, чтобы проверять запросы. Закомментируйте эту стрчоку, если он вам не нужен
         */
        file_put_contents('alisalog.txt', date('Y-m-d H:i:s') . PHP_EOL . $dataRow . PHP_EOL, FILE_APPEND);
        
        /**
         * Преобразуем запрос пользователя в массив
         */
        $data = json_decode($dataRow, true);
        
        /**
         * Проверяем наличие всех необходимых полей
         */
        if (!isset($data['request'], $data['request']['command'], $data['session'], $data['session']['session_id'], $data['session']['message_id'], $data['session']['user_id'])) {
            /**
             * Нет всех необходимых полей. Не понятно, что вернуть, поэтому возвращаем ничего.
             */
            $result = json_encode([]);
        } else {
            /**
             * Получаем что конкретно спросил пользователь
             */
            $text = $data['request']['command'];
            
            /**
             * Приводим на всякий случай запрос пользователя к нижнему регистру
             */
            $textToCheck = strtolower($text);
            
            if (strpos($text, $mySkillName) !== false) {
                $response = json_encode([
                    'version' => '1.0',
                    'session' => [
                        'session_id' => $data['session']['session_id'],
                        'message_id' => $data['session']['message_id'],
                        'user_id' => $data['session']['user_id']
                    ],
                    'response' => [
                        'text' => 'Вставьте сюда начальный текст, который будет видеть пользовать после включения вашего навыка',
                        /**
                         * Ставьте плюсик перед гласной, на которую делается ударение. 
                         * Если вам нужна пауза, добавьте " - ", т.е. дефис с пробелом до и после него.
                         */
                        'tts' => 'Вст+авьте сюд+а нач+альный текст кот+орый б+удет в+идеть п+ользовать п+осле включ+ения в+ашего н+авыка',
                        'buttons' => []
                    ]
                ]);
            } elseif($text == 'помощь') {
                $response = json_encode([
                    'version' => '1.0',
                    'session' => [
                        'session_id' => $data['session']['session_id'],
                        'message_id' => $data['session']['message_id'],
                        'user_id' => $data['session']['user_id']
                    ],
                    'response' => [
                        'text' => 'Здесь необходимо вставить инструкции по использованию вашего навыка',
                        'tts' => '',
                        'buttons' => []
                    ]
                ]);
            } else {
                /**
                 * Здесь опишите логику обработки запроса пользователя.
                 * Например, давайте возвращать количество символов в запросе пользователя.
                 */
                 $answer = strlen($text);
                
                $response = json_encode([
                    'version' => '1.0',
                    'session' => [
                        'session_id' => $data['session']['session_id'],
                        'message_id' => $data['session']['message_id'],
                        'user_id' => $data['session']['user_id']
                    ],
                    'response' => [
                        'text' => 'В вашем запросе ' . $answer . ' символов',
                        'tts' => 'в в+ашем запр+осе ' . $answer . 'с+имволов',
                        'buttons' => [],
                        
                        'end_session' => false
                    ]
                ]);
                
            }
        }
    } else {
        $response = json_encode([
            'version' => '1.0',
            'session' => 'Error',
            'response' => [
                'text' => 'Отсутствуют данные',
                'tts' =>  'Отсутствуют данные'
            ]
        ]);
    }
    
    echo $response;

} catch(\Exception $e){
    echo '["Error occured"]';
}
