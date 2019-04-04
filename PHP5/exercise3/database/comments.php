<?php
function getNumComments($articleID){
    global $db;
    $query = "SELECT count(*) FROM comments WHERE news_id = '$articleID'";
    $db = new PDO('sqlite:news.db');
    $stmt2 = $db->prepare($query);
    $stmt2->execute();
    $numComments = $stmt2->fetch();
    print_r( $numComments[0]);
};

function getComments($id){
    global $db;
    $stmt = $db->prepare('SELECT * FROM comments JOIN users USING (username) WHERE news_id = ?');
    $stmt->execute(array($id));
    $comments = $stmt->fetchAll();
    return $comments;
};

function getNumCommentsString($id){
    global $db;
    $stmt = $db->prepare('SELECT count(*) FROM comments JOIN users USING (username) WHERE news_id = ?');
    $stmt->execute(array($id));
    $numComments = $stmt->fetch();
    if($numComments[0] == 1){
        $numCommentsString = $numComments[0] . ' Comment';
        return $numCommentsString;
    }
    else{
        $numCommentsString = $numComments[0] . ' Comments';
        return $numCommentsString;
    }
};
?>