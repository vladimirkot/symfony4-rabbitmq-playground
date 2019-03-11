<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 2019-03-09
 * Time: 21:38
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TestController extends AbstractController
{
    public function rabbitmqTest()
    {

        dump(getenv());

        return $this->render("test/rabbitmq_test.html.twig",[]);
    }
}