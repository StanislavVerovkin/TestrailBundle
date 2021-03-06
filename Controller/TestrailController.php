<?php

namespace Test\TestrailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Test\TestrailBundle\Service\TestrailService;

class TestrailController extends Controller
{
    public function testAction()
    {

        $testRail = $this->get('test_rail');

        $data = $testRail->getResponseData();

        return $this->render('TestTestrailBundle:Default:index.html.twig',[
            'data' => $data
        ]);
    }
}
