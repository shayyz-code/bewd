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
    <div class="header-top container">
        <a class="brand" href="<?= escape(app_url('index.php')) ?>"<?= $currentExercise === null ? ' aria-current="page"' : '' ?>>
            <span class="brand-mark" aria-hidden="true">b.</span>
            <span>BEWD <span class="brand-divider">/</span> <span class="brand-label">Learning lab</span></span>
        </a>
        <span class="header-tag"><span class="status-dot" aria-hidden="true"></span> PHP fundamentals</span>
    </div>
    <nav class="exercise-nav container" aria-label="Exercises">
        <a class="home-link" href="<?= escape(app_url('index.php')) ?>"<?= $currentExercise === null ? ' aria-current="page"' : '' ?>>Overview</a>
        <?php foreach ($catalogue as $id => $item): ?>
            <a href="<?= escape(app_url('exercises/' . $id . '.php')) ?>"<?= $currentExercise === $id ? ' aria-current="page"' : '' ?>><span class="nav-number"><?= escape($item['number']) ?></span> <?= escape($item['nav']) ?></a>
        <?php endforeach; ?>
    </nav>
</header>
