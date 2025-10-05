document.excluirProjeto = async function(id) {
  if (!confirm('Tem certeza que deseja excluir este projeto?')) return;
  const fd = new FormData();
  fd.append('id_projeto', id);
  const resp = await fetch('../app/projeto/apaga_projeto.php', {
    method: 'POST',
    body: fd
  });
  const dados = await resp.json();
  if (dados.codigo) {
    location.reload();
  } else {
    alert(dados.msg || 'Erro ao excluir projeto.');
  }
}
