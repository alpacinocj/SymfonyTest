<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\DomCrawler\Crawler;

class DomController extends BaseController
{
    private $html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <h1>Symfony2 Component Crawler</h1>
        <p class="message">Hello World!</p>
        <p>Hello Crawler!</p>
        <p id="error">Some error !</p>
        <a href="http://www.baidu.com">Baidu</a>
        <a href="/index.php">Index</a>
        <img src="https://www.baidu.com/img/bd_logo1.png" alt="baidu">
    </body>
</html>
HTML;

    private $crawler;

    public function __construct()
    {
        $this->crawler = new Crawler($this->html);
    }

    public function indexAction()
    {
        return $this->render('MaryWebBundle:Dom:index.html.twig', []);
    }

    public function testAction()
    {
        foreach ($this->crawler as $element) {
            var_dump($element->nodeName);
        }
        die;
    }

    public function filterAction()
    {
        // css selector
        // 所有filter方法返回一个带有已过滤内容的 Crawler 实例
        $filter = $this->crawler->filter('body > p');
        $this->dump($filter);
    }

    public function positionAction()
    {
        $filter = $this->crawler->filter('body p');
        $this->dump($filter->eq(0)->text(), false);
        $this->dump($filter->first()->text(), false);
        $this->dump($filter->last()->text(), false);
        $this->dump($filter->siblings()->text(), false);
        $this->dump($filter->nextAll()->text(), false);
        $this->dump($filter->previousAll()->text(), false);
        die;
    }

    public function valueAction()
    {
        $this->dump($this->crawler->filter('body')->nodeName(), false);
        $this->dump($this->crawler->filter('.message')->text(), false);
        $this->dump($this->crawler->filter('#error')->html(), false);
        $this->dump($this->crawler->filter('body > p')->first()->attr('class'), false);
        die;
    }

    public function addAction()
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent('<p>hello world</p>');
        $this->dump($crawler->filter('p')->text(), false);
        die;
    }

    public function linkAction()
    {
        // current uri parameter is required
        $crawler = new Crawler($this->html, 'http://www.example.com');
        $absLink = $crawler->selectLink('Baidu')->eq(0)->link()->getUri();
        $this->dump($absLink, false);
        $resLink = $crawler->selectLink('Index')->eq(0)->link()->getUri();
        $this->dump($resLink, false);
        die;
    }

}