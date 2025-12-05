<div> 
    
    <h2>Cadastrar Nova Anuidade</h2>

    <?php if (isset($_SESSION['msg_erro'])): ?>
        <div><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>

    <form action="/anuidades" method="POST">
        
        <label for="ano">Ano da Anuidade</label>
        <input 
            type="number" 
            name="ano" 
            id="ano" 
            value="<?= date('Y') + 1 ?>"
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
        
        <div>
            <input 
                type="checkbox" 
                name="gerar_cobranca" 
                id="gerar_cobrancas_massa" 
                value="1" 
                checked 
            >
            <label for="gerar_cobrancas_massa" style="margin-top: 0; font-weight: normal;">
                Gerar automaticamente as cobranças desta anuidade para **todos os associados ativos** no sistema.
            </label>
        </div>

        <div>
            <button type="submit" style="width: 100%;">Cadastrar Anuidade</button>
            <a href="/anuidades" style="width: 100%;">
                Cancelar
            </a>
        </div>

    </form>
</div>