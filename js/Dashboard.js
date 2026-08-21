function cargarModulo(url) {

    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById("panel-trabajo").innerHTML = html;
        })
        .catch(error => {
            document.getElementById("panel-trabajo").innerHTML =
                "<div class='alert alert-danger'>Error al cargar el módulo.</div>";
            console.error(error);
        });

}