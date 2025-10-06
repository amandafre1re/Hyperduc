document.addEventListener("DOMContentLoaded", async () => {
  const id = new URLSearchParams(window.location.search).get('id');
  const card = document.getElementById("projetoCard");
  if (!id) {
    card.innerHTML = '<div class="alert alert-danger">Projeto não encontrado.</div>';
    return;
  }
  try {
    const resp = await fetch(`../app/projeto/ler_projeto.php?id=${id}`);
    const projeto = await resp.json();
    if (!projeto) {
      card.innerHTML = '<div class="alert alert-danger">Projeto não encontrado.</div>';
      return;
    }
    let nomeUsuario = projeto.nome_usuario ? projeto.nome_usuario : 'Desconhecido';
    card.innerHTML = `
      <div class="mb-3 projeto-titulo">${projeto.nm_projeto}</div>
      <div class="projeto-desc">${projeto.desc_projeto}</div>
      <div class="projeto-meta">
        <span>Criado por: ${nomeUsuario}</span><br>
        Criado em: ${new Date(projeto.data_criacao).toLocaleDateString()}
      </div>
    `;
  } catch (e) {
    card.innerHTML = '<div class="alert alert-danger">Erro ao carregar projeto.</div>';
  }
});
