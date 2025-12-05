<?php
function formatar_cpf(string $cpf_limpo): string {
    if (strlen($cpf_limpo) !== 11 || !is_numeric($cpf_limpo)) {
        return $cpf_limpo; // Retorna o valor bruto se for inválido
    }
    return substr($cpf_limpo, 0, 3) . '.' .
           substr($cpf_limpo, 3, 3) . '.' .
           substr($cpf_limpo, 6, 3) . '-' .
           substr($cpf_limpo, 9, 2);
}

?>


<a href="/associados/novo" style="margin-bottom: 20px;">+ Novo Associado</a>

<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>CPF</th>
                <th>Filiação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($associados)): ?>
                <tr>
                    <td colspan="7" >Nenhum associado encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($associados as $associado): ?>
                    <tr data-id="<?= htmlspecialchars($associado['id']) ?>">
                        <td><?= htmlspecialchars($associado['id']) ?></td>
                        <td><?= htmlspecialchars($associado['nome']) ?></td>
                        <td><?= htmlspecialchars($associado['email']) ?></td>
                        <td><?= formatar_cpf(htmlspecialchars($associado['cpf'])) ?></td>
                        <td><?= htmlspecialchars($associado['data_filiacao']) ?></td>

                        <td class="action-column"> 
                            <a href="/associados/editar/<?= htmlspecialchars($associado['id']) ?>" class="btn btn-edit">Editar</a>
                            
                            <form action="/associados/<?= htmlspecialchars($associado['id']) ?>" method="POST" style="display: inline-block;"
                            onsubmit="return confirm('Tem certeza que deseja excluir o associado <?= htmlspecialchars($associado['nome']) ?>?');">
                                <input type="hidden" name="_method" value="DELETE"> 
                                <button type="submit" class="btn btn-delete">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>