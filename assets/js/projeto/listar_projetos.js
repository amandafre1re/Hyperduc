document.addEventListener("DOMContentLoaded", async () => {
  const carouselInner = document.getElementById("carouselProjetosInner");
  try {
    const resp = await fetch("app/projeto/listar_projetos.php");
    const projetos = await resp.json();
    if (projetos.length === 0) {
      carouselInner.innerHTML = '<div class="alert alert-info">Nenhum projeto encontrado.</div>';
      return;
    }
    // carrossel 
    let slides = [];
    for (let i = 0; i < projetos.length; i += 3) {
      slides.push(projetos.slice(i, i + 3));
    }
    let html = '';
    slides.forEach((grupo, idx) => {
      html += `<div class="carousel-item${idx === 0 ? ' active' : ''}">
        <div class="row row-cols-1 row-cols-md-3 g-4">`;
      grupo.forEach(p => {
        html += `
          <div class="col">
            <div class="card h-100 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">${p.nm_projeto}</h5>
                <p class="card-text">${p.desc_projeto}</p>
                <p class="card-text"><small class="text-muted">Criado por: ${p.nome_usuario}</small></p>
                <div class="d-flex gap-2 mt-3">
                  <button class="btn btn-primary btn-sm" onclick="window.location.href='registro/ver_projeto.html?id=${p.id_projeto}'">Saiba mais</button>
                </div>
              </div>
              <div class="card-footer text-muted small">
                Criado em: ${new Date(p.data_criacao).toLocaleDateString()}
              </div>
            </div>
          </div>
        `;
      });
      html += '</div></div>';
    });
    carouselInner.innerHTML = html;
  } catch (e) {
    carouselInner.innerHTML = '<div class="alert alert-danger">Erro ao carregar projetos.</div>';
  }
});
