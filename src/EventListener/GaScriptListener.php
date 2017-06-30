<?php

/*
 * Created by Samuel Moncarey
 * 30/03/2016
 */

namespace Samuelmc\GaBundle\EventListener;


use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class GaScriptListener
 *
 * @package Samuel\GaBundle\EventListener
 */
class GaScriptListener implements EventSubscriberInterface {

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $trackingId;

    /**
     * @var string
     */
    protected $excludePaths;

    /**
     * GaScriptListener constructor.
     *
     * @param \Twig_Environment $twig
     * @param Crawler $crawler
     * @param $trackingId
     * @param $excludePaths
     */
    public function __construct(\Twig_Environment $twig, $trackingId, $excludePaths) {
        $this->twig = $twig;
        $this->trackingId = $trackingId;
        $this->excludePaths = $excludePaths;
    }

    public function onKernelResponse(FilterResponseEvent $event) {
        if ($event->isMasterRequest() && !$event->getRequest()->isXmlHttpRequest()) {
            if (!preg_match("/{$this->excludePaths}/", $event->getRequest()->getPathInfo())) {

                $content = $event->getResponse()->getContent();
                if ($position = $this->findPosition($content) !== false) {
                    $event->getResponse()->setContent($this->injectScript($content, $position));
                }

            }
        }
    }

    private function findPosition($content) {
        $body_pos = strripos($content, '<body>');
        $script_pos = $body_pos !== false ? stripos($content, '<script', $body_pos) : false;
        $body_end_pos = strripos($content, '</body>');
        return (false !== $script_pos) ? $script_pos : ((false !== $body_end_pos) ? $body_end_pos : false);
    }

    private function injectScript($content, $position) {
        $script = $this->twig->render('@SamuelmcGa/ga_script.html.twig', ['tracking_id' => $this->trackingId]);
        $content = substr($content, 0, $position) . $script . substr($content, $position);
        return $content;
    }

    public static function getSubscribedEvents() {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse']
        ];
    }

}
