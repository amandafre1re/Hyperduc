document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("btnAtualizarConta").addEventListener('click', function () {
        atualizar_perfil();
    });
});

async function atualizar_perfil() {
    const fd = new FormData();
    fd.append('nome_usuario', document.getElementById("nome_usuario").value);
    fd.append('email_usuario', document.getElementById("email_usuario").value);
    fd.append('cidade', document.getElementById("cidade").value);
    fd.append('uf', document.getElementById("uf").value);
    fd.append('pais', document.getElementById("pais").value);

    const retorno = await fetch('../app/usuario/usuario_update.php', {
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
