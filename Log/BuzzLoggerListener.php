<?php

namespace Playbloom\Bundle\BuzzBundle\Log;

use Buzz\Listener\ListenerInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;
use Monolog\Logger;

/**
 * Buzz logger listener
 *
 * Provide a Monolog support for Buzz
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class BuzzLoggerListener implements ListenerInterface
{
    /**
     * @var Monolog\Logger
     */
    private $logger;

    /**
     * Constructor
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function preSend(RequestInterface $request)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function postSend(RequestInterface $request, MessageInterface $response)
    {
        $priority = $response && !$response->isSuccessful() ? Logger::INFO : Logger::DEBUG;
        $message = sprintf('Requested "%s" %s "%s"', $request->getHost(), $request->getMethod(), $request->getResource());

        $this->logger->addRecord($priority, $message);
    }
}
