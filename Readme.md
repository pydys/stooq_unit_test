

description
-------------------

This simple commandline app simulates profit/loose for **polish stock** data between 2010 na 2022 for long term small amount investments



docker build
--------------------

`make build`


run  docker container commandline `make app-bash`

then simply `composer install`

Run the app by executing inside the container:
`bin/console s:c --help`

usage
------------

 - list parameters
`bin/console s:c --help`

```
Description:
  Calculates profit/loose for polish stock data between 2010 na 2022 for long term small amount investments

Usage:
  stooq:calculate [options]

Options:
  -a, --monthly-invested-amount[=MONTHLY-INVESTED-AMOUNT]  Monthly Invested Amount [PLN] [default: 100]
  -c, --symbols-count[=SYMBOLS-COUNT]                      how many random symbols are taken for the simulation [default: 5]
  -s, --symbols[=SYMBOLS]                                  multiple symbols (multiple values allowed)
  -f, --from[=FROM]                                        Year from to start simulation [default: 2010]
  -t, --to[=TO]                                            Year to to start simulation [default: 2022]
  -h, --help                                               Display help for the given command. When no command is given display help for the list command
  -q, --quiet                                              Do not output any message
  -V, --version                                            Display this application version
      --ansi|--no-ansi                                     Force (or disable --no-ansi) ANSI output
  -n, --no-interaction                                     Do not ask any interactive question
  -v|vv|vvv, --verbose                                     Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

```

- random symbols, amount 50PLN / month
`bin/console s:c -a50`

```
symbol:mci.txt profit:6955.14
symbol:vot.txt profit:45474.92
symbol:pri.txt profit:-4148.59
symbol:mrc.txt profit:10553.09
symbol:bbt.txt profit:-341.21
packProfit:58493.35, packMoneyInvested:29764.10 percentageProfit:196.52

```

- random symbols, amount 200PLN / month, 6 symbols chosen
`bin/console s:c -a200 -c 6`

```
symbol:uni.txt profit:7113.98
symbol:pmp.txt profit:44724.74
symbol:kgl.txt profit:-4625.51
symbol:hrs.txt profit:-7625.15
symbol:nwg.txt profit:494.71
symbol:cpr.txt profit:-7181.31
packProfit:32901.46, packMoneyInvested:159989.72 percentageProfit:20.56
```


- specified symbols, amount 50PLN / month, simulation till 2021
`bin/console s:c -a50 -spzu -skgh -satt -spkp -s ifi -s san -s ase -s abs -s trn -s pcr -satg -s 1at -s wtn -s bhw -sulg -spkn -sacp -sase -sopn -sska -t2021`

```
symbol:pzu.txt profit:3002.36
symbol:kgh.txt profit:3569.33
symbol:att.txt profit:-166.11
symbol:pkp.txt profit:-2463.98
symbol:ifi.txt profit:121197.11
symbol:san.txt profit:-1899.48
symbol:ase.txt profit:33496.65
symbol:abs.txt profit:20874.24
symbol:trn.txt profit:-126.47
symbol:pcr.txt profit:5415.64
symbol:atg.txt profit:8317.05
symbol:1at.txt profit:3383.91
symbol:wtn.txt profit:692.01
symbol:bhw.txt profit:682.34
symbol:ulg.txt profit:565.98
symbol:pkn.txt profit:4274.57
symbol:acp.txt profit:9810.42
symbol:ase.txt profit:33496.65
symbol:opn.txt profit:26375.38
symbol:ska.txt profit:8415.66
packProfit:278913.28, packMoneyInvested:126585.56 percentageProfit:220.34

```




