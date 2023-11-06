<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateAddresses extends AbstractMigration
{
  /**
   * Change Method.
   *
   * More information on this method is available here:
   * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
   * @return void
   */
  public function change(): void
  {
    $table = $this->table('addresses');
    $table->addColumn('foreign_table', 'string', [
        'default' => null,
        'limit' => 100,
        'null' => false,
      ])->addColumn('foreign_id', 'biginteger', [
        'default' => null,
        'null' => false,
      ])->addColumn('postal_code', 'string', [
        'default' => null,
        'limit' => 8,
        'null' => false,
      ])->addColumn('state', 'string', [
        'default' => null,
        'limit' => 2,
        'null' => false,
      ])->addColumn('city', 'string', [
        'default' => null,
        'limit' => 200,
        'null' => false,
      ])->addColumn('sublocality', 'string', [
        'default' => null,
        'limit' => 200,
        'null' => false,
      ])->addColumn('street', 'string', [
        'default' => null,
        'limit' => 200,
        'null' => false,
      ])->addColumn('street_number', 'string', [
        'default' => null,
        'limit' => 200,
        'null' => false,
      ])->addColumn('complement', 'string', [
        'default' => '',
        'limit' => 200,
        'null' => false,
      ])->addPrimaryKey(['id'])->addIndex([
          'foreign_table',
          'foreign_id'
        ], [
          'unique' => true
        ])->create();
  }
}
