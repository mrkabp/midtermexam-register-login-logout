<?php

session_start();

require_once __DIR__ . "/models.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$userId = $_SESSION["user_id"];

if (isset($_POST["addStudio"])) {
    $studio_name = trim($_POST["studio_name"]);
    $country = trim($_POST["country"]);

    if ($studio_name != "" && $country != "") {
        addStudio($pdo, $studio_name, $country, $userId);
    }

    header("Location: ../index.php");
    exit;
}

if (isset($_POST["updateStudio"])) {
    $id = $_POST["id"];
    $studio_name = trim($_POST["studio_name"]);
    $country = trim($_POST["country"]);

    if ($studio_name != "" && $country != "") {
        updateStudio($pdo, $id, $studio_name, $country, $userId);
    }

    header("Location: ../index.php");
    exit;
}

if (isset($_POST["addGame"])) {
    $game_name = trim($_POST["game_name"]);
    $genre = trim($_POST["genre"]);
    $release_year = $_POST["release_year"];
    $studio_id = $_POST["studio_id"];

    if (
        $game_name != "" &&
        $genre != "" &&
        filter_var($release_year, FILTER_VALIDATE_INT) !== false &&
        filter_var($studio_id, FILTER_VALIDATE_INT) !== false
    ) {
        addGame(
            $pdo,
            $game_name,
            $genre,
            $release_year,
            $studio_id,
            $userId
        );
    }

    header("Location: ../index.php");
    exit;
}

if (isset($_POST["updateGame"])) {
    $id = $_POST["id"];
    $game_name = trim($_POST["game_name"]);
    $genre = trim($_POST["genre"]);
    $release_year = $_POST["release_year"];
    $studio_id = $_POST["studio_id"];

    if (
        $game_name != "" &&
        $genre != "" &&
        filter_var($release_year, FILTER_VALIDATE_INT) !== false &&
        filter_var($studio_id, FILTER_VALIDATE_INT) !== false
    ) {
        updateGame(
            $pdo,
            $id,
            $game_name,
            $genre,
            $release_year,
            $studio_id,
            $userId
        );
    }

    header("Location: ../index.php");
    exit;
}

?>