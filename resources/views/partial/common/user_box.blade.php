@if(auth()->guard('web')->check() == false)

<div class="card mb-3">
    <div class="card-body mb-7 bg-light-success">
        <div class="row user-info">
            <div class="col-xl-3 col-md-3 col-sm-5 d-flex justify-content-center align-items-center">
                <div class="avatar avatar-5xl avatar-profile shadow-sm img-thumbnail rounded-box">
                    <div class="h-100 w-100 rounded-box overflow-hidden position-relative">
                        <img src="{{ imageexist($user->image ) }}" width="200" alt="" id="img" data-dz-thumbnail="data-dz-thumbnail" class="profile-img" />
                        <input class="d-none" id="profile-image" type="file" {{ request()->is('*/profile') ? ""
                        : "disabled" }} />
                        <label class="mb-0 overlay-icon d-flex flex-center" for="profile-image">
                            <span class="bg-holder overlay overlay-0"></span>
                            <span class="z-index-1 text-white dark__text-white text-center fs--1">
                                <span class="fas fa-camera"></span>
                                <span class="d-block">Update</span>
                            </span>
                        </label>
                    </div>
                    <label for="profile-image" class="fa fa-edit @if(request()->is('*/profile') == false) d-none @endif"></label>
                </div>
            </div>
            <div class="col-xl-9 col-md-9 col-sm-7 d-flex flex-column justify-content-center">
                <div class="d-flex justify-content-between my-2 align-items-center">
                    <h2 class="m-0 fw-bold"> {{ $user->name}} </h2>
                    @if(auth()->guard('delivery_partner')->check())
                    <strong class="m-0 fw-bold px-2 fs-5 rounded border border-success"> <i class="fa-solid fa-wallet me-1"></i> <span class="d-none d-md-inline">Wallet Balance</span> : {{ $user->delivery_partner_balance}} </strong>
                    @endif
                </div>
                <ul class="contacts-block list-unstyled">
                    <li>
                        <i class="fa-duotone fa-crown me-2"></i>
                        {{ ucwords(str_replace('_',' ', $role)) }}
                    </li>
                    <li>
                        <i class="fa-duotone fa-envelope me-2"></i>
                        {{ $user->email}}
                    </li>
                    <li>
                        <i class="fa-regular fa-phone me-2"></i>
                        {{ $user->mobile}}
                    </li>
                    @if(auth()->guard('delivery_partner')->check())
                    <li>
                        <i class="fa-solid fa-truck me-2"></i>
                        {{ $user->per_km_rate}}
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>


@endif