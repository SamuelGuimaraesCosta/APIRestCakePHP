<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StoreTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StoreTable Test Case
 */
class StoreTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StoreTable
     */
    protected $Store;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Store',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Store') ? [] : ['className' => StoreTable::class];
        $this->Store = $this->getTableLocator()->get('Store', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Store);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\StoreTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
