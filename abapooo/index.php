<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/core/models.php";

$studiosQuery = $pdo->query("
    SELECT
        s.*,
        ua.username AS added_by_name,
        uu.username AS updated_by_name
    FROM studios s
    LEFT JOIN users ua ON s.added_by = ua.id
    LEFT JOIN users uu ON s.last_updated_by = uu.id
    ORDER BY s.id DESC
");

$studios = $studiosQuery->fetchAll(PDO::FETCH_ASSOC);

$gamesQuery = $pdo->query("
    SELECT
        g.*,
        s.studio_name,
        ua.username AS added_by_name,
        uu.username AS updated_by_name
    FROM games g
    LEFT JOIN studios s ON g.studio_id = s.id
    LEFT JOIN users ua ON g.added_by = ua.id
    LEFT JOIN users uu ON g.last_updated_by = uu.id
    ORDER BY g.id DESC
");

$games = $gamesQuery->fetchAll(PDO::FETCH_ASSOC);

$totalStudios = count($studios);
$totalGames = count($games);

?>

<!DOCTYPE html>
<html>
<head>
    <title>AAA Studio Dashboard</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="sidebar">

    <div class="brand">AAA STUDIO</div>

    <div class="brand-subtitle">
        MANAGEMENT SYSTEM
    </div>

    <div class="nav-title">MAIN MENU</div>

    <a href="#dashboard" class="nav-link active">Dashboard</a>

    <a href="#studios" class="nav-link">Studios</a>

    <a href="#games" class="nav-link">Games</a>

    <a href="logout.php" class="nav-link logout">Logout</a>

</div>


<div class="main" id="dashboard">

    <div class="topbar">

        <h1>Dashboard</h1>

        <div class="user-info">
            Logged in as:
            <strong>
                <?= htmlspecialchars($_SESSION['username']) ?>
            </strong>
        </div>

    </div>


    <div class="welcome">

        <h2>
            Welcome back, <?= htmlspecialchars($_SESSION['first_name']) ?>!
        </h2>

        <p>
            Manage your game studios and development projects here.
        </p>

    </div>


    <div class="stats">

        <div class="stat-card">
            <p>TOTAL STUDIOS</p>
            <h2><?= $totalStudios ?></h2>
        </div>

        <div class="stat-card">
            <p>TOTAL GAMES</p>
            <h2><?= $totalGames ?></h2>
        </div>

    </div>


    <!-- STUDIO MANAGEMENT -->

    <div class="section" id="studios">

        <div class="section-header">
            <h2>Studio Management</h2>
            <span class="badge">Parent Entity</span>
        </div>

        <h3>Add New Studio</h3>

        <form action="core/handleForms.php" method="POST">

            <div class="form-grid">

                <div>
                    <label>Studio Name</label>
                    <input type="text" name="studio_name" required>
                </div>

                <div>
                    <label>Country</label>
                    <input type="text" name="country" required>
                </div>

            </div>

            <button type="submit" name="addStudio" class="form-button">
                + Add Studio
            </button>

        </form>

        <br>

        <h3>Registered Studios</h3>

        <div class="table-container">

            <table>

                <tr>
                    <th>ID</th>
                    <th>Studio Name</th>
                    <th>Country</th>
                    <th>Added By</th>
                    <th>Date Added</th>
                    <th>Last Updated By</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>

                <?php foreach ($studios as $studio): ?>

                <tr>

                    <td><?= $studio['id'] ?></td>

                    <td>
                        <strong>
                            <?= htmlspecialchars($studio['studio_name']) ?>
                        </strong>
                    </td>

                    <td><?= htmlspecialchars($studio['country']) ?></td>

                    <td><?= htmlspecialchars($studio['added_by_name'] ?? 'Not recorded') ?></td>

                    <td class="record-info">
                        <?= htmlspecialchars($studio['created_at'] ?? 'Not recorded') ?>
                    </td>

                    <td><?= htmlspecialchars($studio['updated_by_name'] ?? 'Not recorded') ?></td>

                    <td class="record-info">
                        <?= htmlspecialchars($studio['updated_at'] ?? 'Not recorded') ?>
                    </td>

                    <td>
                        <a class="action"
                           href="edit.php?type=studio&id=<?= $studio['id'] ?>">
                           Edit
                        </a>

                        <a class="action"
                           href="delete.php?deleteStudio=<?= $studio['id'] ?>"
                           onclick="return confirm('Delete this studio and its games?')">
                           Delete
                        </a>
                    </td>

                </tr>

                <?php endforeach; ?>

                <?php if (count($studios) == 0): ?>
                <tr>
                    <td colspan="8" class="empty">
                        No studios have been added yet.
                    </td>
                </tr>
                <?php endif; ?>

            </table>

        </div>

    </div>


    <!-- GAME MANAGEMENT -->

    <div class="section" id="games">

        <div class="section-header">
            <h2>Game Management</h2>
            <span class="badge">Child Entity</span>
        </div>

        <h3>Add New Game</h3>

        <?php if (count($studios) > 0): ?>

        <form action="core/handleForms.php" method="POST">

            <div class="form-grid">

                <div>
                    <label>Game Name</label>
                    <input type="text" name="game_name" required>
                </div>

                <div>
                    <label>Genre</label>
                    <input type="text" name="genre" required>
                </div>

                <div>
                    <label>Release Year</label>
                    <input type="number" name="release_year"
                           min="1950" max="2100" required>
                </div>

                <div>
                    <label>Studio</label>

                    <select name="studio_id" required>

                        <option value="">Select Studio</option>

                        <?php foreach ($studios as $studio): ?>

                        <option value="<?= $studio['id'] ?>">
                            <?= htmlspecialchars($studio['studio_name']) ?>
                        </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>

            <button type="submit" name="addGame" class="form-button">
                + Add Game
            </button>

        </form>

        <?php else: ?>

        <p class="empty">Add a studio before adding a game.</p>

        <?php endif; ?>

        <br>

        <h3>Registered Games</h3>

        <div class="table-container">

            <table>

                <tr>
                    <th>ID</th>
                    <th>Game Name</th>
                    <th>Genre</th>
                    <th>Release Year</th>
                    <th>Studio</th>
                    <th>Added By</th>
                    <th>Date Added</th>
                    <th>Last Updated By</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>

                <?php foreach ($games as $game): ?>

                <tr>

                    <td><?= $game['id'] ?></td>

                    <td>
                        <strong>
                            <?= htmlspecialchars($game['game_name']) ?>
                        </strong>
                    </td>

                    <td><?= htmlspecialchars($game['genre']) ?></td>

                    <td><?= $game['release_year'] ?></td>

                    <td><?= htmlspecialchars($game['studio_name'] ?? 'Unknown') ?></td>

                    <td><?= htmlspecialchars($game['added_by_name'] ?? 'Not recorded') ?></td>

                    <td class="record-info">
                        <?= htmlspecialchars($game['created_at'] ?? 'Not recorded') ?>
                    </td>

                    <td><?= htmlspecialchars($game['updated_by_name'] ?? 'Not recorded') ?></td>

                    <td class="record-info">
                        <?= htmlspecialchars($game['updated_at'] ?? 'Not recorded') ?>
                    </td>

                    <td>
                        <a class="action"
                           href="edit.php?type=game&id=<?= $game['id'] ?>">
                           Edit
                        </a>

                        <a class="action"
                           href="delete.php?deleteGame=<?= $game['id'] ?>"
                           onclick="return confirm('Delete this game?')">
                           Delete
                        </a>
                    </td>

                </tr>

                <?php endforeach; ?>

                <?php if (count($games) == 0): ?>
                <tr>
                    <td colspan="10" class="empty">
                        No games have been added yet.
                    </td>
                </tr>
                <?php endif; ?>

            </table>

        </div>

    </div>

</div>

</body>
</html>