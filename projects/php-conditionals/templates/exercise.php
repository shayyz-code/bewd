<?php

declare(strict_types=1);

$exercise = $catalogue[$currentExercise];
$submitted = [];
$errors = [];
$result = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $validation = validate_fields($exercise['fields'], $_POST);
    $submitted = $validation['submitted'];
    $errors = $validation['errors'];

    if ($errors === []) {
        $calculation = call_user_func_array($exercise['calculator'], array_values($validation['values']));
        $result = result_view($currentExercise, $calculation);
    }
}

require __DIR__ . '/header.php';
?>
<main id="main" class="container exercise-main">
    <a class="back-link" href="<?= escape(app_url('index.php')) ?>"><span aria-hidden="true">←</span> All exercises</a>
    <div class="exercise-heading">
        <p class="eyebrow">EXERCISE <?= escape($exercise['number']) ?> <span aria-hidden="true">/</span> <?= escape($exercise['category']) ?></p>
        <h1><?= escape($exercise['title']) ?></h1>
        <p><?= escape($exercise['description']) ?></p>
    </div>
    <div class="exercise-layout">
        <section class="calculator-panel" aria-labelledby="calculator-title">
            <div class="panel-heading"><h2 id="calculator-title">Try it yourself</h2><span class="syntax-tag"><?= escape($exercise['syntax']) ?></span></div>
            <p class="panel-description">Enter your values. Let the condition do the rest.</p>
            <?php if ($errors !== []): ?>
                <p class="error-summary" role="alert">Check the highlighted fields and try again.</p>
            <?php endif; ?>
            <form method="post" action="<?= escape(app_url('exercises/' . $currentExercise . '.php')) ?>" novalidate>
                <?php foreach ($exercise['fields'] as $name => $field): ?>
                    <div class="form-field">
                        <label for="<?= escape($name) ?>"><?= escape($field['label']) ?></label>
                        <input type="text" inputmode="<?= $field['type'] === 'money' ? 'decimal' : (isset($field['min']) ? 'numeric' : 'text') ?>" name="<?= escape($name) ?>" id="<?= escape($name) ?>" value="<?= escape($submitted[$name] ?? '') ?>" placeholder="<?= escape($field['placeholder']) ?>" required aria-describedby="<?= escape($name) ?>-hint<?= isset($errors[$name]) ? ' ' . escape($name) . '-error' : '' ?>"<?= isset($errors[$name]) ? ' aria-invalid="true"' : '' ?>>
                        <p class="field-hint" id="<?= escape($name) ?>-hint"><?= escape($field['hint']) ?></p>
                        <?php if (isset($errors[$name])): ?><p class="field-error" id="<?= escape($name) ?>-error"><?= escape($errors[$name]) ?></p><?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <button class="button calculate-button" type="submit">Calculate result <span aria-hidden="true">→</span></button>
            </form>
            <div class="result-panel<?= $result === null ? ' result-empty' : '' ?>" aria-live="polite" aria-atomic="true">
                <?php if ($result !== null): ?>
                    <p class="eyebrow">YOUR RESULT</p>
                    <p class="result-caption"><?= escape($result['caption']) ?></p>
                    <h2 class="result-value"><?= escape($result['headline']) ?></h2>
                    <dl class="result-rows">
                        <?php foreach ($result['rows'] as $label => $value): ?><div><dt><?= escape($label) ?></dt><dd><?= escape($value) ?></dd></div><?php endforeach; ?>
                    </dl>
                <?php else: ?>
                    <span class="empty-symbol" aria-hidden="true">=</span><p>Your result will appear here.</p><span>Good answers start with an input.</span>
                <?php endif; ?>
            </div>
        </section>
        <aside class="rules-panel" aria-labelledby="rules-title">
            <span class="exercise-symbol" aria-hidden="true"><?= escape($exercise['symbol']) ?></span>
            <p class="eyebrow">BEHIND THE RESULT</p>
            <h2 id="rules-title">Follow the rule.</h2>
            <ul class="rule-list"><?php foreach ($exercise['rules'] as $rule): ?><li><?= escape($rule) ?></li><?php endforeach; ?></ul>
            <?php if (isset($exercise['note'])): ?><p class="rule-note"><?= escape($exercise['note']) ?></p><?php endif; ?>
            <div class="rule-tip"><span aria-hidden="true">↳</span><p>Try values on either side of a boundary to see when the result changes.</p></div>
        </aside>
    </div>
    <nav class="page-navigation" aria-label="Previous and next exercise">
        <?php $ids = array_keys($catalogue); $position = array_search($currentExercise, $ids, true); ?>
        <?php if ($position > 0): $previous = $ids[$position - 1]; ?><a href="<?= escape(app_url('exercises/' . $previous . '.php')) ?>"><span aria-hidden="true">←</span> <?= escape($catalogue[$previous]['title']) ?></a><?php else: ?><a href="<?= escape(app_url('index.php')) ?>"><span aria-hidden="true">←</span> Overview</a><?php endif; ?>
        <?php if ($position < count($ids) - 1): $next = $ids[$position + 1]; ?><a href="<?= escape(app_url('exercises/' . $next . '.php')) ?>"><?= escape($catalogue[$next]['title']) ?> <span aria-hidden="true">→</span></a><?php else: ?><a href="<?= escape(app_url('index.php')) ?>">Back to overview <span aria-hidden="true">→</span></a><?php endif; ?>
    </nav>
</main>
<?php require __DIR__ . '/footer.php'; ?>
