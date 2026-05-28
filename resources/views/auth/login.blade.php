@extends('layouts.auth')

@section('title', 'Login - Inventory App')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-login-image"
                        style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); display: flex; align-items: center; justify-content: center; min-height: 400px;">
                        <div class="text-center text-white p-5">
                            <i class="fas fa-boxes fa-5x mb-4 opacity-75"></i>
                            <h3 class="font-weight-bold">Sistem Inventaris</h3>
                            <p class="mb-0 opacity-75">Kelola stok suku cadang Anda dengan mudah, cepat, dan akurat.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h1 class="h4 text-gray-900 font-weight-bold">Selamat Datang!</h1>
                                <p class="text-gray-600 small">Masuk untuk melanjutkan ke sistem inventaris.</p>
                            </div>

                            {{-- Session Error --}}
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            {{-- Validation Errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <form id="login-form" method="POST" action="{{ route('login.post') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="users_email" class="text-gray-700 font-weight-bold small">
                                        Email Address
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0">
                                                <i class="fas fa-envelope text-gray-400"></i>
                                            </span>
                                        </div>
                                        <input
                                            id="users_email"
                                            type="email"
                                            class="form-control border-left-0 @error('users_email') is-invalid @enderror"
                                            name="users_email"
                                            value="{{ old('users_email') }}"
                                            placeholder="Masukkan email Anda..."
                                            required
                                            autofocus>
                                        @error('users_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="users_password_hash" class="text-gray-700 font-weight-bold small">
                                        Password
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0">
                                                <i class="fas fa-lock text-gray-400"></i>
                                            </span>
                                        </div>
                                        <input
                                            id="users_password_hash"
                                            type="password"
                                            class="form-control border-left-0 @error('users_password_hash') is-invalid @enderror"
                                            name="users_password_hash"
                                            placeholder="Masukkan password Anda..."
                                            required>
                                        @error('users_password_hash')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group d-flex align-items-center justify-content-between">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" id="rememberMe" name="remember">
                                        <label class="custom-control-label" for="rememberMe">Remember Me</label>
                                    </div>
                                </div>

                                <button type="submit" id="btn-login" class="btn btn-primary btn-user btn-block font-weight-bold">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                                </button>
                            </form>

                            <hr>
                            <div class="text-center">
                                <small class="text-gray-500">
                                    &copy; {{ date('Y') }} Inventory App. All rights reserved.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
