<?php
// app/Core/View.php

namespace App\Core;

class View
{

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