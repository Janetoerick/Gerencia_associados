
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('.clickable-row');

    rows.forEach(row => {
        // Adiciona o evento de clique a cada linha
        row.addEventListener('click', function(event) {
            // 1. Verifica se o clique ocorreu dentro de uma coluna de ação (para não interceptar botões)
            if (event.target.closest('.action-column')) {
                return; // Sai da função se o clique foi nos botões/links
            }
            
            // 2. Obtém o ID do associado armazenado no atributo data-id
            const associadoId = this.getAttribute('data-id');
            
            if (associadoId) {
                // 3. Constrói a URL de destino (Rota que lista as cobranças)
                const url = '/associados/' + associadoId + '/cobrancas';
                
                // 4. Redireciona o navegador
                window.location.href = url;
            }
        });
    });
});

function formatCPF(cpf) {
    cpf = cpf.replace(/\D/g, "");
    if (cpf.length !== 11) return cpf;
    return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
}

// Formata todos os <td class="cpf">
document.querySelectorAll('.cpf').forEach(td => {
    td.textContent = formatCPF(td.textContent);
});
