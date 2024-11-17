<!-- Modal Sửa Biến Thể -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Chỉnh sửa biến thể</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editVariantForm" method="POST" action="/suabienthesanpham"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                

                    <input type="hidden" id="ma_bien_the" name="ma_bien_the">
                    <div class="form-group" id="mau_sac_group1" style="display: none;">
                        <label for="mau_sac">Màu sắc</label>
                        <select class="form-control" id="mau_sac" name="mau_sac">
                            <option value="">Chọn màu sắc</option>
                            @foreach($tuyChonList[1] ?? [] as $tuyChon)  <!-- Giả sử '1' là mã nhóm tùy chọn màu sắc -->
                                <option value="{{ $tuyChon->ten_tuy_chon }}">{{ $tuyChon->ten_tuy_chon }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group" id="loai_da_group1" style="display: none;">
                        <label for="loai_da">Loại da</label>
                        <select class="form-control" id="loai_da" name="loai_da">
                            <option value="">Chọn loại da</option>
                            @foreach($tuyChonList[2] ?? [] as $tuyChon)  <!-- Giả sử '2' là mã nhóm tùy chọn loại da -->
                                <option value="{{ $tuyChon->ten_tuy_chon }}">{{ $tuyChon->ten_tuy_chon }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group" id="dung_tich_group1" style="display: none;">
                        <label for="dung_tich">Dung tích</label>
                        <select class="form-control" id="dung_tich" name="dung_tich">
                            <option value="">Chọn dung tích</option>
                            @foreach($tuyChonList[3] ?? [] as $tuyChon)  <!-- Giả sử '3' là mã nhóm tùy chọn dung tích -->
                                <option value="{{ $tuyChon->ten_tuy_chon }}">{{ $tuyChon->ten_tuy_chon }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="so_luong_ton_kho">Số lượng tồn kho:</label>
                        <input type="number" class="form-control" id="so_luong_ton_kho" name="so_luong_ton_kho" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="gia_ban">Giá bán:</label>
                        <input type="text" class="form-control" id="gia_ban" name="gia_ban" value="" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
    $('#editVariantForm').on('submit', function(event) {
        

        // Lấy dữ liệu từ form
        var data = {
            _token: '{{ csrf_token() }}', // CSRF token để bảo mật
            ma_bien_the: $('#ma_bien_the').val(),
            mau_sac: $('#mau_sac').val(),
            loai_da: $('#loai_da').val(),
            dung_tich: $('#dung_tich').val(),
            so_luong_ton_kho: $('#so_luong_ton_kho').val(),
            gia_ban: $('#gia_ban').val(),
        };

        // Gửi AJAX đến server để cập nhật biến thể
        $.ajax({
            url: '/suabienthesanpham', // Đường dẫn đến route xử lý
            type: 'POST',
            data: data,
            // success: function(response) {
            //     if (response.success) {
            //         alert('Cập nhật thành công!');
            //         $('#editModal').modal('hide');   // Đóng modal
            //         location.reload(); // Reload lại trang để hiển thị dữ liệu mới
            //     } else {
            //         alert('Cập nhật thất bại: ' + response.message);
            //     }
            // },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Đã xảy ra lỗi: ' + error);
            }
        });
        
    });
       // Kiểm tra và hiển thị nhóm tùy chọn nếu có dữ liệu
    @if(count($tuyChonList[1] ?? []) > 0)
        document.getElementById("mau_sac_group1").style.display = "block";
    @endif

    @if(count($tuyChonList[2] ?? []) > 0)
        document.getElementById("loai_da_group1").style.display = "block";
    @endif

    @if(count($tuyChonList[3] ?? []) > 0)
        document.getElementById("dung_tich_group1").style.display = "block";
    @endif

</script>
