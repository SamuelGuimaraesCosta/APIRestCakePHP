# CakePHP 4.x API Rest

## Pré-requisitos

Antes de começar a trabalhar com o CakePHP, certifique-se de que você atende aos seguintes pré-requisitos:

* PHP 7.2 ou superior instalado no seu sistema.
* Composer instalado no seu sistema.
* Servidor web (por exemplo, Apache ou Nginx) configurado e funcionando no seu sistema.
* MySQL ou outro sistema de gerenciamento de banco de dados configurado e em execução no seu sistema.
* Banco de dados configurado com tabelas __stores__ e __addresses__.
* Associação estabelecida entre as tabelas __stores__ e __addresses__.

## Instalação do CakePHP

* Inicie o servidor web embutido do CakePHP com o seguinte comando:
  
```bash
bin/cake server
```

Isso iniciará o servidor de desenvolvimento e mostrará a URL do aplicativo no terminal. Geralmente, é http://localhost:8765.

Abra seu navegador e acesse a URL exibida no terminal para verificar se o CakePHP está funcionando corretamente. Você verá a página inicial padrão do CakePHP.

## Operações do Aplicativo

Agora que você tem o CakePHP configurado, você pode realizar as seguintes operações no aplicativo:

* __Criar uma Nova Loja com um Endereço__: Crie uma nova loja seguindo as etapas mencionadas na seção "Criar uma Nova Loja" da documentação anterior.

* __Visualizar Lojas e Endereços__: Acesse a página inicial do aplicativo para visualizar a lista de lojas e seus endereços.

* __Atualizar uma Loja e Seu Endereço__: Siga as etapas mencionadas na seção "Atualizar uma Loja e Seu Endereço" da documentação anterior.

* __Excluir uma Loja e Seu Endereço__: Siga as etapas mencionadas na seção "Excluir uma Loja e Seu Endereço" da documentação anterior.

## Criar uma Nova Loja

Para criar uma nova loja e associar um endereço a ela, siga os passos:

Faça uma requisição POST para a rota ****/stores/addWithAddress**** no seu aplicativo.
No corpo da requisição, forneça os dados da loja e do endereço no formato JSON. Certifique-se de incluir pelo menos um endereço.
Exemplo de requisição:

```json
{
  "name": "Minha Loja",
  "addresses": [
    {
      "postal_code": "12345-678",
      "street_number": "123",
      "complement": "Sala 101"
    }
  ]
}
```

Se a requisição for bem-sucedida, você receberá uma resposta indicando que a loja e o endereço foram salvos com sucesso.

## Visualizar Lojas e Endereços

* Para visualizar a lista de lojas e seus endereços, acesse a rota **/addresses**. Isso exibirá uma lista de lojas com os detalhes dos endereços associados a cada uma delas.
* Para visualizar a lista de lojas, acesse a rota **/stores**. Isso exibirá uma lista de lojas.

## Atualizar uma Loja e Seu Endereço

Para atualizar os dados de uma loja e seu endereço associado, siga os passos:

Faça uma requisição POST para a rota **/stores/updateWithAddress/{id}**, onde **{id}** é o ID da loja que você deseja atualizar.
No corpo da requisição, forneça os novos dados da loja e do endereço no formato JSON. Certifique-se de incluir pelo menos um endereço. Qualquer endereço anterior associado à loja será substituído pelo novo endereço.
Exemplo de requisição:

```json
{
  "name": "Minha Loja Atualizada",
  "addresses": [
    {
      "postal_code": "54321-876",
      "street_number": "456",
      "complement": "Sala 202"
    }
  ]
```

Se a requisição for bem-sucedida, você receberá uma resposta indicando que a loja e o endereço foram atualizados com sucesso.

## Excluir uma Loja e Seu Endereço

Para excluir uma loja e o endereço associado a ela, siga os passos:

* Faça uma requisição POST para a rota **/stores/delete/{id}**, onde **{id}** é o ID da loja que você deseja excluir.
* Se a requisição for bem-sucedida, você receberá uma resposta indicando que a loja e o endereço (se houver) foram excluídos com sucesso.

Observação: Se uma loja estiver associada a um endereço, o endereço também será excluído.
