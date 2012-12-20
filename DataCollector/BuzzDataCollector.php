<?php

namespace Playbloom\Bundle\BuzzBundle\DataCollector;

use Buzz\Listener\History\Journal;
use Buzz\Util\Url;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * BuzzDataCollector.
 *
 * @author Ludovic Fleury <ludo.flery@gmail.com>
 */
class BuzzDataCollector extends DataCollector
{
    /**
     * @var Buzz\Listener\History\Journal
     */
    private $profiler;

    /**
     * Constructor
     *
     * @param Journal $profiler
     */
    public function __construct(Journal $profiler)
    {
        $this->data = array(
            'calls'    => array(),
            'error_count' => 0,
            'methods'     => array(),
            'total_time'  => 0,
        );
        $this->profiler = $profiler;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        foreach($this->profiler->getEntries() as $call) {
            $error = false;
            $request = $call->getRequest();

            // @todo this is quick & dirty, since Buzz doesn't provide much.
            // We have the same problem with the headers
            // it would be better to expose some advanced Request & Response objects
            // Yet it will require more logic here
            $request->_url = new Url($request->getUrl());

            $response = $call->getResponse();

            $requestContent = null;
            $responseContent = $response->getContent();

            $time = array(
                'total' => $call->getDuration(),
                'connection' => null
            );

            $this->data['total_time'] += $call->getDuration();

            if (!isset($this->data['methods'][$request->getMethod()])) {
                $this->data['methods'][$request->getMethod()] = 0;
            }

            $this->data['methods'][$request->getMethod()]++;

            if ($response->isClientError() || $response->isServerError()) {
                $this->data['error_count']++;
                $error = true;
            }

            $this->data['calls'][] = array(
                'request' => $request,
                'requestContent' => $requestContent,
                'response' => $response,
                'responseContent' => $responseContent,
                'time' => $time,
                'error' => $error
            );
        }
    }

    /**
     * Return the HTTP calls
     *
     * @return array
     */
    public function getCalls()
    {
        return $this->data['calls'];
    }

    /**
     * Return the total errors
     *
     * @return int
     */
    public function countErrors()
    {
        return $this->data['error_count'];
    }

    /**
     * Return all the HTTP methods used (with a counter)
     *
     * @return array
     */
    public function getMethods()
    {
        return $this->data['methods'];
    }

    /**
     * Return the total HTTP calls time
     *
     * @return int Time in second
     */
    public function getTotalTime()
    {
        return $this->data['total_time'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'buzz';
    }
}
