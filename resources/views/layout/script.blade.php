<!-- Vendor JS -->
<script src="{{ asset('assets/theme/js/vendors.min.js')}}"></script>
<script src="{{ asset('assets/theme/js/pages/chat-popup.js') }}"></script>

<script src="{{ asset('assets/theme/assets/vendor_components/apexcharts-bundle/dist/apexcharts.min.js')}}"></script>
<script src="{{ asset('assets/theme/assets/icons/feather-icons/feather.min.js')}}"></script>
    
<script src="{{ asset('assets/theme/assets/vendor_components/OwlCarousel2/dist/owl.carousel.js')}}"></script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
<script src="https://cdn.amcharts.com/lib/4/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/kelly.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<script src="{{ asset('assets/theme/assets/vendor_components/bootstrap-select/dist/js/bootstrap-select.js')}}"></script>

<script src="{{ asset('assets/theme/assets/vendor_components/datatable/datatables.min.js') }}"></script>

<!-- Riday Admin App -->
<script src="{{ asset('assets/theme/js/template.js') }}"></script>
<script src="{{ asset('assets/theme/js/pages/dashboard.js') }}"></script>

<!-- Validations -->
<script src="{{ asset('assets/theme/js/pages/validation.js')}}"></script>
<script src="{{ asset('assets/theme/js/pages/form-validation.js')}}"></script>

<script src="{{ asset('assets/theme/assets/vendor_components/select2/dist/js/select2.full.js')}}"></script>

<!-- daterange -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<!-- Pusher Notification -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    var pusher_key = "{{ env('PUSHER_APP_KEY')}}";
    
    var pusher = new Pusher(pusher_key, {
        cluster: 'eu'
    });

    var companyId = {{ Auth::user()->company_id }}
    var channel = pusher.subscribe('my-channel-' + companyId);

    var soundEnabled = true;
    var audio = new Audio("{{ asset('assets/sound/order-received.wav') }}");

    audio.loop = true;
    
    channel.bind('order-received', function(data) {
        if (soundEnabled) {
            audio.play().catch(function(error) {
                console.error('Error playing sound:', error);
            });
        }

        toastr.success('New Order Received. <a href="'+data.url+'" target="_blank" class="order-link">View Order</a>', {
            timeOut: 0,  
            extendedTimeOut: 0,
            allowHtml: true,
        });

        $('.order-link').css({
            'color': '#ffffff',
            'text-decoration': 'underline',
        });
    });
</script>