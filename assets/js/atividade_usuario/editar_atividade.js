function editarAtividade({id_atv_usuario, id_projeto, id_atv, data_comeco, data_termino, estado}) {
  const fd = new FormData();
  fd.append('id_atv_usuario', id_atv_usuario);
  fd.append('id_projeto', id_projeto);
  fd.append('id_atv', id_atv);
  fd.append('data_comeco', data_comeco);
  fd.append('data_termino', data_termino);
  fd.append('estado', estado);
  return fetch('../app/atividade_usuario/editar_atividade.php', { method: 'POST', body: fd })
    .then(resp => resp.json());
}

function showFormEditarAtividade(id_projeto, atividade, onSuccess) {
  if (document.getElementById('formEditarAtividade')) return;
  const formDiv = document.createElement('div');
  formDiv.className = 'd-flex justify-content-center align-items-center position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50';
  formDiv.id = 'formEditarAtividade';
  formDiv.innerHTML = `
    <div class="bg-white p-4 rounded shadow" style="min-width:340px;max-width:420px;width:100%">
      <form>
        <h5 class="mb-3 text-center">Editar Associação de Atividade</h5>
        <input type="hidden" name="id_atv_usuario" value="${atividade.id_atv_usuario}">
        <div class="mb-3">
          <label class="form-label">Atividade</label>
          <select class="form-select" name="id_atv" required id="selectAtividadeEdit"></select>
        </div>
        <div class="mb-3">
          <label class="form-label">Início</label>
          <input type="date" class="form-control" name="data_comeco" required value="${atividade.data_comeco}">
        </div>
        <div class="mb-3">
          <label class="form-label">Término</label>
          <input type="date" class="form-control" name="data_termino" required value="${atividade.data_termino}">
        </div>
        <div class="mb-3">
          <label class="form-label">Estado</label>
          <select class="form-select" name="estado">
            <option value="A Fazer" ${atividade.estado === 'A Fazer' ? 'selected' : ''}>A Fazer</option>
            <option value="Em Progresso" ${atividade.estado === 'Em Progresso' ? 'selected' : ''}>Em Progresso</option>
            <option value="Concluído" ${atividade.estado === 'Concluído' ? 'selected' : ''}>Concluído</option>
          </select>
        </div>
        <div class="d-flex gap-2 justify-content-end">
          <button type="button" class="btn btn-secondary" id="btnCancelarEditarAtividade">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  `;
  // Preencher select de atividades
  fetch('../app/atividade/listar_tipos_atividade.php')
    .then(r => r.json())
    .then(lista => {
      const select = formDiv.querySelector('#selectAtividadeEdit');
      select.innerHTML = lista.map(a => `<option value="${a.idAtividade}" ${a.idAtividade == atividade.id_atv ? 'selected' : ''}>${a.nm_atividade}</option>`).join('');
    });
  formDiv.querySelector('form').onsubmit = async e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    fd.append('id_projeto', id_projeto);
    const result = await editarAtividade(Object.fromEntries(fd.entries()));
    if (result.codigo) {
      formDiv.remove();
      if (typeof onSuccess === 'function') onSuccess();
    } else {
      alert(result.msg || 'Erro ao editar associação.');
    }
  };
  formDiv.querySelector('#btnCancelarEditarAtividade').onclick = () => formDiv.remove();
  document.body.appendChild(formDiv);
}

window.showFormEditarAtividade = showFormEditarAtividade;
window.editarAtividade = editarAtividade;
