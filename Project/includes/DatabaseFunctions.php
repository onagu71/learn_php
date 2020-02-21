<?php

function query($pdo, $sql, $parameters = []) {
    $query = $pdo->prepare($sql);
    $query->execute($parameters);

    return $query;
}

function processDates($fields) {
    foreach($fields as $key => $value) {
        if($value instanceof DateTime) {
            $fields[$key] = $value->format('Y-m-d H:i:s');
        }
    }

    return $fields;
}

function save($pdo, $table, $primaryKey, $record) {
    try {
        if($record[$primaryKey] == '') {
            $record[$primaryKey] = null;
        }
        insert($pdo, $table, $record);
    } catch (PDOException $e) {
        update($pdo, $table, $primaryKey, $record);
    }
}



/**
 *   SELECT
 */

// // 글의 총 개수 불러오가
// function totalJokes($pdo) {
//     $query = query($pdo, 'SELECT COUNT(*) FROM `joke`');

//     $row = $query->fetch();

//     return $row[0];
// }

// // 특정 글 정보 불러오기
// function getJoke($pdo, $id) {
//     $parameters = [':id' => $id];
//     $query = query($pdo, 'SELECT * FROM `joke` WHERE `id` = :id', $parameters);

//     return $query->fetch();
// }

// // 모든 글 정보 불러오기
// function allJokes($pdo) {
//     $sql = 'SELECT `joke`.`id`, `joketext`, `jokedate`, `name`, `email` FROM `joke` INNER JOIN `author` ON `authorId` = `author`.`id`';
//     $query = query($pdo, $sql);

//     return $query->fetchAll();
// }

// // 모든 글 작성자 불러오기
// function allAuthor($pdo) {
//     $sql = 'SELECT * FROM `author`';
//     $query = query($pdo, $sql);

//     return $query->fetchAll();
// }

// $table의 모든 데이터 불러오기
function findAll($pdo, $table) {
    $result = query($pdo, 'SELECT * FROM `' . $table . '`');

    return $result->fetchAll();
}

function findById($pdo, $table, $primaryKey, $value) {
    $query = 'SELECT * FROM `' .$table . '` WHERE `' . $primaryKey . '` = :value';
    $parameters = [':value' => $value];

    $query = query($pdo, $query, $parameters);

    return $query->fetch();
}

function total($pdo, $table) {
    $query = query($pdo, 'SELECT COUNT(*) FROM `' . $table . '`');

    $row = $query->fetch();

    return $row[0];
}



/**
 * INSERT
 */
function insert($pdo, $table, $fields) {
    $query = 'INSERT INTO `' . $table . '` (';

    foreach($fields as $key => $value) {
        $query .= '`' . $key . '`,';
    }
    $query = rtrim($query, ',');
    
    $query .= ') VALUES (';
    foreach($fields as $key => $value) {
        $query .= ':' . $key . ',';
    }
    $query = rtrim($query, ',');
    $query .= ')';

    $fields = processDates($fields);

    query($pdo, $query, $fields);
}

// // 글 업로드
// function insertJoke($pdo, $fields) {
//     $query = 'INSERT INTO `joke` (';
    
//     foreach($fields as $key => $value) {
//         $query .= '`' . $key . '`,';
//     }
//     $query = rtrim($query, ',');
    
//     $query .= ') VALUES (';
//     foreach($fields as $key => $value) {
//         $query .= ':' . $key . ',';
//     }
//     $query = rtrim($query, ',');
//     $query .= ')';

//     $fields = processDates($fields);

//     query($pdo, $query, $fields);
// }

// 작성자 추가
// function insertAuthor($pdo, $fields) {
//     $query = 'INSERT INTO `묘쇅` (';
    
//     foreach($fields as $key => $value) {
//         $query .= '`' . $key . '`,';
//     }
//     $query = rtrim($query, ',');
    
//     $query .= ') VALUES (';
//     foreach($fields as $key => $value) {
//         $query .= ':' . $key . ',';
//     }
//     $query = rtrim($query, ',');
//     $query .= ')';

//     $fields = processDates($fields);

//     query($pdo, $query, $fields);
// }




/**
 * UPDATE
 */
function update($pdo, $table, $primaryKey, $fields) {
    $query = 'UPDATE `' . $table . '` SET ';
    
    foreach($fields as $key => $value) {
        $query .= '`' . $key . '` = :' .$key . ' ,';
    }

    $query = rtrim($query, ',');
    $query .= ' WHERE `' . $primaryKey .'` = :primaryKey';

    $fields['primaryKey'] = $fields['id'];

    $fields = processDates($fields);

    query($pdo, $query, $fields);
}

// function updateJoke($pdo, $fields) {
//     $query = 'UPDATE `joke` SET ';
    
//     foreach($fields as $key => $value) {
//         $query .= '`' . $key . '` = :' .$key . ' , ';
//     }
//     $query = rtrim($query, ',');
//     $query .= 'WHERE `id` = :primaryKey';

//     $fields = processDates($fields);

//     $fields['primatyKey'] = $fields['id'];

//     query($pdo, $query, $query);
// }




/**
 * DELETE
 */
// 글 삭제
// function deleteJoke($pdo, $id) {
//     $parameters = [':id' => $id];
//     $sql = 'DELETE FROM `joke` WHERE `id` = :id';
//     query($pdo, $sql, $parameters);
// }

function deleteAutor($pdo, $id) {
    $parameters = [':id' => $id];
    $sql = 'DELETE FROM `author` WHERE `id` = :id';
    query($pdo, $sql, $parameters);
}

function delete($pdo, $table, $primaryKey, $id) {
    $parameters = [':id' => $id];
    query($pdo, 'DELETE FROM `' . $table . '` WHERE `' . $primaryKey . '` = :id', $parameters);
}