@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('Dashboard/plugins/sumoselect/sumoselect-rtl.css') }}">
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@section('title')
    {{ trans('doctors.edit_doctor') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{ trans('doctors.edit_doctor') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                {{ $doctor->name }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')

@include('Dashboard.messages_alert')

<!-- row -->
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('doctors.update', $doctor->id) }}" method="post" autocomplete="off" enctype="multipart/form-data">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                        <div class="pd-30 pd-sm-40 bg-gray-200">
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    {{ trans('doctors.name') }}</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <input class="form-control" name="name" value="{{ $doctor->name }}" type="text">
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    {{ trans('doctors.email') }}</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <input class="form-control" name="email" value="{{ $doctor->email }}" type="email">
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    {{ trans('doctors.phone') }}</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <input class="form-control" name="phone" value="{{ $doctor->phone }}" type="tel">
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    {{ trans('doctors.section') }}</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <select name="section_id" class="form-control SlectBox">
                                    <option value="" disabled>------</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ $doctor->section_id == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    {{ trans('doctors.appointments') }}</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <select multiple="multiple" class="testselect2" name="appointments[]">
                                    <option value="" disabled>-- حدد المواعيد --</option>
                                    <option value="السبت" {{ in_array('السبت', json_decode($doctor->appointments, true) ?? []) ? 'selected' : '' }}>السبت</option>
                                    <option value="الأحد" {{ in_array('الأحد', json_decode($doctor->appointments, true) ?? []) ? 'selected' : '' }}>الأحد</option>
                                    <option value="الأثنين" {{ in_array('الأثنين', json_decode($doctor->appointments, true) ?? []) ? 'selected' : '' }}>الأثنين</option>
                                    <option value="الثلاثاء" {{ in_array('الثلاثاء', json_decode($doctor->appointments, true) ?? []) ? 'selected' : '' }}>الثلاثاء</option>
                                    <option value="الأربعاء" {{ in_array('الأربعاء', json_decode($doctor->appointments, true) ?? []) ? 'selected' : '' }}>الأربعاء</option>
                                    <option value="الخميس" {{ in_array('الخميس', json_decode($doctor->appointments, true) ?? []) ? 'selected' : '' }}>الخميس</option>
                                    <option value="الجمعة" {{ in_array('الجمعة', json_decode($doctor->appointments, true) ?? []) ? 'selected' : '' }}>الجمعة</option>
                                </select>
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    {{ trans('doctors.price') }}</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <input class="form-control" name="price" value="{{ $doctor->price }}" type="text">
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    {{ trans('doctors.Status') }}</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <input class="form-check-input" name="status" type="checkbox" value="1" {{ $doctor->status == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexCheckIndeterminate">
                                    {{ $doctor->status == 1 ? trans('doctors.Enabled') : trans('doctors.Not_enabled') }}
                                </label>
                            </div>
                        </div>

                        @if ($doctor->images && $doctor->images->filename)
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    {{ trans('doctors.doctor_photo') }}</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <img src="{{ URL::asset('images/doctors/'.$doctor->images->filename) }}" style="width: 250px; height: 250px;">
                            </div>
                        </div>
                        @endif

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    {{ trans('doctors.doctor_photo') }}</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <input type="file" accept="image/*" name="photo" onchange="loadFile(event)">
                                <img style="border-radius:50%" width="150px" height="150px" id="output"/>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">{{ trans('doctors.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /row -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')
    <script>
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
@endsection