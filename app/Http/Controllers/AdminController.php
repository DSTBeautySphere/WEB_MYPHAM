<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\anh_san_pham;
use App\Models\bien_the_san_pham;
use App\Models\don_dat;
use Illuminate\Http\Request;
use App\Models\san_pham;
use App\Models\loai_san_pham;
use App\Models\dong_san_pham;
use App\Models\hoa_don;
use App\Models\nha_cung_cap;
use App\Models\nhom_tuy_chon;
use App\Models\tuy_chon;
use App\Models\User;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class AdminController extends Controller
{
    //

    public function showLoginForm()
    {
        return view('dangnhap');
        
    }



    public function register(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'email' => 'required|email|unique:admin,email',
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:15',
            'anh_dai_dien' => 'nullable|string',
            'mat_khau' => 'required|string|min:1', // Yêu cầu nhập lại mật khẩu để xác nhận
        ]);
    
        // Tạo tài khoản admin mới
        $admin = Admin::create([
            'email' => $validatedData['email'],
            'ho_ten' => $validatedData['ho_ten'],
            'so_dien_thoai' => $validatedData['so_dien_thoai'],
            'anh_dai_dien' => $validatedData['anh_dai_dien'] ?? null,
            'trang_thai' => 1, // Mặc định kích hoạt
            'mat_khau' => Hash::make($validatedData['mat_khau']), // Mã hóa mật khẩu bằng Hash::make
        ]);
    
        return response()->json([
            'message' => 'Đăng ký thành công!',
            'admin' => $admin
        ], 201);
    }

    public function login(Request $request)
    {
        // Validate dữ liệu đầu vào
        $credentials = $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'required|string',
        ]);
    
        // Tìm admin dựa trên email
        $admin = Admin::where('email', $credentials['email'])->first();
    
        // Kiểm tra mật khẩu
        if (!$admin || !Hash::check($credentials['mat_khau'], $admin->mat_khau)) {
            return response()->json(['message' => 'Thông tin đăng nhập không đúng'], 401);
        }
    
        // Tạo token đăng nhập
        // $token = $admin->createToken('auth_token')->plainTextToken;
        
        // Đăng nhập và trả về dữ liệu
        Auth::guard('admin')->login($admin);
        return response()->json([
            'message' => 'DNTC',
            // 'token' => $token,
            'redirect_url' => url('/showquanlysanpham'), // Chuyển hướng
            'admin' => $admin
        ]);
    }
    

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('/admin/login');
    }
    

    public function view_san_pham()
    {
        return view('sanpham');
    }

    public function lay_san_pham_all()
    {
        $dongSanPhams = dong_san_pham::all();     
        $data = [
            'dong_san_pham' => []
        ];
    
        foreach ($dongSanPhams as $dongSanPham) {
            
            $item = [
                'ma_dong_san_pham' => $dongSanPham->ma_dong_san_pham,
                'ten_dong_san_pham' => $dongSanPham->ten_dong_san_pham,
                'mo_ta'=>$dongSanPham->mo_ta,
                'loai_san_pham' => []
            ];
    
            foreach ($dongSanPham->loai_san_pham as $loaiSanPham) {
                
                $loaiItem = [
                    'ma_loai_san_pham' => $loaiSanPham->ma_loai_san_pham,
                    'ma_dong_san_pham'=>$loaiSanPham->ma_dong_san_pham,
                    'ten_loai_san_pham' => $loaiSanPham->ten_loai_san_pham,
                    'mo_ta' => $loaiSanPham->mo_ta,
                    'san_pham' => []
                ];
    
                foreach ($loaiSanPham->san_pham as $sanPham) { 
                   
                    $sanPhamItem = [
                        'ma_san_pham' => $sanPham->ma_san_pham,
                        'ma_loai_san_pham'=>$sanPham->ma_loai_san_pham,                
                        'ten_san_pham' => $sanPham->ten_san_pham,
                        'bien_the_san_pham'=>[],
                        'nha_cung_cap' => [
                            'ma_nha_cung_cap' => $sanPham->nha_cung_cap->ma_nha_cung_cap,
                            'ten_nha_cung_cap' => $sanPham->nha_cung_cap->ten_nha_cung_cap,
                            'dia_chi' => $sanPham->nha_cung_cap->dia_chi,
                            'so_dien_thoai' => $sanPham->nha_cung_cap->so_dien_thoai,
                            'email' => $sanPham->nha_cung_cap->email,
                        ],
                        'khuyen_mai_san_pham' => $sanPham->khuyen_mai_san_pham->map(function($khuyenMai) {
                            return [
                                'ma_khuyen_mai' => $khuyenMai->ma_khuyen_mai,
                                'muc_giam_gia' => $khuyenMai->muc_giam_gia,
                                'ngay_bat_dau' => $khuyenMai->ngay_bat_dau,
                                'ngay_ket_thuc' => $khuyenMai->ngay_ket_thuc,
                                'dieu_kien_ap_dung' => $khuyenMai->dieu_kien_ap_dung,
                            ];
                        }),
                        'anh_san_pham' => $sanPham->anh_san_pham->map(function($anh) {
                            return [
                                'ma_anh_san_pham' => $anh->ma_anh_san_pham,
                                'url_anh' => $anh->url_anh,
                                'la_anh_chinh' => $anh->la_anh_chinh,
                            ];
                        }),
                    ];

                    foreach($sanPham->bien_the_san_pham as $bthe_sp)
                    {
                        $bienthesp=[
                            'ma_bien_the'=>$bthe_sp->ma_bien_the,
                            'ma_san_pham'=>$bthe_sp->ma_san_pham,
                            'mau_sac'=>$bthe_sp->mau_sac,
                            'loai_da'=>$bthe_sp->loai_da,
                            'dung_tich'=>$bthe_sp->dung_tich,
                            'so_luong_ton_kho'=>$bthe_sp->so_luong_ton_kho,
                            'gia_ban'=>$bthe_sp->gia_ban,
                        ];

                        $sanPhamItem['bien_the_san_pham'][]=$bienthesp;

                    }
    
                    $loaiItem['san_pham'][] = $sanPhamItem; 
                }
    
                $item['loai_san_pham'][] = $loaiItem; 
            }
    
            $data['dong_san_pham'][] = $item; 
        }
    
        // Trả về dữ liệu dưới dạng JSON
        return response()->json($data);
    }
    
    public function lay_san_pham_phan_trang(Request $request)
    {
        $sosanpham = $request->input('so_san_pham', 4);
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])
                    ->paginate($sosanpham);
        return response()->json($sanpham);
    }

    public function lay_san_pham()
    {
        
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham','bien_the_san_pham'])->get();
        
        return response()->json($sanpham);
    }


    public function loc_san_pham_theo_loai(Request $request)
    {

        $sanpham=san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham','bien_the_san_pham'])->where('ma_loai_san_pham',$request->ma_loai_san_pham)->get();
        return response()->json($sanpham,200);


    }

    public function loc_san_pham_theo_dong(Request $request)
    {
        $loaiSanPham = loai_san_pham::where('ma_dong_san_pham', $request->input('ma_dong_san_pham'))->get();
        $maLoaiSanPham = $loaiSanPham->pluck('ma_loai_san_pham');
        $sanpham = san_pham::whereIn('ma_loai_san_pham', $maLoaiSanPham)->get();
        return response()->json($sanpham,200);
    }

    public function chi_tiet_san_pham(Request $request)
    {
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])
        ->where('ma_san_pham', $request->ma_san_pham)
        ->first();
        return response()->json($sanpham,200);
    }

    public function loc_san_pham_theo_gia(Request $request)
    {
        $min = $request->input('min', 0);
        $max = $request->input('max', 0);

        $sanpham = san_pham::when($min != 0 && $max == 0, function ($query) use ($min) {
            return $query->where('gia_ban', '>=', $min);
        })
        ->when($min == 0 && $max != 0, function ($query) use ($max) {
            return $query->where('gia_ban', '<=', $max);
        })
        ->when($min != 0 && $max != 0, function ($query) use ($min, $max) {
            return $query->whereBetween('gia_ban', [$min, $max]);
        })
        ->get();

        return response()->json($sanpham, 200);
    }

    public function tim_san_pham(Request $request)
    {

    }

    public function showThemSanPham()
    {
        $loaiSanPham=loai_san_pham::all();
        $nhaCungCap=nha_cung_cap::all();
        $tuyChon=tuy_chon::all();
        $nhomTuyChon = nhom_tuy_chon::all();
        $dongSanPham= dong_san_pham::all();
        return view('sanpham.them-san-pham',['loaiSanPham'=>$loaiSanPham,'nhaCungCap'=>$nhaCungCap,'tuyChon'=>$tuyChon,'nhomTuyChon'=> $nhomTuyChon,'dongSanPham'=>$dongSanPham]);
    }


    public function xoa_san_pham(Request $request)
    {
        $sanpham=san_pham::where('ma_san_pham',$request->input('ma_san_pham'))->first();
        $sanpham->delete();
        return Response()->json(['message'=>'Xóa Thành Công!'],200);
    }

    public function cap_nhat_san_pham(Request $request)
    {
            // Xác thực yêu cầu
        $request->validate([
            'ma_san_pham' => 'required|integer|exists:san_pham,ma_san_pham',
            'ma_loai_san_pham' => 'required|integer|exists:loai_san_pham,ma_loai_san_pham',
            'ma_nha_cung_cap' => 'required|integer|exists:nha_cung_cap,ma_nha_cung_cap',
            'ten_san_pham' => 'required|string|max:255',
        ]);

        try {
          
            $sanPham = san_pham::find($request->ma_san_pham);

            
            $sanPham->update([
                'ma_loai_san_pham' => $request->ma_loai_san_pham,
                'ma_nha_cung_cap' => $request->ma_nha_cung_cap,
                'ten_san_pham' => $request->ten_san_pham,
            ]);

            
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được cập nhật thành công.',
                'data' => $sanPham
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage()
            ]);
        }
        
    }

    public function lay_san_pham_form(Request $request)
    {
        $sosanpham = $request->input('so_san_pham', 4);
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])
                    ->paginate($sosanpham);
        return response()->json($sanpham);
    }
    

    public function showQuanLySanPham(Request $request)
    {
        $sanPham = san_pham::paginate(4); 

    
        return view('sanpham.quan-ly-san-pham', compact('sanPham')); 
    }

    public function showQuanLyDonHang()
    {
        $donDat = don_dat::with(['chi_tiet_don_dat', 'hoa_don'])->get();

        return view('donhang.quan-ly-don-hang', compact('donDat'));
    }
    
  

    public function Statistics(Request $request)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh')->endOfDay();
        Log::info('Current DateTime (endOfDay): ', ['now' => $now]);

        $year = Carbon::now('Asia/Ho_Chi_Minh')->subDays(365)->startOfYear()->toDateString();
        Log::info('Start of Year (365 days ago): ', ['year' => $year]);

        $start_of_month = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth();
        Log::info('Start of Current Month: ', ['start_of_month' => $start_of_month]);

        // Tổng đơn hàng hoàn tất trong năm
        $total_year = don_dat::whereBetween('ngay_dat', [$year, $now])
            ->where('trang_thai', "Hoàn tất")
            ->get();
        Log::info('Total orders in the last year: ', ['total_year' => $total_year->count()]);

        // Tổng đơn hàng hoàn tất hôm nay
        $ordersToday = don_dat::whereDate('ngay_dat', Carbon::today())
            ->where('trang_thai', "Hoàn tất")
            ->get();
        Log::info('Orders for today: ', ['ordersToday' => $ordersToday->count()]);

        // Tổng số lượng khách hàng
        $userCount = User::count();
        Log::info('Total users count: ', ['userCount' => $userCount]);

        // Tổng số sản phẩm
        $productCount = san_pham::count();
        Log::info('Total products count: ', ['productCount' => $productCount]);

        // Tính tổng doanh thu hôm nay từ cột tong_tien trong bảng hoa_don
        $sum_today = hoa_don::whereDate('ngay_thanh_toan', Carbon::today())
        ->whereHas('don_dat', function ($query) {
            $query->where('trang_thai', 'Hoàn tất');
        })
        ->sum('tong_tien');
        Log::info('Total revenue for today: ', ['sum_today' => $sum_today]);

        $sum_year = hoa_don::whereBetween('ngay_thanh_toan', [$year, $now])
            ->whereHas('don_dat', function ($query) {
                $query->where('trang_thai', 'Hoàn tất');
            })
            ->sum('tong_tien');
        Log::info('Total revenue for the last year: ', ['sum_year' => $sum_year]);

        // Doanh thu theo danh mục sản phẩm
        // $revenueByCategory = loai_san_pham::with(['san_pham.bien_the_san_pham.chi_tiet_don_dat'])
        //     ->get()
        //     ->map(function ($category) {
        //         $totalRevenue = $category->san_pham->flatMap(function ($product) {
        //             return $product->bien_the_san_pham->flatMap(function ($variation) {
        //                 return $variation->chi_tiet_don_dat->where('don_dat.trang_thai', 'Hoàn tất');
        //             });
        //         })->sum('thanh_tien');

        //         return [
        //             'category' => $category->ten_loai_san_pham,
        //             'revenue' => $totalRevenue,
        //         ];
        //     });
        // Log::info('Revenue by category: ', ['revenueByCategory' => $revenueByCategory]);

        // // Doanh thu theo sản phẩm
        // $revenueByProduct = san_pham::with(['bien_the_san_pham.chi_tiet_don_dat'])
        //     ->get()
        //     ->map(function ($product) {
        //         $totalRevenue = $product->bien_the_san_pham->flatMap(function ($variation) {
        //             return $variation->chi_tiet_don_dat->where('don_dat.trang_thai', 'Hoàn tất');
        //         })->sum('thanh_tien');

        //         return [
        //             'product' => $product->ten_san_pham,
        //             'revenue' => $totalRevenue,
        //         ];
        //     });
        // Log::info('Revenue by product: ', ['revenueByProduct' => $revenueByProduct]);

            // Doanh thu theo loại sản phẩm
        $revenueByCategory = loai_san_pham::with(['san_pham.bien_the_san_pham.chi_tiet_don_dat.don_dat'])
        ->get()
        ->map(function ($category) {
            $totalRevenue = $category->san_pham->flatMap(function ($product) {
                return $product->bien_the_san_pham->flatMap(function ($variation) {
                    return $variation->chi_tiet_don_dat->filter(function ($orderDetail) {
                        return $orderDetail->don_dat && $orderDetail->don_dat->trang_thai === 'Hoàn tất';
                    });
                });
            })->sum(function ($orderDetail) {
                return $orderDetail->so_luong * $orderDetail->gia_ban;
            });

            return [
                'category' => $category->ten_loai_san_pham,
                'revenue' => $totalRevenue,
            ];
        });
        Log::info('Revenue by category: ', ['revenueByCategory' => $revenueByCategory]);

        // Doanh thu theo sản phẩm
        $revenueByProduct = san_pham::with(['bien_the_san_pham.chi_tiet_don_dat.don_dat'])
        ->get()
        ->map(function ($product) {
            $totalRevenue = $product->bien_the_san_pham->flatMap(function ($variation) {
                return $variation->chi_tiet_don_dat->filter(function ($orderDetail) {
                    return $orderDetail->don_dat && $orderDetail->don_dat->trang_thai === 'Hoàn tất';
                });
            })->sum(function ($orderDetail) {
                return $orderDetail->so_luong * $orderDetail->gia_ban;
            });

            return [
                'product' => $product->ten_san_pham,
                'revenue' => $totalRevenue,
            ];
        });
        Log::info('Revenue by product: ', ['revenueByProduct' => $revenueByProduct]);

        // Trả về view
        return view('baocao-thongke.bao-cao-doanh-thu', compact(
            'userCount',
            'sum_today',
            'sum_year',
            'ordersToday',
            'now',
            'year',
            'revenueByCategory',
            'revenueByProduct'
        ));
    }

    public function getRevenueData(Request $request)
    {
        $statistical = $request->statistical;
        
        // Xử lý thời gian bắt đầu và kết thúc
        $start_time = $request->start_time ? Carbon::parse($request->start_time) : Carbon::now();
        $end_time = $request->end_time ? Carbon::parse($request->end_time) : Carbon::now();
        
        // Lọc theo khoảng thời gian được yêu cầu
        if ($statistical == 'week') {
            $start_time = Carbon::now()->startOfWeek(); 
            $end_time = Carbon::now()->endOfWeek(); 
        } elseif ($statistical == 'last_week') {
            $start_time = Carbon::now()->subWeek()->startOfWeek();
            $end_time = Carbon::now()->subWeek()->endOfWeek();
        } elseif ($statistical == 'this_month') {
            $start_time = Carbon::now()->startOfMonth();
            $end_time = Carbon::now()->endOfMonth();
        } elseif ($statistical == 'last_month') {
            $start_time = Carbon::now()->subMonth()->startOfMonth();
            $end_time = Carbon::now()->subMonth()->endOfMonth();
        } elseif ($statistical == 'year') {
            $start_time = Carbon::now()->startOfYear();
            $end_time = Carbon::now()->endOfYear();
        } elseif ($statistical == 'last_year') {
            $start_time = Carbon::now()->subYear()->startOfYear();
            $end_time = Carbon::now()->subYear()->endOfYear();
        } elseif ($statistical == 'all_time') {
            $start_time = Carbon::createFromDate(2000, 1, 1); 
            $end_time = Carbon::now(); 
        }

        // Lọc các hóa đơn trong khoảng thời gian, thuộc đơn hàng hoàn tất
        $revenue_per_day = hoa_don::whereHas('don_dat', function ($query) {
            $query->where('trang_thai', 'Hoàn tất');
        })
        ->whereBetween('ngay_thanh_toan', [$start_time, $end_time])
        ->selectRaw('DATE(ngay_thanh_toan) as date, SUM(tong_tien) as revenue')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Lấy dữ liệu cho nhãn và doanh thu
        $labels = $revenue_per_day->pluck('date');
        $revenues = $revenue_per_day->pluck('revenue');

        // Trả về dữ liệu dưới dạng JSON
        return response()->json([
            'labels' => $labels,
            'revenues' => $revenues,
        ]);
    }


}
