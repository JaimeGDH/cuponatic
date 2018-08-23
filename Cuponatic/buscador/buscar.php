<!DOCTYPE html>
    <head>
        <meta charset="utf-8" />
        <title>Buscador de productos</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <h1>Buscar producto</h1>
        <input type="text" id="txtBuscar" name="campoBuscar" value="" size="50" />
        <input type="submit" id="btnBuscar" name="botonBuscar" value="Buscar" /> 

        <div id="datosMostrar">
        </div>
    </body>

    <script>
        $("#btnBuscar").click(buscarProducto);
        
        function buscarProducto() {
            var valor = $("#txtBuscar").val();
            var datajson = {"keywords": valor};
            $(document).ready(function() {
                
                $.ajax(
                {
                    url: '/Cuponatic/product/search.php',
                    type: 'POST',
                    data: JSON.stringify(datajson),
                    dataType: 'json',
                    contentType: 'application/json; charset=utf-8',                    
                    success: function (data, textStatus, jqXHR) {
                        $('#datosMostrar').html(JSON.stringify(data));
                    },
                    error: function (request, status, error) {    
                        $('#datosMostrar').html("Hubo problemas al buscar el producto");
                    }
                })
            })
        }        
    </script>
</html>