<?php
require_once('conpolitic.php');
$conn->set_charset("utf8mb4");

/* ===== ลบข้อมูล ===== */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM address WHERE id = $id");
    header("Location: new.php");
    exit;
}

/* ===== ดึงจังหวัด ===== */
$result = $conn->query("SELECT * FROM province ORDER BY PROVINCE_NAME ASC");

/* ===== บันทึก ===== */
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $province = (int)$_POST['txprov2'];
    $amphur   = (int)$_POST['txAMP'];
    $tambol   = (int)$_POST['txtambol'];

    if($province && $amphur && $tambol){
        $stmt = $conn->prepare("INSERT INTO address (province_id, amphur_id, tambol_id) VALUES (?, ?, ?)");
        if($stmt){
            $stmt->bind_param("iii",$province,$amphur,$tambol);
            if($stmt->execute()){
                $msg = "บันทึกข้อมูลเรียบร้อยแล้ว";
            }else{
                $msg = "บันทึกไม่สำเร็จ";
            }
        }
    }else{
        $msg = "กรุณาเลือกให้ครบ";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>WebGIS</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap');

body{
    font-family:'Sarabun',sans-serif;
    background:#f4f6f9;
    margin:0;
}

/* NAVBAR */
.navbar-custom{
    background:#1e2a38;
    color:#fff;
    padding:15px 30px;
    font-size:18px;
    font-weight:600;
}

/* WRAPPER */
.wrapper{
    max-width:900px;
    margin:40px auto;
}

/* CARD */
.card{
    background:#fff;
    border-radius:15px;
    padding:30px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

/* FORM */
.form-control{
    height:45px;
    border-radius:10px;
}

.form-control:focus{
    border-color:#28a745;
    box-shadow:0 0 5px rgba(40,167,69,0.3);
}

/* BUTTON */
.btn-success{
    height:48px;
    border-radius:10px;
    font-weight:600;
}

/* TABLE */
.table thead{
    background:#1e2a38;
    color:#fff;
}

.table-hover tbody tr:hover{
    background:#f1f3f6;
}
</style>

<script>
$(function(){

$("#txprov2").change(function(){
    let prov = $(this).val();

    if(prov==""){
        $("#txAMP").html('<option>เลือกอำเภอ</option>').prop("disabled",true);
        $("#txtambol").html('<option>เลือกตำบล</option>').prop("disabled",true);
        return;
    }

    $.get("province2amp.php",{provid:prov},function(data){
        $("#txAMP").html(data).prop("disabled",false);
        $("#txtambol").html('<option>เลือกตำบล</option>').prop("disabled",true);
    });
});

$("#txAMP").change(function(){
    let amp = $(this).val();

    if(amp==""){
        $("#txtambol").html('<option>เลือกตำบล</option>').prop("disabled",true);
        return;
    }

    $.get("amp2tanbol.php",{ampid:amp},function(data){
        $("#txtambol").html(data).prop("disabled",false);
    });
});

});
</script>
</head>

<body>

<div class="navbar-custom">
🗺️ ระบบจัดการข้อมูลที่อยู่ (WebGIS)
</div>

<div class="wrapper">
<div class="card">

<h3>📍 เลือกที่อยู่</h3>

<?php if(isset($msg)): ?>
<div class="alert alert-success text-center"><?= $msg ?></div>
<?php endif; ?>

<form method="post">

<div class="form-group">
<label>จังหวัด</label>
<select name="txprov2" id="txprov2" class="form-control">
<option value="">เลือกจังหวัด</option>
<?php while($row = $result->fetch_assoc()): ?>
<option value="<?= $row['PROVINCE_ID'] ?>"><?= $row['PROVINCE_NAME'] ?></option>
<?php endwhile; ?>
</select>
</div>

<div class="form-group">
<label>อำเภอ</label>
<select name="txAMP" id="txAMP" class="form-control" disabled>
<option>เลือกอำเภอ</option>
</select>
</div>

<div class="form-group">
<label>ตำบล</label>
<select name="txtambol" id="txtambol" class="form-control" disabled>
<option>เลือกตำบล</option>
</select>
</div>

<button class="btn btn-success btn-block">บันทึกข้อมูล</button>

</form>

<hr>

<h4>📋 ข้อมูลที่บันทึก</h4>

<table class="table table-bordered table-hover">
<thead>
<tr>
<th>จังหวัด</th>
<th>อำเภอ</th>
<th>ตำบล</th>
<th>ลบ</th>
</tr>
</thead>

<tbody>
<?php
$sql = "SELECT ad.id, p.PROVINCE_NAME, a.AMPHUR_NAME, t.TAMBOL_NAME
FROM address ad
JOIN province p ON ad.province_id = p.PROVINCE_ID
JOIN amphur a ON ad.amphur_id = a.AMPHUR_ID
JOIN tambol t ON ad.tambol_id = t.TAMBOL_ID";

$res = $conn->query($sql);

while($row = $res->fetch_assoc()):
?>
<tr>
<td><?= $row['PROVINCE_NAME'] ?></td>
<td><?= $row['AMPHUR_NAME'] ?></td>
<td><?= $row['TAMBOL_NAME'] ?></td>
<td>
<a href="?delete=<?= $row['id'] ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('ยืนยันการลบ?')">
🗑 ลบ
</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</div>
</div>

</body>
</html>