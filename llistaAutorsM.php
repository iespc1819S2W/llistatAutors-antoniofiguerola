<?php

    $mysqli = new mysqli();
    $servidor = "127.0.0.1";
    $usuario = "root";
    $password = "";
    $nombreBD = "biblioteca";
    $mysqli->connect($servidor, $usuario, $password, $nombreBD); // conexion con la base de datos
    $mysqli->set_charset("utf8mb4"); //reconocer simbolos como ñ y acentos
    If (!$mysqli){ 
        echo "Connection error: " . mysqli_connect_error();
    }
    
    if (isset($_POST['ordre'])) {
        $ordre = $_POST['ordre'];
    } else {
        $ordre = "NOM_AUT ASC"; // variable per pasar al Select
    }

    if (isset($_POST['pagina'])) {
        $pagina = $_POST['pagina'];
    } else {
        $pagina = 1;
    }

    if(isset($_POST['ID_AUT_ASC'])) { // 4 botones ordenacion
        $ordre = "ID_AUT ASC";
        $pagina = 1;
    }
    if(isset($_POST['ID_AUT_DESC'])) {
        $ordre = "ID_AUT DESC";
        $pagina = 1;
    }
    if(isset($_POST['NOM_AUT_DESC'])) {
        $ordre = "NOM_AUT DESC";
        $pagina = 1;
    }
    if(isset($_POST['NOM_AUT_ASC'])) {
        $ordre = "NOM_AUT ASC";
        $pagina = 1;
    }

    if (isset($_POST['pagAnterior'])) { // botones cambiar pagina
        if ($pagina > 1) {
            $pagina--;
        }
    }
    if (isset($_POST['pagSeguent'])) {
        if ($pagina < $_POST['numPagines']) {
            $pagina++;
        }
    }
    if (isset($_POST['pagPrimera'])) {
            $pagina = 1;
    }
    if (isset($_POST['pagDarrera'])) {
            $pagina = $_POST['numPagines'];
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script  src="https://code.jquery.com/jquery-3.3.1.min.js"
  		integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  		crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
		crossorigin="anonymous">
    <title>Llista Autors</title>
    <script>
        window.onload = function (){
            document.getElementById("btnAfegir").onclick = function () {
                document.getElementById("rowAfegir").style.display = "flex";
            }
            document.getElementById("btnAmagar").onclick = function () {
                document.getElementById("rowAfegir").style.display = "none";
            }
        }
    </script>
</head>
<body>
    <?php
        // Insertar
        if (isset($_POST['btnAfegir'])) {
            $sqlMaxId = "SELECT MAX(ID_AUT) FROM autors";
            $resultID = $mysqli->query($sqlMaxId);
            if ($rowID = $resultID->fetch_row()) {
                $id = $rowID[0];
                $id++;
            }
            $nomAut = $mysqli->real_escape_string($_POST['afegir']);
            $sqlInsert = "INSERT INTO autors(ID_AUT, NOM_AUT) VALUES ($id, '$nomAut')";
            $resultInsert = $mysqli->query($sqlInsert);
            $ordre = "ID_AUT DESC";
        }
        // Borrar
        if (isset($_POST['btnBorrar'])) {
            $idBorrar = $_POST['btnBorrar'];
            $sqlBorrar = "DELETE FROM autors WHERE ID_AUT = $idBorrar";
            $mysqli->query($sqlBorrar);
            $ordre = "ID_AUT DESC";
        }
        // Consulta
        $sql="SELECT ID_AUT, NOM_AUT FROM `autors`";
        $where="";
        $valor = "";
        $numRegPag = isset($_POST['numRegPag'])?$_POST['numRegPag']:20;
        // Cercar
        if (isset($_POST['cercar']) && $_POST['cercar'] != "") {
            $valor = $mysqli->real_escape_string($_POST['cercar']);
            $where=" WHERE ID_AUT = '$valor' OR NOM_AUT LIKE '%$valor%'";
        }
        // Consulta paginacio
        $orderBy=" ORDER BY $ordre"; 
        $result = $mysqli->query($sql.$where);
        $numRegistres = mysqli_num_rows($result);
        $numPaginas = ceil($numRegistres/$numRegPag);
        $iniciTuples = ($pagina - 1) * $numRegPag;
        $limit = " LIMIT $iniciTuples , $numRegPag";
        $sql=$sql.$where.$orderBy.$limit;
        $result = $mysqli->query($sql);
        

    ?>
    <form action="" method="post">
        <input type="hidden" class="form-control" name="ordre" id="ordre" value="<?=$ordre?>">
        <input type="hidden" class="form-control" name="pagina" id="pagina" value="<?=$pagina?>">
        <input type="hidden" class="form-control" name="numPagines" id="numPagines" value="<?=$numPaginas?>">
        <div class="row">
            <div class="col-md-12 order-md-1">
                <div class="col-md-6 mb-3">
                    <button name="ID_AUT_ASC" class="btn btn-primary">CODI ASC</button>
                    <button name="ID_AUT_DESC" class="btn btn-primary">CODI DES</button>
                    <button name="NOM_AUT_ASC" class="btn btn-primary">NOM ASC</button>
                    <button name="NOM_AUT_DESC" class="btn btn-primary">NOM DES</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 order-md-1">
                <div class="col-md-8 mb-3">
                    <label for="">Codi / Nom</label>
                    <input type="text" name="cercar" id="cercar" placeholder="Cerca" value="<?=$valor?>">
                    <button name="btnCercar" class="btn btn-primary">Cercar</button>
                    <label for="">Nombre de Registres x pagina</label>
                    <select name="numRegPag" id="numRegPag">
                        <option value="5" <?php if($numRegPag == 5) echo " selected";?>>5</option>
                        <option value="10" <?php if($numRegPag == 10) echo " selected";?>>10</option>
                        <option value="20" <?php if($numRegPag == 20) echo " selected";?>>20</option>
                        <option value="30" <?php if($numRegPag == 30) echo " selected";?>>30</option>
                        <option value="40" <?php if($numRegPag == 40) echo " selected";?>>40</option>
                    </select>
                    <button name="btnRegPag" class="btn btn-primary">Asignar</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 order-md-1">
                <div class="col-md-6 mb-3">
                    <button name="pagPrimera" class="btn btn-primary">Primera</button>
                    <button name="pagAnterior" class="btn btn-primary">Anterior</button>
                    <button name="pagSeguent" class="btn btn-primary">Seguent</button>
                    <button name="pagDarrera" class="btn btn-primary">Darrera</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 order-md-1">
                <div class="col-md-6 mb-3">
                    <button type="button" id="btnAfegir" name="btnAfegir" class="btn btn-primary">+</button>
                    <button type="button" id="btnAmagar" name="btnAmagar" class="btn btn-primary">^</button>
                </div>
            </div>
        </div>
        <div id="rowAfegir" class="row" style="display:none">
            <div class="col-md-12 order-md-1">
                <div class="col-md-6 mb-3">
                    <input type="text" name="afegir" id="afegir" placeholder="LLINATGES, NOM">
                    <button name="btnAfegir" class="btn btn-primary">Afegir</button>
                </div>
            </div>
        </div>
    </form>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <!-- <th scope="col"></th>
                <th scope="col"></th> -->
                <th scope="col" colspan="4" style="text-align:center">BIBLIOTECA</th>
                <!-- <th scope="col"></th> -->
            </tr>
            <tr>
                <th scope="col">Identificador</th>
                <th scope="col">Nom Complet</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <form action="" method="POST">
                <?php
                    // echo("<p>$sql</p>"); // mostra per pantalla consulta sql i altre variables
                    // echo("<p> Pagina: $pagina");
                    // echo(" / Num registres: $numRegistres");
                    // echo(" / Num registres x pagina: $numRegPag");
                    // echo(" / Num pagines: $numPaginas</p>");
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            echo("<tr>");
                            echo("<th scope='row'>".$row["ID_AUT"]."</th>");
                            echo("<td>".$row["NOM_AUT"]."</td>");
                            echo("<td><button name='btnEditar' class='btn btn-success btn-sm' value='{$row["ID_AUT"]}'>Editar</button></td>");
                            echo("<td><button name='btnBorrar' class='btn btn-danger btn-sm' value='{$row["ID_AUT"]}'>Borrar</button></td>");
                            echo("</tr>");
                        }   
                        $result->free();
                    }
                    $mysqli->close();
                    if ($pagina >= $numPaginas) {
                        $pagina = $numPaginas;
                    }
                ?>
            </form>
        </tbody>
    </table>
</body>
</html>