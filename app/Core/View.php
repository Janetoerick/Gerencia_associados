<?php
// app/Core/View.php

namespace App\Core;

class View
{

    protected array $cssAssets = [];
    protected array $jsAssets = [];

    public function addCss(string $path): void
    {
        // Garante que o CSS é adicionado apenas uma vez
        if (!in_array($path, $this->cssAssets)) {
            $this->cssAssets[] = $path;
        }
    }

    public function addJs(string $path): void
    {
        // Garante que o CSS é adicionado apenas uma vez
        if (!in_array($path, $this->jsAssets)) {
            $this->jsAssets[] = $path;
        }
    }

    /**
    * Renderiza a View.
    */
    public function render(string $viewPath, array $data = [], string $layout = 'main')
    {
        // Converte o array de dados em variáveis
        extract($data); 

        // Define o caminho para o conteúdo da view específica
        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';

        if (!file_exists($viewFile)) {
            echo "Erro: View '{$viewPath}' não encontrada.";
            return;
        }

        // Captura o conteúdo da view em um buffer
        ob_start();
        require $viewFile;
        $content = ob_get_clean(); 

        // Define o caminho do layout principal
        $layoutFile = __DIR__ . '/../Views/layout/' . $layout . '.php';

        // Inclui o layout principal
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
}