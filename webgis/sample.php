<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>นำเข้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap');

body{
    font-family:'Sarabun',sans-serif;
    background: linear-gradient(135deg,#1e3c72,#2a5298);
    min-height:100vh;
}

/* CARD */
.card{
    border:none;
    border-radius:20px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    box-shadow:0 20px 50px rgba(0,0,0,0.25);
}

/* HEADER */
.card-header{
    background: linear-gradient(90deg,#0f2027,#203a43,#2c5364);
    border-radius:20px 20px 0 0 !important;
    font-weight:600;
    letter-spacing:.5px;
}

/* FORM */
.form-label{
    font-weight:600;
    color:#333;
}

.form-control{
    border-radius:10px;
    height:45px;
    border:1px solid #ddd;
    transition:.2s;
}

textarea.form-control{
    height:auto;
}

.form-control:focus{
    border-color:#3498db;
    box-shadow:0 0 6px rgba(52,152,219,.3);
}

/* MAP */
#map{
    height:420px;
    border-radius:15px;
    border:2px solid #eee;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}

/* BUTTON */
.btn-primary{
    height:55px;
    border-radius:12px;
    font-size:18px;
    background: linear-gradient(45deg,#00c6ff,#0072ff);
    border:none;
    transition:.3s;
}

.btn-primary:hover{
    transform: translateY(-2px);
    box-shadow:0 10px 20px rgba(0,0,0,.2);
}

/* SECTION SPACING */
hr{
    margin:30px 0;
}

/* INPUT LAT LNG */
input[readonly]{
    background:#f8f9fa !important;
    font-weight:500;
}
</style>
</head>
<body class="bg-light">
    <div class="container mt-4 mb-5">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">ระบบย่อยการนำเข้าสถานที่ท่องเที่ยว</h4>
            </div>
            <div class="card-body">
                <form action="sampleinsert2db.php" method="POST" enctype="multipart/form-data">
                  <div class="mb-3">
                        <label class="form-label">ระบุชื่อสถานที่ท่องเที่ยว</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">สาระสังเขป</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ประเภทของสถานที่:</label>
                            <select id="typeplace" name="typeplace" class="form-control" >
                                <option value="">---เลือกประเภทสถานที่ท่องเที่ยว---</option>
                                <option value="โบราณสถานที่ขึ้นทะเบียนแล้ว">โบราณสถานที่ขึ้นทะเบียนแล้ว</option>
                                <option value="พิพิธภัณฑ์และแหล่งเรียนรู้">พิพิธภัณฑ์และแหล่งเรียนรู้</option>
                                <option value="แหล่งประวัติศาสตร์">เแหล่งประวัติศาสตร์</option>
                                <option value="ความเชื่อประเพณี">ความเชื่อประเพณี</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">หน่วยงานที่ดูแลสถานที่ท่องเที่ยว :</label>
                            <select id="ownplace" name="ownplace" class="form-control" >
                                <option value="0"><< เลือกหน่วยงานดูแลสถานที่ท่องเที่ยว—>></option>
                                <option value="อบจ">อบจ</option>
                                <option value="อบต">อบต</option>
                                <option value="กระทรวงวัฒนธรรม">กระทรวงวัฒนธรรม</option>
                                <option value="กรมศิลปากร">กรมศิลปากร</option>
                                <option value="มูลนิธิ">มูลนิธิ</option>
                            </select>
                        </div>
                    </div>    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">แทรกรูปภาพ</label>
                            <input type="file" name="photo" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">แทรกไฟล์ (PDF/Docx)</label>
                            <input type="file" name="filepdf2" class="form-control" accept=".pdf,.docx">
                        </div>
                    </div>    
                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-bold">แสดงแผนที่บน Google Maps:</label>
                        <div id="map"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ละติจูด(Latitude)</label>
                            <input type="text" name="latitude" id="lat" class="form-control bg-white" readonly required placeholder="กรุณาระบุเลือกสถานที่">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ลองจิจูด (Longitude)</label>
                            <input type="text" name="longitude" id="lng" class="form-control bg-white" readonly required placeholder="กรุณาระบุเลือกสถานที่">
                        </div>
                    </div>

                

                    <button type="submit" name="submit" class="btn btn-primary btn-lg w-100">บันทึกข้อมูล:</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5l0aPyGFqiqY5DpqOYf-8YhN_eYHHobU&callback=initMap" async defer></script>

    <script>
        let map;
        let marker;

        function initMap() {
            // เธเธดเธเธฑเธ”เน€เธฃเธดเนเธกเธ•เนเธ (เธเธฃเธธเธเน€เธ—เธเธฏ)
            const myLatLng = { lat: 6.87563, lng: 101.245018 };

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: myLatLng,
                mapTypeControl: true,
                streetViewControl: false
            });

            // เธ•เธฃเธงเธเธเธฑเธเธเธฒเธฃเธเธฅเธดเธเธเธเนเธเธเธ—เธตเน
            map.addListener("click", (mapsMouseEvent) => {
                const position = mapsMouseEvent.latLng;

                // เธญเธฑเธเน€เธ”เธ•เธเนเธฒเนเธ Input Box
                document.getElementById("lat").value = position.lat().toFixed(7);
                document.getElementById("lng").value = position.lng().toFixed(7);

                // เธขเนเธฒเธขเธซเธฃเธทเธญเธชเธฃเนเธฒเธ Marker เนเธซเธกเน
                if (marker) {
                    marker.setPosition(position);
                } else {
                    marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        animation: google.maps.Animation.DROP
                    });
                }
            });
        }
    </script>
</body>
</html>