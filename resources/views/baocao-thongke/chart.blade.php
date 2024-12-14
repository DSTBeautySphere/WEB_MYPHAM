{{-- <div class="row mt-4">
    <!-- Biểu đồ danh mục -->
    <div class="col-md-6"> <!-- Chiếm 6 cột -->
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Doanh thu loại sản phẩm</h6>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" style="height: 250px; width: 100%"></canvas>
            </div>
        </div>
    </div>

    <!-- Biểu đồ sản phẩm -->
    <div class="col-md-6"> <!-- Chiếm 6 cột -->
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Doanh thu sản phẩm</h6>
            </div>
            <div class="card-body">
                <canvas id="productChart" style="height: 250px; width: 100%"></canvas>
            </div>
        </div>
    </div>
</div> --}}
<div class="row mt-4">
    <!-- Doanh thu theo loại sản phẩm -->
    <div class="col-md-12">
        <div class="row">
            <!-- Bảng dữ liệu -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Bảng Doanh thu loại sản phẩm</h6>
                    </div>
                    <div class="card-body">
                        <table id="categoryTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Loại sản phẩm</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Nội dung sẽ được thêm qua JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Doanh thu loại sản phẩm</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryChart" style="height: 250px; width: 100%"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Doanh thu theo sản phẩm -->
    <div class="col-md-12">
        <div class="row">
            <!-- Bảng dữ liệu -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Bảng Doanh thu sản phẩm</h6>
                    </div>
                    <div class="card-body">
                        {{-- <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody id="productTable">
                               
                            </tbody>
                        </table> --}}
                        <table id="productTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Nội dung sẽ được thêm qua JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Doanh thu sản phẩm</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="productChart" style="height: 250px; width: 100%"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Fun Statistic</h6>
            </div>
            <div class="card-body ms-8">
                <div class="row">
                    <div class="col-md-5">
                        <label for="start_time" class="form-control-label">Start Time</label>
                        <div class="form-group" style="text-align:center">
                            <input name="start_time" id="start_time" class="form-control datepicker" placeholder="Please select date" type="date">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label for="end_time" class="form-control-label">End Time</label>
                        <div class="form-group" style="text-align:center">
                            <input name="end_time" id="end_time" value="{!! date('Y-m-d') !!}" class="form-control datepicker" placeholder="Please select date" type="date">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button id="btn-statistical-filter" class="form-control">Accept</button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="statistical" class="form-control-label">Filter Time</label>
                        <div class="form-group" style="text-align:center">
                            <select id="statistical" style="width: 70%" class="statistical-filter form-control">
                                <option value="null" selected>Selected</option>
                                <option value="week">This Week</option>
                                <option value="last_week">Last Week</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="year">This Year</option>
                                <option value="last_year">Last Year</option>
                                <option value="all_time">All Time</option>
                            </select>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <label for="theater" class="form-control-label">Category</label>
                        <div class="form-group" style="text-align:center">
                            <select id="theater" style="width: 70%" class="statistical-sortby form-control">
                                <option value="null" selected>Selected</option>
                                <option value="cate">Category</option>
                                <option value="product">Product</option>
                            </select>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <canvas id="admin_chart" style="height: 300px; width: 100%"></canvas>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
{{-- <script>
    $(document).ready(function() {
    // Hàm vẽ biểu đồ
    function drawChart(chartId, labels, data, chartTitle) {
        var ctx = document.getElementById(chartId).getContext('2d');
        new Chart(ctx, {
            type: 'line', // hoặc 'bar', tùy vào yêu cầu
            data: {
                labels: labels, // Dữ liệu nhãn ngày
                datasets: [{
                    label: chartTitle,
                    data: data, // Dữ liệu doanh thu
                    borderColor: '#4e73df', // Màu đường
                    backgroundColor: 'rgba(78, 115, 223, 0.1)', // Màu nền
                    fill: true, // Đổ màu nền dưới đường
                    tension: 0.3
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Ẩn hoặc hiển thị legend
                    }
                }
            }
        });
    }

    // Khi click nút "Accept" để lọc dữ liệu
    $('#btn-statistical-filter').click(function() {
        var start_time = $('#start_time').val();
        var end_time = $('#end_time').val();
        var statistical = $('#statistical').val();

        // Gửi yêu cầu AJAX đến server để lấy dữ liệu
        $.ajax({
            url: '/thongke/revenue', // API của bạn
            method: 'GET',
            data: {
                start_time: start_time,
                end_time: end_time,
                statistical: statistical
            },
            success: function(response) {
                // Lấy dữ liệu từ response
                var labels = response.labels;
                var revenues = response.revenues;

                // Vẽ biểu đồ cho "Category Statistics"
                drawChart('categoryChart', labels, revenues, 'Category Statistics');

                // Vẽ biểu đồ cho "Product Statistics"
                drawChart('productChart', labels, revenues, 'Product Statistics');

                // Vẽ biểu đồ cho "Admin Statistics"
                drawChart('admin_chart', labels, revenues, 'Admin Statistics');
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
}); --}}

{{-- </script> --}}
<script>
    $(document).ready(function() {
        
        // Dữ liệu từ Blade (Laravel) đã được chuyển sang mảng JavaScript
        var categories = @json($revenueByCategory->pluck('category'));  // Lấy danh mục
        var categoryRevenue = @json($revenueByCategory->pluck('revenue')); // Lấy doanh thu danh mục

        var products = @json($revenueByProduct->pluck('product')); // Lấy sản phẩm
        var productRevenue = @json($revenueByProduct->pluck('revenue')); // Lấy doanh thu sản phẩm
        // Thêm dữ liệu vào bảng loại sản phẩm
        var categoryTable = $('#categoryTable');
    categories.forEach(function (category, index) {
        var revenue = categoryRevenue[index];
        categoryTable.append(
            `<tr>
                <td>${category}</td>
                <td>${revenue.toLocaleString()} đ</td>
            </tr>`
        );
    });

    // Khởi tạo phân trang cho bảng categoryTable
    $('#categoryTable').DataTable({
        "pageLength": 10, 
        "lengthChange": false, 
        "paging": true, 
        "searching": true, 
        "ordering": true, 
        "info":false,
    });

       // Chuẩn bị dữ liệu cho bảng
        var productData = products.map((product, index) => {
            return [product, `${productRevenue[index].toLocaleString()} đ`];
        });

        // Khởi tạo DataTables
        $('#productTable').DataTable({
            data: productData, // Gán dữ liệu
            columns: [
                { title: "Sản phẩm" },
                { title: "Doanh thu" }
            ],
            pageLength: 10, // Số dòng trên mỗi trang
            //dom: 'lrtip', // Loại bỏ lengthMenu
            dom: 'ftp',
            language: {
                search: "Tìm kiếm:",
                
            
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: ">",
                    previous: "<"
                },
            }
        });

        // Hàm tạo màu ngẫu nhiên
        function generateRandomColors(count) {
            let colors = [];
            for (let i = 0; i < count; i++) {
                const color = `#${Math.floor(Math.random() * 16777215).toString(16)}`;
                colors.push(color);
            }
            return colors;
        }

        // Tạo màu tự động dựa trên số lượng danh mục
        var categoryColors = generateRandomColors(categories.length);

        // Biểu đồ Tròn cho Doanh Thu theo Danh Mục
        var ctx1 = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: categories,
                datasets: [{
                    label: 'Revenue by Category',
                    data: categoryRevenue,
                    backgroundColor: categoryColors,
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' đ';
                            }
                        }
                    }
                }
            }
        });


        var productColors = generateRandomColors(products.length);
        // Biểu đồ Tròn cho Doanh Thu theo Sản Phẩm
        var ctx2 = document.getElementById('productChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: products,
                datasets: [{
                    label: 'Revenue by Product',
                    data: productRevenue,
                    backgroundColor: productColors,
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' đ';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
<script>
$(document).ready(function() {
   
   function drawChart(labels, revenues) {
       let ctx = document.getElementById('admin_chart').getContext('2d');
       let chart = new Chart(ctx, {
           type: 'bar',
           data: {
               labels: labels, // Các ngày
               datasets: [{
                   label: 'Revenue',
                   data: revenues, // Doanh thu của từng ngày
                   backgroundColor: 'rgba(54, 162, 235, 0.2)',
                   borderColor: 'rgba(54, 162, 235, 1)',
                   borderWidth: 1
               }]
           },
           options: {
               scales: {
                   y: {
                       beginAtZero: true
                   }
               }
           }
       });
   }

   function fetchAndDrawChart(requestData) {
       // Gửi dữ liệu qua AJAX
       $.ajax({
           url: "{{ route('thongke.revenue') }}",
           method: 'POST',
           data: requestData,
           success: function(response) {
               // Kiểm tra dữ liệu trả về
               console.log('Response: ', response);

               // Vẽ lại biểu đồ
               drawChart(response.labels, response.revenues);
           }
       });
   }

   // Sự kiện khi nhấn nút lọc thống kê
   $('#btn-statistical-filter').on('click', function() {
       // Lấy giá trị từ các input
       let start_time = $('#start_time').val();
       let end_time = $('#end_time').val();
    

       // Xóa biểu đồ cũ trước khi vẽ lại
       $('#admin_chart').remove();  // Xóa canvas cũ
       $('.col-lg-12').append('<canvas id="admin_chart" style="height: 300px; width: 100%"></canvas>'); // Tạo lại canvas mới

       let requestData = {
           _token: "{{ csrf_token() }}",
          
           start_time: start_time,
           end_time: end_time
       };

       // Gọi hàm để lấy và vẽ lại biểu đồ
       fetchAndDrawChart(requestData);
   });

   // Sự kiện khi thay đổi loại thống kê
   $('#statistical').on('change', function () {
       let statistical = $(this).val();

       // Xóa biểu đồ cũ trước khi vẽ lại
       $('#admin_chart').remove(); // Xóa canvas cũ
       $('.col-lg-12').append('<canvas id="admin_chart" style="height: 300px; width: 100%"></canvas>'); // Tạo lại canvas mới

       let requestData = {
           _token: "{{ csrf_token() }}",
           statistical: statistical
       };

       // Gọi hàm để lấy và vẽ lại biểu đồ
       fetchAndDrawChart(requestData);
   });

});

</script>
