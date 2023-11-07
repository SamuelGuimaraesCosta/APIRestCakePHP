<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Http\Client;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Addresses Model
 *
 * @method \App\Model\Entity\Address newEmptyEntity()
 * @method \App\Model\Entity\Address newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Address[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Address get($primaryKey, $options = [])
 * @method \App\Model\Entity\Address findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Address patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Address[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Address|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Address saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Address[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Address[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Address[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Address[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AddressesTable extends Table
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

    $this->setTable('addresses');
    $this->setDisplayField('foreign_table');
    $this->setPrimaryKey('id');
    $this->belongsTo('Stores', [
      'foreignKey' => 'foreign_id',
      'joinType' => 'INNER',
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

    $validator
      ->requirePresence('postal_code')
      ->notEmptyString('postal_code', 'CEP é obrigatório')
      ->add('postal_code', 'custom', [
        'rule' => 'validatePostalCode',
        // Implemente a função para consultar informações do CEP
        'message' => 'CEP não encontrado',
      ]);

    $validator
      ->requirePresence('street_number')
      ->notEmptyString('street_number', 'Número de rua é obrigatório');

    $validator
      ->allowEmptyString('complement');

    return $validator;
  }

  public function validatePostalCode($value, array $context)
  {
    $postalCode = preg_replace('/\D/', '', $value); // Remove caracteres não numéricos

    $httpClient = new Client();
    $api1Response = $httpClient->get("https://cep.la/{$postalCode}?json&sua-chave-de-acesso");

    if ($api1Response->isOk()) {
      $data = $api1Response->getJson();
      if (!empty($data) && isset($data['logradouro'], $data['bairro'], $data['localidade'], $data['uf'])) {
        $this->address->street = $data['logradouro'];
        $this->address->sublocality = $data['bairro'];
        $this->address->city = $data['localidade'];
        $this->address->state = $data['uf'];

        return true; // Validação bem-sucedida
      }
    }

    // Caso a primeira API não retorne resultados satisfatórios, tente a segunda API (Via CEP)
    $api2Response = $httpClient->get("https://viacep.com.br/ws/{$postalCode}/json/");

    if ($api2Response->isOk()) {
      $data = $api2Response->getJson();
      if (!empty($data) && isset($data['logradouro'], $data['bairro'], $data['localidade'], $data['uf'])) {
        $this->address->street = $data['logradouro'];
        $this->address->sublocality = $data['bairro'];
        $this->address->city = $data['localidade'];
        $this->address->state = $data['uf'];

        return true; // Validação bem-sucedida
      }
    }

    return false; // Validação falhou
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
