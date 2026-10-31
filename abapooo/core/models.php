<?php

require_once __DIR__ . "/dbConfig.php";

function getAllStudios($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM studios ORDER BY id DESC");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addStudio($pdo, $studio_name, $country, $userId) {
    $stmt = $pdo->prepare(
        "INSERT INTO studios
        (studio_name, country, added_by, last_updated_by)
        VALUES (?, ?, ?, ?)"
    );

    return $stmt->execute([
        $studio_name, $country, $userId, $userId
    ]);
}

function getStudioByID($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM studios WHERE id = ?");
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateStudio($pdo, $id, $studio_name, $country, $userId) {
    $stmt = $pdo->prepare(
        "UPDATE studios
        SET studio_name = ?, country = ?, last_updated_by = ?
        WHERE id = ?"
    );

    return $stmt->execute([
        $studio_name, $country, $userId, $id
    ]);
}

function deleteStudio($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM studios WHERE id = ?");

    return $stmt->execute([$id]);
}

function getAllGames($pdo) {
    $stmt = $pdo->prepare(
        "SELECT games.*, studios.studio_name
        FROM games
        INNER JOIN studios ON games.studio_id = studios.id
        ORDER BY games.id DESC"
    );

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addGame($pdo, $game_name, $genre, $release_year, $studio_id, $userId) {
    $stmt = $pdo->prepare(
        "INSERT INTO games
        (game_name, genre, release_year, studio_id, added_by, last_updated_by)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    return $stmt->execute([
        $game_name,
        $genre,
        $release_year,
        $studio_id,
        $userId,
        $userId
    ]);
}

function getGameByID($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateGame($pdo, $id, $game_name, $genre, $release_year, $studio_id, $userId) {
    $stmt = $pdo->prepare(
        "UPDATE games
        SET game_name = ?,
            genre = ?,
            release_year = ?,
            studio_id = ?,
            last_updated_by = ?
        WHERE id = ?"
    );

    return $stmt->execute([
        $game_name,
        $genre,
        $release_year,
        $studio_id,
        $userId,
        $id
    ]);
}

function deleteGame($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM games WHERE id = ?");

    return $stmt->execute([$id]);
}

?>