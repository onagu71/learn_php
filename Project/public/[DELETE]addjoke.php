<?php 

if (isset($_POST['joketext'])) {
    try {
        include __DIR__ . '/../includes/DatabaseConnection.php';
        include_once __DIR__ . '/../includes/DatabaseFunctions.php';

        
        insert($pdo, 'joke', [
            'joketext' => $_POST['joketext'], 
            'authorId' => 1,
            'jokedate' => new DateTime()
        ]);

        header('Location: jokes.php');
    } catch(PDOException $e) {
        $title = '오류가 발생했습니다.';

        $output = '데이터베이스 오류: ' . $e->getMessage() . ', 위치: ' . $e->getFile() . ':' . $e->getLine();
    }
} else {
    $title = '유머 글 등록';

    ob_start();
    include __DIR__ . '/../templates/addjoke.html.php';
    $output = ob_get_clean();

}
include __DIR__ . '/../templates/layout.html.php';

?>