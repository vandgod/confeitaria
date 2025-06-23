// Adicione esta função para filtrar por categoria no painel
function filtrarPorCategoria(categoria) {
    const linhas = document.querySelectorAll('.tabela-pedidos tbody tr');
    
    linhas.forEach(linha => {
        if(categoria === 'todos' || linha.dataset.categoria === categoria) {
            linha.style.display = '';
        } else {
            linha.style.display = 'none';
        }
    });
}

// Atualize o event listener para os botões de filtro
document.querySelectorAll('.filtros-pedidos .btn-doce').forEach(btn => {
    btn.addEventListener('click', function() {
        // Remove classe ativa de todos
        document.querySelectorAll('.filtros-pedidos .btn-doce').forEach(b => {
            b.classList.remove('filtro-ativo');
        });
        
        // Adiciona classe ativa no clicado
        this.classList.add('filtro-ativo');
        
        // Verifica tipo de filtro
        if(this.hasAttribute('data-status')) {
            filtrarPedidos(this.dataset.status);
        } else if(this.hasAttribute('data-categoria')) {
            filtrarPorCategoria(this.dataset.categoria);
        }
    });
});