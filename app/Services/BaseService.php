<?php

namespace App\Services;

class BaseService
{
    /**
     * Call an Action class and execute it with provided parameters.
     *
     * @param class-string $actionClass
     * @param mixed ...$params
     * @return mixed
     */
    protected function callAction(string $actionClass, ...$params)
    {
        $action = app($actionClass);
        return $action->execute(...$params);
    }
}
