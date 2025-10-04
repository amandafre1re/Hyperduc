document.addEventListener("DOMContentLoaded", async () => {
  const resposta = await fetch('../app/usuario/usuario_ler.php');
  const dados = await resposta.json();

  if (dados.codigo) {
    const user = dados.usuario;
    document.getElementById('nome_usuario').value = user.nome_usuario;
    document.getElementById('email_usuario').value = user.email_usuario;
    document.getElementById('cidade').value = user.cidade;
    document.getElementById('uf').value = user.uf;
    document.getElementById('pais').value = user.pais;
  } else {
    document.getElementById('alerta').innerHTML =
      `<div class="alert alert-danger">${dados.msg}</div>`;
  }
});
