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

$ids = array_keys($catalogue);
$position = array_search($currentExercise, $ids, true);

require __DIR__ . '/header.php';
?>
<main id="main" class="container">
    <a href="<?= escape(app_url('index.php')) ?>">All exercises</a>
    <h1>Exercise <?= escape((int) $exercise['number']) ?>: <?= escape($exercise['title']) ?></h1>
    <p><?= escape($exercise['description']) ?></p>

    <section aria-labelledby="rules-title">
        <h2 id="rules-title">Rules</h2>
        <p>PHP statement: <code><?= escape($exercise['syntax']) ?></code></p>
        <ul>
            <?php foreach ($exercise['rules'] as $rule): ?>
                <li><?= escape($rule) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php if (isset($exercise['note'])): ?>
            <p><?= escape($exercise['note']) ?></p>
        <?php endif; ?>
    </section>

    <section aria-labelledby="calculator-title">
        <h2 id="calculator-title">Calculate</h2>
        <?php if ($errors !== []): ?>
            <p class="error-summary" role="alert">Check the highlighted fields and try again.</p>
        <?php endif; ?>
        <form method="post" action="<?= escape(app_url('exercises/' . $currentExercise . '.php')) ?>" novalidate>
            <?php foreach ($exercise['fields'] as $name => $field): ?>
                <div class="form-field">
                    <label for="<?= escape($name) ?>"><?= escape($field['label']) ?></label>
                    <input type="text" inputmode="<?= $field['type'] === 'money' ? 'decimal' : (isset($field['min']) ? 'numeric' : 'text') ?>" name="<?= escape($name) ?>" id="<?= escape($name) ?>" value="<?= escape($submitted[$name] ?? '') ?>" placeholder="<?= escape($field['placeholder']) ?>" required aria-describedby="<?= escape($name) ?>-hint<?= isset($errors[$name]) ? ' ' . escape($name) . '-error' : '' ?>"<?= isset($errors[$name]) ? ' aria-invalid="true"' : '' ?>>
                    <p class="field-hint" id="<?= escape($name) ?>-hint"><?= escape($field['hint']) ?></p>
                    <?php if (isset($errors[$name])): ?>
                        <p class="field-error" id="<?= escape($name) ?>-error"><?= escape($errors[$name]) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit">Calculate result</button>
        </form>
    </section>

    <section class="result-panel" aria-labelledby="result-title" aria-live="polite" aria-atomic="true">
        <h2 id="result-title">Result</h2>
        <?php if ($result !== null): ?>
            <p><?= escape($result['caption']) ?></p>
            <p class="result-value"><?= escape($result['headline']) ?></p>
            <dl class="result-rows">
                <?php foreach ($result['rows'] as $label => $value): ?>
                    <dt><?= escape($label) ?></dt>
                    <dd><?= escape($value) ?></dd>
                <?php endforeach; ?>
            </dl>
        <?php else: ?>
            <p>Your result will appear here after submitting valid values.</p>
        <?php endif; ?>
    </section>

    <nav class="page-navigation" aria-label="Previous and next exercise">
        <?php if ($position > 0): $previous = $ids[$position - 1]; ?>
            <a href="<?= escape(app_url('exercises/' . $previous . '.php')) ?>">Previous: <?= escape($catalogue[$previous]['title']) ?></a>
        <?php else: ?>
            <a href="<?= escape(app_url('index.php')) ?>">Home</a>
        <?php endif; ?>
        <?php if ($position < count($ids) - 1): $next = $ids[$position + 1]; ?>
            <a href="<?= escape(app_url('exercises/' . $next . '.php')) ?>">Next: <?= escape($catalogue[$next]['title']) ?></a>
        <?php else: ?>
            <a href="<?= escape(app_url('index.php')) ?>">Back to home</a>
        <?php endif; ?>
    </nav>
</main>
<?php require __DIR__ . '/footer.php'; ?>
