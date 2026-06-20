document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search_ip"]');
    if (!searchInput) return;
    searchInput.addEventListener('input', function() {
        const filter = this.value.trim().toLowerCase();
        document.querySelectorAll('table.apresentacao').forEach(table => {
            let anyVisible = false;
            table.querySelectorAll('tbody tr').forEach(row => {
                // Verifica se alguma célula da linha contém o filtro
                const match = Array.from(row.querySelectorAll('td')).some(td =>
                    td.textContent.trim().toLowerCase().includes(filter)
                );
                if (filter === "" || match) {
                    row.style.display = "";
                    anyVisible = true;
                } else {
                    row.style.display = "none";
                }
            });
            // Mostra ou esconde a tabela e o título acima dela
            const title = table.previousElementSibling;
            if (anyVisible) {
                table.style.display = "";
                if (title && title.tagName.startsWith('H')) title.style.display = "";
            } else {
                table.style.display = "none";
                if (title && title.tagName.startsWith('H')) title.style.display = "none";
            }
        });
    });
});