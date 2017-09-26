<?php

namespace Mary\WebBundle\Controller;

class ResponseController extends BaseController
{
    public function jsonAction()
    {
        $data = [
            'error' => 0,
            'message' => 'success'
        ];
        return $this->responseJson($data);
    }

    public function jsonSuccessAction()
    {
        $data = [
            'name' => 'tom',
            'age' => 22,
            'gender' => 'male'
        ];
        return $this->responseJsonSuccess($data);
    }

    public function jsonErrorAction()
    {
        return $this->responseJsonError('params invalid');
    }
}