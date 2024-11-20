<div class="row mt-4">
    <!-- Biểu đồ danh mục -->
    <div class="col-md-6"> <!-- Chiếm 6 cột -->
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Products Statistics</h6>
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
                <h6>DetailsProduct Statistics</h6>
            </div>
            <div class="card-body">
                <canvas id="productChart" style="height: 250px; width: 100%"></canvas>
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

        // Biểu đồ Tròn cho Doanh Thu theo Danh Mục
        var ctx1 = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: categories,
                datasets: [{
                    label: 'Revenue by Category',
                    data: categoryRevenue,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF5733', '#C70039'],
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

        // Biểu đồ Tròn cho Doanh Thu theo Sản Phẩm
        var ctx2 = document.getElementById('productChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: products,
                datasets: [{
                    label: 'Revenue by Product',
                    data: productRevenue,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF5733', '#C70039'],
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
