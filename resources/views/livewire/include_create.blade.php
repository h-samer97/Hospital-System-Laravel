@extends('dashboard.layouts.master')

@section('title')
    {{-- استخدام الترجمة أو نص مباشر --}}
    مجموعات الخدمات - نظام إدارة المستشفى
@stop

@section('css')
    {{-- أي تنسيقات إضافية خاصة بهذه الصفحة فقط توضع هنا --}}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الخدمات</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ مجموعات الخدمات</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    {{-- رسائل التنبيه يمكن وضعها هنا أو داخل مكون Livewire --}}
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">إدارة مجموعات الخدمات</h4>
                    </div>
                    <p class="tx-12 tx-gray-500 mb-2">يمكنك إضافة، تعديل، أو حذف مجموعات الخدمات الطبية من هنا.</p>
                </div>
                <div class="card-body">
                    {{-- استدعاء مكون Livewire --}}
                    @livewire('create-group-services')
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection

@section('js')
    {{-- أي سكربتات إضافية خاصة بالصفحة توضع هنا --}}
    <script>
        // مثال: إخفاء رسائل النجاح تلقائياً بعد 3 ثوانٍ
        window.addEventListener('livewire:load', function () {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 3000);
        });
    </script>
@endsection