<?php
require_once("conpolitic.php");

$provid = isset($_GET['provid']) ? (int)$_GET['provid'] : 0;

echo '<option value=""><< เลือกอำเภอ >></option>';

$sql = "SELECT AMPHUR_ID, AMPHUR_NAME 
        FROM amphur 
        WHERE PROVINCE_ID = $provid
        ORDER BY AMPHUR_NAME ASC";

$result = $conn->query($sql);

if($result){
    while($row = $result->fetch_assoc()){
        echo "<option value='".$row['AMPHUR_ID']."'>".$row['AMPHUR_NAME']."</option>";
    }
}
?>