<?php

namespace modules\former\controllers;

use Craft;
use craft\log\Dispatcher;
use craft\web\Controller;
use craft\web\Response;

class ValidationController extends Controller
{
    public array|bool|int $allowAnonymous = true;

    public function actionLocalizations(): Response
    {
        $this->response->acceptMimeType = 'application/javascript; charset=UTF-8';
        $this->response->format = $this->response::FORMAT_RAW;
        $this->response->headers->remove('Content-Type');
        $this->response->headers->set('Content-Type', 'application/javascript; charset=UTF-8');

        $this->response->data = 'const validationMessages = ' . json_encode(
            include __DIR__ . '/../translations/de/validation.php'
        );

        return $this->response;
    }
}