<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller {

    protected function initialize() {
        $this->tag->prependTitle('MobisteinReport | ');
        $this->view->setTemplateAfter('main');
        date_default_timezone_set('Europe/Lisbon');
    }

    protected function forward($uri) {
        $uriParts = explode('/', $uri);
        $params = array_slice($uriParts, 2);
        return $this->dispatcher->forward(
                        array(
                            'controller' => $uriParts[0],
                            'action' => isset($uriParts[1]) ? $uriParts[1] : '',
                            'params' => $params
                        )
        );
    }

}
