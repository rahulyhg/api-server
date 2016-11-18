<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassUserTest\V1\Rest\Account;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use OwnPassUser\V1\Rest\Account\AccountResource;
use OwnPassUser\V1\Rest\Account\AccountResourceFactory;
use PHPUnit_Framework_TestCase;
use Zend\Crypt\Password\PasswordInterface;

class AccountResourceFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassUser\V1\Rest\Account\AccountResourceFactory::__invoke
     */
    public function testInvoke()
    {
        // Arrange
        $factory = new AccountResourceFactory();

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMockForAbstractClass();
        $crypter = $this->getMockBuilder(PasswordInterface::class)->getMockForAbstractClass();

        $container = $this->getMockBuilder(ContainerInterface::class)->getMockForAbstractClass();
        $container->expects($this->at(0))->method('get')->willReturn($entityManager);
        $container->expects($this->at(1))->method('get')->willReturn($crypter);

        // Act
        $result = $factory($container, '', null);

        // Asserts
        $this->assertInstanceOf(AccountResource::class, $result);
    }
}
