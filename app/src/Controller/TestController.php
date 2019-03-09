<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 2019-03-09
 * Time: 21:38
 */

namespace App\Controller;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    public function rabbitmqTest()
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'rbtmq_user', 'rbtmq111');
        $channel = $connection->channel();

        dump($channel->queue_declare('ppm-parse', true));
        dump(getenv());

        //list($queue, $messageCount, $consumerCount) = $channel->queue_declare('ppm-parse', true);
        return $this->render("test/rabbitmq_test.html.twig",[]);
    }
}