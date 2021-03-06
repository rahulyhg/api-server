<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplicationTest\Entity;

use DateTimeImmutable;
use OwnPassApplication\Entity\Application;
use OwnPassApplication\Entity\RefreshToken;
use OwnPassApplication\Entity\Account;
use PHPUnit_Framework_TestCase;

class RefreshTokenTest extends PHPUnit_Framework_TestCase
{
    private $application;
    private $account;
    private $expirationDate;

    protected function setUp()
    {
        $this->application = new Application('client', 'name');
        $this->account = new Account('', '', '');
        $this->expirationDate = new DateTimeImmutable();
    }

    /**
     * @covers OwnPassApplication\Entity\RefreshToken::__construct
     * @covers OwnPassApplication\Entity\RefreshToken::getRefreshToken
     */
    public function testGetRefreshToken()
    {
        // Arrange
        $entity = new RefreshToken('token', $this->application, $this->account, $this->expirationDate);

        // Act
        $result = $entity->getRefreshToken();

        // Assert
        $this->assertEquals('token', $result);
    }

    /**
     * @covers OwnPassApplication\Entity\RefreshToken::__construct
     * @covers OwnPassApplication\Entity\RefreshToken::getApplication
     */
    public function testGetApplication()
    {
        // Arrange
        $entity = new RefreshToken('token', $this->application, $this->account, $this->expirationDate);

        // Act
        $result = $entity->getApplication();

        // Assert
        $this->assertEquals($this->application, $result);
    }

    /**
     * @covers OwnPassApplication\Entity\RefreshToken::__construct
     * @covers OwnPassApplication\Entity\RefreshToken::getAccount
     */
    public function testGetAccount()
    {
        // Arrange
        $entity = new RefreshToken('token', $this->application, $this->account, $this->expirationDate);

        // Act
        $result = $entity->getAccount();

        // Assert
        $this->assertEquals($this->account, $result);
    }

    /**
     * @covers OwnPassApplication\Entity\RefreshToken::__construct
     * @covers OwnPassApplication\Entity\RefreshToken::getExpires
     */
    public function testGetExpires()
    {
        // Arrange
        $entity = new RefreshToken('token', $this->application, $this->account, $this->expirationDate);

        // Act
        $result = $entity->getExpires();

        // Assert
        $this->assertInstanceOf(\DateTimeInterface::class, $result);
    }

    /**
     * @covers OwnPassApplication\Entity\RefreshToken::getScope
     * @covers OwnPassApplication\Entity\RefreshToken::setScope
     */
    public function testSetGetScope()
    {
        // Arrange
        $entity = new RefreshToken('token', $this->application, $this->account, $this->expirationDate);

        // Act
        $entity->setScope('scope');

        // Assert
        $this->assertEquals('scope', $entity->getScope());
    }
}
