<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Practice PHP if, elseif, and else with six interactive exercises. Enter values, explore the rules, and see the results.">
    <title><?= escape($pageTitle) ?> · BEWD</title>
    <link rel="stylesheet" href="<?= escape(app_url('assets/css/styles.css')) ?>">
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header">
    <div class="container">
        <p class="site-title">BEWD · PHP Exercises</p>
        <nav class="exercise-nav" aria-label="Exercises">
            <a href="<?= escape(app_url('index.php')) ?>"<?= $currentExercise === null ? ' aria-current="page"' : '' ?>>Home</a>
            <?php foreach ($catalogue as $id => $item): ?>
                <a href="<?= escape(app_url('exercises/' . $id . '.php')) ?>"<?= $currentExercise === $id ? ' aria-current="page"' : '' ?>><?= escape($item['nav']) ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</header>
