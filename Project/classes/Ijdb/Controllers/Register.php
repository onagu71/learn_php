<?php
namespace Ijdb\Controllers;
use \Hanbit\DatabaseTable;

class Register
{
    private $authorsTable;

    public function __construct(DatabaseTable $authorsTable)
    {
        $this->authorsTable = $authorsTable;
    }

    public function registrationForm()
    {
        return ['template' => 'register.html.php', 'title' => '사용자 등록'];   
    }

    public function success()
    {
        return ['template' => 'registersuccess.html.php', 'title' => '등록 성공'];
    }

    public function registerUser() 
    {
        $author = $_POST['author'];

        $valid = true;
        $errors = [];

        if(empty($author['name'])) {
            $valid = false;
            $errors[] = '이름을 입력해야 합니다.';
        }

        if(empty($author['email'])) {
            $valid = false;
            $errors[] = '이메일을 입력해야 합니다.';
        } elseif (filter_var($author['email'], FILTER_VALIDATE_EMAIL) == false) {
            $valid = false;
            $errors[] = '유효하지 않은 이메일 주소입니다..';
        } else {

            $author['email'] = strtolower($author['email']);

            if (count($this->authorsTable->find('email', $author['email'])) > 1) {
                $valid = false;
                $errors[] = '이미 가입된 이메일 주소입니다..';
            }   
        }

        if(empty($author['password'])) {
            $valid = false;
            $errors[] = '비밀번호를 입력해야 합니다.';
        }

        if($valid == true) {
            $author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);

            $this->authorsTable->save($author);
        
            header('Location: /author/success');
        } else {
            return ['template' => 'register.html.php', 'title' => '사용자 등록', 
                'variables' => [
                    'errors' => $errors,
                    'author' => $author
                ]
            ];
        }


        
    }
}
