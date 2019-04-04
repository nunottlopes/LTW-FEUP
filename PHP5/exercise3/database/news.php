<?php
function getAllNews(){
    global $db;
    $stmt = $db->prepare('SELECT * FROM news');
    $stmt->execute();
    $articles = $stmt->fetchAll();
    return $articles;
};

function getArticle($id){
    global $db;
    $stmt = $db->prepare('SELECT * FROM news JOIN users USING (username) WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $article = $stmt->fetch();
    return $article;
};
?>