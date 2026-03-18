// Validación de formulario de registro
function validarRegistro(){

    let correo = document.querySelector("[name='correo']").value;
    let cedula = document.querySelector("[name='cedula']").value;

    if(correo == "" || cedula == ""){
        alert("Campos obligatorios");
        return false;
    }

    if(!correo.includes("@")){
        alert("Correo inválido");
        return false;
    }

    return true;
}