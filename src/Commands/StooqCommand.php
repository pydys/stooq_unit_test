<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StooqCommand extends Command
{
    protected static $defaultName = 'stooq:calculate';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesList = array_diff(scandir(__DIR__ . '/../../data/stooq_data/d_pl_txt/data/daily/pl/wse stocks/'), array('.', '..'));


        shuffle($filesList);
        $shortShuffledFileList = array_slice($filesList, 0, 5);


        $packProfit = 0;
        foreach ($shortShuffledFileList as $symbolFile) {
            //get data
            $fh = fopen(__DIR__ . '/../../data/stooq_data/d_pl_txt/data/daily/pl/wse stocks/' . $symbolFile, 'r');

            $investedAmount = 200;
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

//                $output->writeln(sprintf(
//                    "date:%s\tprice:%0.2f\tsharesToBuy:%d\ttransactionValue:%0.2f\tspent:%0.2f\tcurrentValue:%0.2f\tprofit:%0.2f",
//                    $currentDate,
//                    $sharePrice,
//                    $shareCountToBuy,
//                    $transactionValue,
//                    $accumulatedSpendMoney,
//                    $investmentCurrentValue,
//                    $profit
//                ));
            }

            $output->writeln(sprintf(
                "symbol:%s profit:%0.2f",
                $symbolFile,
                $profit
            ));

            $packProfit += $profit;
        }

        $output->writeln(sprintf(
            "packProfit:%0.2f",
            $packProfit
        ));

        return Command::SUCCESS;
    }
}