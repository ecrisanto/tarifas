<?php
$con = mysqli_connect("localhost", "root", "", "tarifasmym");
        
if(!$con){
echo "Error: ".mysqli_connect_error();
}   