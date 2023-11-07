<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Client;
use Cake\ORM\TableRegistry;

/**
 * Stores Controller
 *
 * @method \App\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StoresController extends AppController
{
  /**
   * Index method
   *
   * @return \Cake\Http\Response|null|void Renders view
   */
  public function index()
  {
    $stores = $this->Stores->find('all')->contain(['Addresses'])->toList();

    $this->set(compact('stores'));
  }

  /**
   * View method
   *
   * @param string|null $id Store id.
   * @return \Cake\Http\Response|null|void Renders view
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function view($id = null)
  {
    $store = $this->Stores->get($id, [
      'contain' => [],
    ]);

    $this->set(compact('store'));
  }

  /**
   * Add method
   *
   * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
   */
  public function add()
  {
    $store = $this->Stores->newEmptyEntity();
    if ($this->request->is('post')) {
      $store = $this->Stores->patchEntity($store, $this->request->getData());
      if ($this->Stores->save($store)) {
        $this->Flash->success(__('A loja foi salva com sucesso!'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Houve um problema ao salvar a loja.<br>Tente novamente mais tarde!'));
    }
    $this->set(compact('store'));
  }

  /**
   * Edit method
   *
   * @param string|null $id Store id.
   * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function edit($id = null)
  {
    $store = $this->Stores->get($id, [
      'contain' => [],
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $store = $this->Stores->patchEntity($store, $this->request->getData());
      if ($this->Stores->save($store)) {
        $this->Flash->success(__('A loja foi alterada com sucesso!'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Houve um problema ao alterar a loja.<br>Tente novamente mais tarde!'));
    }
    $this->set(compact('store'));
  }

  /**
   * Delete method
   *
   * @param string|null $id Store id.
   * @return \Cake\Http\Response|null|void Redirects to index.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $store = $this->Stores->get($id);

    // Inicie a transação
    $connection = ConnectionManager::get('default');
    $connection->begin();

    $address = $this->Stores->Addresses->find()->where(['foreign_id' => $id])->first();

    if ($this->Stores->delete($store) && $address) {
      // Exclua o endereço associado à loja
      if ($this->Stores->Addresses->delete($address)) {
        $connection->commit();
      } else {
        $connection->rollback();
        throw new \Exception('Houve um problema ao excluir o endereco (Loja nao excluida)!');
      }
    } else {
      $connection->rollback();

      throw new \Exception('Houve um problema ao excluir a loja!');
    }

    return $this->redirect(['action' => 'index']);
  }

  public function addWithAddress()
  {
    $this->request->allowMethod(['post']);

    $this->autoRender = false;

    if ($this->request->is('post')) {
      $data = $this->request->getData();

      if (empty($data)) {
        throw new \Exception('Nenhum dado foi recebido.');
      }

      if (empty($data['name'])) {
        throw new \Exception('Nenhuma loja foi recebida.');
      }

      // Verifique se os dados incluem 'addresses'
      if (!empty($data['addresses'])) {
        $data['addresses'][0]['_destroy'] = false; // Defina _destroy para false para manter o primeiro endereço

        // Remova endereços adicionais e defina _destroy para true para excluir endereços anteriores
        for ($i = 1; $i < count($data['addresses']); $i++) {
          $data['addresses'][$i]['_destroy'] = true;
        }
      } else {
        throw new \Exception('Nenhum endereço foi enviado.');
      }

      // Inicie a transação
      $connection = ConnectionManager::get('default');
      $connection->begin();

      // Valide a entidade Store
      $store = $this->Stores->newEntity($data, ['associated' => ['Addresses']]);

      // Valida a entidade Store.
      $store = $this->Stores->patchEntity($store, $this->request->getData());

      // Verifica erros de validação.
      if ($store->getErrors()) {
        $storeErrors = $store->getErrors();
        $errorMessage = '';
        // Aqui, você pode usar $storeErrors para trabalhar com os erros, se houver algum
        foreach ($storeErrors as $field => $errors) {
          foreach ($errors as $error) {
            // Lógica para lidar com erros, se necessário
            $errorMessage .= $error;
            // Faça algo com o erro, como exibí-lo ou registrá-lo
          }
        }
        throw new \Exception('Erro no cadastro da loja: ' . $errorMessage);
      }

      // Valide a entidade Address manualmente
      $addressData = $data['addresses'][0]; // Somente o primeiro endereco permanecera na inclusao 1:1
      $addressData['foreign_table'] = 'stores';
      $addressNew = self::validatePostalCode($addressData['postal_code']);
      $addressData['street'] = $addressNew['street'];
      $addressData['sublocality'] = $addressNew['sublocality'];
      $addressData['city'] = $addressNew['city'];
      $addressData['state'] = $addressNew['state'];

      if ($this->Stores->save($store, ['associated' => ['Addresses']])) {
        $addressData['foreign_id'] = $store->id;

        $address = $this->Stores->Addresses->newEntity($addressData);

        if ($this->Stores->Addresses->save($address)) {
          // Commit a transação se ambos os salvamentos forem bem-sucedidos
          $connection->commit();

          $response = [
            'success' => true,
            'message' => 'Loja e Endereco cadastrados com sucesso!'
          ];

          echo json_encode($response);
        } else {
          $connection->rollback();
          throw new \Exception('Erro ao salvar o endereço!');
        }
      } else {
        // Lida com erros de validação, se houver algum
        throw new \Exception('Erro ao salvar a loja!');
      }
    }

    $this->set(compact('store'));
  }

  public function validatePostalCode($value)
  {
    $postalCode = preg_replace('/\D/', '', $value); // Remove caracteres não numéricos

    $httpClient = new Client();
    $api1Response = $httpClient->get("https://viacep.com.br/ws/{$postalCode}/json/");

    if ($api1Response->isOk()) {
      $data = $api1Response->getJson();
      if (!empty($data) && isset($data['logradouro'], $data['bairro'], $data['localidade'], $data['uf'])) {
        $address['street'] = $data['logradouro'];
        $address['sublocality'] = $data['bairro'];
        $address['city'] = $data['localidade'];
        $address['state'] = $data['uf'];

        return $address;
      }
    }

    // Caso a primeira API não retorne resultados satisfatórios, tente a segunda API (Via CEP)
    $api2Response = $httpClient->get("https://viacep.com.br/ws/{$postalCode}/json/");

    if ($api2Response->isOk()) {
      $data = $api2Response->getJson();
      if (!empty($data) && isset($data['logradouro'], $data['bairro'], $data['localidade'], $data['uf'])) {
        $address['street'] = $data['logradouro'];
        $address['sublocality'] = $data['bairro'];
        $address['city'] = $data['localidade'];
        $address['state'] = $data['uf'];

        return $address;
      }
    }

    return null; // Validação falhou
  }

  public function updateWithAddress($id)
  {
    $this->request->allowMethod(['post']);

    $this->autoRender = false;

    try {
      $store = $this->Stores->get($id, ['contain' => 'Addresses']);
    } catch (RecordNotFoundException $e) {
      throw new \Exception('Loja não encontrada.');
    }

    if ($this->request->is(['patch', 'post', 'put'])) {
      $data = $this->request->getData();

      if (empty($data)) {
        throw new \Exception('Nenhum dado foi recebido.');
      }

      if (empty($data['name'])) {
        throw new \Exception('Nenhuma loja foi recebida.');
      }

      // Verifique se os dados incluem 'addresses'
      if (!empty($data['addresses'])) {
        $data['addresses'][0]['_destroy'] = false; // Defina _destroy para false para manter o primeiro endereço

        // Remova endereços adicionais e defina _destroy para true para excluir endereços anteriores
        for ($i = 1; $i < count($data['addresses']); $i++) {
          $data['addresses'][$i]['_destroy'] = true;
        }
      } else {
        throw new \Exception('Nenhum endereço foi enviado.');
      }

      // Valida a entidade Store.
      $actualStores = $this->Stores->find()->where(['name' => $data['name']])->first();

      if ($actualStores && $actualStores->id !== $id) {
        throw new \Exception('Nome em uso!');
      }

      // Inicie a transação
      $connection = ConnectionManager::get('default');
      $connection->begin();

      // Valida a entidade Store.
      $store = $this->Stores->patchEntity($store, $this->request->getData());

      // Verifica erros de validação.
      if ($store->getErrors()) {
        $storeErrors = $store->getErrors();
        $errorMessage = '';
        // Aqui, você pode usar $storeErrors para trabalhar com os erros, se houver algum
        foreach ($storeErrors as $field => $errors) {
          foreach ($errors as $error) {
            // Lógica para lidar com erros, se necessário
            $errorMessage .= $error;
            // Faça algo com o erro, como exibí-lo ou registrá-lo
          }
        }
        throw new \Exception('Erro na atualização da loja: ' . $errorMessage);
      }

      if ($this->Stores->save($store, ['associated' => ['Addresses']])) {
        $addressTable = TableRegistry::getTableLocator()->get('Addresses');
        $address = $addressTable->find('all')->where(['foreign_id' => $id])->first();

        // Exclua o endereço antigo.
        $this->Stores->Addresses->delete($address);

        // Valide a entidade Address manualmente
        $addressData = $data['addresses'][0]; // Somente o primeiro endereco permanecera na inclusao 1:1
        $addressData['foreign_table'] = 'stores';
        $addressNew = self::validatePostalCode($addressData['postal_code']);
        $addressData['street'] = $addressNew['street'];
        $addressData['sublocality'] = $addressNew['sublocality'];
        $addressData['city'] = $addressNew['city'];
        $addressData['state'] = $addressNew['state'];

        $addressData['foreign_id'] = $id;

        $address = $this->Stores->Addresses->newEntity($addressData);

        if ($this->Stores->Addresses->save($address)) {
          // Commit a transação se ambos os salvamentos forem bem-sucedidos
          $connection->commit();

          $response = [
            'success' => true,
            'message' => 'Loja e Endereco atualizados com sucesso!'
          ];

          echo json_encode($response);
        } else {
          $connection->rollback();
          throw new \Exception('Erro ao atualizar o endereço!');
        }
      }

      $this->Flash->error('Houve um erro ao salvar. Certifique-se de inserir pelo menos um endereço.');
    }

    $this->set(compact('store'));
  }
}
