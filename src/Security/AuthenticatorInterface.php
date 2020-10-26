<?php

namespace App\Security;

use App\Entity\Token;

interface AuthenticatorInterface
{
    public function authenticate(Token $token);
}
