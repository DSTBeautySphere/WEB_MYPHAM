<!-- Modal Thêm Biến Thể -->
<div class="modal fade" id="addVariantModal" tabindex="-1" role="dialog" aria-labelledby="addVariantModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVariantModalLabel">Thêm Biến Thể Sản Phẩm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addVariantForm" action="/thembienthesanpham" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="form-control" id="ma_san_pham_modal" name="ma_san_pham" value="">

                    <div class="form-group" id="mau_sac_group" style="display: none;">
                        <label for="mau_sac">Màu sắc</label>
                        <select class="form-control" id="mau_sac" name="mau_sac">
                            <option value="">Chọn màu sắc</option>
                            @foreach($tuyChonList[1] ?? [] as $tuyChon)  <!-- Giả sử '1' là mã nhóm tùy chọn màu sắc -->
                                <option value="{{ $tuyChon->ten_tuy_chon }}">{{ $tuyChon->ten_tuy_chon }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group" id="loai_da_group" style="display: none;">
                        <label for="loai_da">Loại da</label>
                        <select class="form-control" id="loai_da" name="loai_da">
                            <option value="">Chọn loại da</option>
                            @foreach($tuyChonList[2] ?? [] as $tuyChon)  <!-- Giả sử '2' là mã nhóm tùy chọn loại da -->
                                <option value="{{ $tuyChon->ten_tuy_chon }}">{{ $tuyChon->ten_tuy_chon }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group" id="dung_tich_group" style="display: none;">
                        <label for="dung_tich">Dung tích</label>
                        <select class="form-control" id="dung_tich" name="dung_tich">
                            <option value="">Chọn dung tích</option>
                            @foreach($tuyChonList[3] ?? [] as $tuyChon)  <!-- Giả sử '3' là mã nhóm tùy chọn dung tích -->
                                <option value="{{ $tuyChon->ten_tuy_chon }}">{{ $tuyChon->ten_tuy_chon }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="so_luong_ton_kho">Số lượng tồn kho</label>
                        <input class="form-control" id="so_luong_ton_kho" name="so_luong_ton_kho" type="number" required>
                    </div>

                    <div class="form-group">
                        <label for="gia_ban">Giá bán</label>
                        <input class="form-control" id="gia_ban" name="gia_ban" type="number" step="0.01" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Lưu</button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                        
                    </div>
                </form>
            </div>
                
        </div>
    </div>
</div>
<script>
    // Kiểm tra và hiển thị nhóm tùy chọn nếu có dữ liệu
    @if(count($tuyChonList[1] ?? []) > 0)
        document.getElementById("mau_sac_group").style.display = "block";
    @endif

    @if(count($tuyChonList[2] ?? []) > 0)
        document.getElementById("loai_da_group").style.display = "block";
    @endif

    @if(count($tuyChonList[3] ?? []) > 0)
        document.getElementById("dung_tich_group").style.display = "block";
    @endif
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  




  $(document).ready(function() {
    // Gửi form qua AJAX
    $('#addVariantForm').on('submit', function(e) {
        e.preventDefault();  // Ngừng việc gửi form mặc định

        var formData = $(this).serialize();  // Lấy dữ liệu từ form

        $.ajax({
            url: '/thembienthesanpham',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert(response.message);  // Thông báo thành công
                    $('#myModal').modal('hide');  // Đóng modal

                    location.reload();  // Tải lại trang
                } else {
                    alert(response.message);  // Thông báo lỗi nếu có
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);  // In ra phản hồi từ server để kiểm tra chi tiết lỗi
                alert('Đã xảy ra lỗi: ' + error);  // Thông báo lỗi AJAX
            }
        });

    });
});



    

    
</script>
