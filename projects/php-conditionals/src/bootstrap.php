<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/exercises.php';

$catalogue = exercise_catalogue();
$currentExercise = $currentExercise ?? null;
$pageTitle = $currentExercise === null ? 'PHP Conditional Exercises' : $catalogue[$currentExercise]['title'];
