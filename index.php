<?php
include "Include\db.php";
header('Location:http://localhost/tarifasMyM/index.php');
//header("Location: index.php");  
/*if(isset($_POST["Guarda"]))
{
    
}*/    
?>
<html>
    <head>
        <meta charset="UTF-8">
        <!--<link rel="stylesheet" type="text/css" href="Include\css\style-desktop.css" media="screen" />-->
        <!--<link rel="stylesheet" type="text/css" href="Include\css-\main.css" media="screen" />-->
        <link rel="stylesheet" type="text/css" href="Include\css--\default.css" media="screen" />
        <title></title>
    </head>
    <body>
        <form name = "trafico_facturar" action = "index.php" method="POST">
        <?php   
        /*if(isset($_POST["Ir"]))
        echo "POST Ir: ".$_POST["Ir"];
        
        if(isset($_POST["Cambia"]))
        echo "POST Cambia: ".$_POST["Cambia"];*/
        
        $aduana = mysqli_query($con, "select * from trafico_facturar where trafico = '16-012707'");
        
        if (mysqli_num_rows($aduana) <1){
            echo "La aduana para el tráfico proporcionado no existe";
        }
        
        $row = mysqli_fetch_row($aduana);
        echo "<br>";        
        
        echo "<table border = '0'>";
            echo "<thead>";
                echo "<tr>";
                echo "<th>Trafico</th>";
                echo "<th>Cliente</th>";
                echo "<th width = '150 px'>Aduana</th>";
                echo "<th width = '150 px'>Division</th>";
                echo "<th width = '150 px'>Operacion</th>";
                echo "<th>Base honorarios</th>";                
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td>".$row[0]."</td>";
                    echo "<td>".$row[1]."</td>";
                    
                    if(isset($_POST["Cambia"]))
                        echo "<td><input type = 'text' name = 'aduana' value = '".$row[2]."' /></td>";
                    else
                        echo "<td>".$row[2]."</td>";
                    
                    if(isset($_POST["Cambia"]))
                        echo "<td><input type = 'text' name = 'division' value = '".$row[3]."' /></td>";
                    else
                        echo "<td>".$row[3]."</td>";
                    
                    if(isset($_POST["Cambia"]))
                        echo "<td><input type = 'text' name = 'Operacion' value = '".$row[4]."' /></td>";
                    else
                        echo "<td>".$row[4]."</td>";
                    
                    echo "<td>".$row[5]."</td>";                                           
                
                echo "</tr>";  
            echo "</tbody>";
        echo "</table>";
        
        echo "<br>Información tarifa: ";

        $tarifa = mysqli_query($con, "select tar.cliente, tar.aduana, tar.operacion, des.itemId, des.importe, des.idDesgloseTarifa 
                                        from tarifa tar join desglosetarifa des
                                        where tar.idTarifa = des.kfTarifa
                                        and tar.cliente = '".$row[1]."' ");
                                        //and tar.aduana = '".$row[2]."' ");
        
        $tarifas = mysqli_fetch_all($tarifa);        
        echo "<table border = '1'>";
            echo "<thead>";
                echo "<tr>";
                echo "<th>Cliente</th>";
                echo "<th>Aduana</th>";
                echo "<th>Operacion</th>";
                echo "<th>Concepto</th>";
                echo "<th width = '250 px'>Descripción</th>";
                echo "<th>Importe</th>";                               
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";            
       
            for($i=0; $i< count($tarifas); $i++){              
                echo "<tr>";
                echo "<td>".$tarifas[$i][0]."</td>";
                echo "<td>".$tarifas[$i][1]."</td>";
                echo "<td>".$tarifas[$i][2]."</td>";
                echo "<td>".$tarifas[$i][3]."</td>"; 
                echo "<td>";                                                       
                    $desgloseT = mysqli_query($con, "select * from condiciontarifa where kfdesgloseTarifa = '".$tarifas[$i][5]."'");
                    $desgloseF = mysqli_fetch_all($desgloseT);

                    echo "<table border = '0'>";
                        echo "<thead>";
                            echo "<tr>";
                            echo "<th>Descripción</th>";
                            echo "<th>Monto</th>";                               
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";            

                        for($j=0; $j< count($desgloseF); $j++){              
                            echo "<tr>";
                            echo "<td>".$desgloseF[$j][2]."</td>";
                            echo "<td>".$desgloseF[$j][3]."</td>";
                            echo "</tr>";  
                        }            
                       echo "</tbody>";
                    echo "</table>"; 
                    
                echo "</td>";                                                                                                                 
                echo "<td>".$tarifas[$i][4]."</td>"; 
                echo "</tr>";  
            }
            
           echo "</tbody>";
           
        echo "</table>";   
        
        echo "<input type='hidden' value='".$row[2]."' name='aduana' />";
        echo "<input type='hidden' value='".$row[3]."' name='division' />";
        echo "<input type='hidden' value='".$row[4]."' name='operacion' />";
        
        if(!isset($_POST["Cambia"]))
            echo "<input type='submit' value='Modificar' name='Cambia'/>";
        
        if(isset($_POST["Cambia"]))
            echo "<input type='submit' value='Guardar' name='Guarda'/>";
        
        mysqli_close($con);
        ?>                     
    </form >
    </body>
</html>
