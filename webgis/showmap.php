<?php
$conn = mysqli_connect("localhost", "root", "", "gisweb69");
mysqli_set_charset($conn,"utf8");

$sql = "SELECT * FROM krabi_tour ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

// เก็บข้อมูลไว้ใช้ใน map
$data = [];
while($r = mysqli_fetch_assoc($result)){
    $data[] = $r;
}
mysqli_data_seek($result,0);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แผนที่สถานที่ท่องเที่ยว</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- 🔥 ใช้ OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
body{
    background:#f4f7fb;
    font-family:'Sarabun',sans-serif;
}

#map{
height:450px;
width:100%;
border-radius:15px;
margin-bottom:25px;
box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.card{
border:none;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

.table-hover tbody tr:hover{
cursor:pointer;
background:#eef6ff;
transition:.2s;
}
</style>

</head>

<body>

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">
<h4>📍 ระบบแสดงสถานที่ท่องเที่ยว</h4>
<a href="sample.php" class="btn btn-success">+ เพิ่มข้อมูล</a>
</div>

<div class="card p-3 mb-4">
<div id="map"></div>
</div>

<div class="card p-3">
<div class="table-responsive">
<table class="table table-hover align-middle">

<thead class="table-dark text-center">
<tr>
<th>รูป</th>
<th>ชื่อ</th>
<th>รายละเอียด</th>
<th>พิกัด</th>
<th>หน่วยงาน</th>
<th>จัดการ</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr onclick="zoomMap(<?php echo $row['latitude']; ?>,<?php echo $row['longitude']; ?>)">

<td>
<?php if($row['photo']) { ?>
<img src="uploads/<?php echo $row['photo']; ?>" width="80" class="rounded shadow-sm">
<?php } ?>
</td>

<td><b><?php echo $row['name']; ?></b></td>

<td><?php echo $row['descript']; ?></td>

<td>
<small>
<?php echo $row['latitude']; ?><br>
<?php echo $row['longitude']; ?>
</small>
</td>

<td>
<span class="badge bg-info text-dark">
<?php echo $row['ownplace']; ?>
</span>
</td>

<td class="text-center">
<a href="adjust.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
<a href="delete.php?id=<?php echo $row['id']; ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('ยืนยันการลบ?')">ลบ</a>
</td>

</tr>
<?php } ?>
</tbody>

</table>
</div>
</div>

</div>

<script>

// 🔥 สร้างแผนที่ (OpenStreetMap)
let map = L.map('map').setView([8.0, 99.5], 7);

// แผนที่พื้นฐาน
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
}).addTo(map);

// ดึงข้อมูลจาก PHP
let locations = <?php echo json_encode($data); ?>;

// ถ้ามีข้อมูล → zoom ไปตัวแรก
if(locations.length > 0){
    map.setView([
        parseFloat(locations[0].latitude),
        parseFloat(locations[0].longitude)
    ], 10);
}

// ปักหมุด
locations.forEach(loc => {

    let marker = L.marker([
        parseFloat(loc.latitude),
        parseFloat(loc.longitude)
    ]).addTo(map);

    let popup = `<?php
$conn = mysqli_connect("localhost", "root", "geographypsu", "gisweb69");
mysqli_set_charset($conn,"utf8");

$sql = "SELECT * FROM krabi_tour ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

// เก็บข้อมูลสำหรับ map
$data = [];
while($r = mysqli_fetch_assoc($result)){
    // กันค่าพิกัดว่าง
    if(!empty($r['latitude']) && !empty($r['longitude'])){
        $data[] = $r;
    }
}
mysqli_data_seek($result,0);
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แผนที่สถานที่ท่องเที่ยว</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- 🔥 OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
body{
    background:#f4f7fb;
    font-family:'Sarabun',sans-serif;
}

#map{
height:450px;
width:100%;
border-radius:15px;
margin-bottom:25px;
box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.card{
border:none;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

.table-hover tbody tr:hover{
cursor:pointer;
background:#eef6ff;
}
</style>
</head>

<body>

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">
<h4>📍 ระบบแสดงสถานที่ท่องเที่ยว</h4>
<a href="sample.php" class="btn btn-success">+ เพิ่มข้อมูล</a>
</div>

<div class="card p-3 mb-4">
<div id="map"></div>
</div>

<div class="card p-3">
<div class="table-responsive">
<table class="table table-hover align-middle">

<thead class="table-dark text-center">
<tr>
<th>รูป</th>
<th>ชื่อ</th>
<th>รายละเอียด</th>
<th>พิกัด</th>
<th>หน่วยงาน</th>
<th>จัดการ</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr onclick="zoomMap(<?php echo $row['latitude']; ?>,<?php echo $row['longitude']; ?>)">

<td>
<?php if(!empty($row['photo'])) { ?>
<img src="uploads/<?php echo $row['photo']; ?>" width="80" class="rounded shadow-sm">
<?php } ?>
</td>

<td><b><?php echo $row['name']; ?></b></td>

<td><?php echo $row['descript']; ?></td>

<td>
<small>
<?php echo $row['latitude']; ?><br>
<?php echo $row['longitude']; ?>
</small>
</td>

<td>
<span class="badge bg-info text-dark">
<?php echo $row['ownplace']; ?>
</span>
</td>

<td class="text-center">
<a href="adjust.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
<a href="delete.php?id=<?php echo $row['id']; ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('ยืนยันการลบ?')">ลบ</a>
</td>

</tr>
<?php } ?>
</tbody>

</table>
</div>
</div>

</div>

<script>

// 🔥 สร้าง map
let map = L.map('map').setView([8.0, 99.5], 7);

// layer แผนที่
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
}).addTo(map);

// ข้อมูลจาก PHP
let locations = <?php echo json_encode($data); ?>;

// ถ้ามีข้อมูล → zoom ไปตัวแรก
if(locations.length > 0){
    map.setView([
        parseFloat(locations[0].latitude),
        parseFloat(locations[0].longitude)
    ], 10);
}

// ปักหมุด
locations.forEach(loc => {

    if(!loc.latitude || !loc.longitude) return;

    let marker = L.marker([
        parseFloat(loc.latitude),
        parseFloat(loc.longitude)
    ]).addTo(map);

    let popup = `
    <div style="width:200px">
        <h6>${loc.name}</h6>
        ${loc.photo ? `<img src="uploads/${loc.photo}" width="100%" style="border-radius:8px;">` : ''}
        <p style="font-size:13px">${loc.descript ?? ''}</p>
    </div>
    `;

    marker.bindPopup(popup);
});

// zoom จากตาราง
function zoomMap(lat,lng){
    if(!lat || !lng) return;
    map.setView([parseFloat(lat),parseFloat(lng)], 14);
}

</script>

</body>
</html>
    <div style="width:200px">
        <h6>${loc.name}</h6>
        ${loc.photo ? `<img src="uploads/${loc.photo}" width="100%" style="border-radius:8px;">` : ''}
        <p style="font-size:13px">${loc.descript}</p>
    </div>
    `;

    marker.bindPopup(popup);

});

// คลิกตาราง → zoom map
function zoomMap(lat,lng){
    map.setView([lat,lng], 14);
}

</script>

</body>
</html>