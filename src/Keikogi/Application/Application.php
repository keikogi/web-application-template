<?php

namespace Keikogi\Application;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

use Silex\Application as SilexApplication;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\MonologServiceProvider;

class Application
{
    public static function run($options, $debug = true)
    {
        $app = new SilexApplication();

        $app['debug'] = $debug;

        $app->register(
            new TwigServiceProvider(),
            array(
                'twig.path' => array(
                    __DIR__ . '/Views',
                    ROOT_PATH . '/src/Views',
                ),
                'twig.class_path' => ROOT_PATH . '/vendor/Twig/lib',
                'twig.options' => array(
                    'cache' => ROOT_PATH . '/cache'
                ),
            )
        );

        $app->register(
            new MonologServiceProvider(),
            array(
                'monolog.logfile' => ROOT_PATH . '/log/' . ($app['debug'] ? 'dev' : 'prod') . '.log',
            )
        );

        $app->error(
            function (\Exception $e, $code) use ($app) {
                if (!$app['debug']) {
                    return $app['twig']->render(
                        'Error/index.html.twig',
                        array(
                            'code' => $code
                        )
                    );
                }
            }
        );

        $app['routes'] = $app->extend(
            'routes',
            function (RouteCollection $routes, SilexApplication $app) {
                $loader = new YamlFileLoader(
                    new FileLocator(
                        ROOT_PATH . '/resources/config'
                    )
                );

                $collection = $loader->load('routes.yml');
                $routes->addCollection($collection);
                return $routes;
            }
        );

        return $app->run();
    }
}
