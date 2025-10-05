document.getElementById("btnExcluirConta").addEventListener("click", async () => {
  const confirmacao = confirm("Tem certeza que deseja excluir sua conta? Todos os projetos serão excluídos também. Esta ação é irreversível.");
  if (!confirmacao) return;

  const resposta = await fetch("../app/usuario/apaga_usuario.php", {
    method: "POST"
  });

  const dados = await resposta.json();

  if (dados.codigo) {
    alert("Conta excluída com sucesso.");
    window.location.href = "../login/index.html";
  } else {
    alert("Erro ao excluir a conta: " + dados.msg);
  }
});
