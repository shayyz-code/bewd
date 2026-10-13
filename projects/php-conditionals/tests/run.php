<?php

declare(strict_types=1);

require __DIR__ . '/../src/exercises.php';
require __DIR__ . '/../src/helpers.php';

$passed = 0;
$failed = 0;

function check(mixed $actual, mixed $expected, string $scenario): void
{
    global $passed, $failed;

    if ($actual === $expected) {
        $passed++;
    } else {
        $failed++;
        fwrite(STDERR, "FAIL: {$scenario}\nExpected: " . var_export($expected, true) . "\nActual: " . var_export($actual, true) . "\n");
    }
}

foreach ([0 => 'Fail', 39 => 'Fail', 40 => 'Pass', 100 => 'Pass'] as $mark => $expected) {
    check(calculate_pass_or_fail($mark)['status'], $expected, "Pass threshold: {$mark}");
}

foreach ([-12 => 'Negative Number', 0 => 'Zero', 12 => 'Positive Number'] as $number => $expected) {
    check(calculate_number_classification($number)['classification'], $expected, "Number classification: {$number}");
}

// Prices are passed in cents; expected results are rounded to the nearest cent.
foreach ([[0, 0, 0], [4_999_999, 0, 4_999_999], [5_000_000, 500_000, 4_500_000], [5_000_001, 500_000, 4_500_001], [5_000_005, 500_001, 4_500_004], [6_500_000, 650_000, 5_850_000]] as [$price, $discount, $final]) {
    $result = calculate_discount($price);
    check($result['price'], $price, 'Original price retained');
    check($result['discount'], $discount, "Discount: {$price} cents");
    check($result['final_price'], $final, "Final price: {$price} cents");
}

foreach ([[24, 18, 24], [18, 24, 24], [-12, -2, -2], [-2, -12, -2], [0, 0, null], [7, 7, null]] as [$num1, $num2, $largest]) {
    $result = calculate_largest_number($num1, $num2);
    check($result['largest'], $largest, "Largest of {$num1}, {$num2}");
    if ($largest === null) {
        check($result['message'], 'Both numbers are equal', 'Equality message');
    }
}

foreach ([[0, 35, 0], [50, 35, 1750], [51, 50, 2550], [100, 50, 5000], [101, 75, 7575], [200, 75, 15000], [201, 100, 20100], [300, 100, 30000], [301, 150, 45150]] as [$units, $rate, $total]) {
    $result = calculate_electricity_bill($units);
    check($result['rate'], $rate, "Electricity rate: {$units}");
    check($result['total'], $total, "All-unit electricity charge: {$units}");
}

foreach ([0 => 0, 4 => 0, 5 => 1000, 12 => 1000, 13 => 2000, 17 => 2000, 18 => 4000, 59 => 4000, 60 => 2000, 69 => 2000, 70 => 1000, 99 => 1000] as $age => $price) {
    check(calculate_movie_ticket($age)['ticket_price'], $price, "Ticket age boundary: {$age}");
}

foreach (['0' => 0, '-0' => 0, '0007' => 7, '+12' => 12, '-0012' => -12, (string) PHP_INT_MAX => PHP_INT_MAX, (string) PHP_INT_MIN => PHP_INT_MIN] as $input => $expected) {
    check(parse_integer((string) $input), $expected, 'Integer parsing: ' . $input);
}
foreach (['', '1.0', '1e3', '1,000', '--3', '12abc', '9' . PHP_INT_MAX] as $input) {
    check(parse_integer($input), null, 'Invalid integer: ' . $input);
}
foreach (['0' => 0, '0.01' => 1, '10.5' => 1050, '50000.05' => 5000005, '00012.00' => 1200] as $input => $expected) {
    check(parse_money((string) $input), $expected, 'Money parsing: ' . $input);
}
foreach (['', '-1', '1.001', 'NaN', 'INF', '1e6', '.50', '10.', '50,000', '9' . PHP_INT_MAX] as $input) {
    check(parse_money($input), null, 'Invalid money: ' . $input);
}

$catalogue = exercise_catalogue();
foreach ([['pass-or-fail', 'mark', '-1'], ['pass-or-fail', 'mark', '101'], ['movie-ticket', 'age', '-1'], ['electricity-bill', 'units', '-1'], ['electricity-bill', 'units', (string) PHP_INT_MAX], ['discount-calculator', 'price', '-0.01'], ['number-classification', 'number', '<script>alert(1)</script>']] as [$id, $field, $input]) {
    $validation = validate_fields($catalogue[$id]['fields'], [$field => $input]);
    check(isset($validation['errors'][$field]), true, "Rejected invalid {$field}: {$input}");
    check($validation['submitted'][$field], $input, 'Invalid input retained for correction');
}
foreach ($catalogue as $exercise) {
    foreach (array_keys($exercise['fields']) as $field) {
        check(isset(validate_fields($exercise['fields'], [])['errors'][$field]), true, 'Blank required field: ' . $field);
        check(isset(validate_fields($exercise['fields'], [$field => ['20']])['errors'][$field]), true, 'Reject array input: ' . $field);
    }
}
$valid = validate_fields($catalogue['largest-number']['fields'], ['num1' => ' -12 ', 'num2' => '+0024']);
check($valid['errors'], [], 'Accept signed, trimmed integers');
check($valid['values'], ['num1' => -12, 'num2' => 24], 'Normalized signed inputs');
check($valid['submitted']['num1'], '-12', 'Trimmed submitted value');
check(parse_money('92233720368547758.07'), PHP_INT_MAX, 'Exact maximum cents on 64-bit PHP');
check(parse_money('92233720368547758.08'), null, 'Reject overflowing cents');
check(format_mmk(PHP_INT_MAX, true), '92,233,720,368,547,758.07 MMK', 'Exact formatting of maximum cents');
check(calculate_discount(PHP_INT_MAX)['discount'], 922337203685477581, 'Exact large discount');
check(format_mmk(2550), '2,550.00 MMK', 'Currency units');
check(format_mmk(5000005, true), '50,000.05 MMK', 'Currency cents');
check(result_view('movie-ticket', calculate_movie_ticket(4))['headline'], 'Free', 'Free admission display');
check(escape('<script>"&'), '&lt;script&gt;&quot;&amp;', 'HTML escaping');

$_SERVER['SCRIPT_NAME'] = '/index.php';
check(app_url('exercises/movie-ticket.php'), '/exercises/movie-ticket.php', 'Root URL');
$_SERVER['SCRIPT_NAME'] = '/learning/exercises/movie-ticket.php';
check(app_url('index.php'), '/learning/index.php', 'Subdirectory URL');

echo "{$passed} checks passed; {$failed} failed.\n";
exit($failed === 0 ? 0 : 1);
