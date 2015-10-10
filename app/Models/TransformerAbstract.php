<?php

namespace App\Models;

use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract as TransformerAbstractOld;

class TransformerAbstract extends TransformerAbstractOld {

    protected function callIncludeMethod(Scope $scope, $includeName, $data) {
        $scopeIdentifier = $scope->getIdentifier($includeName);
        $params = $scope->getManager()->getIncludeParams($scopeIdentifier);

        // Check if the method name actually exists
        $methodName = 'include'.str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('-', '', $includeName))));

        $resource = call_user_func(array($this, $methodName), $data, $params);

        if ($resource === null) {
            return false;
        }

        if (! $resource instanceof ResourceAbstract) {
            throw new \Exception(sprintf(
                'Invalid return value from %s::%s(). Expected %s, received %s.',
                __CLASS__,
                $methodName,
                'League\Fractal\Resource\ResourceAbstract',
                gettype($resource)
            ));
        }

        return $resource;
    }

}