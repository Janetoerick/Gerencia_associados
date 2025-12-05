<div>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Gestão de Anuidades</h2>
        <a href="/" style="background-color: #6c757d; color: white;">Início</a>
    </div>
    
    <a href="/anuidades/nova" style="margin-bottom: 20px;">+ Cadastrar Nova Anuidade</a>
    
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
                <th>Ano</th>
                <th>Valor (R$)</th>
                <th>Status</th> 
                <th style="width: 150px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($anuidades)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Nenhuma anuidade cadastrada.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($anuidades as $anuidade): ?>
                    <tr>
                        <td><?= htmlspecialchars($anuidade['ano']) ?></td>
                        <td>R$ <?= number_format($anuidade['valor'], 2, ',', '.') ?></td>
                        
                        <td>
                            <?php 
                            if ((int)$anuidade['ano'] > date('Y')) {
                                echo '<span style="color: #007bff; font-weight: bold;">Futura</span>';
                            } else {
                                echo '<span>Ativa/Passada</span>';
                            }
                            ?>
                        </td>

                        <td class="action-column"> 
                            <a href="/anuidades/<?= htmlspecialchars($anuidade['ano']) ?>/editar" class="btn btn-edit">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>