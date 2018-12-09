<?php

declare(strict_types=1);

namespace tests\Feature;

use App\Domain\Investment;
use App\Domain\Investor;
use App\Domain\Offering;
use App\Validation\EmailAddress;
use App\Validation\PaymentMethod;
use PHPUnit\Framework\TestCase;

class TotalDollarsInvestedForInvestorTest extends TestCase
{
    /** @var string */
    const firstName = 'Rich';
    /** @var string */
    const lastName = 'Jones';
    /** @var string */
    const email = 'jones_rich@yahoo.com';

    /** @var Investor */
    private $investor = null;

    /**
     * @throws \Exception
     */
    public function setUp()
    {
        parent::setUp();

        $this->setInvestor(
            new Investor(
                self::firstName,
                self::lastName,
                new EmailAddress(self::email)
            )
        );
    }

    /**
     * @throws \Exception
     */
    public function test_total_dollars_invested()
    {
        //
        // build investments
        //
        $investmentA = new Investment(
            1000,
            new PaymentMethod('cash')
        );

        $investmentB = new Investment(
            2000,
            new PaymentMethod('cash')
        );

        $investmentC = new Investment(
            1000,
            new PaymentMethod('check')
        );

        $investmentD = new Investment(
            2000,
            new PaymentMethod('check')
        );

        //
        // build offers
        //
        $offerA = new Offering(
            'Offer A',
            2
        );
        $offerA->addInvestment($investmentA);
        $offerA->addInvestment($investmentB);

        $offerB = new Offering(
            'Offer B',
            2
        );
        $offerB->addInvestment($investmentC);
        $offerB->addInvestment($investmentD);

        //
        // add offers to Investor
        //
        $this->getInvestor()->addOffering($offerA);
        $this->getInvestor()->addOffering($offerB);

        // check that they match...
        $this->assertSame(6000, $this->getInvestor()->getTotalDollarsInvested());
    }

    /**
     * @param Investor $investor
     *
     * @return TotalDollarsInvestedForInvestorTest
     */
    protected function setInvestor(Investor $investor): self
    {
        $this->investor = $investor;

        return $this;
    }

    /**
     * @return Investor
     */
    protected function getInvestor(): Investor
    {
        return $this->investor;
    }
}