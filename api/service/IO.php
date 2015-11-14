<?php

namespace service;

class IO
{
    public $request;
    public $response;
    public $parameter;

    /**
     * IO constructor.
     */
    public function __construct()
    {
        $this->setRequest();
        $this->setResponse(null);
    }

    /**
     * @return object
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest()
    {
        $request = file_get_contents("php://input");
        $this->request = json_decode($request);
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param $response array
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function sendResponse()
    {
        header("Content-Type: application/json");
        echo json_encode($this->response);
    }
}