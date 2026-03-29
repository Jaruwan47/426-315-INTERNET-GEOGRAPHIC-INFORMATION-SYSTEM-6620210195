<?php
require_once('conpolitic.php');

if(isset($_POST['submit'])){

    // รับค่า
    $name  = $_POST['name'];
    $desc  = $_POST['description'];
    $owner = $_POST['ownplace'];
    $lat   = $_POST['latitude'];
    $lng   = $_POST['longitude'];

    // 🔥 สร้างโฟลเดอร์ uploads ถ้ายังไม่มี
    $uploadDir = __DIR__ . "/uploads/";
    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    // 🔥 สร้างชื่อไฟล์ใหม่
    $photoName = time() . "_" . basename($_FILES['photo']['name']);
    $target = $uploadDir . $photoName;

    // 🔥 อัปโหลดไฟล์
    if(move_uploaded_file($_FILES["photo"]["tmp_name"], $target)){

        // 🔥 INSERT ให้ตรงกับตาราง krabi_tour
        $sql = "INSERT INTO krabi_tour 
        (name, descript, ownplace, latitude, longitude, photo)
        VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if($stmt){
            $stmt->bind_param("sssdds", $name, $desc, $owner, $lat, $lng, $photoName);

            if($stmt->execute()){
                echo "<script>
                        alert('✅ บันทึกข้อมูลเรียบร้อย');
                        window.location='new.php';
                      </script>";
            }else{
                echo "❌ Insert Error: " . $stmt->error;
            }

        }else{
            echo "❌ Prepare Error: " . $conn->error;
        }

    }else{
        echo "❌ อัปโหลดรูปไม่สำเร็จ";
    }
}
?>