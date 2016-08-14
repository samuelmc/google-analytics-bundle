<?php

/*
 * Created by Samuel Moncarey
 * 30/03/2016
 */

namespace Samuel\GaBundle\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class GaScriptListener implements EventSubscriberInterface
{

    /** @var \Twig_Environment */
    protected $twig;

    protected $trackingId;

    protected $excludePaths;

    public function __construct(\Twig_Environment $twig, $trackingId, $excludePaths)
    {
        $this->twig = $twig;
        $this->trackingId = $trackingId;
        $this->excludePaths = $excludePaths;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($event->isMasterRequest() && !$event->getRequest()->isXmlHttpRequest()) {
            if (!preg_match("/{$this->excludePaths}/", $event->getRequest()->getPathInfo())) {
                $script = $this->twig->render('@SamuelGa/ga_script.html.twig', ['tracking_id' => $this->trackingId]);

                $content = $event->getResponse()->getContent();
                $body_pos = strripos($content, '<body>');
                $script_pos = $body_pos !== false ? strripos($content, '<script', $body_pos) : false;
                $body_end_pos = strripos($content, '</body>');

                if (false !== $script_pos) {
                    $content = substr($content, 0, $script_pos) . $script . substr($content, $script_pos);
                    $event->getResponse()->setContent($content);
                } elseif (false !== $body_end_pos) {
                    $content = substr($content, 0, $body_end_pos) . $script . substr($content, $body_end_pos);
                    $event->getResponse()->setContent($content);
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse']
        ];
    }

}
