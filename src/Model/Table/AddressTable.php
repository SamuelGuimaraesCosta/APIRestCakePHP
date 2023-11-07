<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Address Model
 *
 * @method \App\Model\Entity\Addres newEmptyEntity()
 * @method \App\Model\Entity\Addres newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Addres[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Addres get($primaryKey, $options = [])
 * @method \App\Model\Entity\Addres findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Addres patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Addres[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Addres|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Addres saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Addres[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Addres[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Addres[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Addres[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AddressTable extends Table
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

        $this->setTable('Addresses');
        $this->setDisplayField('foreign_table');
        $this->setPrimaryKey('id');
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
            ->scalar('foreign_table')
            ->maxLength('foreign_table', 100)
            ->requirePresence('foreign_table', 'create')
            ->notEmptyString('foreign_table');

        $validator
            ->requirePresence('foreign_id', 'create')
            ->notEmptyString('foreign_id');

        $validator
            ->scalar('postal_code')
            ->maxLength('postal_code', 8)
            ->requirePresence('postal_code', 'create')
            ->notEmptyString('postal_code');

        $validator
            ->scalar('state')
            ->maxLength('state', 2)
            ->requirePresence('state', 'create')
            ->notEmptyString('state');

        $validator
            ->scalar('city')
            ->maxLength('city', 200)
            ->requirePresence('city', 'create')
            ->notEmptyString('city');

        $validator
            ->scalar('sublocality')
            ->maxLength('sublocality', 200)
            ->requirePresence('sublocality', 'create')
            ->notEmptyString('sublocality');

        $validator
            ->scalar('street')
            ->maxLength('street', 200)
            ->requirePresence('street', 'create')
            ->notEmptyString('street');

        $validator
            ->scalar('street_number')
            ->maxLength('street_number', 200)
            ->requirePresence('street_number', 'create')
            ->notEmptyString('street_number');

        $validator
            ->scalar('complement')
            ->maxLength('complement', 200)
            ->notEmptyString('complement');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['foreign_table', 'foreign_id']), ['errorField' => 'foreign_table']);

        return $rules;
    }
}
