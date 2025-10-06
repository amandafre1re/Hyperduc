
function listarAtividades(id_projeto) {
  return fetch(`../app/atividade_usuario/listar_atividades.php?id_projeto=${id_projeto}`, { credentials: 'same-origin' })
    .then(resp => resp.json());
}

function renderKanbanAtividades(id_projeto) {
  listarAtividades(id_projeto).then(atividades => {
    const colunas = { 'A Fazer': [], 'Em Progresso': [], 'Concluído': [] };
    atividades.forEach(a => {
      if (colunas[a.estado]) colunas[a.estado].push(a);
    });
    function renderAtividade(a) {
      // passar onSuccess para atualizar o kanban em tempo real
      return `<div class="card mb-2 shadow-sm">
        <div class="card-body p-2">
          <div class="fw-bold">${a.nm_atividade}</div>
          <div class="small text-muted">${a.data_comeco} até ${a.data_termino}</div>
          <div class="d-flex gap-1 mt-2">
            <button class="btn btn-sm btn-outline-primary" onclick='window.showFormEditarAtividade && window.showFormEditarAtividade("${a.id_projeto}", {id_atv_usuario: ${a.id_atv_usuario}}, () => window.renderKanbanAtividades("${a.id_projeto}"))'>Editar</button>
            <button class="btn btn-sm btn-outline-danger" onclick='window.excluirAtividade && window.excluirAtividade({id_atv_usuario: ${a.id_atv_usuario}}, () => window.renderKanbanAtividades("${a.id_projeto}"))'>Excluir</button>
          </div>
        </div>
      </div>`;
    }
    document.getElementById('kanban-todo').innerHTML = colunas['A Fazer'].map(renderAtividade).join('');
    document.getElementById('kanban-doing').innerHTML = colunas['Em Progresso'].map(renderAtividade).join('');
    document.getElementById('kanban-done').innerHTML = colunas['Concluído'].map(renderAtividade).join('');
  });
}

window.listarAtividades = listarAtividades;
window.renderKanbanAtividades = renderKanbanAtividades;
