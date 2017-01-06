<?php
error_reporting(E_ALL);
include "Include\db.php"; 

$cambia =(!isset($_POST["Cambia"]))?'':$_POST["Cambia"]; 
$guarda = (!isset($_POST["Guarda"]))?'':$_POST["Guarda"]; 
$cliente =(!isset($_POST["textCliente"]))?'':$_POST["textCliente"];
$aduana =(!isset($_POST["textAduana"]))?'':$_POST["textAduana"];
$division =(!isset($_POST["textDivision"]))?'':$_POST["textDivision"];
$operacion =(!isset($_POST["textOperacion"]))?'':$_POST["textOperacion"];
$honorarios =(!isset($_POST["textHonorarios"]))?'':$_POST["textHonorarios"];
$trafico =(!isset($_POST["trafico"]))?'':$_POST["trafico"];
//echo "Cambia: ".$cambia.", Guarda: ".$guarda." Cliente: ".$cliente." Aduana: ".$aduana." Div: ".$division.", Op: ".$operacion." Cliente ".$cliente." Tráfico: ".$trafico;

if($cambia == '' && $guarda != '' && $aduana != '')
{     
    if($division != '')
    {
        $query = "select con.idCondicion from tarifa tar
                                join desglosetarifa des
                                join condiciontarifa con
                                on tar.idTarifa = des.kfTarifa
                                and con.kfDesgloseTarifa = des.idDesgloseTarifa
                                and tar.cliente = '".$cliente."'
                                and (tar.aduana = '".$aduana."' or aduana = '')
                                and tar.operacion = ".$operacion."
                                and con.idCondicion = '".$division."'";

        $revisaDiv = mysqli_query($con, $query);

        if(mysqli_num_rows($revisaDiv) > 0)
        {
            $row = mysqli_fetch_row($revisaDiv);
            $division = $row[0];
        }else
            $division = '';
    }
    
    $sql = "update trafico_facturar
                        set noCliente = '".$cliente."', aduana = '".$aduana."', cve_division = '".$division."', operacion = '".$operacion."', base_hono = ".$honorarios."
                        where trafico = '".$trafico."'";    

    if (!mysqli_query($con, $sql)) 
            echo "Error updating record: " . mysqli_error($con);             
}
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
        <form name = "trafico_facturar" action = "tarifas.php" method="POST">
        <?php   
        $aduana = mysqli_query($con, "select * from trafico_facturar where trafico = '16-012707'");
        
        if (mysqli_num_rows($aduana) <1){
            echo "No existe información para el tráfico seleccionado.";
        }

        $row = mysqli_fetch_row($aduana);
        echo "<br> Información de tabla trafico_facturar:";        
        
        echo "<table border = '0'>";
            echo "<thead>";
                echo "<tr>";
                echo "<th>Trafico</th>";
                echo "<th width = '150 px'>Cliente</th>";
                echo "<th width = '150 px'>Aduana</th>";
                echo "<th width = '150 px'>Division</th>";
                echo "<th width = '150 px'>Operacion</th>";
                echo "<th>Base honorarios</th>";                
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td>".$row[0]."</td>";
                    
                    if(isset($_POST["Cambia"]))
                        echo "<td><input type = 'text' name = 'textCliente' value = '".$row[1]."' /></td>";
                    else
                        echo "<td>".$row[1]."</td>";
                    
                    if(isset($_POST["Cambia"]))
                        echo "<td><input type = 'text' name = 'textAduana' value = '".$row[2]."' /></td>";
                    else
                        echo "<td>".$row[2]."</td>";                                           
                    
                    $idTar = mysqli_query($con, "select des.idDesgloseTarifa, des.itemId, des.importe, des.aplicaFactor from desglosetarifa des JOIN tarifa tar 
                                 where des.kfTarifa = tar.idTarifa and tar.cliente = '".$row[1]."' and tar.aduana = '".$row[2]."' and tar.operacion = ".$row[4]." ");
                        
                    $idTarifa = mysqli_fetch_row($idTar);    
                    if(isset($_POST["Cambia"]))
                    {                                                                       
                        $condiciones = mysqli_query($con, "select idCondicion, descripcion, monto FROM condiciontarifa where kfDesgloseTarifa = ".$idTarifa[0]);
                        
                        if (mysqli_query($con, "select idCondicion, descripcion, monto FROM condiciontarifa where kfDesgloseTarifa = ".$idTarifa[0])) 
                        $resCondiciones = mysqli_fetch_all($condiciones);
                        
                        echo "<td>";
                            echo "<select name = 'textDivision'>";
                            echo "<option  value=''></option>"; 
                            for($k=0; $k< count($resCondiciones); $k++){    
                                if($row[3] == $resCondiciones[$k][0])
                                    echo "<option  value='".$resCondiciones[$k][0]."' selected>".$resCondiciones[$k][1].", $".$resCondiciones[$k][2]."</option>"; 
                                else
                                    echo "<option  value='".$resCondiciones[$k][0]."'>".$resCondiciones[$k][1].", $".$resCondiciones[$k][2]."</option>"; 
                            }                            
                            echo "</select >";
                        echo "</td>";
                    }
                    else
                    {                                
                        if($row[3] != '')
                        {
                            $condicionTar = mysqli_query($con, "select descripcion, monto FROM condiciontarifa where idCondicion = ".$row[3]);
                            $idCondicion = mysqli_fetch_row($condicionTar);
                            echo "<td>".$idCondicion[0].", $".$idCondicion[1]."</td>";
                        }else
                            echo "<td></td>";                                                                          
                    }
                    
                    if(isset($_POST["Cambia"]))
                        echo "<td><input type = 'text' name = 'textOperacion' value = '".$row[4]."' /></td>";
                    else
                        echo "<td>".$row[4]."</td>";
                    
                    if(isset($_POST["Cambia"]))
                        echo "<td><input type = 'text' name = 'textHonorarios' value = '".$row[5]."' /></td>";
                    else
                        echo "<td>".$row[5]."</td>";                                                         
                
                echo "</tr>";  
            echo "</tbody>";
        echo "</table>";
        
        if(!isset($_POST["Cambia"]))
            
            echo "<input type='submit' value='Modificar' name='Cambia'/>";
        
        if(isset($_POST["Cambia"]))
            echo "<input type='submit' value='Guardar' name='Guarda'/>";     
                              
        echo "<br><br>Información tarifa: ";

        $tarifa = mysqli_query($con, "select tar.cliente, tar.aduana, tar.operacion, des.itemId, des.importe, des.idDesgloseTarifa 
                                        from tarifa tar join desglosetarifa des
                                        where tar.idTarifa = des.kfTarifa
                                        and tar.cliente = '".$row[1]."' 
                                        order by tar.cliente, tar.aduana, tar.operacion,des.itemId ");                                        
        
        $tarifas = mysqli_fetch_all($tarifa);        
        echo "<table border = '0'>";
            echo "<thead>";
                echo "<tr>";
                echo "<th>Cliente</th>";
                echo "<th>Aduana</th>";
                echo "<th>Operacion</th>";
                echo "<th>Concepto</th>";
                echo "<th width = '485 px'>Descripción</th>";
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
                
                    //Condiciones tarifas
                    $desgloseT = mysqli_query($con, "select * from condiciontarifa where kfdesgloseTarifa = '".$tarifas[$i][5]."'");
         
                    if (mysqli_num_rows($desgloseT) >0)
                    {
                        
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
                    } 
                    
                    //Tarifa Multirango
                    $desgloseRango = mysqli_query($con, "SELECT * FROM rangotarifa WHERE kfDesgloseTarifa = '".$tarifas[$i][5]."'
                        order by limiteInferior ASC ");
         
                    if (mysqli_num_rows($desgloseRango) >0)
                    {
                        
                    $desgloseR = mysqli_fetch_all($desgloseRango);

                    echo "<table border = '0'>";
                        echo "<thead>";
                            echo "<tr>";
                            echo "<th>Monto</th>";
                            echo "<th>Limite inferior</th>";                               
                            echo "<th>Limite superior</th>";                               
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";            

                        for($s=0; $s< count($desgloseR); $s++){              
                            echo "<tr>";
                            echo "<td>".$desgloseR[$s][2]."</td>";
                            echo "<td>".$desgloseR[$s][3]."</td>";
                            echo "<td>".$desgloseR[$s][4]."</td>";
                            echo "</tr>";  
                        }            
                       echo "</tbody>";
                    echo "</table>"; 
                    } 
                    
                echo "</td>";                                                                                                                 
                echo "<td>".$tarifas[$i][4]."</td>"; 
                echo "</tr>";  
            }            
           echo "</tbody>";           
        echo "</table>";                                              
         
        echo "<br>Pedido de venta: ";
        echo "<table border = '0'>";
            echo "<thead>";
                echo "<tr>";
                echo "<th>Cliente</th>";
                echo "<th>Aduana</th>";
                echo "<th>Tráfico</th>";
                echo "<th>Concepto</th>";
                echo "<th>Monto</th>";                                              
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";            
                                                         
                If($row[3] == '')
                   $consulta = "select des.idDesgloseTarifa, des.itemId, des.importe, des.aplicaFactor 
                                    from desglosetarifa des JOIN tarifa tar 
                                    where des.kfTarifa = tar.idTarifa 
                                    and tar.cliente = '".$row[1]."' and tar.operacion = ".$row[4];                    
                else
                    $consulta = "select des.idDesgloseTarifa, des.itemId, des.importe, des.aplicaFactor 
                        from desglosetarifa des JOIN tarifa tar JOIN condiciontarifa con 
                        where des.kfTarifa = tar.idTarifa 
                        and con.kfDesgloseTarifa = des.idDesgloseTarifa 
                        and tar.cliente = '".$row[1]."' 
                        and tar.operacion = ".$row[4]."
                        and con.idCondicion = ".$row[3];
                                    
                echo $consulta;
                $idTarFactor = mysqli_query($con, $consulta);
                        
                $idTarifaFactor = mysqli_fetch_all($idTarFactor);
                for($m=0; $m<count($idTarifaFactor); $m++)
                {                       
                    echo "<tr>";
                    echo "<td>".$row[1]."</td>";
                    echo "<td>".$row[2]."</td>";
                    echo "<td>".$row[0]."</td>";
                    
                    $montoRango = 0;
                    $sqlRango = "select monto from rangotarifa 
                        where kfDesgloseTarifa = ".$idTarifaFactor[$m][0]."
                         and (limiteInferior <= ".$row[5]." and limiteSuperior > ".$row[5].")";
                    
                    //echo $sqlRango;
                    
                    $consultaRango = mysqli_query($con, $sqlRango);
                    if(mysqli_num_rows($consultaRango) >0){
                        $montoR = mysqli_fetch_row($consultaRango);
                        $montoRango = $montoR[0];
                    } 

                    if($montoRango > 0)
                    {
                        echo "<td>".$idTarifaFactor[$m][1]."</td>";
                        echo "<td>".$montoRango."</td>";  
                        
                    }else if($row[3] != '')
                    {                        
                        $tarifaTraf = mysqli_query($con, "select * from condiciontarifa where idCondicion = ".$row[3]);                      
                        if (mysqli_num_rows($tarifaTraf) >= 1)                            
                        {
                             $tarIndiv = mysqli_fetch_row($tarifaTraf); 
                             echo "<td>".$tarIndiv[2]."</td>";
                             echo "<td>".$tarIndiv[3]."</td>";                            
                        }
                    } else {

                        if($idTarifaFactor[$m][3] == 1)
                        {                       
                            echo "<td>".$idTarifaFactor[$m][1]."</td>";
                            echo "<td>".$idTarifaFactor[$m][2]*$row[5]."</td>";                         
                        }else
                        {
                            echo "<td>".$idTarifaFactor[$m][1]."</td>";
                            echo "<td>".$idTarifaFactor[$m][2]."</td>";   
                        }
                    }

                    echo "</tr>";
                }                                                            
                
            echo "</tbody>";
        echo "</table>";
        echo "<br>";
        
        echo "<input type='hidden' value='".$row[0]."' name='trafico' />";                                        
        
        mysqli_close($con);
        ?>                     
    </form >
    </body>
</html>
