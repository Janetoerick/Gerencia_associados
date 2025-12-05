<div class="container form-small-width"> 
    
    <h2>Editar Associado: <?= htmlspecialchars($associado['nome']) ?></h2>

    <?php if (isset($_SESSION['msg_erro'])): ?>
        <div class="message-box error-message"><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>

    <form action="/associados/atualizar/<?= htmlspecialchars($associado['id']) ?>" method="POST">
        
        <input type="hidden" name="_method" value="PUT"> 
        
        <label for="nome">Nome Completo</label>
        <input 
            type="text" 
            name="nome" 
            id="nome" 
            value="<?= htmlspecialchars($associado['nome']) ?>" 
            required 
        >

        <label for="email">E-mail</label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            value="<?= htmlspecialchars($associado['email']) ?>" 
            required 
        >

        <label for="cpf">CPF (apenas números)</label>
        <input 
            type="text" 
            name="cpf" 
            id="cpf" 
            value="<?= htmlspecialchars($associado['cpf']) ?>" 
            readonly 
            style="background-color: #eee;" 
        >
        
        <label for="data_filiacao">Data de Filiação</label>
        <input 
            type="date" 
            name="data_filiacao" 
            id="data_filiacao" 
            value="<?= htmlspecialchars($associado['data_filiacao']) ?>" 
            required
        >
        
        <hr style="margin: 30px 0;">

        <div class="btn-stack">
            
            <button type="submit" class="btn btn-edit" style="width: 100%;">Atualizar Associado</button>
            
            <a href="/associados" class="btn btn-secondary" style="width: 100%;">Cancelar</a>
        </div>

    </form>
</div>