<?php
// O objeto $anuidade deve ser passado do Controller
$ano_atual = htmlspecialchars((string)($ano ?? ''));
$valor_atual = htmlspecialchars((string)($valor ?? ''));
?>

<div class="container form-small-width">
    
    <h2>Editar Anuidade: Ano <?= htmlspecialchars($ano_atual) ?></h2>

    <?php if (isset($_SESSION['msg_erro'])): ?>
        <div class="message-box error-message"><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>

    <form action="/anuidades/<?= htmlspecialchars($ano_atual) ?>/update" method="POST">
        
        <input type="hidden" name="_method" value="PUT"> 
        
        <label for="ano">Ano da Anuidade</label>
        <input type="text" name="ano" id="ano" value="<?= htmlspecialchars($ano_atual) ?>" readonly style="background-color: #eee;">

        <label for="valor">Valor (R$)</label>
        <input 
            type="number" 
            name="valor" 
            id="valor" 
            value="<?= htmlspecialchars($valor_atual) ?>" 
            step="0.01" 
            min="0.01" 
            required 
            placeholder="Ex: 150.00"
        >

        <div class="btn-stack">
            
            <button type="submit" class="btn btn-edit" style="width: 100%;">Atualizar Anuidade</button>
            
            <a href="/anuidades" class="btn btn-secondary" style="width: 100%;">Cancelar</a>
        </div>

    </form>
</div>