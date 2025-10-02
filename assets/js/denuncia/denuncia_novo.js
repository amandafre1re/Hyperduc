document.getElementById("btnNovoCliente").addEventListener( 
    'click', function(){
        cliente_novo();
    }
);

async function cliente_novo() {
    const fd = new FormData();
    fd.append('descricao',document.getElementById("descricao").value);
    fd.append('id1',document.getElementById("id1").value);
    fd.append('id2',document.getElementById("id2").value);
    fd.append('status',document.getElementById("status").value);


    // do retorno vem uma promise
     const retorno = await fetch('../app/denuncia_novo.php',
     {
        method: 'POST',
        body: fd
     });
const resposta = await retorno.json();
var msg = "";
if (resposta.codigo){
     msg = "<span class = 'alert alert-sucess'>"+resposta.msg;"</span>";
}else{
    msg = "<span class = 'alert alert-danger'>"+resposta.msg;"</span>";
}
document.getElementById("alerta").innerHTML = msg;
    } ;
