@extends('layout.app')
@section('title', 'Delivery Radius | FO - Food Ordering System')
<style type="text/css">
    #map {
            height: 400px;
            width: 100%;
        }
</style>
@section('content')

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Delivery Radius</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Delivery Radius</li>
                            <li class="breadcrumb-item active" aria-current="page">Delivery Radius</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('radius.store') }}" method="post" id="radius_form" class="form-horizontal needs-validation" role="form" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-12">						
                                    <div class="form-group">
                                        <h5>Add Radius in KM <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="radius" value="{{ $radius ?? '' }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">						
                                    <div class="form-group">
                                        <h5>Select Restaurant Location<span class="text-danger">*</span></h5>
                                        <input type="hidden" id="coordinates" name="coordinates" value="{{ $coordinates }}">
                                        <div id="map"></div>
                                    </div>
                                </div>
                            
                                <div class="text-xs-right">
                                    <button type="submit" class="btn btn-info">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initMap" async defer></script>

    <script>
        function initMap() {
            let map, marker;
            const coordinatesInput = document.getElementById('coordinates');
            let savedCoordinates = coordinatesInput.value ? JSON.parse(coordinatesInput.value) : null;
    
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
    
                    // Set map options based on saved or current location
                    const centerPos = savedCoordinates || pos;
    
                    // Initialize the map
                    map = new google.maps.Map(document.getElementById('map'), {
                        center: centerPos,
                        zoom: 20
                    });
    
                    // Initialize the marker
                    marker = new google.maps.Marker({
                        position: centerPos,
                        map: map,
                        title: "Restaurant Location",
                        draggable: true
                    });
    
                    // Update hidden input field with current position
                    coordinatesInput.value = JSON.stringify(centerPos);
    
                    // Listen for dragend event to update position
                    google.maps.event.addListener(marker, 'dragend', function (event) {
                        var lat = event.latLng.lat();
                        var lng = event.latLng.lng();
                        var newPos = {
                            lat: lat,
                            lng: lng
                        };
    
                        // Update the hidden input field with the new position
                        coordinatesInput.value = JSON.stringify(newPos);
                    });
    
                    // Listen for click events on the map
                    map.addListener('click', function (event) {
                        var lat = event.latLng.lat();
                        var lng = event.latLng.lng();
                        var newPos = {
                            lat: lat,
                            lng: lng
                        };
    
                        // Move the marker to the clicked location
                        marker.setPosition(newPos);
    
                        // Update the hidden input field with the new position
                        coordinatesInput.value = JSON.stringify(newPos);
                    });
    
                }, function () {
                    handleLocationError(true, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, map.getCenter());
            }
        }
    
        function handleLocationError(browserHasGeolocation, pos) {
            alert(browserHasGeolocation ?
                "Error: The Geolocation service failed." :
                "Error: Your browser doesn't support geolocation.");
        }
    </script>

    {{-- <script>
        function initMap() {
            let map, marker;

            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    map = new google.maps.Map(document.getElementById('map'), {
                        center: pos,
                        zoom: 14
                    });

                    marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        title: "You are here!",
                        draggable: true
                    });

                    // Update hidden input field with current position
                    document.getElementById('coordinates').value = JSON.stringify(pos);

                    // Listen for drag events on the marker
                    google.maps.event.addListener(marker, 'dragend', function (event) {
                        var lat = event.latLng.lat();
                        var lng = event.latLng.lng();
                        var newPos = {
                            lat: lat,
                            lng: lng
                        };

                        // Update the hidden input field with new position
                        document.getElementById('coordinates').value = JSON.stringify(newPos);
                    });

                }, function () {
                    handleLocationError(true, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, map.getCenter());
            }
        }

        function handleLocationError(browserHasGeolocation, pos) {
            alert(browserHasGeolocation ?
                "Error: The Geolocation service failed." :
                "Error: Your browser doesn't support geolocation.");
        }
    </script> --}}
@endsection