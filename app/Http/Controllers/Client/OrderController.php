<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\don_dat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = DB::table('don_dat')
                ->where('don_dat.ma_user', $id)
                ->get();

            return response()->json($order);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function detail(string $id)
    {
        try {
            $order = don_dat::with(['chi_tiet_don_dat', 'chi_tiet_don_dat.bien_the_san_pham', 'chi_tiet_don_dat.bien_the_san_pham.san_pham', 'chi_tiet_don_dat.bien_the_san_pham.san_pham.anh_san_pham'])
                    ->where('ma_don_dat', $id)->first();

            return response()->json($order);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $order = don_dat::where('ma_don_dat', $id)->first();
            $order->trang_thai_thanh_toan = "DA_THANH_TOAN";
            $order->save();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function huyDon(Request $request)
    {
        $order=don_dat::where('ma_don_dat', $request->ma_don_dat)->first();
        $order->trang_thai_don_dat="Đã hủy";
        $order->save();
        return response()->json(true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getProvinces()
    {
        // Token và URL API
        $token = env('GHN_API_TOKEN'); // Lấy từ .env
        $baseUrl = env('GHN_BASE_URL', 'https://online-gateway.ghn.vn/shiip/public-api');

        // Gửi yêu cầu đến GHN API
        $response = Http::withHeaders([
            'Token' => $token,
        ])->get("{$baseUrl}/master-data/province");

        // Xử lý kết quả trả về
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $response->json('data'),
            ]);
        }

        // Xử lý lỗi nếu có
        return response()->json([
            'success' => false,
            'message' => $response->json('message', 'Unable to fetch provinces.'),
        ], $response->status());
    }

    public function getDistrictsByProvince(Request $request)
    {
        // Token và URL API
        $token = env('GHN_API_TOKEN'); // Lấy từ .env
        $baseUrl = env('GHN_BASE_URL', 'https://online-gateway.ghn.vn/shiip/public-api');

        // Gửi yêu cầu đến GHN API với province_id để lọc quận huyện theo tỉnh
        $response = Http::withHeaders([
            'Token' => $token,
        ])->get("{$baseUrl}/master-data/district", [
            'province_id' => $request->provinceId, // Tham số province_id để lọc
        ]);

        // Xử lý kết quả trả về
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $response->json('data'),
            ]);
        }

        // Xử lý lỗi nếu có
        return response()->json([
            'success' => false,
            'message' => $response->json('message', 'Unable to fetch districts for the selected province.'),
        ], $response->status());
    }

    public function getWards(Request $request)
{
    // Token và URL API
    $token = env('GHN_API_TOKEN'); // Lấy từ .env
    $baseUrl = env('GHN_BASE_URL', 'https://online-gateway.ghn.vn/shiip/public-api');

    // Lấy district_id từ request
    $districtId = $request->input('districtId');

    // Gửi yêu cầu đến GHN API để lấy phường xã theo quận huyện
    $response = Http::withHeaders([
        'Token' => $token,
    ])->get("{$baseUrl}/master-data/ward", [
        'district_id' => $districtId,  // Thêm district_id vào query params
    ]);

    // Xử lý kết quả trả về
    if ($response->successful()) {
        return response()->json([
            'success' => true,
            'data' => $response->json('data'),
        ]);
    }

    // Xử lý lỗi nếu có
    return response()->json([
        'success' => false,
        'message' => $response->json('message', 'Unable to fetch wards.'),
    ], $response->status());
}

public function getAvailableServices(Request $request)
{
    $token = env('GHN_API_TOKEN');
    $baseUrl = env('GHN_BASE_URL', 'https://online-gateway.ghn.vn/shiip/public-api');
    
    // Lấy các tham số từ request
    $fromDistrictId = 1456; // Quận xuất phát (có thể thay đổi nếu cần)
    $toDistrictId = $request->input('to_district', 1451); // Quận đến (mặc định hoặc lấy từ request)
    $weight = $request->input('weight', 1000); // Trọng lượng (mặc định 1000g)
    $insuranceValue = $request->input('insurance_value', 500000); // Giá trị bảo hiểm (mặc định)
    Log::info("Tỉnh ĐẾn:".$toDistrictId);

    // Gửi yêu cầu đến API GHN để lấy danh sách dịch vụ
    $response = Http::withHeaders([
        'Token' => $token,
    ])->get("{$baseUrl}/v2/shipping-order/available-services", [
        'from_district' => $fromDistrictId,
        'to_district' => $toDistrictId,
        'shop_id' => 5512908 // ID cửa hàng, có thể thay đổi
    ]);

    // Kiểm tra nếu có lỗi khi lấy danh sách dịch vụ
    if (!$response->successful()) {
        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy danh sách dịch vụ.',
        ]);
    }

    // Lấy danh sách dịch vụ từ phản hồi
    $services = $response->json('data');
    
    // Trả về tất cả dịch vụ không cần lọc
    return response()->json([
        'success' => true,
        'data' => $services, // Trả về tất cả dịch vụ mà không lọc
    ]);
}


public function calculateShippingFee(Request $request)
{
    $token = env('GHN_API_TOKEN');
    $baseUrl = env('GHN_BASE_URL', 'https://online-gateway.ghn.vn/shiip/public-api');

    // Lấy dữ liệu từ request
    $fromDistrictId = 1456; // Quận xuất phát (có thể thay đổi nếu cần)
    $toDistrictId = $request->input('to_district', 1442); // Quận đến (mặc định hoặc lấy từ request)
    $service_id = $request->input('service_id', 53320); // ID dịch vụ (mặc định là "Hàng nhẹ")
    $to_ward_code = $request->input('to_ward_code', 20314); // Mã phường (mặc định)
    Log::info("Huyện đến:".$to_ward_code);
    // Gửi yêu cầu tính phí vận chuyển đến GHN
    $response = Http::withHeaders([
        'Token' => $token,
    ])->post("{$baseUrl}/v2/shipping-order/fee", [
        'from_district_id' => $fromDistrictId,
        'to_district_id' => (int)$toDistrictId,
        'to_ward_code' => $to_ward_code,
        'service_id' => (int)$service_id,
        'weight' => 1000, // Trọng lượng (mặc định là 1000g, có thể lấy từ request)
        'insurance_value' => 500000, // Giá trị bảo hiểm (mặc định)
    ]);

    // Kiểm tra kết quả trả về từ API GHN
    if ($response->successful()) {
        // Trả về kết quả tính phí vận chuyển
        return response()->json([
            'success' => true,
            'data' => $response->json('data'),
        ]);
    }

    // Xử lý lỗi nếu có
    return response()->json([
        'success' => false,
        'message' => $response->json('message', 'Không thể tính phí vận chuyển.'),
    ], $response->status());
}









}