@extends('layout.index')
@section('title','Management Promotion')
@section('css')
<style>
.promotion-info {
    max-width: 800px; 
    margin: 0 auto;
}

.form-row {
    display: flex;
    justify-content: space-between; 
    gap: 10px; 
    align-items: flex-start;
}

.form-group {
    flex: 1;
    min-width: 150px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input.form-control {
    width: 100%;
    padding: 8px;
    font-size: 14px;
}

.product-list {
    max-width: 1000px;
    margin: 0 auto;
}

.product-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: flex-start;
    max-height: 300px; /* Giới hạn chiều cao */
    overflow-y: auto; /* Hiển thị thanh cuộn dọc nếu nội dung vượt quá chiều cao */
}


.product-card {
    flex: 1 0 21%;  
    min-width: 200px; 
    max-width: 210px; 
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
    padding: 10px;  
}

.product-card input {
    margin-bottom: 10px;
}

.product-card p {
    margin: 5px 0;
}

button#apply-discount {
    margin-top: 20px;
    display: block;
    width: 200px;
    margin-left: auto;
    margin-right: auto;
}
.product-checkbox{
    float: left;
}
.form-row {
    display: flex;
    justify-content: space-between;
    gap: 10px; /* Khoảng cách giữa các ô */
    align-items: flex-start;
    margin-top: 10px; /* Tạo khoảng cách với phần trên */
}

.form-group {
    flex: 1;
    min-width: 150px;
}

label {
    display: block;
    margin-bottom: 5px;
}

select.form-control {
    width: 100%;
    padding: 8px;
    font-size: 14px;
}


</style>
@endsection

@section('content')
<div class="container">
    <!-- Phần thông tin giảm giá -->
    <div class="promotion-info">      
        <form id="promotion-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="discount">Mức giảm giá (%):</label>
                    <select id="discount" name="discount" class="form-control">
                        <option value="">Mức giảm giá</option>
                        
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Ngày bắt đầu:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="end_date">Ngày kết thúc:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                </div>
            </div>
        </form>
    
        <!-- 3 Select Boxes -->
        <div class="form-row">
            <div class="form-group">
                <select id="dongSanPham" name="dongSanPham" class="form-control">
                    

                </select>
            </div>
            <div class="form-group">
                <select id="loaiSanPham" name="loaiSanPham" class="form-control">
                    
                </select>
            </div>
            {{-- <div class="form-group">
                <select id="product" name="product" class="form-control">
                    
                </select>
            </div> --}}
        </div>
    </div>
    
    
    <div class="product-list">
        <h3>Product List</h3>
        <input type="checkbox" id="check-all" class="check-all-checkbox"> Check All

        <div class="product-grid">
            
        </div>
        <button type="button" id="apply-discount" class="btn btn-success">Apply Discount</button>
    </div>
    <div>
        <table>
            <thead>
                <tr>
                   
                    <th>Tên sản phẩm</th>
                    <th>Discount(%)</th>
                    <th>Hình sản phẩm</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody id="product-list-discount">
               
            </tbody>
        </table>
    </div>
    
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script> 
    $(document).ready(function() {
        var discountSelect = $('#discount');

        // Tạo các option với mức giảm giá từ 5% đến 50% với bước nhảy là 5%
        for (var i = 5; i <= 50; i += 5) {
            discountSelect.append('<option value="' + i + '">' + i + '%</option>');
        }
    });
    $(document).ready(function() {
        // Load dòng sản phẩm và loại sản phẩm khi trang được tải
        loadDongSanPham();
        loadLoaiSanPham();
        loadDiscountProducts();
        // Hàm tải dòng sản phẩm
        function loadDongSanPham() {
            $.ajax({
                url: '/by-dong', // Gọi API lấy dòng sản phẩm
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var options = ' <option value="">Chọn dòng sản phẩm</option>';
                    
                    // Duyệt qua dữ liệu dòng sản phẩm và thêm vào select box
                    $.each(data, function(index, dong) {
                        options += `<option value="${dong.ma_dong_san_pham}">${dong.ten_dong_san_pham}</option>`;
                    });

                    // Cập nhật lại dropdown dòng sản phẩm
                    $('#dongSanPham').html(options);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching dòng sản phẩm data: ', error);
                }
            });
        }

        // Hàm tải loại sản phẩm
        function loadLoaiSanPham() {
            $.ajax({
                url: '/by-loai', // Gọi API lấy loại sản phẩm
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var options = ' <option value="">Chọn loại sản phẩm</option>';
                    
                    // Duyệt qua dữ liệu loại sản phẩm và thêm vào select box
                    $.each(data, function(index, loai) {
                        options += `<option value="${loai.ma_loai_san_pham}">${loai.ten_loai_san_pham}</option>`;
                    });

                    // Cập nhật lại dropdown loại sản phẩm
                    $('#loaiSanPham').html(options);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching loại sản phẩm data: ', error);
                }
            });
        }

        // Lắng nghe sự kiện thay đổi trên dropdown dòng sản phẩm
        $('#dongSanPham').on('change', function() {
            var dongSanPhamId = $(this).val();
            if (dongSanPhamId) {
                loadSanPhamByDong(dongSanPhamId); // Tải sản phẩm theo dòng sản phẩm đã chọn
            } else {
                $('.product-grid').empty(); // Nếu không chọn, xóa hết sản phẩm
            }
        });

        // Lắng nghe sự kiện thay đổi trên dropdown loại sản phẩm
        $('#loaiSanPham').on('change', function() {
            var loaiSanPhamId = $(this).val();
            if (loaiSanPhamId) {
                loadSanPhamByLoai(loaiSanPhamId); // Tải sản phẩm theo loại sản phẩm đã chọn
            } else {
                $('.product-grid').empty(); // Nếu không chọn, xóa hết sản phẩm
            }
        });

        // Hàm tải sản phẩm theo dòng sản phẩm
        function loadSanPhamByDong(dongSanPhamId) {
            $.ajax({
                url: '/getsanpham-by-dong',
                method: 'GET',
                data: { ma_dong_san_pham: dongSanPhamId },
                dataType: 'json',
                success: function(data) {
                    displayProducts(data); // Hiển thị sản phẩm
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching products by dòng: ', error);
                }
            });
        }

        // Hàm tải sản phẩm theo loại sản phẩm
        function loadSanPhamByLoai(loaiSanPhamId) {
            $.ajax({
                url: '/getsanpham-by-loai',
                method: 'GET',
                data: { ma_loai_san_pham: loaiSanPhamId },
                dataType: 'json',
                success: function(data) {
                    displayProducts(data); // Hiển thị sản phẩm
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching products by loại: ', error);
                }
            });
        }

        // Hàm hiển thị sản phẩm
        
        function displayProducts(data) {
            $('.product-grid').empty(); // Xóa hết sản phẩm cũ
            $.each(data, function(index, product) {
                // Tìm ảnh chính hoặc sử dụng ảnh đầu tiên, nếu không có ảnh thì sử dụng ảnh mặc định
                let imageUrl = '/images/default-product.jpg'; // Ảnh mặc định
                if (product.anh_san_pham && product.anh_san_pham.length > 0) {
                    const mainImage = product.anh_san_pham.find(image => image.la_anh_chinh === 1); // Tìm ảnh chính
                    imageUrl = mainImage ? mainImage.url_anh : product.anh_san_pham[0].url_anh; // Ảnh chính hoặc ảnh đầu tiên
                }

                // Tạo HTML cho sản phẩm
                var productHtml = `
                    <div class="product-card">
                        <input type="checkbox" class="product-checkbox" data-product-id="${product.ma_san_pham}">
                        <div style="clear: both"></div>
                        <p><img style="width: 150px;" src="${imageUrl}" alt="Product Image"></p>
                        <p><strong>Product Name:</strong> ${product.ten_san_pham}</p>
                       
                    </div>`;
                
                // Thêm sản phẩm vào grid
                $('.product-grid').append(productHtml);
            });
        }

        var selectedProducts = []; 
            //này check all
            $('#check-all').on('change', function() {
                var isChecked = $(this).prop('checked'); 
                $('.product-checkbox').prop('checked', isChecked);

                if (isChecked) {
                    $('.product-checkbox').each(function() {
                        selectedProducts.push($(this).data('product-id'));
                    });
                } else {
                    selectedProducts = [];
                }
                console.log('Selected Products:', selectedProducts); 
        });
        //này xóa check hoặc thêm
        $(document).on('change', '.product-checkbox', function() {
            var productId = $(this).data('product-id'); 
            var isChecked = $(this).prop('checked'); 

            if (isChecked) {
                if (!selectedProducts.includes(productId)) {
                    selectedProducts.push(productId);
                }
            } else {
                var index = selectedProducts.indexOf(productId);
                if (index > -1) {
                    selectedProducts.splice(index, 1);
                }
            }
            console.log('Selected Products:', selectedProducts); 
        });

        //them khuyen mai 
        $('#apply-discount').on('click', function() {
            var selectedDiscount = $('#discount').val();
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            if (!selectedDiscount || !startDate || !endDate) {
                alert('Vui lòng nhập đầy đủ thông tin khuyến mãi!');
                return;
            }

            if (selectedProducts.length === 0) {
                alert('Vui lòng chọn ít nhất một sản phẩm!');
                return;
            }

            // Gửi dữ liệu lên server
            $.ajax({
                url: '/add-discount',
                method: 'POST',
                data: {
                    discount: selectedDiscount,
                    start_date: startDate,
                    end_date: endDate,
                    product_ids: selectedProducts
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert('Khuyến mãi đã được áp dụng thành công!');
                    console.log(response);
                    loadDiscountProducts();
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi áp dụng khuyến mãi:', error);
                    alert('Có lỗi xảy ra khi áp dụng khuyến mãi.');
                }
            });
        });

    });


    // $(document).ready(function() {
    //     // Gọi API để lấy danh sách sản phẩm
    //     $.ajax({
    //         url: '/getsanpham',  // Địa chỉ API
    //         type: 'GET',         // Phương thức GET
    //         dataType: 'json',    // Dữ liệu trả về là JSON
    //         success: function(data) {
    //             // Làm sạch các lựa chọn cũ trong dropdown
    //             $('#product').empty();
                
    //             // Thêm option mặc định
    //             $('#product').append('<option value="">Chọn sản phẩm</option>');
                
    //             // Duyệt qua dữ liệu sản phẩm và thêm từng sản phẩm vào dropdown
    //             $.each(data, function(index, product) {
    //                 $('#product').append('<option value="' + product.ma_san_pham + '">' + product.ten_san_pham + '</option>');
    //             });
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error fetching product data: ', error);
    //         }
    //     });
    // });
   
    
    
    
</script>



{{-- <script>
        $(document).ready(function() {
        // Khi chọn dòng sản phẩm
        $('#dongSanPham').on('change', function() {
            var dongSanPhamId = $(this).val();
            selectedProducts = [];  // Reset danh sách sản phẩm đã chọn
            if (dongSanPhamId) {
                $.ajax({
                    url: '/getsanpham-by-dong',  // API lấy sản phẩm theo dòng
                    type: 'GET',
                    data: { ma_dong_san_pham: dongSanPhamId },
                    dataType: 'json',
                    success: function(data) {
                        $('.product-grid').empty();  // Xóa danh sách sản phẩm hiện tại

                        // Duyệt qua từng sản phẩm và hiển thị chúng
                        $.each(data, function(index, variation) {
                            var imageUrl = variation.product.product_images && variation.product.product_images.length > 0
                                ? variation.product.product_images[0].IMG_URL 
                                : '/images/team.jpg';

                            var productHtml = '<div class="product-card">' +
                                '<input type="checkbox" class="product-checkbox" data-product-id="' + variation.id + '">' +
                                '<div style="clear: both"></div>' +
                                '<p><img style="width: 150px;" src="' + imageUrl + '" alt="Product Image"></p>' +
                                '<p><strong>Product Name:</strong> ' + variation.product.ten_san_pham + '</p>' +
                                '<p><strong>Price:</strong> ' + variation.Price + '</p>' +
                                '</div>';

                            $('.product-grid').append(productHtml);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching products by dong: ', error);
                    }
                });
            } else {
                $('.product-grid').empty();  // Nếu không có dòng sản phẩm nào được chọn, xóa danh sách
            }
        });

        // Khi chọn loại sản phẩm
        $('#loaiSanPham').on('change', function() {
            var loaiSanPhamId = $(this).val();
            selectedProducts = [];  // Reset danh sách sản phẩm đã chọn
            if (loaiSanPhamId) {
                $.ajax({
                    url: '/getsanpham-by-loai',  // API lấy sản phẩm theo loại
                    type: 'GET',
                    data: { ma_loai_san_pham: loaiSanPhamId },
                    dataType: 'json',
                    success: function(data) {
                        $('.product-grid').empty();  // Xóa danh sách sản phẩm hiện tại

                        // Duyệt qua từng sản phẩm và hiển thị chúng
                        $.each(data, function(index, variation) {
                            var imageUrl = variation.product.product_images && variation.product.product_images.length > 0
                                ? variation.product.product_images[0].IMG_URL 
                                : '/images/team.jpg';

                            var productHtml = '<div class="product-card">' +
                                '<input type="checkbox" class="product-checkbox" data-product-id="' + variation.id + '">' +
                                '<div style="clear: both"></div>' +
                                '<p><img style="width: 150px;" src="' + imageUrl + '" alt="Product Image"></p>' +
                                '<p><strong>Product Name:</strong> ' + variation.product.ten_san_pham + '</p>' +
                                '<p><strong>Price:</strong> ' + variation.Price + '</p>' +
                                '</div>';

                            $('.product-grid').append(productHtml);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching products by loai: ', error);
                    }
                });
            } else {
                $('.product-grid').empty();  // Nếu không có loại sản phẩm nào được chọn, xóa danh sách
            }
        });
    });
</script> --}}
{{-- <script>
        $(document).ready(function() {
    // Khi chọn dòng sản phẩm
    $('#dongSanPham').on('change', function() {
        var dongSanPhamId = $(this).val();
        selectedProducts = [];  // Reset danh sách sản phẩm đã chọn
        if (dongSanPhamId) {
            $.ajax({
                url: '/getsanpham-by-dong',  // API lấy sản phẩm theo dòng
                type: 'GET',
                data: { ma_dong_san_pham: dongSanPhamId },
                dataType: 'json',
                success: function(data) {
                    $('.product-grid').empty();  // Xóa danh sách sản phẩm hiện tại

                    // Duyệt qua từng sản phẩm và hiển thị chúng
                    $.each(data, function(index, variation) {
                        var imageUrl = variation.product.product_images && variation.product.product_images.length > 0
                            ? variation.product.product_images[0].IMG_URL 
                            : '/images/team.jpg';

                        var productHtml = '<div class="product-card">' +
                            '<input type="checkbox" class="product-checkbox" data-product-id="' + variation.id + '">' +
                            '<div style="clear: both"></div>' +
                            '<p><img style="width: 150px;" src="' + imageUrl + '" alt="Product Image"></p>' +
                            '<p><strong>Product Name:</strong> ' + variation.product.productName + '</p>' +
                            '<p><strong>Price:</strong> ' + variation.Price + '</p>' +
                            '</div>';

                        $('.product-grid').append(productHtml);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching products by dong: ', error);
                }
            });
        } else {
            $('.product-grid').empty();  // Nếu không có dòng sản phẩm nào được chọn, xóa danh sách
        }
    });

    // Khi chọn loại sản phẩm
    $('#loaiSanPham').on('change', function() {
            var loaiSanPhamId = $(this).val();
            selectedProducts = [];  // Reset danh sách sản phẩm đã chọn
            if (loaiSanPhamId) {
                $.ajax({
                    url: '/getsanpham-by-loai',  // API lấy sản phẩm theo loại
                    type: 'GET',
                    data: { ma_loai_san_pham: loaiSanPhamId },
                    dataType: 'json',
                    success: function(data) {
                        $('.product-grid').empty();  // Xóa danh sách sản phẩm hiện tại

                        // Duyệt qua từng sản phẩm và hiển thị chúng
                        $.each(data, function(index, variation) {
                            var imageUrl = variation.product.product_images && variation.product.product_images.length > 0
                                ? variation.product.product_images[0].IMG_URL 
                                : '/images/team.jpg';

                            var productHtml = '<div class="product-card">' +
                                '<input type="checkbox" class="product-checkbox" data-product-id="' + variation.id + '">' +
                                '<div style="clear: both"></div>' +
                                '<p><img style="width: 150px;" src="' + imageUrl + '" alt="Product Image"></p>' +
                                '<p><strong>Product Name:</strong> ' + variation.product.productName + '</p>' +
                                '<p><strong>Price:</strong> ' + variation.Price + '</p>' +
                                '</div>';

                            $('.product-grid').append(productHtml);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching products by loai: ', error);
                    }
                });
            } else {
                $('.product-grid').empty();  // Nếu không có loại sản phẩm nào được chọn, xóa danh sách
            }
        });
    });

        // Áp dụng giảm giá cho sản phẩm đã chọn
        $('#apply-discount').click(function() {
            var selectedProducts = [];
            $('.product-checkbox:checked').each(function() {
                selectedProducts.push($(this).data('id'));
            });

            if (selectedProducts.length > 0) {
                $.ajax({
                    url: '/apply-discount',  // API áp dụng giảm giá
                    type: 'POST',
                    data: {
                        product_ids: selectedProducts,
                        discount_percentage: 10  // Tỷ lệ giảm giá (có thể thay đổi)
                    },
                    success: function(response) {
                        alert('Discount applied successfully!');
                        loadSanPhamByDong($('#dongSanPham').val());  // Tải lại sản phẩm sau khi áp dụng giảm giá
                    },
                    error: function(xhr, status, error) {
                        console.error('Error applying discount: ', error);
                    }
                });
            } else {
                alert('Please select at least one product.');
            }
        });

        // Áp dụng giảm giá cho 1 sản phẩm
        function applyDiscountToProduct(productId) {
            $.ajax({
                url: '/apply-discount',  // API áp dụng giảm giá cho sản phẩm
                type: 'POST',
                data: {
                    product_ids: [productId],
                    discount_percentage: 10  // Tỷ lệ giảm giá (có thể thay đổi)
                },
                success: function(response) {
                    alert('Discount applied to product!');
                    loadSanPhamByDong($('#dongSanPham').val());  // Tải lại sản phẩm sau khi áp dụng giảm giá
                },
                error: function(xhr, status, error) {
                    console.error('Error applying discount to product: ', error);
                }
            });
        }

        // Xóa giảm giá cho sản phẩm
        function removeDiscountFromProduct(productId) {
            $.ajax({
                url: '/remove-discount',  // API xóa giảm giá
                type: 'POST',
                data: { product_id: productId },
                success: function(response) {
                    alert('Discount removed from product!');
                    loadSanPhamByDong($('#dongSanPham').val());  // Tải lại sản phẩm sau khi xóa giảm giá
                },
                error: function(xhr, status, error) {
                    console.error('Error removing discount from product: ', error);
                }
            });
        }

        // Kiểm tra tất cả các sản phẩm
        $('#check-all').change(function() {
            if ($(this).prop('checked')) {
                $('.product-checkbox').prop('checked', true);
            } else {
                $('.product-checkbox').prop('checked', false);
            }
        });
    });

</script> --}}
{{-- check all cho ds chưa discount --}}
{{-- <script>
        $(document).ready(function() {
            var selectedProducts = []; 
            //này check all
            $('#check-all').on('change', function() {
                var isChecked = $(this).prop('checked'); 
                $('.product-checkbox').prop('checked', isChecked);

                if (isChecked) {
                    $('.product-checkbox').each(function() {
                        selectedProducts.push($(this).data('product-id'));
                    });
                } else {
                    selectedProducts = [];
                }
                console.log('Selected Products:', selectedProducts); 
        });
        //này xóa check hoặc thêm
        $(document).on('change', '.product-checkbox', function() {
            var productId = $(this).data('product-id'); 
            var isChecked = $(this).prop('checked'); 

            if (isChecked) {
                if (!selectedProducts.includes(productId)) {
                    selectedProducts.push(productId);
                }
            } else {
                var index = selectedProducts.indexOf(productId);
                if (index > -1) {
                    selectedProducts.splice(index, 1);
                }
            }
            console.log('Selected Products:', selectedProducts); 
        });
        //3 thàng sau này là lọc biến thể
        $(document).ready(function() {
            $('#dongSanPham').on('change', function() {
                var categoryId = $(this).val();
                selectedProducts = []; 
                if (categoryId) {
                    $.ajax({
                        url: '/getproductvariationbycategory', 
                        type: 'GET',
                        data: { id: categoryId },
                        dataType: 'json',
                        success: function(data) {
                            $('.product-grid').empty();

                            $.each(data, function(index, variation) {
                                
                                var imageUrl = variation.product.product_images && variation.product.product_images.length > 0
                                    ? variation.product.product_images[0].IMG_URL 
                                    : '/images/team.jpg';

                                var productHtml = '<div class="product-card">' +
                                    '<input type="checkbox" class="product-checkbox" data-product-id="' + variation.id + '">' +
                                    '<div style="clear: both"></div>' +
                                    '<p><img style="width: 150px;" src="' + imageUrl + '" alt="Product Image"></p>' +
                                    '<p><strong>Product Name:</strong> ' + variation.product.productName + '</p>' +
                                    '<p><strong>Price:</strong> ' + variation.Price + '</p>' +
                                    '</div>';

                                $('.product-grid').append(productHtml);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching product variations:', error);
                        }
                    });
                } else {
                    $('.product-grid').empty();
                }
            });
        });
        $(document).ready(function() {
            $('#subcategory').on('change', function() {
                var categoryId = $(this).val();
                selectedProducts = []; 
                if (categoryId) {
                    $.ajax({
                        url: '/getproductvariationbysubcategory', 
                        type: 'GET',
                        data: { id: categoryId },
                        dataType: 'json',
                        success: function(data) {
                            $('.product-grid').empty();

                            $.each(data, function(index, variation) {
                                
                                var imageUrl = variation.product.product_images && variation.product.product_images.length > 0
                                    ? variation.product.product_images[0].IMG_URL 
                                    : '/images/team.jpg';

                                var productHtml = '<div class="product-card">' +
                                    '<input type="checkbox" class="product-checkbox" data-product-id="' + variation.id + '">' +
                                    '<div style="clear: both"></div>' +
                                    '<p><img style="width: 150px;" src="' + imageUrl + '" alt="Product Image"></p>' +
                                    '<p><strong>Product Name:</strong> ' + variation.product.productName + '</p>' +
                                    '<p><strong>Price:</strong> ' + variation.Price + '</p>' +
                                    '</div>';

                                $('.product-grid').append(productHtml);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching product variations:', error);
                        }
                    });
                } else {
                    $('.product-grid').empty();
                }
            });
        });
        $(document).ready(function() {
            $('#product').on('change', function() {
                var categoryId = $(this).val();
                selectedProducts = []; 
                if (categoryId) {
                    $.ajax({
                        url: '/getproductvariationbyproduct', 
                        type: 'GET',
                        data: { id: categoryId },
                        dataType: 'json',
                        success: function(data) {
                            $('.product-grid').empty();

                            $.each(data, function(index, variation) {
                                
                                var imageUrl = variation.product.product_images && variation.product.product_images.length > 0
                                    ? variation.product.product_images[0].IMG_URL 
                                    : '/images/team.jpg';

                                var productHtml = '<div class="product-card">' +
                                    '<input type="checkbox" class="product-checkbox" data-product-id="' + variation.id + '">' +
                                    '<div style="clear: both"></div>' +
                                    '<p><img style="width: 150px;" src="' + imageUrl + '" alt="Product Image"></p>' +
                                    '<p><strong>Product Name:</strong> ' + variation.product.productName + '</p>' +
                                    '<p><strong>Price:</strong> ' + variation.Price + '</p>' +
                                    '</div>';

                                $('.product-grid').append(productHtml);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching product variations:', error);
                        }
                    });
                } else {
                    $('.product-grid').empty();
                }
            });
        });
        //này apply giảm giá
        $('#apply-discount').on('click', function () {
            var discountId = $('#discount').val();
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            console.log(discountId);
            console.log(startDate);
            console.log(endDate);
            // Kiểm tra nếu ngày bắt đầu và ngày kết thúc hợp lệ
            if (!discountId || !startDate || !endDate) {
                alert('Vui lòng chọn đầy đủ thông tin giảm giá, ngày bắt đầu và ngày kết thúc!');
                return;  // Dừng việc gửi yêu cầu nếu thiếu thông tin
            }

            // Kiểm tra ngày bắt đầu phải nhỏ hơn ngày kết thúc
            if (new Date(startDate) >= new Date(endDate)) {
                alert('Ngày bắt đầu phải nhỏ hơn ngày kết thúc!');
                return;  // Dừng nếu ngày không hợp lệ
            }

            // Gửi AJAX nếu tất cả các trường đã được điền đầy đủ và hợp lệ
            $.ajax({
                url: '/addvariationdiscount',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    ID_Variation: selectedProducts,
                    ID_Discount: discountId,
                    StartDate: startDate,
                    EndDate: endDate,
                },
                success: function(response) {
                    alert(response.message || 'Thêm giảm giá thành công!');
                    selectedProducts.forEach(function (productId) {
                        $('.product-checkbox[data-product-id="' + productId + '"]').closest('.product-card').remove();
                    });
                    loadProducts();
                    selectedProducts = [];
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseJSON);
                    if (xhr.responseJSON.errors) {
                        alert('Lỗi: ' + JSON.stringify(xhr.responseJSON.errors));
                    } else {
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    }
                },
            });
        });
        

    });

    function loadProducts() {
        $.ajax({
            url: '/getproductvariationdiscount', 
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (!Array.isArray(data)) {
                    console.error('Invalid data format:', data);
                    return;
                }
                $('#product-list-discount').empty();
                data.forEach(function(product) {
                    var row = `
                        <tr id="product-${product.id}">
                            <td>${product.product.productName}</td>
                            <td>${product.size}</td>
                            <td>${product.Price}</td>
                            <td>${product.variationdiscount?.[0]?.discount?.discount || 'N/A'}</td>
                            <td><img src="${product.product?.product_images?.[0]?.IMG_URL || 'placeholder.jpg'}" alt="${product.product.productName}" width="100"></td>
                            <td><button class="btn btn-success delete-discount" data-product-id="${product.id}">Accept</button></td>
                        </tr>
                    `;
                    $('#product-list-discount').append(row);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading products:', error);
            }
        });
    }
</script> --}}


<script>
    // $(document).ready(function () {
    //     loadDiscountProducts();

    //     function loadDiscountProducts() {
    //         // Gửi yêu cầu GET tới API
    //         $.ajax({
    //             url: '/with-discount', // API lấy danh sách sản phẩm khuyến mãi
    //             method: 'GET',
    //             dataType: 'json',
    //             success: function (data) {
    //                 // Làm trống bảng trước khi hiển thị dữ liệu mới
    //                 $('#product-list-discount').empty();

    //                 // Duyệt qua danh sách sản phẩm và thêm vào bảng
    //                 $.each(data, function (index, product) {
    //                     var rowHtml = `
    //                         <tr>
    //                             <td>${product.ten_san_pham}</td>
    //                             <td>${product.discount}%</td>
    //                             <td><img src="${product.image_url}" alt="Product Image" style="width: 100px; height: auto;"></td>
    //                             <td><button  class="btn btn-success delete-discount" data-id="${product.ma_san_pham}">Delete</button></td>
    //                         </tr>
    //                     `;
    //                     $('#product-list-discount').append(rowHtml);
    //                 });
    //                 // Gắn sự kiện Click sau khi thêm HTML
    //                 $('.delete-discount').on('click', function () {
    //                     var productId = $(this).data('id');
    //                     deleteDiscount(productId);
    //                 });
    //             },
    //             error: function (xhr, status, error) {
    //                 console.error('Error loading discount products:', error);
    //             }
    //         });
    //     }

    //     function deleteDiscount(productId) {
    //         if (!confirm('Bạn có chắc muốn xóa khuyến mãi này không?')) {
    //             return;
    //         }

    //         // Gửi yêu cầu POST tới server để xóa khuyến mãi
    //         $.ajax({
    //             url: '/remove-discount', // API xóa khuyến mãi
    //             method: 'POST',
    //             data: {
    //                 product_id: productId // Gửi product_id trong dữ liệu
    //             },
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token bảo mật
    //             },
    //             success: function (response) {
    //                 alert('Khuyến mãi đã được xóa thành công!');
    //                 loadDiscountProducts(); // Tải lại danh sách sản phẩm khuyến mãi
    //             },
    //             error: function (xhr, status, error) {
    //                 console.error('Error deleting discount:', error);
    //                 alert('Có lỗi xảy ra khi xóa khuyến mãi.');
    //             }
    //         });
    //     }

        
    // });
    function loadDiscountProducts() {
        // Gửi yêu cầu GET tới API
        $.ajax({
            url: '/with-discount', // API lấy danh sách sản phẩm khuyến mãi
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                // Làm trống bảng trước khi hiển thị dữ liệu mới
                $('#product-list-discount').empty();

                // Duyệt qua danh sách sản phẩm và thêm vào bảng
                $.each(data, function (index, product) {
                    var rowHtml = `
                        <tr>
                            <td>${product.ten_san_pham}</td>
                            <td>${product.discount}%</td>
                            <td><img src="${product.image_url}" alt="Product Image" style="width: 100px; height: auto;"></td>
                            <td><button class="btn btn-success delete-discount" data-id="${product.ma_san_pham}">Delete</button></td>
                        </tr>
                    `;
                    $('#product-list-discount').append(rowHtml);
                });

                // Gắn sự kiện Click sau khi thêm HTML
                $('.delete-discount').on('click', function () {
                    var productId = $(this).data('id');
                    deleteDiscount(productId);
                });
            },
            error: function (xhr, status, error) {
                console.error('Error loading discount products:', error);
            }
        });
    }

    function deleteDiscount(productId) {
        if (!confirm('Bạn có chắc muốn xóa khuyến mãi này không?')) {
            return;
        }

        // In giá trị product_id để kiểm tra trước khi gửi request
        console.log('Product ID:', productId);  // Hiển thị trong console của trình duyệt

        // Gửi yêu cầu POST tới server để xóa khuyến mãi
        $.ajax({
            url: '/delete-discount', // API xóa khuyến mãi
            method: 'POST',
            data: {
                product_id: productId // Gửi product_id trong dữ liệu
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token bảo mật
            },
            success: function (response) {
                console.log('Response from server:', response); // Log response từ server
                alert('Khuyến mãi đã được xóa thành công!');
                loadDiscountProducts(); // Tải lại danh sách sản phẩm khuyến mãi
            },
            error: function (xhr, status, error) {
                console.error('Error deleting discount:', error); // Log lỗi nếu có
                alert('Có lỗi xảy ra khi xóa khuyến mãi.');
            }
        });
    }

    $(document).ready(function () {
        loadDiscountProducts();

       
    });

    
</script>
@endsection
