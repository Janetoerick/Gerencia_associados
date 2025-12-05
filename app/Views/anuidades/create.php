<div class="container form-small-width"> 
    
    <h2>Cadastrar Nova Anuidade</h2>

    <?php if (isset($_SESSION['msg_erro'])): ?>
        <div class="message-box error-message"><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>

    <form action="/anuidades" method="POST">
        
        <label for="ano">Ano da Anuidade</label>
        <input 
            type="number" 
            name="ano" 
            id="ano" 
            value="<?= date('Y') + 1 ?>" 
            min="<?= date('Y') ?>" 
            required 
            placeholder="Ex: 2026"
        >

        <label for="valor">Valor (R$)</label>
        <input 
            type="number" 
            name="valor" 
            id="valor" 
            value="" 
            step="0.01" 
            min="0.01" 
            required 
            placeholder="Ex: 150.00"
        >
        
        <hr style="margin: 30px 0;">
        
        <div class="checkbox-line">
            <input 
                type="checkbox" 
                name="gerar_cobranca" 
                id="gerar_cobrancas_massa" 
                value="1" 
                checked 
            >
            <label for="gerar_cobrancas_massa" style="margin-top: 0; font-weight: normal;">
                Gerar automaticamente as cobranças desta anuidade para <b>todos os associados ativos</b> no sistema.
            </label>
        </div>

        <div class="btn-stack">
            <button type="submit" class="btn btn-new" style="width: 100%;">Cadastrar Anuidade</button>
            <a href="/anuidades" class="btn btn-secondary"style="width: 100%;">
                Cancelar
            </a>
        </div>

    </form>
</div>