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
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;


class TestController extends AbstractController
{
    public function rabbitmqTest()
    {
/*        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'rbtmq_user', 'rbtmq111');
        $channel = $connection->channel();
        list($queue, $messageCount, $consumerCount) = $channel->queue_declare('ppm-parse', true);
        dump($channel->queue_declare('ppm-parse', true));*/

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://rabbitmq:15672/api/queues/%2f/ppm-parse', [
            'auth' => [
                'rbtmq_user',
                'rbtmq111'
            ]
        ]);

        dump($response);

        //dump(json_decode($response->getBody()));

        dump(getenv());



        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['Task', 'Hours per Day'],
                ['Work',     11],
                ['Eat',      2],
                ['Commute',  2],
                ['Watch TV', 2],
                ['Sleep',    7]
            ]
        );
        $pieChart->getOptions()->setTitle('My Daily Activities');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);


        return $this->render("test/rabbitmq_test.html.twig",["pie_chart"=>$pieChart]);
    }
}