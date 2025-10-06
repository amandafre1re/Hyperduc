function criarAtividade({id_projeto, id_atv, data_comeco, data_termino, estado}) {
  const fd = new FormData();
  fd.append('id_projeto', id_projeto);
  fd.append('id_atv', id_atv);
  fd.append('data_comeco', data_comeco);
  fd.append('data_termino', data_termino);
  fd.append('estado', estado);
  return fetch('../app/atividade_usuario/criar_atividade.php', { method: 'POST', body: fd })
    .then(resp => resp.json());
}

function showFormCriarAtividade(id_projeto, onSuccess) {
  if (document.getElementById('formNovaAtividade')) return;
  const formDiv = document.createElement('div');
  formDiv.className = 'd-flex justify-content-center align-items-center position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50';
  formDiv.id = 'formNovaAtividade';
  formDiv.innerHTML = `
    <div class="bg-white p-4 rounded shadow" style="min-width:340px;max-width:420px;width:100%">
      <form>
        <h5 class="mb-3 text-center">Associar Atividade ao Usuário</h5>
        <div class="mb-3">
          <label class="form-label">Atividade</label>
          <select class="form-select" name="id_atv" required id="selectAtividade"></select>
        </div>
        <div class="mb-3">
          <label class="form-label">Início</label>
          <input type="date" class="form-control" name="data_comeco" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Término</label>
          <input type="date" class="form-control" name="data_termino" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Estado</label>
          <select class="form-select" name="estado">
            <option value="A Fazer">A Fazer</option>
            <option value="Em Progresso">Em Progresso</option>
            <option value="Concluído">Concluído</option>
          </select>
        </div>
        <div class="d-flex gap-2 justify-content-end">
          <button type="button" class="btn btn-secondary" id="btnCancelarNovaAtividade">Cancelar</button>
          <button type="submit" class="btn btn-success">Associar</button>
        </div>
      </form>
    </div>
  `;

//Lisntando as atividades
  fetch('../app/atividade/listar_tipos_atividade.php')
    .then(resp => resp.json())
    .then(atividades => {
      const select = formDiv.querySelector('#selectAtividade');
      select.innerHTML = '<option value="">Selecione...</option>' +
        atividades.map(a => `<option value="${a.idAtividade}">${a.nm_atividade}</option>`).join('');
    });
  formDiv.querySelector('form').onsubmit = async e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    fd.append('id_projeto', id_projeto);
    const result = await criarAtividade(Object.fromEntries(fd.entries()));
    if (result.codigo) {
      formDiv.remove();
      if (typeof onSuccess === 'function') onSuccess();
    } else {
      alert(result.msg || 'Erro ao associar atividade.');
    }
  };
  formDiv.querySelector('#btnCancelarNovaAtividade').onclick = () => formDiv.remove();
  document.body.appendChild(formDiv);
  // Carregar opções de atividades
  fetch('../app/atividade/listar_tipos_atividade.php')
    .then(r => r.json())
    .then(lista => {
      const select = formDiv.querySelector('#selectAtividade');
      select.innerHTML = lista.map(a => `<option value="${a.idAtividade}">${a.nm_atividade}</option>`).join('');
    });
}

window.showFormCriarAtividade = showFormCriarAtividade;
window.criarAtividade = criarAtividade;
