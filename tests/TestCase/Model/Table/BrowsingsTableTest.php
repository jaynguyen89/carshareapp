<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BrowsingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BrowsingsTable Test Case
 */
class BrowsingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BrowsingsTable
     */
    public $Browsings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.browsings',
        'app.customers',
        'app.users',
        'app.messages',
        'app.rentals',
        'app.cars',
        'app.parks',
        'app.requests'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Browsings') ? [] : ['className' => BrowsingsTable::class];
        $this->Browsings = TableRegistry::get('Browsings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Browsings);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
