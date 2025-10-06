document.getElementById("btnExcluirProjeto").addEventListener("click", async () => {
  const id = new URLSearchParams(window.location.search).get('id');
  if (!confirm('Tem certeza que deseja excluir este projeto?')) return;
  const fd = new FormData();
  fd.append('id_projeto', id);
  const resp = await fetch('../app/projeto/apaga_projeto.php', {
    method: 'POST',
    body: fd
  });
  const dados = await resp.json();
  let msg = "";
  if (dados.codigo) {
    msg = `<div class='alert alert-success'>${dados.msg}</div>`;
    setTimeout(() => { window.location.href = 'meus_projetos.html'; }, 1200);
  } else {
    msg = `<div class='alert alert-danger'>${dados.msg}</div>`;
  }
  document.getElementById("alerta").innerHTML = msg;
});


