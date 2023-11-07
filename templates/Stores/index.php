<h1>Lista de Lojas</h1>
<table>
    <tr>
        <th>Nome</th>
    </tr>
    <?php foreach ($stores as $store): ?>
    <tr>
        <td><?= h($store->name) ?></td>
    </tr>
    <?php endforeach; ?>
</table>