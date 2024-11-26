<div class="row mt-4">
    <div class="col-lg-7 mb-lg-0 mb-4">
        <div class="card ">
            <div class="card-header pb-0 p-3">
                <div class="d-flex justify-content-between ">
                    <h6 class="mb-2">
                        Loại sản phẩm
                    </h6>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#movie" class="float-end">Xem tất cả</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center">
                    <thead>
                        <tr>
                            <th class="w-30">Loại sản phẩm</th>
                            <th class="text-center">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(collect($revenueByCategory)->take(5) as $vl)
                        <tr>
                            <td class="w-30">
                                <div class="d-flex px-2 py-1 align-items-center">
                                    <div class="ms-4">
                                     
                                        <h6 class="text-sm mb-0">
                                            {{$vl['category']}}
                                        </h6>
                                    </div>
                                </div>
                            </td>
                
                            <td>
                                <div class="text-center">
                                   
                                    <h6 class="text-sm mb-0">
                                        {{$vl['revenue']}} đ
                                    </h6>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card ">
            <div class="card-header pb-0 p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="mb-2">Sản phẩm</h6>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#theater_modal" class="float-end">Xem tất cả</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center ">
                    <tbody>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Doanh thu</th>
                        </tr>
                        @foreach(collect($revenueByProduct)->take(5) as $vl)
                        <tr>
                            <td class="w-30">
                                <div class="d-flex px-2 py-1 align-items-center">
                                    <div class="ms-4">
                                      
                                        <h6 class="text-sm mb-0">{!! $vl['product'] !!}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                  
                                    <h6 class="text-sm mb-0">
                                        {{$vl['revenue']}}
                                    </h6>
                                </div>
                            </td>
                           
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>