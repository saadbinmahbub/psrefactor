<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require('src/app.php');

final class CalculateCommissionsForTransactionsTest extends TestCase
{
    const CHECK_BIN = '45717360';
    const CHECK_AMOUNT = '100.00';
    const CHECK_CURRENCY = 'EUR';

    private function constructDummyData()
    {
        $format = '{"bin":"%s","amount":"%s","currency":"%s"}';
        return sprintf(
            $format,
            self::CHECK_BIN,
            self::CHECK_AMOUNT,
            self::CHECK_CURRENCY
        );
    }

    public function test_is_eu_true()
    {
        $this->assertTrue(isEu('CY'));
    }

    public function test_is_eu_false()
    {
        $this->assertFalse(isEu('BD'));
    }

    public function test_get_bin()
    {
        $row = $this->constructDummyData();
        $bin = getBin($row);
        $this->assertEquals(self::CHECK_BIN, $bin);
    }

    public function test_get_amount()
    {
        $row = $this->constructDummyData();
        $amount = getAmount($row);
        $this->assertEquals(self::CHECK_AMOUNT, $amount);
    }

    public function test_get_currency()
    {
        $row = $this->constructDummyData();
        $currency = getCurrency($row);
        $this->assertEquals(self::CHECK_CURRENCY, $currency);
    }

    public function test_get_amount_fixed()
    {
        $amountFixed = getAmountFixed('EUR', 0, 10.00);
        $this->assertEquals(10.00, $amountFixed);
    }

    public function test_search_bin()
    {
        $results = searchBin('45417360');
        $this->assertNotNull($results);
    }

    public function test_get_rates()
    {
        $results = getRate('USD');
        $this->assertNotNull($results);
    }

    public function test_round_up()
    {
        $this->assertEquals(round_up(0.461801, 2), 0.47);
    }

    public function test_round_up_last_number_zero()
    {
        $this->assertEquals(round_up(0.490801, 2), 0.50);
    }
}