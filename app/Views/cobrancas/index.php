<?php 
// Os dados devem vir do Controller
$associado = $associado ?? ['nome' => 'Desconhecido', 'id' => 0];
$cobrancas = $cobrancas ?? [];
$total = $total ?? 0.00; // Total de dívidas em aberto
?>

<div class="container content-wrapper">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Cobranças de: <?= htmlspecialchars($associado['nome']) ?></h2>
        <a href="/associados" class="btn btn-secondary">Voltar</a>
    </div>
    
    <a 
        href="/associados/<?= htmlspecialchars($associado['id']) ?>/cobrancas/novo" 
        class="btn btn-new" 
        style="margin-bottom: 20px;"
    >
        + Gerar Nova Cobrança
    </a>
    
    <?php if (isset($_SESSION['msg_sucesso'])): ?>
        <div class="message-box success-message"><?= htmlspecialchars($_SESSION['msg_sucesso']) ?></div>
        <?php unset($_SESSION['msg_sucesso']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['msg_erro'])): ?>
        <div class="message-box error-message"><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>
    
    <p style="margin-top: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9; border-radius: 4px;">
        Total de Dívidas em Aberto: 
        <span style="color: red; font-weight: bold;">
            R$ <?= number_format($total, 2, ',', '.') ?>
        </span>
    </p>

    <h3>Histórico de Cobranças</h3>

    <?php if (!empty($cobrancas)): ?>
        <table>
            <thead>
                <tr>
                    <th>Ano</th>
                    <th>Valor Base Anuidade</th>
                    <th>Valor Cobrado</th>
                    <th>Status</th>
                    <th class="action-column">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cobrancas as $cobranca): ?>
                    <tr>
                        <td><?= htmlspecialchars($cobranca['Anuidade_ano']) ?></td>
                        <td>R$ <?= number_format((float)$cobranca['valor_anuidade_base'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format((float)$cobranca['valor_cobrado'], 2, ',', '.') ?></td>
                        
                        <td>
                            <?php if ($cobranca['pago']): ?>
                                <span style="color: green; font-weight: bold;">PAGO</span> 
                                (<?= date('d/m/Y', strtotime($cobranca['data_pagamento'] ?? '')) ?>)
                            <?php else: ?>
                                <span style="color: red; font-weight: bold;">PENDENTE</span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="action-column">
                            <?php if (!$cobranca['pago']): ?>
                                <form action="/cobrancas/<?= htmlspecialchars($cobranca['id']) ?>/pagar" method="POST" style="display:inline;">
                                    <input type="hidden" name="associado_id" value="<?= htmlspecialchars($associado['id']) ?>">
                                    <button type="submit" class="btn btn-new">Pagar</button>
                                </form>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma cobrança registrada para este associado.</p>
    <?php endif; ?>
</div>