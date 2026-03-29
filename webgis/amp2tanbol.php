<?php
require_once("conpolitic.php");

$ampid = isset($_GET['ampid']) ? (int)$_GET['ampid'] : 0;

echo '<option value=""><< เลือกตำบล >></option>';

$sql = "SELECT TAMBOL_ID, TAMBOL_NAME 
        FROM tambol 
        WHERE AMPHUR_ID = $ampid
        ORDER BY TAMBOL_NAME ASC";

$result = $conn->query($sql);

if($result){
    while($row = $result->fetch_assoc()){
        echo "<option value='".$row['TAMBOL_ID']."'>".$row['TAMBOL_NAME']."</option>";
    }
}
?>