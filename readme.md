## About Project
This is a refactoring project.

## File Description
`src/app.php` This is the new refactored file.<br/>
`tests/CalculateCommissionsForTransactionsTest` Tests for app.php.<br/>
`src/app_old.php` Old php code which was refactored.<br/>
`input.txt` Sample input for app.php. 

## Instructions to run code and tests
```
$ composer install
$ php src/app.php input.txt 
$ ./vendor/phpunit/phpunit/phpunit tests/CalculateCommissionsForTransactionsTest.php 
```

## Sample Output
```
1.00
0.47
1.70
2.40
45.69
```