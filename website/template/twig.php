<?php

use Twig\Environment;

include_once \dirname(__DIR__) . '/Twig/AsyncAwsTwigExtension.php';

return function (Environment $twig) {
    $twig->addExtension(new \Website\AsyncAwsTwigExtension());
};
