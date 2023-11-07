<?php
declare(strict_types=1);

namespace App\Controller;

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
    $stores = $this->paginate($this->Stores);

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
    if ($this->Stores->delete($store)) {
      $this->Flash->success(__('A loja foi excluída com sucesso!'));
    } else {
      $this->Flash->error(__('Houve um problema ao excluir a loja.<br>Tente novamente mais tarde!'));
    }

    return $this->redirect(['action' => 'index']);
  }
}
