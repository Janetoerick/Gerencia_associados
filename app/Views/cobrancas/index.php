<?php 
// Os dados devem vir do Controller
$associado = $associado ?? ['nome' => 'Desconhecido', 'id' => 0];
$cobrancas = $cobrancas ?? [];
$total = $total ?? 0.00; // Total de dívidas em aberto

$this->addCss('/assets/css/cobranca.css');

?>

<div class="container content-wrapper">
    
    <div class="header">
        <h2>Cobranças de: <?= htmlspecialchars($associado['nome']) ?></h2>
        <a href="/associados" class="btn btn-secondary">Voltar</a>
    </div>
    
    <a 
        href="/associados/<?= htmlspecialchars($associado['id']) ?>/cobrancas/novo" 
        class="btn btn-new" 
    >
        + Gerar Nova Cobrança
    </a>
    <br><br>
    
    <?php if (isset($_SESSION['msg_sucesso'])): ?>
        <div class="message-box success-message"><?= htmlspecialchars($_SESSION['msg_sucesso']) ?></div>
        <?php unset($_SESSION['msg_sucesso']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['msg_erro'])): ?>
        <div class="message-box error-message"><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>
    
    <div class="total_dividas">
        <p>
            Total de Dívidas em Aberto: 
            <span>
                R$ <?= number_format($total, 2, ',', '.') ?>
            </span>
        </p>

        <?php 
            // Lógica para exibir o botão apenas se houver dívida
            if ($total > 0): 
        ?>
            <form action="/associados/<?= htmlspecialchars($associado['id']) ?>/cobrancas/pagar_tudo" method="POST">
                
                <button type="submit" class="btn-pagar-tudo"
                onclick="return confirm('Tem certeza que deseja registrar o pagamento de TODAS as dívidas pendentes?');"
                >
                    Pagar tudo
                </button>
            </form>
        <?php endif; ?>
    </div>

    

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
                                <span class="status_pago">PAGO</span> 
                                (<?= date('d/m/Y', strtotime($cobranca['data_pagamento'] ?? '')) ?>)
                            <?php else: ?>
                                <span class="status_pendente">PENDENTE</span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="action-column">
                            <?php if (!$cobranca['pago']): ?>
                                <form action="/cobrancas/<?= htmlspecialchars($cobranca['id']) ?>/pagar" method="POST">
                                    <input type="hidden" name="_method" value="PUT"> 
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