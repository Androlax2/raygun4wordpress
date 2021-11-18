<?php


namespace Androlax2\Raygun4Wordpress;

use GuzzleHttp\Client;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Raygun4php\Interfaces\TransportInterface;
use Raygun4php\RaygunClient as BaseRaygunClient;
use Raygun4php\Transports\GuzzleAsync;
use Raygun4php\Transports\GuzzleSync;

class RaygunClient extends BaseRaygunClient
{
    /**
     * The instance of Raygun Client.
     *
     * @var RaygunClient
     */
    private static RaygunClient $instance;


    /**
     * Return the transport used.
     *
     * @return TransportInterface
     */
    public function getTransport(): TransportInterface
    {
        return $this->transport;
    }

    /**
     * Is the client asynchronous ?
     *
     * @return bool
     */
    public function isAsync(): bool
    {
        return method_exists($this->getTransport(), 'wait');
    }

    /**
     * Get the instance of RaygunClient.
     *
     * @return RaygunClient
     */
    public static function getInstance(): RaygunClient
    {
        // Check is $instance has been set
        if (!isset(self::$instance)) {
            // Creates sets object to instance
            $httpClient = new Client([
                'base_uri' => 'https://api.raygun.com',
                'headers'  => [
                    'X-ApiKey' => get_option('rg4wp_apikey'),
                ],
            ]);

            $isAsync = get_option('rg4wp_async') === "1";

            /**
             * Asynchronous usage or synchronous usage
             *
             * @see https://raygun.com/documentation/language-guides/php/crash-reporting/installation/#synchronous-usage
             */
            $transport = $isAsync ? new GuzzleAsync($httpClient) : new GuzzleSync($httpClient);

            /**
             * Start logging logic.
             */

            if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
                $logPath = WP_DEBUG_LOG;
            } else if (defined('WP_CONTENT_DIR') && WP_CONTENT_DIR) {
                $logPath = WP_CONTENT_DIR;
            } else {
                error_log("Could not define a log path for Raygun.", E_WARNING);
            }

            if (isset($logPath) && $logPath) {
                // Create logger
                $logger = new Logger('raygun');

                $logger
                    ->pushHandler(new StreamHandler($logPath))
                    ->pushHandler(new FirePHPHandler());

                // Attach logger to transport
                $transport->setLogger($logger);
            }

            self::$instance = new RaygunClient($transport, !get_option('rg4wp_usertracking'));
        }

        // Returns the instance
        return self::$instance;
    }
}