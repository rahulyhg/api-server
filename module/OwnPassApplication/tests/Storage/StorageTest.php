<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplicationTest\Storage;

use DateTime;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use OwnPassApplication\Entity\AccessToken;
use OwnPassApplication\Entity\Application;
use OwnPassApplication\Storage\Storage;
use PHPUnit_Framework_TestCase;
use Zend\Crypt\Password\PasswordInterface;

class StorageTest extends PHPUnit_Framework_TestCase
{
    private $entityManager;
    private $entityRepository;
    private $crypter;

    protected function setUp()
    {
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMockForAbstractClass();
        $this->entityRepository = $this->getMockBuilder(ObjectRepository::class)->getMockForAbstractClass();
        $this->crypter = $this->getMockBuilder(PasswordInterface::class)->getMockForAbstractClass();
    }

    /**
     * @covers OwnPassApplication\Storage\Storage::__construct
     * @covers OwnPassApplication\Storage\Storage::getAccessToken
     */
    public function testGetAccessToken()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        $application = new Application('client', 'name');
        $accessToken = new AccessToken('token', $application, new DateTime());

        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($this->entityRepository);
        $this->entityRepository->expects($this->once())->method('find')->willReturn($accessToken);

        // Act
        $result = $storage->getAccessToken('token');

        // Assert
        $this->assertEquals('client', $result['client_id']);
        $this->assertNull($result['user_id']);
        $this->assertNull($result['scope']);
        $this->assertNull($result['id_token']);
    }

    /**
     * @covers OwnPassApplication\Storage\Storage::__construct
     * @covers OwnPassApplication\Storage\Storage::getApplication
     * @covers OwnPassApplication\Storage\Storage::setAccessToken
     */
    public function testSetAccessTokenWithInvalidApplication()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        $this->entityManager->expects($this->once())->method('find')->willReturn(null);
        $this->entityManager->expects($this->never())->method('persist');
        $this->entityManager->expects($this->never())->method('flush');

        // Act
        $result = $storage->setAccessToken('token', 'client', 'user', time(), null);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers OwnPassApplication\Storage\Storage::__construct
     * @covers OwnPassApplication\Storage\Storage::getApplication
     * @covers OwnPassApplication\Storage\Storage::setAccessToken
     */
    public function testSetAccessTokenWithExceptionOnApplication()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        $this->entityManager->expects($this->once())->method('find')->will($this->throwException(new Exception()));
        $this->entityManager->expects($this->never())->method('persist');
        $this->entityManager->expects($this->never())->method('flush');

        // Act
        $result = $storage->setAccessToken('token', 'client', 'user', time(), null);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers OwnPassApplication\Storage\Storage::__construct
     * @covers OwnPassApplication\Storage\Storage::getAccount
     * @covers OwnPassApplication\Storage\Storage::getApplication
     * @covers OwnPassApplication\Storage\Storage::setAccessToken
     */
    public function testSetAccessTokenWithExceptionOnAccount()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        $application = new Application('client', 'name');

        $this->entityManager->expects($this->at(0))->method('find')->willReturn($application);
        $this->entityManager->expects($this->at(1))->method('find')->will($this->throwException(new Exception()));

        // Act
        $result = $storage->setAccessToken('token', 'client', 'user', time(), null);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassApplication\Storage\Storage::__construct
     * @covers OwnPassApplication\Storage\Storage::getApplication
     * @covers OwnPassApplication\Storage\Storage::getAccount
     * @covers OwnPassApplication\Storage\Storage::setAccessToken
     */
    public function testSetAccessToken()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        $application = new Application('client', 'name');

        $this->entityManager->expects($this->at(0))->method('find')->willReturn($application);
        $this->entityManager->expects($this->at(1))->method('find')->willReturn(null);
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        // Act
        $storage->setAccessToken('token', 'client', 'user', time(), null);

        // Assert
        // ...
    }

    /**
     * @covers OwnPassApplication\Storage\Storage::__construct
     * @covers OwnPassApplication\Storage\Storage::getAccessToken
     */
    public function testGetAccessTokenWithInvalidToken()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($this->entityRepository);

        // Act
        $result = $storage->getAccessToken('token');

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassApplication\Storage\Storage::scopeExists
     */
    public function testScopeExistsReturnsFalse()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        // Act
        $result = $storage->scopeExists('invalid');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers OwnPassApplication\Storage\Storage::getDefaultScope
     */
    public function testGetDefaultScope()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        // Act
        $result = $storage->getDefaultScope();

        // Assert
        $this->assertNull($result);
    }
}
