@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $logo = $settings['site_logo'] ?? null;
            if ($logo && !str_starts_with($logo, 'http')) {
                $logo = Storage::url($logo);
            }
        @endphp

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-general" type="button" role="tab">General</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-payment" type="button" role="tab">Payment</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-email" type="button" role="tab">Email</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-system" type="button" role="tab">System</button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                    <div class="tn-card-static p-4 mb-4">
                        <h3 class="mb-3">General Settings</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Site Name</label>
                                <input type="text" name="site_name" class="tn-form-control" value="{{ $settings['site_name'] ?? config('app.name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Email</label>
                                <input type="email" name="contact_email" class="tn-form-control" value="{{ $settings['contact_email'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="contact_phone" class="tn-form-control" value="{{ $settings['contact_phone'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" name="contact_address" class="tn-form-control" value="{{ $settings['contact_address'] ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Site Logo</label>
                                <input type="file" name="site_logo" class="tn-form-control" accept="image/*">
                                @if ($logo)
                                    <div class="mt-3">
                                        <img src="{{ $logo }}" alt="Site Logo" width="140" height="80" style="border-radius:12px;object-fit:cover;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-payment" role="tabpanel">
                    <div class="tn-card-static p-4 mb-4">
                        <h3 class="mb-3">Payment Settings</h3>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="payment_bkash_enabled" value="1" @checked(($settings['payment_bkash_enabled'] ?? '0') === '1')>
                                    <label class="form-label">Enable bKash</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="payment_nagad_enabled" value="1" @checked(($settings['payment_nagad_enabled'] ?? '0') === '1')>
                                    <label class="form-label">Enable Nagad</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="payment_card_enabled" value="1" @checked(($settings['payment_card_enabled'] ?? '0') === '1')>
                                    <label class="form-label">Enable Card</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SSLCommerz Store ID</label>
                                <input type="text" name="sslcommerz_store_id" class="tn-form-control" value="{{ $settings['sslcommerz_store_id'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Store Password</label>
                                <input type="password" name="sslcommerz_store_password" class="tn-form-control" value="{{ $settings['sslcommerz_store_password'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-email" role="tabpanel">
                    <div class="tn-card-static p-4 mb-4">
                        <h3 class="mb-3">Email Settings</h3>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Mail Driver</label>
                                <input type="text" name="mail_driver" class="tn-form-control" value="{{ $settings['mail_driver'] ?? 'smtp' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mail Host</label>
                                <input type="text" name="mail_host" class="tn-form-control" value="{{ $settings['mail_host'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mail Port</label>
                                <input type="number" name="mail_port" class="tn-form-control" value="{{ $settings['mail_port'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mail Username</label>
                                <input type="text" name="mail_username" class="tn-form-control" value="{{ $settings['mail_username'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mail Password</label>
                                <input type="password" name="mail_password" class="tn-form-control" value="{{ $settings['mail_password'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-system" role="tabpanel">
                    <div class="tn-card-static p-4 mb-4">
                        <h3 class="mb-3">System Settings</h3>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Currency</label>
                                <input type="text" name="system_currency" class="tn-form-control" value="{{ $settings['system_currency'] ?? 'BDT' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Timezone</label>
                                <input type="text" name="system_timezone" class="tn-form-control" value="{{ $settings['system_timezone'] ?? config('app.timezone') }}">
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1" @checked(($settings['maintenance_mode'] ?? '0') === '1')>
                                    <label class="form-label">Maintenance Mode</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button class="btn-primary-tn" type="submit">Save Settings</button>
            </div>
        </form>
    </div>
@endsection
