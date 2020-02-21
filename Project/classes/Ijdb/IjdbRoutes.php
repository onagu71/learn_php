<?php
namespace Ijdb;

class IjdbRoutes implements \Hanbit\Routes
{
    private $authorsTable;
    private $jokesTable;
    private $authentication;

    public function __construct()
    {
        include __DIR__ . '/../../includes/DatabaseConnection.php';
        $this->jokesTable = new \Hanbit\DatabaseTable($pdo, 'joke', 'id');
        $this->authorsTable = new \Hanbit\DatabaseTable($pdo, 'author', 'id');
        $this->authentication = new \Hanbit\Authentication($this->authorsTable, 'email', 'password');
    }

    public function getRoutes(): array
    {
        $jokeController = new \Ijdb\Controllers\Joke($this->jokesTable, $this->authorsTable, $this->authentication);
        $authorController = new \Ijdb\Controllers\Register($this->authorsTable);

        $loginController = new \Ijdb\Controllers\Login($this->authentication);

        $routes = [
            'joke/edit' => [
                'POST' => [
                    'controller' => $jokeController,
                    'action' => 'saveEdit'
                ],
                'GET' => [
                    'controller' =>$jokeController,
                    'action' => 'edit'
                ],
                'login' => true
            ],
            'joke/delete' => [
                'POST' => [
                    'controller' =>$jokeController,
                    'action' => 'delete'
                ],
                'login' => true
            ],
            'joke/list' => [
                'GET' => [
                    'controller' =>$jokeController,
                    'action' => 'list'
                ]
            ],
            '' => [
                'GET' => [
                    'controller' =>$jokeController,
                    'action' => 'home'
                ]
            ],
            'author/register' => [
                'GET' => [
                    'controller' =>$authorController,
                    'action' => 'registrationForm'
                ],
                'POST' => [
                    'controller' => $authorController,
                    'action' => 'registerUser'
                ]
            ],
            'author/success' => [
                'GET' => [
                    'controller' =>$authorController,
                    'action' => 'success'
                ]
            ],
            'login' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'loginForm'
                ],
                'POST' => [
                    'controller' => $loginController,
                    'action' => 'processLogin'
                ]
            ],
            'login/success' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'success'
                ],
                'login' => true
            ],
            'login/error' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'error'
                ]
            ],
            'logout' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'logout'
                ]
            ]
        ];

        return $routes;
    }

    public function getAuthentication(): \Hanbit\Authentication
    {
        return $this->authentication;
    }
}