function deletarAtividade(id_atv_usuario) {
  const fd = new FormData();
  fd.append('id_atv_usuario', id_atv_usuario);
  return fetch('../app/atividade_usuario/deletar_atividade.php', { method: 'POST', body: fd, credentials: 'same-origin' })
    .then(async resp => {
      let data;
      try { data = await resp.json(); } catch (e) { data = { codigo: false, msg: 'Resposta inválida do servidor' }; }
      return { status: resp.status, ok: resp.ok, body: data };
    })
    .catch(err => ({ status: 0, ok: false, body: { codigo: false, msg: 'Erro de rede: ' + err.message } }));
}

function excluirAtividade(atividadeOrId, onSuccess) {
  if (!confirm('Deseja remover esta associação de atividade?')) return;
  const id = typeof atividadeOrId === 'number' ? atividadeOrId : (atividadeOrId && (atividadeOrId.id_atv_usuario || atividadeOrId.idAtvUsuario || atividadeOrId.id_atv)) ? (atividadeOrId.id_atv_usuario || atividadeOrId.idAtvUsuario || atividadeOrId.id_atv) : null;
  if (!id) return alert('ID da associação inválido para exclusão.');
  console.log('Excluindo associação id_atv_usuario=', id);
  deletarAtividade(id).then(result => {
    console.log('Resposta deletarAtividade:', result);
    const body = result.body || {};
    if (result.ok && body.codigo) {
      if (typeof onSuccess === 'function') onSuccess();
    } else {
      alert(body.msg || 'Erro ao remover associação.');
    }
  });
}
window.deletarAtividade = deletarAtividade;
window.excluirAtividade = excluirAtividade;
