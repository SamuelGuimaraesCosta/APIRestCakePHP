<h1>Lista de Lojas e seus respectivos Endereços</h1>

<?php foreach ($groupedAddresses as $storeName => $addresses): ?>
    <h2>Loja: <?= h($storeName) ?></h2>
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
        <?php foreach ($addresses as $address): ?>
            <tr>
                <td><?= h($address->postal_code_masked) ?></td>
                <td><?= h($address->state) ?></td>
                <td><?= h($address->city) ?></td>
                <td><?= h($address->sublocality) ?></td>
                <td><?= h($address->street) ?></td>
                <td><?= h($address->street_number) ?></td>
                <td><?= h($address->complement) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endforeach; ?>