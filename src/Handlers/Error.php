<?php

namespace App\Handlers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Monolog\Logger;

final class Error extends \Slim\Handlers\Error
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Error constructor.
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param \Exception $exception
     * @return Response
     */
    public function __invoke(Request $request, Response $response, \Exception $exception)
    {
        $logMessage = $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine();
        // Log the message
        $this->logger->critical($logMessage);

        // create a JSON error string for the Response body
        $body = json_encode([
            'error' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return $response
            ->withStatus(500)
            ->withHeader('Content-type', 'application/json')
            ->write($body);
    }
}
