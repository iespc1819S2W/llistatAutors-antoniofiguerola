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
    $orderBy = "NOM_AUT ASC"; // variable per pasar al Select
    if(isset($_POST['ID_AUT_ASC'])) {
        $orderBy = "ID_AUT ASC";
    }
    if(isset($_POST['ID_AUT_DESC'])) {
        $orderBy = "ID_AUT DESC";
    }
    if(isset($_POST['NOM_AUT_DESC'])) {
        $orderBy = "NOM_AUT DESC";
    }
    if(isset($_POST['NOM_AUT_ASC'])) {
        $orderBy = "NOM_AUT ASC";
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
</head>
<body>
    <form action="" method="post">
        <div class="form-group">
        <input type="hidden" class="form-control" name="ordre" id="ordre" value="<?=$orderBy?>">
        <button name="ID_AUT_ASC" class="btn btn-primary">CODI ASC</button>
        <button name="ID_AUT_DESC" class="btn btn-primary">CODI DES</button>
        <button name="NOM_AUT_ASC" class="btn btn-primary">NOM ASC</button>
        <button name="NOM_AUT_DESC" class="btn btn-primary">NOM DES</button>
        </div>
        <div class="form-group">
        <label for="">Codi / Nom</label>
        <input type="text" class="form-control" name="cercar" id="cercar" aria-describedby="helpId" placeholder="Cerca">
        <button name="btnCercar" class="btn btn-primary">Buscar</button>
        </div>
    </form>
    <!-- <form class="form-inline" action="" method="Post">
        <div class="container-fluid">
            <button name="ID_AUT_ASC" class="btn btn-primary">CODI ASC</button>
            <button name="ID_AUT_DESC" class="btn btn-primary">CODI DES</button>
            <button name="NOM_AUT_ASC" class="btn btn-primary">NOM ASC</button>
            <button name="NOM_AUT_DESC" class="btn btn-primary">NOM DES</button>
        </div>
        <div class="container-fluid">
            <label for="">Codi / Nom</label>
            <input type="text" class="form-control" name="cercar" id="cercar" aria-describedby="helpId" placeholder="Cerca">
            <button name="btnCercar" class="btn btn-primary">Buscar</button>
        </div>
        <div class="container-fluid">
            <button name="btnSeguent" class="btn btn-primary">Seguent</button>
        </div>
    </form> -->
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
                $sql="SELECT ID_AUT, NOM_AUT FROM `autors`";
                $where="";
                $orderBy=" ORDER BY $orderBy";
                if (isset($_POST['btnCercar'])) {
                    $valor = ($_POST['cercar']);
                    $where=" WHERE ID_AUT = '$valor' OR NOM_AUT LIKE '%$valor%'";
                }
                $sql=$sql.$where.$orderBy;
                echo("<p>$sql</p>");
                $result = $mysqli->query($sql);
                if ($result) {
                    $pagina=0;  // del botón...
                    $numRegPagina=10; // del select

                    $dir=$pagina*$numRegPagina;
                    $actual=0;
                    $offset=0;
                    $pagina = ceil($pagina/$numRegPagina);

                    while ($row = $result->fetch_assoc()) {
                        if($actual>=$dir){
                            echo("<tr>");
                            echo("<th scope='row'>".$row["ID_AUT"]."</th>");
                            echo("<td>".$row["NOM_AUT"]."</td>");
                            echo("</tr>");
                            $offset++;                        
                            if($offset==$numRegPagina){
                                break;
                            }
                        }
                        $actual++;
                    }   
                    $result->free();
                }
                $mysqli->close();
            ?>
        </tbody>
    </table>
</body>
</html>