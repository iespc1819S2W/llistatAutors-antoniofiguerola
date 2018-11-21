<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pagination</title>
</head>
<body>
    <?php
        // connect to database
        $mysqli = new mysqli();
        $servidor = "127.0.0.1";
        $usuario = "root";
        $password = "";
        $nombreBD = "biblioteca";
        $mysqli->connect($servidor, $usuario, $password, $nombreBD);
        // $con = mysqli_connect('localhost','root','');
        // mysqli_select_db($con, 'pagination');
        // define how many results you want per page
        $results_per_page = 20;
        $orderBy = "NOM_AUT ASC";
        // find out the number of results stored in database
        $sql="SELECT ID_AUT, NOM_AUT FROM `autors`";
        $where="";
        $orderBy=" ORDER BY $orderBy";
        $sql=$sql.$where.$orderBy;
        $result = mysqli_query($mysqli, $sql);
        $number_of_results = mysqli_num_rows($result);
        // determine number of total pages available
        $number_of_pages = ceil($number_of_results/$results_per_page);
        // determine which page number visitor is currently on
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        // determine the sql LIMIT starting number for the results on the displaying page
        $this_page_first_result = ($page-1)*$results_per_page;
        // retrieve selected results from database and display them on page
        $sql="SELECT ID_AUT, NOM_AUT FROM `autors` " . $this_page_first_result . ',' .  $results_per_page;
        $result = mysqli_query($mysqli, $sql);
        while($row = mysqli_fetch_array($result)) {
            echo $row['ID_AUT'] . ' ' . $row['NOM_AUT']. '<br>';
        }
        // display the links to the pages
        for ($page=1;$page<=$number_of_pages;$page++) {
            echo '<a href="index.php?page=' . $page . '">' . $page . '</a> ';
        }
    ?>
</body>
</html>