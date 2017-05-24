<?php

use Phalcon\Mvc\View;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaData;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * We register the events manager
 */
$di->set('dispatcher', function() use ($di) {

    $eventsManager = new EventsManager;

    /**
     * Check if the user is allowed to access certain action using the SecurityPlugin
     */
    $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);

    /**
     * Handle exceptions and not-found exceptions using NotFoundPlugin
     */
    //$eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

    $dispatcher = new Dispatcher;
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function() use ($config) {
    $url = new UrlProvider();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});


$di->set('view', function() use ($config) {

    $view = new View();

    $view->setViewsDir(APP_PATH . $config->application->viewsDir);

    $view->registerEngines(array(
        ".volt" => 'volt'
    ));

    return $view;
});

/**
 * Setting up volt
 */
$di->set('volt', function($view, $di) {

    $volt = new VoltEngine($view, $di);

    $volt->setOptions(array(
        "compiledPath" => APP_PATH . "cache/volt/",
        'compileAlways' => true
    ));

    $compiler = $volt->getCompiler();
    $compiler->addFunction('is_a', 'is_a');

    return $volt;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function() use ($config) {
    $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    return new $dbclass(array(
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->name
    ));
});

$di->set('db2', function() use ($config) {
    $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database2->adapter;
    return new $dbclass(array(
        "host" => $config->database2->host,
        "username" => $config->database2->username,
        "password" => $config->database2->password,
        "dbname" => $config->database2->name
    ));
});
//nexus
$di->set('db4', function() use ($config) {
    $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database2->adapter;
    return new $dbclass(array(
        "host" => $config->database4->host,
        "username" => $config->database4->username,
        "password" => $config->database4->password,
        "dbname" => $config->database4->name
    ));
});

$di->set('db5', function() use ($config) {
    $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database2->adapter;
    return new $dbclass(array(
        "host" => $config->database5->host,
        "username" => $config->database5->username,
        "password" => $config->database5->password,
        "dbname" => $config->database5->name
    ));
});

$di->set('db6', function() {
    return new mysqli('52.58.239.5', 'elgringo', 'hugala@o219H2', 'mobisteinreporting');
});

$di->set('db7', function() use($config) {
    $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database2->adapter;
    return new $dbclass(array(
        "host" => $config->database7->host,
        "username" => $config->database7->username,
        "password" => $config->database7->password,
        "dbname" => $config->database7->name
    ));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function() {
    return new MetaData();
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function() {
    ini_set('session.gc_maxlifetime', 65530);
    session_set_cookie_params(65530);
    $session = new SessionAdapter();
    $session->start();
    return $session;
});

/**
 * Register the flash service with custom CSS classes
 */
$di->set('flash', function() {
    return new FlashSession(array(
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
    ));
});

$di->set(
    "crypt",
    function () {
        $crypt = new Phalcon\Crypt();

        $crypt->setKey('#1dj8$2r1q=dp?.ak//j1V$'); // Use your own key!

        return $crypt;
    }
);

$di->set('cookies', function () {
    $cookies = new Phalcon\Http\Response\Cookies();
    $cookies->useEncryption(true);
    return $cookies;
},true);

/**
 * Register a user component
 */
$di->set('elements', function() {
    return new Elements();
});

$di->set('viewCache', function () use ($config) {
    //Cache for one day
    $frontCache = new \Phalcon\Cache\Frontend\Data(array("lifetime" => 900));
    //Set file cache
    $cache = new Phalcon\Cache\Backend\File($frontCache, array("cacheDir" => '../app/cache/'));
    return $cache;
});


