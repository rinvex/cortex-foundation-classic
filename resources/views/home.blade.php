@extends('rinvex/fort::frontend.common.layout')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Welcome</div>

                    <div class="panel-body">

                        @include('rinvex/fort::frontend/alerts.success')
                        @include('rinvex/fort::frontend/alerts.warning')
                        @include('rinvex/fort::frontend/alerts.error')

                        Your Application's Landing Page.
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
