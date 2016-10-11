<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\Crypt\Password\PasswordInterface;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'console' => [
        'router' => [
            'routes' => [
                'account-create' => [
                    'options' => [
                        'route' => 'ownpass:account:create [--name=] [--role=] [--email=] [--username=] [--force]',
                        'defaults' => [
                            'controller' => Controller\UserCli::class,
                            'action' => 'create',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\Authenticate::class => Controller\Service\AuthenticateFactory::class,
            Controller\UserCli::class => Controller\Service\UserCliFactory::class,
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ => [
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\XmlDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/doctrine',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ => __NAMESPACE__
                ],
            ],
        ]
    ],
    'forms' => [
        Form\Login::class => [
            'type' => Form\Login::class,
            'input_filter' => InputFilter\Login::class,
        ],
    ],
    'input_filters' => [
        'factories' => [
            InputFilter\Login::class => InputFilter\Service\LoginFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'login' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => Controller\Authenticate::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => Controller\Authenticate::class,
                        'action' => 'logout',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            AuthenticationServiceInterface::class => Authentication\Service\AuthenticationFactory::class,
        ],
        'invokables' => [
            PasswordInterface::class => Bcrypt::class,
        ],
    ],
    'session_containers' => [
        'AuthenticateSession',
    ],
    'validators' => [
        'factories' => [
            Validator\NoIdentityExists::class => Validator\Service\NoIdentityExistsFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'own-pass-user/authenticate/login' => __DIR__ . '/../view/own-pass-user/authenticate/login.phtml',
        ],
    ],
];
