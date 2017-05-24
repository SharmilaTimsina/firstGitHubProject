<?php

class ErrorsController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Oops!');
        parent::initialize();
    }

    public function show404Action() {
        $this->response->redirect('index/index');
    }

    public function show401Action() {
        $this->response->redirect('index/index');
    }

    public function show500Action() {
        //$this->response->redirect('index/index');
    }

}
