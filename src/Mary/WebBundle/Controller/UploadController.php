<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Upload:index.html.twig');
    }

    public function testAction(Request $request)
    {
        if ($request->isMethod('post')) {
            //$this->dump($_FILES);
            $file = $_FILES['file'];
            if ($file['error']) {
                $this->dump('Upload Error: ' . $file['error']);
            }
            $uploadedFile = new UploadedFile($file['tmp_name'], $file['name'], $file['type']);
            $filename = $this->getUploaderService()->upload($uploadedFile, 'default');
            $this->dump($filename);
        }
        return $this->render('MaryWebBundle:Upload:test.html.twig');
    }
}