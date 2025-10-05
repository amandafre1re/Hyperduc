document.getElementById("btnSalvarProjeto").addEventListener("click", async () => {
  const id = new URLSearchParams(window.location.search).get('id');
  const fd = new FormData();
  fd.append("id_projeto", id);
  fd.append("nm_projeto", document.getElementById("nm_projeto").value);
  fd.append("desc_projeto", document.getElementById("desc_projeto").value);

  const resp = await fetch('../app/projeto/altera_projeto.php', {
    method: 'POST',
    body: fd
  });

  const dados = await resp.json();

  let msg = "";
  if (dados.codigo) {
    msg = `<div class='alert alert-success'>${dados.msg}</div>`;
  } else {
    msg = `<div class='alert alert-danger'>${dados.msg}</div>`;
  }
  document.getElementById("alerta").innerHTML = msg;
});
