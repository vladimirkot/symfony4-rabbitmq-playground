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
use GuzzleHttp\Exception\ClientException;

/**
 * @Route("/api")
 */
class QueueApiController extends AbstractFOSRestController
{
    /**
     * @Route("/queue-stat/{name}", name="get_queue_stat")
     * @var string $name - queue name
     * @return View
     */

    public function queueStat(string $name):View
    {
        $client = new \GuzzleHttp\Client();
        $auth = [
            'rbtmq_user',
            'rbtmq111'
        ];

        $url = 'http://rabbitmq:15672/api/queues/%2f/' . $name;

        try{

            $response = $client->get($url, ['auth' => $auth]);
        }
        catch (ClientException $e)
        {
            $this->initDataItems($name);
            $response = $client->get($url, ['auth' => $auth]);
        }


        $view = View::create();
        $view->setData(["queue_info"=>json_decode($response->getBody()->getContents(), true)]);

        return $view;
    }

    /**
     * @Route("/queue-generate-msg/{amount}", name="queue_generate_messages")
     * @var int $amount - amount of messages to generate
     * @var ProducerInterface $producer - producer service instance
     * @return View
     * @throws
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

    /**
     * @param string $name - common name for new created exchange and queue that binds to exchange
     */
    private function initDataItems(string $name)
    {
        $exchangeName = $name;
        $queueName = $name;

        $client = new \GuzzleHttp\Client();

        $auth = [
            'rbtmq_user',
            'rbtmq111'
        ];

        $client->put('http://rabbitmq:15672/api/queues/%2f/' . $queueName, [
            'body' => '{"auto_delete":false,"durable":true}',
            'auth' => $auth
        ]);

        $client->put('http://rabbitmq:15672/api/exchanges/%2f/' . $exchangeName, [
            'body' => '{"type":"direct","auto_delete":false,"durable":true,"internal":false,"arguments":{}}',
            'auth' => $auth
        ]);


        $client->post("http://rabbitmq:15672/api/bindings/%2f/e/{$exchangeName}/q/{$queueName}", [
            'body' => '{}',
            'auth' => $auth
        ]);
    }
}