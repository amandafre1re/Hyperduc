document.getElementById('btnLogin').addEventListener('click', async () => {
  const fd = new FormData();
  fd.append('email', document.getElementById('email').value);
  fd.append('senha', document.getElementById('senha').value);

  const resposta = await fetch('../app/login.php', {
    method: 'POST',
    body: fd
  });

  const dados = await resposta.json();

  if (dados.codigo) {
    window.location.href = '../index.html';
  } else {
    document.getElementById('erro').innerHTML = `
      <div class="alert alert-danger">${dados.msg}</div>
    `;
  }
});
