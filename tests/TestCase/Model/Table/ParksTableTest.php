<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ParksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ParksTable Test Case
 */
class ParksTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ParksTable
     */
    public $Parks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.parks',
        'app.cars'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Parks') ? [] : ['className' => ParksTable::class];
        $this->Parks = TableRegistry::get('Parks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Parks);

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
}
