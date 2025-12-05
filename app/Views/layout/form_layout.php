<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Gerenciamento</title>
    
    <link rel="stylesheet" href="/assets/css/main.css">
    
    <?php foreach ($this->cssAssets as $cssPath): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($cssPath) ?>">
    <?php endforeach; ?>

</head>
<body>
    
    <div class="content-wrapper centered-form-wrapper">
        <?= $content ?> 
    </div>

    <?php foreach ($this->jsAssets as $jsPath): ?>
        <script src="<?= htmlspecialchars($jsPath) ?>"></script>
    <?php endforeach; ?>

</body>
</html>