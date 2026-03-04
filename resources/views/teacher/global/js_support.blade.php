<script src="{{ asset('source/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('source/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('source/plugins/simplebar/simplebar.min.js') }}"></script>
<script src="https://unpkg.com/hotkeys-js/dist/hotkeys.min.js"></script>
<script src="{{ asset('source/plugins/jvectormap/jquery-jvectormap-2.0.3.min.js') }}"></script>
<script src="{{ asset('source/plugins/jvectormap/jquery-jvectormap-world-mill.js') }}"></script>
<script src="{{ asset('source/plugins/jvectormap/jquery-jvectormap-us-aea.js') }}"></script>
<script src="{{ asset('source/plugins/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('source/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('source/plugins/select2/js/select2.min.js') }}"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    jQuery(document).ready(function() {
        jQuery('input[name="dateRange"]').daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        jQuery('input[name="dateRange"]').on('apply.daterangepicker', function(ev, picker) {
            jQuery(this).val(picker.startDate.format('MM/DD/YYYY'));
        });
        jQuery('input[name="dateRange"]').on('cancel.daterangepicker', function(ev, picker) {
            jQuery(this).val('');
        });
    });
</script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="{{ asset('source/plugins/toaster/toastr.min.js') }}"></script>
<script src="{{ asset('source/js/mono.js') }}"></script>
<script src="{{ asset('source/js/chart.js') }}"></script>
<script src="{{ asset('source/js/map.js') }}"></script>
<script src="{{ asset('source/js/custom.js') }}"></script>

<script>
    $(document).ready(function () {
        @if(session('success'))
            showToast('success', "{{ session('success') }}");
        @endif

        @if(session('error'))
            showToast('error', "{{ session('error') }}");
        @endif

        @if(session('warning'))
            showToast('warning', "{{ session('warning') }}");
        @endif
    });

    function showToast(type, message) {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };

        toastr[type](message);
    }
</script>
