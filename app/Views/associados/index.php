<?php

    $this->addJs('/assets/js/associados.js');

?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Gerenciamento de Associados</h2>
        <a href="/" class="btn" style="background-color: #6c757d; color: white;">Início</a>
    </div>
    
    <a href="/associados/novo" class="btn btn-new" style="margin-bottom: 20px;">+ Novo Associado</a>
    
    <?php if (isset($_SESSION['msg_sucesso'])): ?>
        <div class="message-box success-message"><?= htmlspecialchars($_SESSION['msg_sucesso']) ?></div>
        <?php unset($_SESSION['msg_sucesso']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['msg_erro'])): ?>
        <div class="message-box error-message"><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>CPF</th>
                <th>Filiação</th>
                <th>Status Cobrança</th>
                <th class="action-column" style="width: 200px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($associados)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Nenhum associado encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($associados as $associado): ?>
                    <tr class="clickable-row" data-id="<?= htmlspecialchars($associado['id']) ?>">
                        <td><?= htmlspecialchars($associado['id']) ?></td>
                        <td><?= htmlspecialchars($associado['nome']) ?></td>
                        <td><?= htmlspecialchars($associado['email']) ?></td>
                        <td class="cpf"><?= htmlspecialchars($associado['cpf']) ?></td>
                        <td><?= htmlspecialchars($associado['data_filiacao']) ?></td>
                        
                        <td>
                            <?php 
                                $pendencias = (int)$associado['total_pendencias'];
                                
                                if ($pendencias > 0) {
                                    $cor = 'red';
                                    $status_texto = 'Em Débito (' . $pendencias . ')';
                                } else {
                                    $cor = 'green';
                                    $status_texto = 'Em Dia';
                                }
                            ?>
                            <div style="
                                width: 15px; 
                                height: 15px; 
                                background-color: <?= $cor ?>; 
                                border: 1px solid #333; 
                                border-radius: 3px;
                                display: inline-block;
                                margin-right: 5px;
                                vertical-align: middle;
                            "></div>
                            <?= htmlspecialchars($status_texto) ?>
                        </td>

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
</div>