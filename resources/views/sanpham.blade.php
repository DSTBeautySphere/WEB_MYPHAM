<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <style>
        .container {
            width: 80%; 
            margin: auto;
            margin-top: 10px; 
        }
    </style>
</head>
<body>
    <h3 style="width:80%; margin:auto">san_phamController</h3>
    <div class="container">
        <table class="table">
            <thead>
              <tr class="table-info">
                <th scope="col">Tất Cả SP</th>
                <th scope="col">Url: /sanpham </th>
                <th scope="col">lay_san_pham()</th>
              </tr>
            </thead>
            <tbody>
              <tr class="table-success">
                <td>GET</td>
                <td>#</td>
                <td>#</td>
              </tr>
            </tbody>
          </table>
    </div>

    <div class="container">
      <table class="table">
          <thead>
            <tr class="table-info">
              <th scope="col">Lấy Sản Phẩm Phân Trang</th>
              <th scope="col">Url: /sanphamphantrang</th>
              <th scope="col">lay_san_pham_phan_trang()</th>
            </tr>
          </thead>
          <tbody>
            <tr class="table-success">
              <td>GET</td>
              <td>?so_san_pham=4</td>
              <td>{"so_san_pham":4}</td>
            </tr>
          </tbody>
        </table>
  </div>

    <div class="container">
        <table class="table">
            <thead>
              <tr class="table-info">
                <th scope="col">Lọc SP Theo Loai</th>
                <th scope="col">Url: /locsanphamtheoloai </th>
                <th scope="col">loc_san_pham_theo_loai()</th>
              </tr>
            </thead>
            <tbody>
              <tr class="table-success">
                <td>GET</td>
                <td>?ma_loai_san_pham=1</td>
                <td>
                    {
                        "ma_loai_san_pham": 1   
                    }   
                </td>
              </tr>
            </tbody>
          </table>
    </div>

    <div class="container">
        <table class="table">
            <thead>
              <tr class="table-info">
                <th scope="col">Lọc SP Theo Dong</th>
                <th scope="col">Url: /locsanphamtheodong </th>
                <th scope="col">loc_san_pham_theo_dong()</th>
              </tr>
            </thead>
            <tbody>
              <tr class="table-success">
                <td>GET</td>
                <td>?ma_dong_san_pham=1</td>
                <td>
                    {
                        "ma_dong_san_pham": 1   
                    }   
                </td>
              </tr>
            </tbody>
          </table>
    </div>

    <div class="container">
      <table class="table">
          <thead>
            <tr class="table-info">
              <th scope="col">Xem Chi Tiet SP</th>
              <th scope="col">Url: /chitietsanpham </th>
              <th scope="col">chi_tiet_san_pham()</th>
            </tr>
          </thead>
          <tbody>
            <tr class="table-success">
              <td>GET</td>
              <td>?ma_san_pham=1</td>
              <td>
                  {
                      "ma_san_pham": 1   
                  }   
              </td>
            </tr>
          </tbody>
        </table>
  </div>
  <div class="container">
    <table class="table">
        <thead>
          <tr class="table-info">
            <th scope="col">Lọc Sản Phẩm Theo Giá</th>
            <th scope="col">Url: /locsanphamtheogia </th>
            <th scope="col">loc_san_pham_theo_gia()</th>
          </tr>
        </thead>
        <tbody>
          <tr class="table-success">
            <td>GET</td>
            <td>
              Tối Thiểu: ?min=500<br>
              Tối Đa   : ?max=350000<br>
              Khoản    : ?min=300&max=1000000
            </td>
            <td>
                {
                    "min":500  
                } <br>
                {
                    "max":350000  
                } <br>
                {
                    "min":500,
                    "max":350000  
                }
            </td>
          </tr>
        </tbody>
      </table>
  </div>
  <div class="container">
    <table class="table">
        <thead>
          <tr class="table-info">
            <th scope="col">Thêm Sản Phẩm</th>
            <th scope="col">Url: /themsanpham </th>
            <th scope="col">them_san_pham()</th>
          </tr>
        </thead>
        <tbody>
          <tr class="table-success">
            <td>POST</td>
            <td>#</td>
            <td>
              {<br>
                "ma_loai_san_pham": 1,<br>
                "ma_nha_cung_cap": 1,<br>
                "ten_san_pham": "Son môi",<br>
                "mau_sac": "Đỏ",<br>
                "tinh_trang": "hahaha",<br>
                "gia_ban": 200000,<br>
                "mo_ta": "Son môi màu đỏ, không chứa chì"<br>
              } 
            </td>
          </tr>
        </tbody>
      </table>
</div>

<div class="container">
  <table class="table">
      <thead>
        <tr class="table-info">
          <th scope="col">Xóa Sản Phẩm</th>
          <th scope="col">Url: /xoasanpham </th>
          <th scope="col">xoa_san_pham()</th>
        </tr>
      </thead>
      <tbody>
        <tr class="table-success">
          <td>POST</td>
          <td>#</td>
          <td>
              {
                  "ma_san_pham": 6  
              }   
          </td>
        </tr>
      </tbody>
    </table>
</div>

<div class="container">
  <table class="table">
      <thead>
        <tr class="table-info">
          <th scope="col">Cập Nhật Sản Phẩm</th>
          <th scope="col">Url: /capnhatsanpham </th>
          <th scope="col">cap_nhat_san_pham()</th>
        </tr>
      </thead>
      <tbody>
        <tr class="table-success">
          <td>POST</td>
          <td>#</td>
          <td>
            {<br>
              "ma_san_pham":1,<br>
              "ma_loai_san_pham": 1,<br>
              "ma_nha_cung_cap": 1,<br>
              "ten_san_pham": "Son môi",<br>
              "mau_sac": "Đỏ",<br>
              "tinh_trang": "hahaha",<br>
              "gia_ban": 200000,<br>
              "mo_ta": "Son môi màu đỏ, không chứa chì"<br>
           }  
          </td>
        </tr>
      </tbody>
    </table>
</div>


</body>
</html>