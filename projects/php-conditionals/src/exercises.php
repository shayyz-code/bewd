<?php

declare(strict_types=1);

/** The catalogue supplies navigation, forms, and rule explanations from one place. */
function exercise_catalogue(): array
{
    $integer = ['type' => 'integer'];

    return [
        'pass-or-fail' => [
            'number' => '01', 'title' => 'Pass or Fail', 'nav' => 'Pass or fail',
            'description' => 'See how one condition decides whether a student passes the exam.',
            'syntax' => 'if / else', 'calculator' => 'calculate_pass_or_fail',
            'rules' => ['A mark of 40 or above is a pass.', 'A mark below 40 is a fail.'],
            'fields' => ['mark' => $integer + ['label' => 'Student mark', 'hint' => 'Enter a whole number from 0 to 100.', 'placeholder' => 'e.g. 75', 'min' => 0, 'max' => 100]],
        ],
        'number-classification' => [
            'number' => '02', 'title' => 'Number Classification', 'nav' => 'Number type',
            'description' => 'Find out whether an integer is positive, negative, or zero.',
            'syntax' => 'if / elseif / else', 'calculator' => 'calculate_number_classification',
            'rules' => ['Greater than 0: Positive Number.', 'Less than 0: Negative Number.', 'Equal to 0: Zero.'],
            'fields' => ['number' => $integer + ['label' => 'Your number', 'hint' => 'Enter any signed whole number.', 'placeholder' => 'e.g. −12']],
        ],
        'discount-calculator' => [
            'number' => '03', 'title' => 'Discount Calculator', 'nav' => 'Discount',
            'description' => 'Calculate a product’s final price with a threshold-based discount.',
            'syntax' => 'if / else', 'calculator' => 'calculate_discount',
            'rules' => ['50,000 MMK or above: a 10% discount.', 'Below 50,000 MMK: no discount.'],
            'fields' => ['price' => ['type' => 'money', 'label' => 'Product price', 'hint' => 'MMK · Up to two decimal places.', 'placeholder' => 'e.g. 65000', 'min' => 0]],
        ],
        'largest-number' => [
            'number' => '04', 'title' => 'Largest Number', 'nav' => 'Largest number',
            'description' => 'Compare two integers and find the larger one, or an exact match.',
            'syntax' => 'if / else', 'calculator' => 'calculate_largest_number',
            'rules' => ['Compare the first number with the second.', 'If they match, display “Both numbers are equal”.'],
            'fields' => [
                'num1' => $integer + ['label' => 'First number', 'hint' => 'A signed whole number.', 'placeholder' => 'e.g. 24'],
                'num2' => $integer + ['label' => 'Second number', 'hint' => 'A signed whole number.', 'placeholder' => 'e.g. 18'],
            ],
        ],
        'electricity-bill' => [
            'number' => '05', 'title' => 'Electricity Bill', 'nav' => 'Electricity bill',
            'description' => 'Select a rate from your usage and calculate the total bill in MMK.',
            'syntax' => 'if / elseif / else', 'calculator' => 'calculate_electricity_bill',
            'rules' => ['0–50 units: 35 MMK per unit.', '51–100 units: 50 MMK per unit.', '101–200 units: 75 MMK per unit.', '201–300 units: 100 MMK per unit.', 'Above 300 units: 150 MMK per unit.'],
            'note' => 'The selected rate applies to every unit consumed. For example, 51 units × 50 MMK = 2,550 MMK. These are the assignment’s example rates.',
            'fields' => ['units' => $integer + ['label' => 'Electricity units', 'hint' => 'Enter a nonnegative whole number.', 'placeholder' => 'e.g. 125', 'min' => 0, 'max' => intdiv(PHP_INT_MAX, 150)]],
        ],
        'movie-ticket' => [
            'number' => '06', 'title' => 'Movie Ticket', 'nav' => 'Movie ticket',
            'description' => 'Find the movie ticket price for any age, from children to seniors.',
            'syntax' => 'if / elseif / else', 'calculator' => 'calculate_movie_ticket',
            'rules' => ['Under 5: free admission.', 'Ages 5–12: 1,000 MMK.', 'Ages 13–17: 2,000 MMK.', 'Ages 18–59: 4,000 MMK.', 'Ages 60–69: 2,000 MMK.', 'Ages 70 and above: 1,000 MMK.'],
            'fields' => ['age' => $integer + ['label' => 'Person’s age', 'hint' => 'Age in complete years, starting at 0.', 'placeholder' => 'e.g. 21', 'min' => 0]],
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
