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

async function showFormEditarAtividade(id_projeto, atividade, onSuccess) {
  if (document.getElementById('formEditarAtividade')) return;
  console.log('showFormEditarAtividade chamado com id_projeto=', id_projeto, 'atividade=', atividade);

  let assoc = atividade || {};
  const id_atv_usuario = (atividade && (atividade.id_atv_usuario || atividade.idAtvUsuario || atividade.id_atv)) ? (atividade.id_atv_usuario || atividade.idAtvUsuario || atividade.id_atv) : null;
  if (id_atv_usuario && (!atividade.data_comeco && !atividade.data_termino && !atividade.id_atv)) {
    try {
  const r = await fetch(`../app/atividade_usuario/ler_atividade_usuario.php?id_atv_usuario=${id_atv_usuario}`, { credentials: 'same-origin' });
      assoc = await r.json();
      if (!assoc || !assoc.id_atv_usuario) {
        alert('Associação não encontrada.');
        return;
      }
    } catch (err) {
      console.error('Erro ao buscar associação:', err);
      alert('Erro ao buscar dados da associação. Veja console.');
      return;
    }
  }

  function formatDate(dateStr) {
    if (!dateStr) return '';
    if (typeof dateStr !== 'string') return '';
    if (dateStr.indexOf(' ') !== -1) return dateStr.split(' ')[0];
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return dateStr;
    const d = new Date(dateStr);
    if (isNaN(d)) return '';
    return d.toISOString().slice(0, 10);
  }

  const formDiv = document.createElement('div');
  formDiv.className = 'd-flex justify-content-center align-items-center position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50';
  formDiv.id = 'formEditarAtividade';
  formDiv.innerHTML = `
    <div class="bg-white p-4 rounded shadow" style="min-width:340px;max-width:420px;width:100%">
      <form>
        <h5 class="mb-3 text-center">Editar Associação de Atividade</h5>
        <input type="hidden" name="id_atv_usuario" value="${assoc.id_atv_usuario || ''}">
        <div class="mb-3">
          <label class="form-label">Atividade</label>
          <select class="form-select" name="id_atv" required id="selectAtividadeEdit"></select>
        </div>
        <div class="mb-3">
          <label class="form-label">Início</label>
          <input type="date" class="form-control" name="data_comeco" required value="${formatDate(assoc.data_comeco)}">
        </div>
        <div class="mb-3">
          <label class="form-label">Término</label>
          <input type="date" class="form-control" name="data_termino" required value="${formatDate(assoc.data_termino)}">
        </div>
        <div class="mb-3">
          <label class="form-label">Estado</label>
          <select class="form-select" name="estado">
            <option value="A Fazer" ${assoc.estado === 'A Fazer' ? 'selected' : ''}>A Fazer</option>
            <option value="Em Progresso" ${assoc.estado === 'Em Progresso' ? 'selected' : ''}>Em Progresso</option>
            <option value="Concluído" ${assoc.estado === 'Concluído' ? 'selected' : ''}>Concluído</option>
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
  fetch('../app/atividade/listar_tipos_atividade.php', { credentials: 'same-origin' })
    .then(r => r.json())
    .then(lista => {
      const select = formDiv.querySelector('#selectAtividadeEdit');
      select.innerHTML = lista.map(a => `<option value="${a.idAtividade}" ${a.idAtividade == assoc.id_atv ? 'selected' : ''}>${a.nm_atividade}</option>`).join('');
    }).catch(err => console.error('Erro ao listar tipos de atividade:', err));

  formDiv.querySelector('form').onsubmit = async e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    fd.append('id_projeto', id_projeto);
    const data = Object.fromEntries(fd.entries());
    if (data.id_atv) data.id_atv = parseInt(data.id_atv, 10);
    if (data.id_atv_usuario) data.id_atv_usuario = parseInt(data.id_atv_usuario, 10);
    console.log('Enviando editarAtividade com', data);
    try {
      const result = await editarAtividade(data);
      console.log('Resposta editarAtividade:', result);
      if (result && result.codigo) {
        formDiv.remove();
        if (typeof onSuccess === 'function') onSuccess();
      } else {
        alert((result && result.msg) || 'Erro ao editar associação.');
      }
    } catch (err) {
      console.error('Erro ao chamar editarAtividade:', err);
      alert('Erro inesperado ao editar associação. Veja console.');
    }
  };

  formDiv.querySelector('#btnCancelarEditarAtividade').onclick = () => formDiv.remove();
  document.body.appendChild(formDiv);
}

window.showFormEditarAtividade = showFormEditarAtividade;
window.editarAtividade = editarAtividade;
