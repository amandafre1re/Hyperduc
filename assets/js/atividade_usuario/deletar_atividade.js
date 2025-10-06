function deletarAtividade({id_atv_usuario}) {
  const fd = new FormData();
  fd.append('id_atv_usuario', id_atv_usuario);
  return fetch('../app/atividade_usuario/deletar_atividade.php', { method: 'POST', body: fd })
    .then(resp => resp.json());
}

function excluirAtividade(atividade, onSuccess) {
  if (!confirm('Deseja remover esta associação de atividade?')) return;
  deletarAtividade({id_atv_usuario: atividade.id_atv_usuario}).then(result => {
    if (result.codigo) {
      if (typeof onSuccess === 'function') onSuccess();
    } else {
      alert(result.msg || 'Erro ao remover associação.');
    }
  });
}
window.deletarAtividade = deletarAtividade;
window.excluirAtividade = excluirAtividade;
