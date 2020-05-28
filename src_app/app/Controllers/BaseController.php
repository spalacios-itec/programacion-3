<?php

namespace App\Controllers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Laminas\Diactoros\Response\HtmlResponse;

class BaseController {

    /**
     * @var Environment
     */
    protected $templateEngine;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $loader = new FilesystemLoader( __DIR__.'/../views');
        $this->templateEngine = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);
    }

    /**
     * @param string $view
     * @param array $data
     * @return HtmlResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderHTML(string $view, array $data=[])
    {
        return new HtmlResponse($this->templateEngine->render($view,$data));
    }
}