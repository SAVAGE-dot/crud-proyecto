const formularios_ajax=document.querySelectorAll(".FormularioAjax");

formularios_ajax.forEach(formularios => {
    formularios.addEventListener("submit", function(e){
        e.preventDefault();

        Swal.fire({
            title: "Estas Seguro?",
            text: "Quieres realizar la accion solicitada!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, realizar",
            cancelButtonText: "No, cancelar"
          }).then((result) => {
            if (result.isConfirmed) {
            }
        });
    });

});