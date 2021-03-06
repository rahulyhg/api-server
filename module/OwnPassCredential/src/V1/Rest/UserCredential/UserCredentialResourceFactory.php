<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredential\V1\Rest\UserCredential;

use Doctrine\ORM\EntityManager;

class UserCredentialResourceFactory
{
    public function __invoke($services)
    {
        $entityManager = $services->get(EntityManager::class);

        return new UserCredentialResource($entityManager);
    }
}
