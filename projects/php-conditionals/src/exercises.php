<?php

declare(strict_types=1);

/** The catalogue supplies navigation and form definitions from one place. */
function exercise_catalogue(): array
{
    $integer = ['type' => 'integer'];

    return [
        'pass-or-fail' => [
            'number' => '01', 'title' => 'Pass or Fail', 'nav' => 'Pass or fail',
            'calculator' => 'calculate_pass_or_fail',
            'fields' => ['mark' => $integer + ['label' => 'Student mark', 'hint' => '0–100, whole number', 'placeholder' => 'e.g. 75', 'min' => 0, 'max' => 100]],
        ],
        'number-classification' => [
            'number' => '02', 'title' => 'Number Classification', 'nav' => 'Number type',
            'calculator' => 'calculate_number_classification',
            'fields' => ['number' => $integer + ['label' => 'Your number', 'hint' => 'Signed whole number', 'placeholder' => 'e.g. -12']],
        ],
        'discount-calculator' => [
            'number' => '03', 'title' => 'Discount Calculator', 'nav' => 'Discount',
            'calculator' => 'calculate_discount',
            'fields' => ['price' => ['type' => 'money', 'label' => 'Product price', 'hint' => 'MMK, up to 2 decimal places', 'placeholder' => 'e.g. 65000', 'min' => 0]],
        ],
        'largest-number' => [
            'number' => '04', 'title' => 'Largest Number', 'nav' => 'Largest number',
            'calculator' => 'calculate_largest_number',
            'fields' => [
                'num1' => $integer + ['label' => 'First number', 'hint' => 'Signed whole number', 'placeholder' => 'e.g. 24'],
                'num2' => $integer + ['label' => 'Second number', 'hint' => 'Signed whole number', 'placeholder' => 'e.g. 18'],
            ],
        ],
        'electricity-bill' => [
            'number' => '05', 'title' => 'Electricity Bill', 'nav' => 'Electricity bill',
            'calculator' => 'calculate_electricity_bill',
            'fields' => ['units' => $integer + ['label' => 'Electricity units', 'hint' => '0 or more, whole number', 'placeholder' => 'e.g. 125', 'min' => 0, 'max' => intdiv(PHP_INT_MAX, 150)]],
        ],
        'movie-ticket' => [
            'number' => '06', 'title' => 'Movie Ticket', 'nav' => 'Movie ticket',
            'calculator' => 'calculate_movie_ticket',
            'fields' => ['age' => $integer + ['label' => 'Person’s age', 'hint' => '0 or more, whole years', 'placeholder' => 'e.g. 21', 'min' => 0]],
        ],
    ];
}

function calculate_pass_or_fail(int $mark): array
{
    if ($mark >= 40) {
        $status = 'Pass';
    } else {
        $status = 'Fail';
    }

    return ['mark' => $mark, 'status' => $status];
}

function calculate_number_classification(int $number): array
{
    if ($number > 0) {
        $classification = 'Positive Number';
    } elseif ($number < 0) {
        $classification = 'Negative Number';
    } else {
        $classification = 'Zero';
    }

    return ['number' => $number, 'classification' => $classification];
}

/** Money enters as integer cents, so discount rounding does not depend on floats. */
function calculate_discount(int $price): array
{
    if ($price >= 50_000 * 100) {
        $discount = intdiv($price, 10) + ($price % 10 >= 5 ? 1 : 0);
    } else {
        $discount = 0;
    }

    return ['price' => $price, 'discount' => $discount, 'final_price' => $price - $discount];
}

function calculate_largest_number(int $num1, int $num2): array
{
    if ($num1 === $num2) {
        $largest = null;
        $message = 'Both numbers are equal';
    } else {
        if ($num1 > $num2) {
            $largest = $num1;
        } else {
            $largest = $num2;
        }
        $message = 'The larger number is ' . $largest;
    }

    return ['num1' => $num1, 'num2' => $num2, 'largest' => $largest, 'message' => $message];
}

function calculate_electricity_bill(int $units): array
{
    if ($units <= 50) {
        $rate = 35;
    } elseif ($units <= 100) {
        $rate = 50;
    } elseif ($units <= 200) {
        $rate = 75;
    } elseif ($units <= 300) {
        $rate = 100;
    } else {
        $rate = 150;
    }

    return ['units' => $units, 'rate' => $rate, 'total' => $units * $rate];
}

function calculate_movie_ticket(int $age): array
{
    if ($age < 5) {
        $ticketPrice = 0;
    } elseif ($age <= 12) {
        $ticketPrice = 1_000;
    } elseif ($age <= 17) {
        $ticketPrice = 2_000;
    } elseif ($age <= 59) {
        $ticketPrice = 4_000;
    } elseif ($age <= 69) {
        $ticketPrice = 2_000;
    } else {
        $ticketPrice = 1_000;
    }

    return ['age' => $age, 'ticket_price' => $ticketPrice];
}
