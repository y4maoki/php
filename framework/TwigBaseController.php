<?php

class TwigBaseController extends BaseController {
    protected \Twig\Environment $twig;
    public $template = "";

    public function setTwig(\Twig\Environment $twig) {
        $this->twig = $twig;
    }

    public function get(array $context) {
        // Рендр шаблона
        echo $this->twig->render($this->template, $context);
    }
}