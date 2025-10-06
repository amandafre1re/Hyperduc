document.addEventListener("DOMContentLoaded", () => {
  const kanban = document.getElementById("kanbanProjeto");
  const id = new URLSearchParams(window.location.search).get('id');
  if (!id) {
    kanban.innerHTML = '<div class="alert alert-danger">Projeto não encontrado.</div>';
    return;
  }
  kanban.innerHTML = `
    <div class="row g-3">
      <div class="col-md-4">
        <div class="kanban-col bg-light p-2 rounded">
          <h5 class="text-center">A Fazer</h5>
          <div id="kanban-todo" class="kanban-list min-vh-25"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="kanban-col bg-light p-2 rounded">
          <h5 class="text-center">Em Progresso</h5>
          <div id="kanban-doing" class="kanban-list min-vh-25"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="kanban-col bg-light p-2 rounded">
          <h5 class="text-center">Concluído</h5>
          <div id="kanban-done" class="kanban-list min-vh-25"></div>
        </div>
      </div>
    </div>
    <div class="mt-4 text-center">
      <button class="btn btn-success" id="btnNovaAtividade"><i class="bi bi-plus-lg"></i> Nova Atividade</button>
    </div>
  `;

  // Funções globais para os botões dos cards
  window.editarAtividade = function(idAtividade) {
    listarAtividades(id).then(atividades => {
      const atividade = atividades.find(a => a.atividade_idAtividade == idAtividade);
      if (!atividade) return alert('Atividade não encontrada.');
      showFormEditarAtividade(id, atividade, () => renderKanbanAtividades(id));
    });
  };
  window.excluirAtividade = function(idAtividade) {
    excluirAtividade(id, idAtividade, () => renderKanbanAtividades(id));
  };

  // Botão Nova Atividade
  setTimeout(() => {
    const btnNova = document.getElementById('btnNovaAtividade');
    if (btnNova && id) {
      btnNova.onclick = () => showFormCriarAtividade(id, () => renderKanbanAtividades(id));
    }
  }, 100);

  // Renderizar Kanban ao carregar
  renderKanbanAtividades(id);
});

