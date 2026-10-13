<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../templates/header.php';
?>
<main id="main" class="container">
    <h1>PHP Conditional Exercises</h1>
    <p>Choose an exercise to practice PHP’s <code>if</code>, <code>elseif</code>, and <code>else</code> statements. Enter values to calculate a result.</p>
    <ol class="exercise-list">
        <?php foreach ($catalogue as $id => $exercise): ?>
            <li>
                <a href="<?= escape(app_url('exercises/' . $id . '.php')) ?>"><?= escape($exercise['title']) ?></a>
                <p><?= escape($exercise['description']) ?></p>
            </li>
        <?php endforeach; ?>
    </ol>
</main>
<?php require __DIR__ . '/../templates/footer.php'; ?>
