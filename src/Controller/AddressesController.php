<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;

/**
 * Addresses Controller
 *
 * @method \App\Model\Entity\Address[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AddressesController extends AppController
{
  /**
   * Index method
   *
   * @return \Cake\Http\Response|null|void Renders view
   */
  public function index()
  {
    $storesTable = TableRegistry::getTableLocator()->get('Stores');
    $stores = $storesTable->find('all')->toList();

    $addresses = $this->Addresses->find('all', [
      'contain' => 'Stores',
    ]);

    foreach ($addresses as $address) {
      $postalCode = $address->postal_code;
      if (strlen($postalCode) === 8) {
        $address->postal_code_masked = substr($postalCode, 0, 5) . '-' . substr($postalCode, 5);
      } else {
        $address->postal_code_masked = $postalCode;
      }
    }
    
    $addresses = $this->paginate($this->Addresses);

    $this->set(compact('addresses'));
  }

  /**
   * View method
   *
   * @param string|null $id Address id.
   * @return \Cake\Http\Response|null|void Renders view
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function view($id = null)
  {
    $address = $this->Addresses->get($id, [
      'contain' => [],
    ]);

    $this->set(compact('address'));
  }

  /**
   * Add method
   *
   * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
   */
  public function add()
  {
    $address = $this->Addresses->newEmptyEntity();
    if ($this->request->is('post')) {
      $address = $this->Addresses->patchEntity($address, $this->request->getData());
      if ($this->Addresses->save($address)) {
        $this->Flash->success(__('O endereço foi salvo com sucesso!'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Houve um erro ao salvar o endereço.<br>Tente novamente mais tarde!'));
    }
    $this->set(compact('address'));
  }

  /**
   * Edit method
   *
   * @param string|null $id Address id.
   * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function edit($id = null)
  {
    $address = $this->Addresses->get($id, [
      'contain' => [],
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $address = $this->Addresses->patchEntity($address, $this->request->getData());
      if ($this->Addresses->save($address)) {
        $this->Flash->success(__('O endereço foi alterado com sucesso!'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Houve um erro ao alterar o endereço.<br>Tente novamente mais tarde!'));
    }
    $this->set(compact('address'));
  }

  /**
   * Delete method
   *
   * @param string|null $id Address id.
   * @return \Cake\Http\Response|null|void Redirects to index.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $address = $this->Addresses->get($id);
    if ($this->Addresses->delete($address)) {
      $this->Flash->success(__('O endereço foi excluído com sucesso!'));
    } else {
      $this->Flash->error(__('Houve um erro ao excluir o endereço.<br>Tente novamente mais tarde!'));
    }

    return $this->redirect(['action' => 'index']);
  }
}
