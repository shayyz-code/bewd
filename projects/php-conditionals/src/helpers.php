<?php

declare(strict_types=1);

function escape(string|int $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function app_url(string $path = ''): string
{
    $directory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    if (str_ends_with($directory, '/exercises')) {
        $directory = dirname($directory);
    }

    return rtrim($directory, '/.') . '/' . ltrim($path, '/');
}

/** Normalize leading zeros while rejecting integer overflow. */
function parse_integer(string $input): ?int
{
    if (!preg_match('/^[+-]?[0-9]+$/D', $input)) {
        return null;
    }

    $digits = ltrim(ltrim($input, '+-'), '0');
    $canonical = ($input[0] === '-' && $digits !== '' ? '-' : '') . ($digits === '' ? '0' : $digits);
    $value = filter_var($canonical, FILTER_VALIDATE_INT);

    return $value === false ? null : $value;
}

/** Parse decimal MMK directly into cents without floating-point precision loss. */
function parse_money(string $input): ?int
{
    if (!preg_match('/^([0-9]+)(?:\.([0-9]{1,2}))?$/D', $input, $matches)) {
        return null;
    }

    return parse_integer($matches[1] . str_pad($matches[2] ?? '', 2, '0'));
}

function validate_fields(array $fields, array $input): array
{
    $values = [];
    $errors = [];
    $submitted = [];

    foreach ($fields as $name => $field) {
        $raw = $input[$name] ?? '';
        $submitted[$name] = is_string($raw) ? trim($raw) : '';
        $value = $submitted[$name];

        if (!is_string($raw)) {
            $errors[$name] = 'Enter a single numeric value.';
            continue;
        }
        if ($value === '') {
            $errors[$name] = 'Please enter ' . strtolower($field['label']) . '.';
            continue;
        }

        $parsed = $field['type'] === 'money' ? parse_money($value) : parse_integer($value);
        if ($parsed === null) {
            $errors[$name] = $field['type'] === 'money'
                ? 'Enter a nonnegative price with up to two decimal places within the supported range.'
                : 'Enter a whole number within the supported integer range.';
        } elseif (isset($field['min']) && $parsed < $field['min']) {
            $errors[$name] = 'Enter a value of ' . $field['min'] . ' or above.';
        } elseif (isset($field['max']) && $parsed > $field['max']) {
            $errors[$name] = 'Enter a value of ' . $field['max'] . ' or below.';
        } else {
            $values[$name] = $parsed;
        }
    }

    return ['values' => $values, 'errors' => $errors, 'submitted' => $submitted];
}

/** Format integers exactly, including large supported monetary values. */
function format_integer(int $value): string
{
    return preg_replace('/\B(?=(\d{3})+(?!\d))/', ',', (string) $value);
}

function format_mmk(int $amount, bool $inCents = false): string
{
    if ($inCents) {
        return format_integer(intdiv($amount, 100)) . '.' . str_pad((string) ($amount % 100), 2, '0', STR_PAD_LEFT) . ' MMK';
    }

    return format_integer($amount) . '.00 MMK';
}

/** Map calculation outputs into display rows without embedding HTML in the logic. */
function result_view(string $id, array $result): array
{
    return match ($id) {
        'pass-or-fail' => ['headline' => $result['status'], 'caption' => 'Examination result', 'rows' => ['Student mark' => $result['mark'] . ' / 100', 'Pass threshold' => '40 / 100']],
        'number-classification' => ['headline' => $result['classification'], 'caption' => 'Number classification', 'rows' => ['Your number' => format_integer($result['number'])]],
        'discount-calculator' => ['headline' => format_mmk($result['final_price'], true), 'caption' => 'Final price', 'rows' => ['Original price' => format_mmk($result['price'], true), 'Discount amount' => format_mmk($result['discount'], true)]],
        'largest-number' => ['headline' => $result['largest'] === null ? $result['message'] : format_integer($result['largest']), 'caption' => $result['largest'] === null ? 'A perfect match' : 'The larger number', 'rows' => ['First number' => format_integer($result['num1']), 'Second number' => format_integer($result['num2'])]],
        'electricity-bill' => ['headline' => format_mmk($result['total']), 'caption' => 'Total electricity bill', 'rows' => ['Units consumed' => format_integer($result['units']), 'Rate per unit' => format_mmk($result['rate'])]],
        'movie-ticket' => ['headline' => $result['ticket_price'] === 0 ? 'Free' : format_mmk($result['ticket_price']), 'caption' => 'Movie ticket price', 'rows' => ['Person’s age' => format_integer($result['age']) . ' years', 'Ticket price' => format_mmk($result['ticket_price'])]],
    };
}
