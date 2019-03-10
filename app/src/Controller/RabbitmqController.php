<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 2019-03-10
 * Time: 12:23
 */

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

/**
 * @Route("/api")
 */
class RabbitmqController extends AbstractFOSRestController
{
    /**
     * @Route("/queue-stat/{name}", name="get_queue_stat")
     */

    public function queueStat(string $name)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('http://rabbitmq:15672/api/queues/%2f/' . $name, [
            'auth' => [
                'rbtmq_user',
                'rbtmq111'
            ]
        ]);

        $view = View::create();
        $view->setData(["queue_info"=>json_decode($response->getBody()->getContents(), true)]);

        return $view;
    }

    /**
     * @Route("/queue-generate-msg/{amount}", name="queue_generate_messages")
     */
    public function queueGenerateMessages(int $amount, ProducerInterface $producer)
    {
        $sentCnt = 0;

        while($amount--){
            $msg = array('id' => uniqid(), 'dt' => (new \DateTime())->format("d-m-Y H:i:s"));
            $producer->publish(serialize($msg));

            $sentCnt++;
        }

        $view = View::create();
        $view->setData(["sent"=>$sentCnt]);

        return $view;
    }

}