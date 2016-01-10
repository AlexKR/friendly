<?php

namespace App\Lib;

use \Everyman\Neo4j\Client;

class AppContainer extends \Slim\Container
{
    public function __construct(array $settings = [])
    {
        parent::__construct($settings);

        $this['view'] = function ($container) {
            return new \Slim\Views\JsonView();
        };

        $this['log'] = function ($container) {
            return new \App\Service\Logger();
        };

        $this['errorHandler'] = function ($container) {
            return new \App\Handlers\Error($container['log']);
        };

        $this['friendship'] = function ($container) {
            $client = new Client(NEO4J_HOST, NEO4J_PORT);
            $client->getTransport()
                ->setAuth(NEO4J_LOGIN, NEO4J_PASSWORD);

            return new \App\Service\Friendship($client);
        };
    }
}
