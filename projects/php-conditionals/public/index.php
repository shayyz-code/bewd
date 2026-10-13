<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../templates/header.php';
?>
<main id="main" class="container">
    <section class="hero" aria-labelledby="hero-title">
        <div class="hero-copy">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> THE CONDITIONALS COLLECTION</p>
            <h1 id="hero-title">Small decisions.<br><span>Clear results.</span></h1>
            <p class="hero-description">Six hands-on exercises to make PHP conditionals click. Try a value, follow the rule, and see what happens.</p>
            <a class="button" href="<?= escape(app_url('exercises/pass-or-fail.php')) ?>">Start with exercise 01 <span aria-hidden="true">→</span></a>
            <p class="hero-meta">6 exercises <span aria-hidden="true">·</span> Beginner friendly <span aria-hidden="true">·</span> Built with PHP</p>
        </div>
        <div class="code-window" aria-label="PHP example: marks of 40 or above pass, otherwise fail">
            <div class="window-bar"><span class="window-dots" aria-hidden="true">● ● ●</span><span>one condition, two paths</span><span class="file-type">PHP</span></div>
            <pre><code><span class="code-muted">// Every decision starts here.</span>
<span class="code-variable">$mark</span> = <span class="code-number">75</span>;

<span class="code-keyword">if</span> (<span class="code-variable">$mark</span> &gt;= <span class="code-number">40</span>) {
    <span class="code-keyword">echo</span> <span class="code-string">"Pass"</span>;
} <span class="code-keyword">else</span> {
    <span class="code-keyword">echo</span> <span class="code-string">"Fail"</span>;
}</code></pre>
            <div class="code-output"><span>OUTPUT</span><strong><span aria-hidden="true">✓</span> Pass</strong><span class="output-note">A little logic goes a long way.</span></div>
        </div>
    </section>
    <section class="collection" aria-labelledby="collection-title">
        <div class="section-heading"><div><p class="eyebrow">LEARN BY DOING</p><h2 id="collection-title">Pick a condition. Put it to work.</h2></div><span class="collection-count">01 — 06</span></div>
        <div class="exercise-grid">
            <?php foreach ($catalogue as $id => $exercise): ?>
                <a class="exercise-card" href="<?= escape(app_url('exercises/' . $id . '.php')) ?>">
                    <div class="card-top"><span class="exercise-symbol" aria-hidden="true"><?= escape($exercise['symbol']) ?></span><span class="card-number">EXERCISE <?= escape($exercise['number']) ?></span></div>
                    <h3><?= escape($exercise['title']) ?></h3>
                    <p><?= escape($exercise['description']) ?></p>
                    <div class="card-bottom"><span class="syntax-tag"><?= escape($exercise['syntax']) ?></span><span class="card-arrow" aria-hidden="true">↗</span></div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <aside class="learning-note"><span class="note-icon" aria-hidden="true">{ }</span><p><strong>One concept. Plenty of possibilities.</strong> Every exercise uses PHP’s <code>if</code>, <code>elseif</code>, or <code>else</code> to turn an input into a useful answer.</p></aside>
</main>
<?php require __DIR__ . '/../templates/footer.php'; ?>
