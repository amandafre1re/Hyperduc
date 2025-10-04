document.getElementById("btnNovoUsuario").addEventListener('click', function() {
    usuario_novo();
});

async function usuario_novo() {
    const fd = new FormData();
    fd.append('nome_usuario', document.getElementById("nome_usuario").value);
    fd.append('email_usuario', document.getElementById("email_usuario").value);
    fd.append('senha_usuario', document.getElementById("senha_usuario").value);
    fd.append('data_nasc', document.getElementById("data_nasc").value);
    fd.append('cidade', document.getElementById("cidade").value);
    fd.append('uf', document.getElementById("uf").value);
    fd.append('pais', document.getElementById("pais").value);
 
    const retorno = await fetch('../app/usuario/novo_usuario.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();
    let msg = "";
    if (resposta.codigo) {
        msg = "<span class='alert alert-success d-block'>" + resposta.msg + "</span>";
    } else {
        msg = "<span class='alert alert-danger d-block'>" + resposta.msg + "</span>";
    }
    document.getElementById("alerta").innerHTML = msg;
}