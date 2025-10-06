document.addEventListener("DOMContentLoaded", async () => {
  const id = new URLSearchParams(window.location.search).get('id');
  const alerta = document.getElementById("alerta");
  if (!id) {
    alerta.innerHTML = '<div class="alert alert-danger">Projeto não encontrado.</div>';
    return;
  }
  try {
    const resp = await fetch(`../app/projeto/ler_projeto.php?id=${id}`);
    const projeto = await resp.json();
    if (!projeto) {
      alerta.innerHTML = '<div class="alert alert-danger">Projeto não encontrado.</div>';
      return;
    }
    document.getElementById("nm_projeto").value = projeto.nm_projeto;
    document.getElementById("desc_projeto").value = projeto.desc_projeto;
  } catch (e) {
    alerta.innerHTML = '<div class="alert alert-danger">Erro ao carregar projeto.</div>';
  }
});
