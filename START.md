# Start Here

## Brief Intro

This is the submission for THE HOUSE ALWAYS WINS technical assignment. If you have issues running this, I am happy to do a screenshare.

I realise there is a lot of cruft and overhead here, but I am trying to bolster my laravel portfolio and also learn livewire.

## Running Up

### Running this example up using artisan

If you have PHP installed locally, you can run this demo up in artisan.

1. run `composer install && npm install` to build project
2. run `php artisan migrate` to add an extra col to database
3. run `php artisan serve` to run local server
4. view http://localhost:8000 in browser

### Traditional Run Up

Build dependencies using `composer install` and `npm install` then point public directory to Public

## Running Tests

1. run `php artisan test` to run tests, including boiler plate stuff to test auth and registration etc
2. run `php artisan test --filter SlotMachineTest` to test my component specifically (saves time)

> Please note that due to the nature of testing probablity the tests "probability over [x] credits" tests may need to be re-run 2-3 times to get a positive result.

## Files of note

To save you some time, the files that I have specifically written and are not generic or boiler plate code are:

1. app/Livewire/SlotMachine.php
2. database/migrations/2024_03_20_153541_add_credits_to_users_table.php
3. tests/Unit/SlotMachineTest.php
4. resources/livewire/slot-machine.blade.php