<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplicationTest\Controller\Service;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use OwnPassApplication\Controller\Service\UserCliFactory;
use OwnPassApplication\Controller\UserCli;
use PHPUnit_Framework_TestCase;
use Zend\Crypt\Password\PasswordInterface;

class UserCliFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassApplication\Controller\Service\UserCliFactory::__invoke
     */
    public function testInvoke()
    {
        // Arrange
        $factory = new UserCliFactory();

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMockForAbstractClass();
        $crypter = $this->getMockBuilder(PasswordInterface::class)->getMockForAbstractClass();

        $container = $this->getMockBuilder(ContainerInterface::class)->getMockForAbstractClass();
        $container->expects($this->at(0))->method('get')->willReturn($entityManager);
        $container->expects($this->at(1))->method('get')->willReturn($crypter);

        // Act
        $result = $factory($container, '', null);

        // Assert
        $this->assertInstanceOf(UserCli::class, $result);
    }
}
