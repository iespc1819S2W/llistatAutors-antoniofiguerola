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

    if ((isset($_POST['pagAnteriorDos']) || isset($_POST['pagAnteriorUn']) || 
            isset($_POST['pagActual']) || isset($_POST['pagSeguentUn']) || isset($_POST['pagSeguentDos']))) {
            $pagina = $_POST['pagina'];
    }
    // echo($_POST['pagina']);

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
</head>
<body>
    <?php
        $sql="SELECT ID_AUT, NOM_AUT FROM `autors`";
        $where="";
        if (isset($_POST['btnCercar'])) {
            $valor = ($_POST['cercar']);
            $where=" WHERE ID_AUT = '$valor' OR NOM_AUT LIKE '%$valor%'";
        }
        $orderBy=" ORDER BY $ordre"; // o hauria de ser " ORDER BY $POST['ordre']"?
        $result = $mysqli->query($sql);
        $numRegistres = mysqli_num_rows($result);
        $numRegPag = 10;
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
                <div class="col-md-6 mb-3">
                    <label for="">Codi / Nom</label>
                    <input type="text" name="cercar" id="cercar" placeholder="Cerca">
                    <button name="btnCercar" class="btn btn-primary">Cercar</button>
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
                    <button name="pagAnteriorDos" class="btn btn-primary"><?=$pagina-2?></button>
                    <button name="pagAnteriorUn" class="btn btn-primary"><?=$pagina-1?></button>
                    <button name="pagActual" class="btn btn-primary"><?=$pagina?></button>
                    <button name="pagSeguentUn" class="btn btn-primary"><?=$pagina+1?></button>
                    <button name="pagSeguentDos" class="btn btn-primary"><?=$pagina+2?></button>
                </div>
            </div>
        </div>
    </form>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col"></th>
                <th scope="col">BIBLIOTECA</th>
            </tr>
            <tr>
                <th scope="col">Identificador</th>
                <th scope="col">Nom Complet</th>
            </tr>
        </thead>
        <tbody>
            <?php
                echo("<p>$sql</p>");
                echo("<p> Pagina: $pagina");
                echo(" / Num registres: $numRegistres");
                echo(" / Num registres x pagina: $numRegPag");
                echo(" / Num pagines: $numPaginas</p>");
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                            echo("<tr>");
                            echo("<th scope='row'>".$row["ID_AUT"]."</th>");
                            echo("<td>".$row["NOM_AUT"]."</td>");
                            echo("</tr>");
                    }   
                    $result->free();
                }
                $mysqli->close();
                if ($pagina >= $numPaginas) {
                    $pagina = $numPaginas;
                }
            ?>
        </tbody>
    </table>
</body>
</html>