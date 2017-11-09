<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\Stopwatch\Stopwatch;
use Mary\WebBundle\Event\Events;
use JBZoo\Utils\Str as StringUtil;

class StopwatchController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Stopwatch:index.html.twig', []);
    }

    /*// 返回事件启动时所属的分类
    $event->getCategory();   // Returns the category the event was started in
    // 返回事件启动时间，以毫秒计
    $event->getOrigin();     // Returns the event start time in milliseconds
    // 停止所有尚未停止的周期
    $event->ensureStopped(); // Stops all periods not already stopped
    // 返回最初的那个周期的启动时间
    $event->getStartTime();  // Returns the start time of the very first period
    // 返回最后的那个周期的结束时间
    $event->getEndTime();    // Returns the end time of the very last period
    // 返回事件的持续时间，包括所有周期
    $event->getDuration();   // Returns the event duration, including all periods
    // 返回所有周期中的最大内存占用
    $event->getMemory();     // Returns the max memory usage of all periods*/
    public function periodsAction()
    {
        $eventName = Events::RUN_PERIODS_EVENT;
        $stopwatch = new Stopwatch();
        $stopwatch->start($eventName);
        echo StringUtil::random(10, false) . '<br>';
        $stopwatch->lap($eventName);
        echo StringUtil::uuid() . '<br>';
        $event = $stopwatch->stop($eventName);
        echo '<hr>';
        // 返回时间为毫秒数
        $this->dump($event->getPeriods(), false);
        $this->dump($event->getStartTime(), false);
        $this->dump($event->getEndTime(), false);
        $this->dump($event->getDuration(), false);
        die;
    }
}