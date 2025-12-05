<div class="container form-small-width"> 
    
    <h2>Gerar Nova Cobrança para: <?= htmlspecialchars($associado['nome']) ?></h2>

    <?php if (isset($_SESSION['msg_erro'])): ?>
        <div class="message-box error-message"><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>

    <form action="/associados/cobrancas" method="POST">
        
        <input type="hidden" name="associado_id" value="<?= htmlspecialchars($associado['id']) ?>">
        
        <label for="associado_nome">Associado</label>
        <input 
            type="text" 
            id="associado_nome" 
            value="<?= htmlspecialchars($associado['nome']) ?>" 
            readonly 
            style="background-color: #eee;"
        >

        <label for="ano_referencia">Ano de Referência (Ex: 2025)</label>
        <input 
            type="number" 
            name="ano_referencia" 
            id="ano_referencia" 
            value="<?= date('Y') ?>" 
            min="2000" 
            required 
            placeholder="Ano que a cobrança se refere"
        >
        
        <hr style="margin: 30px 0;">

        <div class="btn-stack">
            
            <button type="submit" class="btn btn-new" style="width: 100%;">Gerar Cobrança</button>
            
            <a href="/associados/<?= htmlspecialchars($associado['id']) ?>/cobrancas" class="btn btn-secondary" style="width: 100%;">Cancelar</a>
        </div>

    </form>
</div>