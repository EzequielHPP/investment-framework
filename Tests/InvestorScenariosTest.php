<?php

use PHPUnit\Framework\TestCase;

class InvestorScenariosTest extends TestCase
{

    /**
     * This tests Scenario 1 and 2, as these are interconnected.
     */
    public function testInvestor1and2CanInvest(): void
    {
        $investmentService = (new \Services\InvestmentService());

        // Get Investor 1
        $investor = (new \Models\Investor())->find(1);
        $trancheA = (new \Models\Tranche())->where('title', 'A')->first();


        $amount = 1000;
        $date = '2020-10-03';

        // Start Testing
        //Scenario 1
        $investment = $investmentService->invest($investor->id, $trancheA->id, $amount, $date);
        $this->assertTrue($investment, 'Investor 1 Cannot invest on Tranche A');

        $this->assertEquals(0, $investor->wallet()->funds);

        //Scenario 2
        $date = '2020-10-04';
        $amount = 1;
        $investor2 = (new \Models\Investor())->find(2);
        $this->expectException(\system\exceptions\TrancheInsufficientFundsAvailable::class,
            'Investor 2 Can invest on Tranche A');
        $investmentService->invest($investor2->id, $trancheA->id, $amount, $date);

    }


    /**
     * This tests Scenario 3 and 4, as these are interconnected.
     */
    public function testInvestor3and4CanInvest(): void
    {
        $investmentService = (new \Services\InvestmentService());
        // Get Investor 3
        $investor3 = (new \Models\Investor())->find(3);
        $trancheB = (new \Models\Tranche())->where('title', 'B')->first();

        $amount = 500;
        $date = '2020-10-10';

        // Start Testing
        //Scenario 3
        $investment = $investmentService->invest($investor3->id, $trancheB->id, $amount, $date);
        $this->assertTrue($investment, 'Investor 3 Cannot invest on Tranche B');


        $this->assertEquals(500, $investor3->wallet()->funds);

        //Scenario 4
        $date = '2020-10-25';
        $amount = 1100;
        $investor4 = (new \Models\Investor())->find(4);
        $this->expectException(\system\exceptions\InsufficientFunds::class, 'Investor 4 Can invest on Tranche B');
        $investmentService->invest($investor4->id, $trancheB->id, $amount, $date);

    }

    /**
     * Tests the first of the month event
     */
    public function testMonthlyReport(): void
    {
        event((new \Events\FirstDayOfTheMonth('2020-11-01')));

        $investor1Funds = (new \Models\Investor())->find(1)->wallet()->funds;
        $this->assertEquals(28.06, $investor1Funds);

        $investor3Funds = (new \Models\Investor())->find(3)->wallet()->funds;
        $this->assertEquals(521.29, $investor3Funds);
    }
}
