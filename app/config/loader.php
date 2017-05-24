<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
        array(
            APP_PATH . $config->application->controllersDir,
            APP_PATH . $config->application->pluginsDir,
            APP_PATH . $config->application->libraryDir,
            APP_PATH . $config->application->excelDir,
            APP_PATH . $config->application->phpFastDir,
            APP_PATH . $config->application->modelsDir,
            APP_PATH . $config->application->formsDir,
        )
)->register();

$loader->registerClasses(
        array(
            "Cloudinary" => APP_PATH . $config->application->cloudinaryDir . 'Cloudinary.php',
            "CustomQuery" => APP_PATH . $config->application->libraryDir . 'CustomQuery.php',
            "FastDatabaseB" => APP_PATH . $config->application->libraryDir . 'FastDatabaseB.php',
            "HelpingFunctions" => APP_PATH . $config->application->libraryDir . 'HelpingFunctions.php',
            "ParseIPs" => APP_PATH . $config->application->libraryDir . 'parseIPs.php',
        )
);

$loader->registerNamespaces(
        array(
            "Cloudinary" => APP_PATH . $config->application->cloudinaryDir
        )
);

$loader->register();
