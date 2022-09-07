<?php declare(strict_types=1);

namespace Tests\App;

use PHPUnit\Framework\TestCase;


class StooqTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @coversNothing
     */
    public function testPzuSuccess()
    {

        $dir = __DIR__;
        //get data
        $fh = fopen(__DIR__ . '/stooq_data/d_pl_txt/data/daily/pl/wse stocks/pzu.txt', 'r');

        $prevMonth = 0;
        $monthData = [];
        $accumulatedSpendMoney = 0;
        $accumulatedShareCount = 0;

        while ($pzuDataRow = fgetcsv($fh, null, ',', '"')) {
            $currentDate = \DateTime::createFromFormat('Ymd', $pzuDataRow[2]);
            if (false === $currentDate) {
                continue;
            }
            $year = $currentDate->format('Y');
            $month = $currentDate->format('m');
            $day = $currentDate->format('d');

            if (2010 > $year) {
                continue;
            }

            if ($month === $prevMonth) {
                continue;
            }

            $prevMonth = $month;
            $sharePrice = $pzuDataRow[4];
            $currentDate = sprintf('%04d-%02d', $year, $month);

            $monthData[] = [
                'date' => $currentDate,
                'value' => $sharePrice,
            ];


            //calculate how many shares You can buy on stock price on first day of each month
            $shareCountToBuy = floor(100 / $sharePrice);

            //buy
            $transactionValue = $shareCountToBuy * $sharePrice;

            $accumulatedSpendMoney += $transactionValue;
            $accumulatedShareCount += $shareCountToBuy;
            //calculate how much stock is worth now
            $investmentCurrentValue = $accumulatedShareCount * $sharePrice;


            $profit = $investmentCurrentValue - $accumulatedSpendMoney;

//            print_r(sprintf(
//                "date:%s price:%0.2f sharesToBuy:%d transactionValue:%0.2f spent:%0.2f currentValue:%0.2f profit:%0.2f\n",
//                $currentDate,
//                $sharePrice,
//                $shareCountToBuy,
//                $transactionValue,
//                $accumulatedSpendMoney,
//                $investmentCurrentValue,
//                $profit
//            ));
        }


        $this->assertGreaterThan(1000, $profit);
    }

    /**
     * @coversNothing
     */
    public function testKghSuccess()
    {

        $dir = __DIR__;
        //get data
        $fh = fopen(__DIR__ . '/stooq_data/d_pl_txt/data/daily/pl/wse stocks/kgh.txt', 'r');

        $investedAmount = 100;
        $prevMonth = 0;
        $monthData = [];
        $accumulatedSpendMoney = 0;
        $accumulatedShareCount = 0;
        $accumulatedRest = 0;

        while ($pzuDataRow = fgetcsv($fh, null, ',', '"')) {
            $currentDate = \DateTime::createFromFormat('Ymd', $pzuDataRow[2]);
            if (false === $currentDate) {
                continue;
            }
            $year = $currentDate->format('Y');
            $month = $currentDate->format('m');
            $day = $currentDate->format('d');

            if (2010 > $year) {
                continue;
            }

            if ($month === $prevMonth) {
                continue;
            }

            $prevMonth = $month;
            $sharePrice = $pzuDataRow[4];
            $currentDate = sprintf('%04d-%02d', $year, $month);

            $monthData[] = [
                'date' => $currentDate,
                'value' => $sharePrice,
            ];


            //calculate how many shares You can buy on stock price on first day of each month
            $currentInvestedAmount = $investedAmount + $accumulatedRest;
            $shareCountToBuy = floor($currentInvestedAmount / $sharePrice);

            //buy
            $transactionValue = $shareCountToBuy * $sharePrice;

            $accumulatedRest = $currentInvestedAmount - $transactionValue;

            $accumulatedSpendMoney += $transactionValue;
            $accumulatedShareCount += $shareCountToBuy;
            //calculate how much stock is worth now
            $investmentCurrentValue = $accumulatedShareCount * $sharePrice;


            $profit = $investmentCurrentValue - $accumulatedSpendMoney;

//            print_r(sprintf(
//                "date:%s price:%0.2f sharesToBuy:%d transactionValue:%0.2f spent:%0.2f currentValue:%0.2f profit:%0.2f\n",
//                $currentDate,
//                $sharePrice,
//                $shareCountToBuy,
//                $transactionValue,
//                $accumulatedSpendMoney,
//                $investmentCurrentValue,
//                $profit
//            ));
        }

        $this->assertGreaterThan(-2000, $profit);
    }

    /**
     * @coversNothing
     * @dataProvider symbols
     */
    public function testMultipleSuccess($symbol)
    {

        $dir = __DIR__;
        //get data
        $fh = fopen(__DIR__ . '/stooq_data/d_pl_txt/data/daily/pl/wse stocks/' . $symbol . '.txt', 'r');

        $investedAmount = 100;
        $prevMonth = 0;
        $monthData = [];
        $accumulatedSpendMoney = 0;
        $accumulatedShareCount = 0;
        $accumulatedRest = 0;

        while ($pzuDataRow = fgetcsv($fh, null, ',', '"')) {
            $currentDate = \DateTime::createFromFormat('Ymd', $pzuDataRow[2]);
            if (false === $currentDate) {
                continue;
            }
            $year = $currentDate->format('Y');
            $month = $currentDate->format('m');
            $day = $currentDate->format('d');

            if (2010 > $year) {
                continue;
            }

            if ($month === $prevMonth) {
                continue;
            }

            $prevMonth = $month;
            $sharePrice = $pzuDataRow[4];
            $currentDate = sprintf('%04d-%02d', $year, $month);

            $monthData[] = [
                'date' => $currentDate,
                'value' => $sharePrice,
            ];


            //calculate how many shares You can buy on stock price on first day of each month
            $currentInvestedAmount = $investedAmount + $accumulatedRest;
            $shareCountToBuy = floor($currentInvestedAmount / $sharePrice);

            //buy
            $transactionValue = $shareCountToBuy * $sharePrice;

            $accumulatedRest = $currentInvestedAmount - $transactionValue;

            $accumulatedSpendMoney += $transactionValue;
            $accumulatedShareCount += $shareCountToBuy;
            //calculate how much stock is worth now
            $investmentCurrentValue = $accumulatedShareCount * $sharePrice;


            $profit = $investmentCurrentValue - $accumulatedSpendMoney;

//            echo (sprintf(
//                "date:%s price:%0.2f sharesToBuy:%d transactionValue:%0.2f spent:%0.2f currentValue:%0.2f profit:%0.2f\n",
//                $currentDate,
//                $sharePrice,
//                $shareCountToBuy,
//                $transactionValue,
//                $accumulatedSpendMoney,
//                $investmentCurrentValue,
//                $profit
//            ));
        }


        $this->assertGreaterThan(1000, $profit);
    }

    public function symbols()
    {
        return [[
            'pzu',
            'kgh',
        ]];
    }

}
