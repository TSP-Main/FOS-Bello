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
                                        <h5>Address <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="address" id="address" value="{{ $address ?? '' }}" class="form-control" required> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">						
                                    <div class="form-group">
                                        <h5>Apartment, Suite, etc. (Optional)</h5>
                                        <div class="controls">
                                            <input type="text" name="apartment" id="apartment" value="{{ $apartment ?? '' }}" class="form-control"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">						
                                    <div class="form-group">
                                        <h5>City <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="city" id="city" value="{{ $city ?? '' }}" class="form-control" required> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">						
                                    <div class="form-group">
                                        <h5>Postcode <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="postcode" id="postcode" value="{{ $postcode ?? '' }}" class="form-control" required> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">						
                                    <div class="form-group">
                                        <h5>Add Radius in KM <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="radius" value="{{ $radius ?? '' }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="latitude" name="latitude" value="{{ $latitude ?? '' }}">
                                <input type="hidden" id="longitude" name="longitude" value="{{ $longitude ?? '' }}">
                                {{-- <div id="map" style="height: 400px; margin-top: 20px;"></div> --}}
                                <div class="col-12">						
                                    <div class="form-group">
                                        {{-- <h5>Select Restaurant Location<span class="text-danger">*</span></h5> --}}
                                        {{-- <input type="hidden" id="coordinates" name="coordinates" value="{{ $coordinates }}"> --}}
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&libraries=places" async defer></script>

    <script>
        let autocomplete, map, marker;
    
        function initAutocomplete() {
            // Initialize autocomplete
            autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'));
            autocomplete.setFields(['address_component', 'geometry']);
    
            autocomplete.addListener('place_changed', function() {
                let place = autocomplete.getPlace();
                if (!place.geometry) {
                    alert("No details available for the input: '" + place.name + "'");
                    return;
                }
    
                // Set latitude and longitude
                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
    
                // Autofill the city, postcode, and apartment
                fillInAddress(place);
    
                // Center map on selected location
                map.setCenter(place.geometry.location);
                marker.setPosition(place.geometry.location);
            });
    
            // Initialize the map with the saved location
            let savedLocation = { 
                lat: parseFloat('{{ $latitude ?? 0 }}'), 
                lng: parseFloat('{{ $longitude ?? 0 }}') 
            };
    
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: savedLocation
            });
    
            marker = new google.maps.Marker({
                map: map,
                position: savedLocation,
                draggable: true
            });
    
            google.maps.event.addListener(marker, 'dragend', function() {
                document.getElementById('latitude').value = marker.getPosition().lat();
                document.getElementById('longitude').value = marker.getPosition().lng();
            });
    
            // Handle manual postcode entry
            document.getElementById('postcode').addEventListener('blur', function() {
                let postcode = this.value;
                if (postcode) {
                    geocodePostcode(postcode);
                }
            });
        }
    
        function fillInAddress(place) {
            let addressComponents = place.address_components;
            let city = '';
            let postcode = '';
            let apartment = '';
    
            addressComponents.forEach(component => {
                let types = component.types;
    
                if (types.includes('locality')) {
                    city = component.long_name;
                } else if (types.includes('postal_code')) {
                    postcode = component.long_name;
                } else if (types.includes('subpremise')) {
                    apartment = component.long_name;
                }
            });
    
            // Autofill fields
            document.getElementById('postcode').value = postcode;
            document.getElementById('apartment').value = apartment;
            document.getElementById('city').value = city;
        }
    
        function geocodePostcode(postcode) {
            let geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'address': postcode }, function(results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        let location = results[0].geometry.location;
    
                        // Update latitude and longitude
                        document.getElementById('latitude').value = location.lat();
                        document.getElementById('longitude').value = location.lng();
    
                        // Center map on location
                        map.setCenter(location);
                        marker.setPosition(location);
    
                        // Autofill other address fields based on geocoded result
                        fillInAddress(results[0]);
                    }
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }
    
        document.addEventListener('DOMContentLoaded', function() {
            initAutocomplete();
        });
    </script>

@endsection