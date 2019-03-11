<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 2019-03-09
 * Time: 21:38
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PlaygroundController extends AbstractController
{
    public function index()
    {

        dump(getenv());

        return $this->render("playground/index.html.twig");
    }
}