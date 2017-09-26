<?php
namespace Mary\Common\Response;

class ResponseFormatter implements ResponseFormatterInterface
{
    protected $successData = [];
    protected $errorData = [];

    public function success($data)
    {
        $this->successData = [
            'error'     => 0,
            'data'      => $data
        ];
        return $this->successData;
    }

    public function error($error, $code = 0)
    {
        $this->errorData = [
            'error'     => 1,
            'code'      => $code,
            'msg'       => $error
        ];
        return $this->errorData;
    }

}