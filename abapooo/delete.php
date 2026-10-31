<?php

require_once __DIR__ . "/core/models.php";

if (isset($_GET["deleteStudio"])) {
    deleteStudio($pdo, $_GET["deleteStudio"]);

    header("Location: index.php");
    exit;
}

if (isset($_GET["deleteGame"])) {
    deleteGame($pdo, $_GET["deleteGame"]);

    header("Location: index.php");
    exit;
}

header("Location: index.php");
exit;

?>