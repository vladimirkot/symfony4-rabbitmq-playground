<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 2019-03-11
 * Time: 14:08
 */

namespace App\Consumer;

use PhpAmqpLib\Message\AMQPMessage;

class PlaygroundConsumer
{
    public function execute(AMQPMessage $message)
    {
        $body = json_decode($message->body, true);

        try {
             echo json_encode($message).PHP_EOL;
        } catch (Exception $e) {
            return false;
        }
    }
}