<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Stores Model
 *
 * @method \App\Model\Entity\Store newEmptyEntity()
 * @method \App\Model\Entity\Store newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Store[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Store get($primaryKey, $options = [])
 * @method \App\Model\Entity\Store findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Store patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Store[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Store|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Store saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class StoresTable extends Table
{
  /**
   * Initialize method
   *
   * @param array $config The configuration for the Table.
   * @return void
   */
  public function initialize(array $config): void
  {
    parent::initialize($config);

    $this->setTable('stores');
    $this->setDisplayField('name');
    $this->setPrimaryKey('id');
    $this->hasMany('Addresses', [
      'foreignKey' => 'foreign_id',
      'dependent' => true,
    ]);
  }

  /**
   * Default validation rules.
   *
   * @param \Cake\Validation\Validator $validator Validator instance.
   * @return \Cake\Validation\Validator
   */
  public function validationDefault(Validator $validator): Validator
  {
    $validator
      ->scalar('name')
      ->maxLength('name', 200)
      ->requirePresence('name')
      ->notEmptyString('name', 'O nome é obrigatório')
      ->add('name', 'unique', [
        'rule' => 'validateUnique',
        'provider' => 'table',
        'message' => 'Nome em uso',
      ]);

    return $validator;
  }

  /**
   * Summary of buildRules
   * @param \Cake\ORM\RulesChecker $rules
   * @return RulesChecker
   */
  public function buildRules(RulesChecker $rules): RulesChecker
  {
    $rules->add($rules->isUnique(['name'], 'Nome em uso'));
    $rules->addDelete(function ($entity, $options) {
      return true;
    }, 'deleteAddress');

    return $rules;
  }

  public function beforeSave($event, $entity, $options)
  {
    if ($entity->isDirty('name')) {
      if ($entity->addresses) {
        $addressesTable = TableRegistry::getTableLocator()->get('Addresses');

        $previousAddressId = $entity->addresses->id;

        if ($previousAddressId) {
          $previousAddress = $addressesTable->get($previousAddressId);
          $addressesTable->delete($previousAddress);
        }
      }
    }
  }
}
