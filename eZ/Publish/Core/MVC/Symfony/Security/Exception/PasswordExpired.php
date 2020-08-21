<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);
namespace eZ\Publish\Core\MVC\Symfony\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

//password expired exists, it this exception will stay as is, rename is needed
class PasswordExpired extends AuthenticationException
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("User's password expired", 0, $previous);
    }
}
