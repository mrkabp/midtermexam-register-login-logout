<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/core/models.php";

$type = $_GET["type"] ?? "";
$id = $_GET["id"] ?? "";

if (
    !in_array($type, ["studio", "game"], true) ||
    !ctype_digit((string)$id) ||
    (int)$id < 1
) {
    header("Location: index.php");
    exit();
}

if ($type == "studio") {
    $record = getStudioByID($pdo, $id);
} else {
    $record = getGameByID($pdo, $id);
}

if (!$record) {
    header("Location: index.php");
    exit();
}

$studios = getAllStudios($pdo);

?>

<!DOCTYPE html>
<html>
<head>

    <title>Edit <?= htmlspecialchars($type) ?> | AAA Studio</title>

    <link rel="stylesheet" href="edit.css">

</head>

<body>

<div class="header">

    <div class="brand">AAA STUDIO</div>

    <a href="index.php">Back to Dashboard</a>

</div>


<div class="main">

    <a href="index.php" class="back-link">
        &larr; Return to Dashboard
    </a>

    <div class="card">

        <div class="tag">
            Editing <?= htmlspecialchars($type) ?>
        </div>

        <h1>Update Record</h1>

        <p class="description">
            Modify the information below and save your changes
            to the studio management system.
        </p>


        <?php if ($type == "studio"): ?>

        <form action="core/handleForms.php" method="POST">

            <input type="hidden" name="id"
                   value="<?= $record["id"] ?>">

            <div class="form-group">

                <label>Studio Name</label>

                <input
                    type="text"
                    name="studio_name"
                    value="<?= htmlspecialchars($record["studio_name"]) ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Country</label>

                <input
                    type="text"
                    name="country"
                    value="<?= htmlspecialchars($record["country"]) ?>"
                    required
                >

            </div>

            <div class="buttons">

                <button type="submit" name="updateStudio">
                    SAVE CHANGES
                </button>

                <a href="index.php" class="cancel">
                    CANCEL
                </a>

            </div>

        </form>


        <?php else: ?>

        <form action="core/handleForms.php" method="POST">

            <input type="hidden" name="id"
                   value="<?= $record["id"] ?>">

            <div class="form-group">

                <label>Game Name</label>

                <input
                    type="text"
                    name="game_name"
                    value="<?= htmlspecialchars($record["game_name"]) ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Genre</label>

                <input
                    type="text"
                    name="genre"
                    value="<?= htmlspecialchars($record["genre"]) ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Release Year</label>

                <input
                    type="number"
                    name="release_year"
                    min="1950"
                    max="2100"
                    value="<?= $record["release_year"] ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Studio</label>

                <select name="studio_id" required>

                    <?php foreach ($studios as $studio): ?>

                    <option
                        value="<?= $studio["id"] ?>"
                        <?= (string)$record["studio_id"] === (string)$studio["id"]
                            ? "selected" : "" ?>
                    >
                        <?= htmlspecialchars($studio["studio_name"]) ?>
                    </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="buttons">

                <button type="submit" name="updateGame">
                    SAVE CHANGES
                </button>

                <a href="index.php" class="cancel">
                    CANCEL
                </a>

            </div>

        </form>

        <?php endif; ?>

    </div>

</div>

</body>
</html>