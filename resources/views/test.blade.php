<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Mô Tả</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    padding: 20px;
}

h1 {
    text-align: center;
}

div {
    margin-bottom: 40px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
}

th, td {
    padding: 10px;
    text-align: left;
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
}

button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

button.delete {
    background-color: red;
}

button.delete:hover {
    background-color: #d32f2f;
}

</style>
<body>
    <div class="form-group col-md-12">
        <label class="control-label">Mô tả sản phẩm</label>
        <table class="table" id="dgv_mota">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Tên Mô Tả</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <input class="form-control" type="text" name="txt_tenmota" id="txt_tenmota" placeholder="Nhập Mô Tả">
        <button class="btn btn-save" id="btn_themmota">Thêm Mô Tả</button>
    </div>
    
    <div class="form-group col-md-12">
        <label class="control-label">Mô tả sản phẩm</label>
        <label class="control-label" id="lb_not">Vui Lòng Chọn Mô Tả Trước Khi Thêm Chi Tiết Mô Tả!</label>
        <table class="table" id="dgv_chitietmota">
            <thead>
                <tr>
                    <th>Tiêu Đề</th>
                    <th>Nội Dung</th>
                    <th>Thao Tác</th>
                </tr>
                <tbody>
                    
                </tbody>
            </thead>
        </table>
        <textarea class="form-control" name="txt_tieude" id="txt_tieude" placeholder="Nhập Tiêu Đề"></textarea>
        <textarea class="form-control" name="txt_noidung" id="txt_noidung" placeholder="Nhập Nội Dung"></textarea>
        <button class="btn btn-save" id="btn_themctmota" >Thêm Chi Tiết Mô Tả</button>
    </div>

    <script>
        // Mảng lưu mô tả
let ds_mota = [];
// Mảng lưu chi tiết mô tả
let ds_ctmota = [];
let temp_mota = null;

// Hàm thêm mô tả
document.getElementById("btn_themmota").addEventListener("click", function() {
    const tenMoTa = document.getElementById("txt_tenmota").value;
    
    if (tenMoTa) {
        const maMoTa = Math.floor(Math.random() * 100000); // Tạo mã mô tả ngẫu nhiên
        const mota = { ma_mo_ta: maMoTa, ten_mo_ta: tenMoTa };
        ds_mota.push(mota);
        Load_TableMoTa();
        document.getElementById("txt_tenmota").value = ''; // Clear input
    } else {
        alert("Vui lòng nhập tên mô tả!");
    }
});

// Hàm thêm chi tiết mô tả
document.getElementById("btn_themctmota").addEventListener("click", function() {
    if (temp_mota !== null) {
        const tieuDe = document.getElementById("txt_tieude").value;
        const noiDung = document.getElementById("txt_noidung").value;
        
        if (tieuDe && noiDung) {
            const chiTiet = { ma_mo_ta: temp_mota, tieu_de: tieuDe, noi_dung: noiDung };
            ds_ctmota.push(chiTiet);
            Load_TableMoTaChiTiet(temp_mota);
            document.getElementById("txt_tieude").value = ''; // Clear input
            document.getElementById("txt_noidung").value = ''; // Clear textarea
        } else {
            alert("Vui lòng nhập tiêu đề và nội dung chi tiết!");
        }
    } else {
        alert("Vui lòng chọn mô tả trước khi thêm chi tiết!");
    }
});

// Load bảng mô tả
function Load_TableMoTa() {
    const tbody = document.getElementById("dgv_mota").getElementsByTagName("tbody")[0];
    tbody.innerHTML = ''; // Clear previous data
    
    ds_mota.forEach(function(moTa) {
        const row = tbody.insertRow();
        row.insertCell(0).innerText = moTa.ten_mo_ta;
        
        const btnSelect = document.createElement("button");
        btnSelect.innerText = "Chọn";
        btnSelect.addEventListener("click", function() {
            temp_mota = moTa.ma_mo_ta;
            document.getElementById("lb_not").innerText = `Nhập Thông Tin Chi Tiết Cho [${temp_mota}]`;
            Load_TableMoTaChiTiet(temp_mota);
        });

        const btnEdit = document.createElement("button");
        btnEdit.innerText = "Sửa";
        btnEdit.addEventListener("click", function() {
            const newName = prompt("Nhập tên mới cho mô tả:", moTa.ten_mo_ta);
            if (newName) {
                moTa.ten_mo_ta = newName;
                Load_TableMoTa();
            }
        });

        const btnDelete = document.createElement("button");
        btnDelete.innerText = "Xóa";
        btnDelete.classList.add("delete");
        btnDelete.addEventListener("click", function() {
            const index = ds_mota.indexOf(moTa);
            if (index > -1) {
                ds_mota.splice(index, 1);
                Load_TableMoTa();
            }
        });

        const cellBtn = row.insertCell(1);
        cellBtn.appendChild(btnSelect);
        cellBtn.appendChild(btnEdit);
        cellBtn.appendChild(btnDelete);
    });
}

// Load bảng chi tiết mô tả
function Load_TableMoTaChiTiet(ma_mo_ta) {
    const tbody = document.getElementById("dgv_chitietmota").getElementsByTagName("tbody")[0];
    tbody.innerHTML = ''; // Clear previous data
    
    const chiTietList = ds_ctmota.filter(ct => ct.ma_mo_ta === ma_mo_ta);
    chiTietList.forEach(function(ct) {
        const row = tbody.insertRow();
        row.insertCell(0).innerText = ct.tieu_de;
        row.insertCell(1).innerText = ct.noi_dung;
        
        const btnEdit = document.createElement("button");
        btnEdit.innerText = "Sửa";
        btnEdit.addEventListener("click", function() {
            const newTieuDe = prompt("Nhập tiêu đề mới:", ct.tieu_de);
            const newNoiDung = prompt("Nhập nội dung mới:", ct.noi_dung);
            if (newTieuDe && newNoiDung) {
                ct.tieu_de = newTieuDe;
                ct.noi_dung = newNoiDung;
                Load_TableMoTaChiTiet(ma_mo_ta);
            }
        });

        const btnDelete = document.createElement("button");
        btnDelete.innerText = "Xóa";
        btnDelete.classList.add("delete");
        btnDelete.addEventListener("click", function() {
            const index = ds_ctmota.indexOf(ct);
            if (index > -1) {
                ds_ctmota.splice(index, 1);
                Load_TableMoTaChiTiet(ma_mo_ta);
            }
        });

        const cellBtn = row.insertCell(2);
        cellBtn.appendChild(btnEdit);
        cellBtn.appendChild(btnDelete);
    });
}
    </script>

</body>
</html>
