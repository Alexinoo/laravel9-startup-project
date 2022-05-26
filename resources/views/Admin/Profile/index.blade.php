@extends('Admin.admin_master')

@section('content')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card ">
                    <div class="text-center my-3">
                        <img class="rounded-circle avatar-xl" src="{{ !empty($user->profile_image) ? asset('upload/admin_images/'.$user->profile_image) : asset('upload/no_image.jpg')}}" alt="Profile">
                    </div>

                    <div class="card-body">
                        <h4 class="card-title">Name : {{ $user->name }}</h4>
                        <hr>
                        <h4 class="card-title">Email : {{ $user->email }}</h4>
                        <hr>
                        <h4 class="card-title">Username : {{ $user->username }}</h4>
                        <hr>
                        <a href="{{route('admin_profile.edit' , $user->id)}}" class="btn btn-info btn-rounded waves-effect waves-light">Edit Profile</a>


                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection
