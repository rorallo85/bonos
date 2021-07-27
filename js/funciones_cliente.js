window.onload = function() {
   
    /**
     * funcion que elimina un cliente de la base de datos y de la tabla de clientes
     */
    var eliminarCliente = function() {
        var id_cliente = this.getAttribute("id_cliente"); // Se recupera el id_cliente del boton btnEliminarModal
        var xhr1 = new XMLHttpRequest();
        xhr1.open('POST', "index.php", true);
        xhr1.onreadystatechange = function() {
            if (this.status == 200 && this.readyState == 4) {
                datosRespuesta = this.responseText;
                console.log(datosRespuesta);
                var respuesta = JSON.parse(datosRespuesta)
                if (respuesta.OK  == id_cliente) {
                    var fila = document.getElementById('Cliente'+id_cliente);
                    var padre  = fila.parentNode;
                    padre.removeChild(fila); // Se elimina la fila de la tabla
                    btnEliminarModal.removeAttribute("id_cliente"); // Se elimina el atributo id_cliente del boton
                }
                                
            };
        
        };

        var data = new FormData();
        data.append('eliminar_cliente', 'eliminar_cliente');
        data.append('id_cliente', id_cliente);
        xhr1.send(data);
    } 

    btnEliminarModal.addEventListener('click', eliminarCliente, false);


    /**
     * funcion que agrega el atributo id_cliente al boton btnEliminarModal
     *  desde el elemento btnEliminarCliente de la tabla clientes(icono cubo de basura)
     */
    var pasar_cliente = function(event){
        event.preventDefault();
        var id_attr = this.getAttribute("id_cliente");
        btnEliminarModal.setAttribute("id_cliente", id_attr);
        var fila = document.getElementById('Cliente' + id_attr);
        span_eliminar.textContent = fila.children[0].textContent + " " + fila.children[1].textContent + "?";
    }
    
    var btsConfirmacionEliminar = document.getElementsByClassName('btnEliminarCliente');
    Array.from(btsConfirmacionEliminar).forEach(element => {
        element.addEventListener('click', pasar_cliente, false);
    });


    /**
     * funcion que agrega el atributo id_cliente al boton btnGuardarModalCliente
     *  desde el elemento btsModificar de la tabla clientes(icono lapiz y papel)
     */
    var modificar = function(event) {
        event.preventDefault();
        var id_attr = this.getAttribute('id_cliente');
        var fila = document.getElementById('Cliente' + id_attr);
        for(let i=0; i<formGuardarCliente.elements.length; i++){
            formGuardarCliente.elements[i].value = fila.children[i].textContent;
        }
        btnGuardarModalCliente.setAttribute("id_cliente", id_attr);
    }
   
    var btsModificar = document.getElementsByClassName('btnModificarCliente');
    Array.from(btsModificar).forEach(element => {
        element.addEventListener('click', modificar, false);
    });
    

    /**
     * funcion que guarda los datos del cliente en la base de datos y agrega o modifica
     *  la fila en la tabla clientes
     */
    var guardar = function() {
        var xhr1 = new XMLHttpRequest();
        xhr1.open('POST', "index.php", true);
        xhr1.onreadystatechange = function() {
                if (this.status == 200 && this.readyState == 4) {
                    datosRespuesta = this.responseText;
                    console.log(datosRespuesta);
                    var respuesta = JSON.parse(datosRespuesta);
                    if (respuesta.OK != null ) {
                        // Si el atributo id_cliente del btnGuardarModalCliente != null, es una modificacion
                        if(btnGuardarModalCliente.getAttribute('id_cliente') != null){
                            // Se modifica la fila del cliente y se quita el atributo del boton btnGuardarModalCliente
                            var linea = document.getElementById('Cliente' + respuesta.OK.id_cliente);
                            linea.outerHTML = respuesta.html;
                            btnGuardarModalCliente.removeAttribute('id_cliente');
                                                       
                        }else{
                            // Si no se cumple la condicion es un nuevo cliente y se crea su fila en la tabla
                            var fila = document.createElement('tr');                        
                            TablaClienteBody.appendChild(fila);
                            fila.outerHTML = respuesta.html;
                                           
                        }

                        var btsConfirmacionEliminar = document.getElementsByClassName('btnEliminarCliente');
                        Array.from(btsConfirmacionEliminar).forEach(element => {
                            element.addEventListener('click', pasar_cliente, false);
                        });

                        var btsModificar = document.getElementsByClassName('btnModificarCliente');
                        Array.from(btsModificar).forEach(element => {
                            element.addEventListener('click', modificar, false);
                        });

                        var btsBonos = document.getElementsByClassName('btnBonos');
                        Array.from(btsBonos).forEach(element => {
                            element.addEventListener('click', mostrar_bonos, false);
                        });

                        limpiarForm(); // se resetea el formulario
                                          
                    }
                                   
                };
        
            };

        var data = new FormData(formGuardarCliente);
        data.append('guardar_cliente', 'guardar_cliente');
        // Si el boton tiene el atributo id_cliente se envia junto con el formulario
        if(btnGuardarModalCliente.getAttribute('id_cliente')){
            data.append('cliente[id_cliente]', btnGuardarModalCliente.getAttribute('id_cliente'));
        }
        xhr1.send(data);
    }

    btnGuardarModalCliente.addEventListener('click', guardar, false);


    /**
     * funcion que resetea el formulario y borra el id_cliente del boton btnGuardarModalCliente
     *  se ejecuta al cerrar el modal sin guardar(btnCerrarModalCliente)
     */
    var limpiarForm = function(){
        formGuardarCliente.reset();
        btnGuardarModalCliente.removeAttribute('id_cliente');
    }

    btnCerrarModalCliente.addEventListener("click", limpiarForm, false);   

    /**
     * funcion que busca un cliente en la base de datos y muestra los resultados encontrados
     * en la tabla de clientes
     */
     var buscarCliente = function() {
        var xhr1 = new XMLHttpRequest();
        xhr1.open('POST', "index.php", true);
        xhr1.onreadystatechange = function() {
                if (this.status == 200 && this.readyState == 4) {
                    datosRespuesta = this.responseText;
                    var respuesta = JSON.parse(datosRespuesta);
                    if (respuesta.OK != null ) {
                        while(TablaClienteBody.firstChild){
                            TablaClienteBody.removeChild(TablaClienteBody.firstChild);
                        }

                        var fila = document.createElement('tr');                        
                        TablaClienteBody.appendChild(fila);
                        fila.outerHTML = respuesta.html;
                        
                        var btsConfirmacionEliminar = document.getElementsByClassName('btnEliminarCliente');
                        Array.from(btsConfirmacionEliminar).forEach(element => {
                            element.addEventListener('click', pasar_cliente, false);
                        });

                        var btsModificar = document.getElementsByClassName('btnModificarCliente');
                        Array.from(btsModificar).forEach(element => {
                            element.addEventListener('click', modificar, false);
                        });

                        var btsBonos = document.getElementsByClassName('btnBonos');
                        Array.from(btsBonos).forEach(element => {
                            element.addEventListener('click', mostrar_bonos, false);
                        });

                        reiniciarBuscar.style.display = 'block';
                        reiniciarBuscar.addEventListener('click', borrarFiltros, false)
                                          
                    }
                                   
                };
        
            };

        var data = new FormData(formBuscarCliente);
        data.append('buscar_cliente', 'buscar_cliente');
        xhr1.send(data);
    }

    btnBuscar.addEventListener('click', buscarCliente, false);

    /**
     * funcion que busca un cliente en la base de datos y muestra los resultados encontrados
     * en la tabla de clientes
     */
     var borrarFiltros = function(event) {
        event.preventDefault();
        var xhr1 = new XMLHttpRequest();
        xhr1.open('POST', "index.php", true);
        xhr1.onreadystatechange = function() {
                if (this.status == 200 && this.readyState == 4) {
                    datosRespuesta = this.responseText;
                    console.log(datosRespuesta);
                    var respuesta = JSON.parse(datosRespuesta);
                    if (respuesta.OK != null ) {
                        while(TablaClienteBody.firstChild){
                            TablaClienteBody.removeChild(TablaClienteBody.firstChild);
                        }

                        var fila = document.createElement('tr');                        
                        TablaClienteBody.appendChild(fila);
                        fila.outerHTML = respuesta.html;
                        
                        var btsConfirmacionEliminar = document.getElementsByClassName('btnEliminarCliente');
                        Array.from(btsConfirmacionEliminar).forEach(element => {
                            element.addEventListener('click', pasar_cliente, false);
                        });

                        var btsModificar = document.getElementsByClassName('btnModificarCliente');
                        Array.from(btsModificar).forEach(element => {
                            element.addEventListener('click', modificar, false);
                        });

                        var btsBonos = document.getElementsByClassName('btnBonos');
                        Array.from(btsBonos).forEach(element => {
                            element.addEventListener('click', mostrar_bonos, false);
                        });

                        reiniciarBuscar.style.display = 'none';
                    }
                                   
                };
        
            };

        var data = new FormData();
        data.append('borrar_filtros', 'borrar_filtros');
        xhr1.send(data);
    }
    

    /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    /*++++++++++++++++++++++++++++++FUNCIONES SELLADO+++++++++++++++++++++++++++++++++++++++++++++*/
    /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

     /**
     * Funcion que muestra los bonos de un cliente
     * 
     */
      function mostrar_bonos(){
        var id_attr = this.getAttribute("id_cliente");
        var xhr1 = new XMLHttpRequest();
        xhr1.open('POST', "index.php", true);
        xhr1.onreadystatechange = function() {
                if (this.status == 200 && this.readyState == 4) {
                    datosRespuesta = this.responseText; 
                    console.log(datosRespuesta);
                    var respuesta = JSON.parse(datosRespuesta);
                    if (respuesta.OK != null ) {
                        cuerpoModalBonos.innerHTML = respuesta.html;

                        var btsSellar = document.getElementsByClassName('btnSellar');
                        Array.from(btsSellar).forEach(element => {
                            element.setAttribute("id_cliente", id_attr);
                            element.addEventListener('click', guardar_sesion, false);
                        });

                        var btsBorrar = document.getElementsByClassName('btnBorrar');
                        Array.from(btsBorrar).forEach(element => {
                            element.setAttribute("id_cliente", id_attr);
                            element.addEventListener('click', borrar_sesion, false);
                        });

                        anterior.addEventListener('click', bono_anterior, false);
                        siguiente.addEventListener('click', bono_siguiente, false);
  
                        btnNuevoBono.setAttribute("id_cliente", id_attr);
                        btnNuevoBono.addEventListener('click', crear_bono, false);

                        if(btsSellar.length > 0){
                            btnNuevoBono.disabled = true;
                        }else{
                            btnNuevoBono.disabled = false;
                        }
                                                                 
                    }
                                   
                };
        
        };

        var data = new FormData();
        data.append('mostrar_bonos', 'mostrar_bonos');
        data.append('id_cliente', id_attr);
        xhr1.send(data);
    }

    var btsBonos = document.getElementsByClassName('btnBonos');
    Array.from(btsBonos).forEach(element => {
        element.addEventListener('click', mostrar_bonos, false);
    });

    
    /**
     * Funcion que guarda una sesion en el bono de un cliente
     * 
     */
    function guardar_sesion(){
        var id_cliente = this.getAttribute('id_cliente');
        var id_bono = this.getAttribute('id_bono');
        var bono_actual = posicion.textContent;
        var xhr1 = new XMLHttpRequest();
        xhr1.open('POST', "index.php", true);
        xhr1.onreadystatechange = function() {
                if (this.status == 200 && this.readyState == 4) {
                    datosRespuesta = this.responseText;
                    console.log(datosRespuesta);
                    var respuesta = JSON.parse(datosRespuesta);
                    if (respuesta.OK != null ) {
                        cuerpoModalBonos.innerHTML = respuesta.html;
                        
                        var btsSellar = document.getElementsByClassName('btnSellar');
                        Array.from(btsSellar).forEach(element => {
                            element.setAttribute("id_cliente", id_cliente);
                            element.addEventListener('click', guardar_sesion, false);
                        });

                        var btsBorrar = document.getElementsByClassName('btnBorrar');
                        Array.from(btsBorrar).forEach(element => {
                            element.setAttribute("id_cliente", id_cliente);
                            element.addEventListener('click', borrar_sesion, false);
                        });

                        anterior.addEventListener('click', bono_anterior, false);
                        siguiente.addEventListener('click', bono_siguiente, false);

                        if(btsSellar.length > 0){
                            btnNuevoBono.disabled = true;
                        }else{
                            btnNuevoBono.disabled = false;
                        }
                    }
                                         
                };
        
        };

        var data = new FormData();
        data.append('guardar_sesion', 'guardar_sesion');
        data.append('id_cliente', id_cliente);
        data.append('id_bono', id_bono);
        data.append("sesion[id_bono]", id_bono);
        data.append("sesion[fecha]", new Date().toISOString().slice(0, 10));
        data.append('bono_actual', bono_actual);
        xhr1.send(data);
    }


    /**
     * Funcion que borra una sesion en el bono de un cliente
     * 
     */
    function borrar_sesion(){
        var id_sesion = this.getAttribute('id_sesion');
        var id_cliente = this.getAttribute('id_cliente');
        var bono_actual = posicion.textContent;
        var xhr1 = new XMLHttpRequest();
        xhr1.open('POST', "index.php", true);
        xhr1.onreadystatechange = function() {
                if (this.status == 200 && this.readyState == 4) {
                    datosRespuesta = this.responseText;
                    console.log(datosRespuesta);
                    var respuesta = JSON.parse(datosRespuesta);
                    if (respuesta.OK != null ) {
                        cuerpoModalBonos.innerHTML = respuesta.html;

                        var btsSellar = document.getElementsByClassName('btnSellar');
                        Array.from(btsSellar).forEach(element => {
                            element.setAttribute("id_cliente", id_cliente);
                            element.addEventListener('click', guardar_sesion, false);
                        });

                        var btsBorrar = document.getElementsByClassName('btnBorrar');
                        Array.from(btsBorrar).forEach(element => {
                            element.setAttribute("id_cliente", id_cliente);
                            element.addEventListener('click', borrar_sesion, false);
                        });

                        anterior.addEventListener('click', bono_anterior, false);
                        siguiente.addEventListener('click', bono_siguiente, false);

                        if(btsSellar.length > 0){
                            btnNuevoBono.disabled = true;
                        }else{
                            btnNuevoBono.disabled = false;
                        }
                                                                
                    }
                                   
                };
        
        };

        var data = new FormData();
        data.append('borrar_sesion', 'borrar_sesion');
        data.append('id_cliente', id_cliente);
        //data.append('id_bono', id_bono);
        data.append('id_sesion', id_sesion);
        data.append('bono_actual', bono_actual);
        xhr1.send(data);
    }


    /**
     * Funcion que crea un nuevo bono para un cliente
     * 
     */
    var crear_bono = function(){
        var id_cliente = this.getAttribute("id_cliente");
        var xhr1 = new XMLHttpRequest();
        xhr1.open('POST', "index.php", true);
        xhr1.onreadystatechange = function() {
                if (this.status == 200 && this.readyState == 4) {
                    datosRespuesta = this.responseText; 
                    console.log(datosRespuesta);
                    var respuesta = JSON.parse(datosRespuesta);
                    if (respuesta.OK != null ) {
                        cuerpoModalBonos.innerHTML = respuesta.html;
                        var btsSellar = document.getElementsByClassName('btnSellar');
                        
                        var btsSellar = document.getElementsByClassName('btnSellar');
                        Array.from(btsSellar).forEach(element => {
                            element.setAttribute("id_cliente", id_cliente);
                            element.addEventListener('click', guardar_sesion, false);
                        });

                        var btsBorrar = document.getElementsByClassName('btnBorrar');
                        Array.from(btsBorrar).forEach(element => {
                            element.setAttribute("id_cliente", id_cliente);
                            element.addEventListener('click', borrar_sesion, false);
                        });

                        anterior.addEventListener('click', bono_anterior, false);
                        siguiente.addEventListener('click', bono_siguiente, false);
                                                                 
                    }
                                   
                };
        
        };

        var data = new FormData();
        data.append('crear_bono', 'crear_bono');
        data.append("bono[id_cliente]", id_cliente);
        //Solucion temporal
        data.append("id_cliente", id_cliente);
        xhr1.send(data);
    }

    btnNuevoBono.addEventListener("click", crear_bono, false);


    /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    /*++++++++++++++++++++++++++++++FUNCIONES NAVEGACION BONOS++++++++++++++++++++++++++++++++++++*/
    /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    
    /**
     * Funcion que muestra el bono anterior al actual
     * 
     */
     function bono_siguiente(){
        var id_attr = btnNuevoBono.getAttribute("id_cliente");
        var bono_actual = posicion.textContent;
        var xhr1 = new XMLHttpRequest();
        xhr1.open('POST', "index.php", true);
        xhr1.onreadystatechange = function() {
                if (this.status == 200 && this.readyState == 4) {
                    datosRespuesta = this.responseText; 
                    console.log(datosRespuesta);
                    var respuesta = JSON.parse(datosRespuesta);
                    if (respuesta.OK != null ) {
                        cuerpoModalBonos.innerHTML = respuesta.html;

                        var btsSellar = document.getElementsByClassName('btnSellar');
                        Array.from(btsSellar).forEach(element => {
                            element.setAttribute("id_cliente", id_attr);
                            element.addEventListener('click', guardar_sesion, false);
                        });

                        var btsBorrar = document.getElementsByClassName('btnBorrar');
                        Array.from(btsBorrar).forEach(element => {
                            element.setAttribute("id_cliente", id_attr);
                            element.addEventListener('click', borrar_sesion, false);
                        });

                        anterior.addEventListener('click', bono_anterior, false);
                        siguiente.addEventListener('click', bono_siguiente, false);

                        btnNuevoBono.setAttribute("id_cliente", id_attr);
                        btnNuevoBono.addEventListener('click', crear_bono, false);
                        
                        if(btsSellar.length > 0){
                            btnNuevoBono.disabled = true;
                        }else{
                            btnNuevoBono.disabled = false;
                        }
                                                                 
                    }
                                   
                };
        
        };

        var data = new FormData();
        data.append('moverse_bonos', 'bono_siguiente');
        data.append('id_cliente', id_attr);
        data.append('bono_actual', bono_actual);
        xhr1.send(data);
    }

    /**
     * Funcion que muestra el bono siguiente al actual
     * 
     */
     function bono_anterior(){
        var id_attr = btnNuevoBono.getAttribute("id_cliente");
        var bono_actual = posicion.textContent;
        var xhr1 = new XMLHttpRequest();
        xhr1.open('POST', "index.php", true);
        xhr1.onreadystatechange = function() {
                if (this.status == 200 && this.readyState == 4) {
                    datosRespuesta = this.responseText; 
                    console.log(datosRespuesta);
                    var respuesta = JSON.parse(datosRespuesta);
                    if (respuesta.OK != null ) {
                        cuerpoModalBonos.innerHTML = respuesta.html;

                        var btsSellar = document.getElementsByClassName('btnSellar');
                        Array.from(btsSellar).forEach(element => {
                            element.setAttribute("id_cliente", id_attr);
                            element.addEventListener('click', guardar_sesion, false);
                        });

                        var btsBorrar = document.getElementsByClassName('btnBorrar');
                        Array.from(btsBorrar).forEach(element => {
                            element.setAttribute("id_cliente", id_attr);
                            element.addEventListener('click', borrar_sesion, false);
                        });

                        anterior.addEventListener('click', bono_anterior, false);
                        siguiente.addEventListener('click', bono_siguiente, false);

                        btnNuevoBono.setAttribute("id_cliente", id_attr);
                        btnNuevoBono.addEventListener('click', crear_bono, false);

                        if(btsSellar.length > 0){
                            btnNuevoBono.disabled = true;
                        }else{
                            btnNuevoBono.disabled = false;
                        }
                                                                 
                    }
                                   
                };
        
        };

        var data = new FormData();
        data.append('moverse_bonos', 'bono_anterior');
        data.append('id_cliente', id_attr);
        data.append('bono_actual', bono_actual);
        xhr1.send(data);
    }


    /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    /*++++++++++++++++++++++++++++++FUNCIONES IMPRIMIR+++++++++++++++++++++++++++++++++++++++++++++*/
    /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

    function imprimir_cliente(event){
        event.preventDefault();
        $id_cliente = this.getAttribute('id_cliente');
        var xhr1 = new XMLHttpRequest();
        xhr1.open('POST', "index.php", true);
        xhr1.onreadystatechange = function() {
                if (this.status == 200 && this.readyState == 4) {
                    datosRespuesta = this.responseText;
                    var respuesta = JSON.parse(datosRespuesta);
                    if (respuesta.OK != null ) {
                        var ventimp = window.open(' ', 'popimpr');
                        ventimp.document.write(respuesta.html);
                        ventimp.document.close();
                        ventimp.print( );
                        ventimp.close();
                                
                    }
                                   
                };
        
        };

        var data = new FormData();
        data.append('imprimir_cliente', 'imprimir_cliente');
        data.append('id_cliente', $id_cliente);
        xhr1.send(data);
    }

    var btsImprimir = document.getElementsByClassName('btnImprimirCliente');
    Array.from(btsImprimir).forEach(element => {
        element.addEventListener('click', imprimir_cliente, false);
    });

}