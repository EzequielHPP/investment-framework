<?php
return [
    Events\PreInvestmentCreation::class => [
        \Listeners\InvestorFundCheck::class,
        \Listeners\CheckTrancheAvailability::class,
        \Listeners\UpdateTrancheFunds::class
    ],
    Events\PostInvestmentCreation::class => [
        \Listeners\UpdateInvestorsWallet::class
    ],
    \Events\FirstDayOfTheMonth::class => [
        \Listeners\RunInterestCalculation::class
    ],
    \Events\InvestorCreated::class => [
        \Listeners\CreateInvestorWallet::class
    ]
];
