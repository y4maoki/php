<?php

abstract class BaseMiddleware {
    abstract public function apply(BaseController $controller, array $context);
}