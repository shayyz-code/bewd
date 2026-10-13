# BEWD

A collection of backend web development exercises, starting with a small PHP learning lab.

## Projects

| Project | What it covers |
| --- | --- |
| [PHP Conditional Exercises](projects/php-conditionals/README.md) | Six interactive exercises using `if`, `elseif`, and `else`, with server-side validation and a responsive interface. |

## Run locally

Requires PHP 8.1 or newer on a 64-bit platform. No Composer packages or build step are needed. Tested with PHP 8.4.12.

```sh
git clone https://github.com/shayyz-code/bewd.git
cd bewd
php -S localhost:8000 -t projects/php-conditionals/public
```

Open [localhost:8000](http://localhost:8000). Press `Ctrl+C` to stop the server.

## Preview

The home page lists all six exercises with basic styling.

![PHP exercise home page](projects/php-conditionals/docs/screenshots/home-desktop.png)

Each page contains a form and displays its result after a valid submission.

![Electricity calculation result](projects/php-conditionals/docs/screenshots/electricity-result-desktop.png)

The same forms work on smaller screens.

<img src="projects/php-conditionals/docs/screenshots/discount-result-mobile.png" alt="Discount calculator on a mobile screen" width="300">

## Check the exercises

```sh
php projects/php-conditionals/tests/run.php
```

See the [subproject README](projects/php-conditionals/README.md) for exercise rules, source organization, and verification details.
