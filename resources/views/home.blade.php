@extends('layouts.app')

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>WellCome</h5>
                        <button onclick="startFCM()" class="btn btn-outline-secondary">Allow notification</button>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ route('send.web-notification') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Message Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label>Message Body</label>
                            <textarea class="form-control" name="body"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Send Notification</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
    <script>
        var firebaseConfig = {

            // databaseURL: 'https://project-id.firebaseio.com',
            // measurementId: 'G-measurement-id',

            apiKey: "AIzaSyAusYstcrHTCR9PfxtYgSiLiRkYPuIg-0w",
            authDomain: "adiyogi--fintech.firebaseapp.com",
            projectId: "adiyogi--fintech",
            storageBucket: "adiyogi--fintech.appspot.com",
            messagingSenderId: "418730370740",
            appId: "1:418730370740:web:49676a2ac6d5ddd6bf99e1"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        function startFCM() {
            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken()
                }).then(function(token) {
                    console.log(token);
                    $.post("{{ route('store.token') }}", {
                        token
                    }, function(data) {
                        toastr.success(data.message);
                    })
                }).catch(function(error) {
                    toastr.error(error);
                });
        }
        messaging.onMessage(function(payload) {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });
    </script>
@endsection
