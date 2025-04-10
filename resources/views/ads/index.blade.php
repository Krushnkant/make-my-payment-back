@extends('layouts.app')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <a href="{{ route('ads.create') }}" class="btn btn-info"> Add Ads </a>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="card-title">Ads Information</h4>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex justify-content-end" style="min-width: 200px;">
                            <form class="" id="sort_categories" action="" method="GET">
                                <div class="form-outline">
                                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder=" Type Ad Id & Enter">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listng" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Device Type</th>
                                        <th>Ad Id</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ads as $key=>$ad)
                                        <tr>
                                            <td>{{ ($key+1) + ($ads->currentPage() - 1)*$ads->perPage() }}</td>
                                            <td>{{ $ad->device_type ?? '-' }}</td>
                                            <td>{{ $ad->ad_id ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('ads.edit', $ad->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a>
                                                <a href="javascript:void(0)"  class="btn btn-danger btn-sm"
                                                    onclick="ConformAlerts({{$ad->id}},'{{ route('ads.destroy') }}' )" ><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                {{ $ads->links( "pagination::bootstrap-4") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

