<h1>Lista de Lojas e seus respectivos Endereços</h1>
TESTE

<?php foreach ($addresses as $address): ?>
    <h2>Loja: <?= h($address->store->name) ?></h2>
    <table>
        <tr>
            <th>CEP</th>
            <th>Estado</th>
            <th>Cidade</th>
            <th>Bairro</th>
            <th>Rua</th>
            <th>Número</th>
            <th>Complemento</th>
        </tr>
        <tr>
            <td><?= h($address->postal_code) ?></td>
            <td><?= h($address->state) ?></td>
            <td><?= h($address->city) ?></td>
            <td><?= h($address->sublocality) ?></td>
            <td><?= h($address->street) ?></td>
            <td><?= h($address->street_number) ?></td>
            <td><?= h($address->complement) ?></td>
        </tr>
    </table>
<?php endforeach; ?>