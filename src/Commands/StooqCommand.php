<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StooqCommand extends Command
{
    protected static $defaultName = 'stooq:calculate';

    protected function configure()
    {
        $this
            ->setDescription('Calculates profit/loose for polish stock data between 2010 na 2022 for long term small amount investments')
            ->setDefinition(
                new InputDefinition([
                    new InputOption(
                        'monthly-invested-amount',
                        'a',
                        InputOption::VALUE_OPTIONAL,
                        "Monthly Invested Amount [PLN]",
                        100
                    ),
                    new InputOption(
                        'symbols-count',
                        'c',
                        InputOption::VALUE_OPTIONAL,
                        "how many random symbols are taken for the simulation",
                        5
                    ),
                    new InputOption(
                        'symbols',
                        's',
                        InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                        "multiple symbols"
                    ),
                    new InputOption(
                        'from',
                        'f',
                        InputOption::VALUE_OPTIONAL,
                        "Year from to start simulation",
                        2010
                    ),
                    new InputOption(
                        'to',
                        't',
                        InputOption::VALUE_OPTIONAL,
                        "Year to to start simulation",
                        2022
                    ),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dataFolder = __DIR__ . '/../../data/stooq_data/d_pl_txt/data/daily/pl/funds stable growth/';
        $dataFolder = __DIR__ . '/../../data/stooq_data/d_pl_txt/data/daily/pl/funds balanced/';
        $dataFolder = __DIR__ . '/../../data/stooq_data/d_pl_txt/data/daily/pl/wse stocks/';

        $filesList = array_diff(scandir($dataFolder), array('.', '..'));

        $investedAmount = $input->getOption('monthly-invested-amount');
        $symbolsCount = $input->getOption('symbols-count');
        $symbolsList = $input->getOption('symbols');
        $yearFrom = $input->getOption('from');
        $yearTo = $input->getOption('to');

        shuffle($filesList);
        $shortShuffledFileList = array_slice($filesList, 0, $symbolsCount);

        $packProfit = 0;
        $packAccumulatedSpendMoney = 0;

        if (!empty($symbolsList)){
            $shortShuffledFileList = array_map(function($item){ return $item. '.txt';},$symbolsList);
        }

        foreach ($shortShuffledFileList as $symbolFile) {
            //get data
            $fh = fopen($dataFolder . $symbolFile, 'r');

            $prevMonth = 0;
            $monthData = [];
            $accumulatedSpendMoney = 0;
            $accumulatedShareCount = 0;
            $accumulatedRest = 0;
            $profit = 0;

            while ($pzuDataRow = fgetcsv($fh, null, ',', '"')) {
                $currentDate = \DateTime::createFromFormat('Ymd', $pzuDataRow[2]);
                if (false === $currentDate) {
                    continue;
                }
                $year = $currentDate->format('Y');
                $month = $currentDate->format('m');
                $day = $currentDate->format('d');

                if (2010 > $year || $yearFrom > $year) {
                    continue;
                }

                if (2022 < $year || $yearTo < $year) {
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
            $packAccumulatedSpendMoney += $accumulatedSpendMoney;
        }

        $output->writeln(sprintf(
            "packProfit:%0.2f, packMoneyInvested:%0.2f percentageProfit:%0.2f",
            $packProfit,
            $packAccumulatedSpendMoney,
            $packProfit / $packAccumulatedSpendMoney * 100
        ));

        return Command::SUCCESS;
    }
}