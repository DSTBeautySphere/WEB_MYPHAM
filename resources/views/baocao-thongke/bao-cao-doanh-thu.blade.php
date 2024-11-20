@extends('layout.index')
@section('title', 'Báo Cáo Doanh Thu')
@section('css')
<style>
   
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Sales -->
    @include('baocao-thongke.sales')
    @include('baocao-thongke.chart')
    @include('baocao-thongke.revenue')

</div>
 
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection
