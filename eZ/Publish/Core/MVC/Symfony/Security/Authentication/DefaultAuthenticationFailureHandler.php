<?php

declare(strict_types=1);

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\Core\MVC\Symfony\Security\Authentication;

use eZ\Publish\Core\MVC\Symfony\Security\Exception\PasswordExpired;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class DefaultAuthenticationFailureHandler extends \Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($exception instanceof PasswordExpired) {
            $this->setOptions([
                'failure_path' => '/user/forgot-password',
            ]);
        }

        return parent::onAuthenticationFailure($request, $exception);
    }
}
