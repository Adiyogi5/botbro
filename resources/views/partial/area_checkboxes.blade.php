<div class="scrollable-box" style="max-height: 200px; overflow-y: auto;">
    <div class="form-group">
        @if (count($areas) > 0)
            @foreach ($areas as $area)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $area->id }}" id="area{{ $area->id }}"
                        {{ $area->delivery_area_status == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="area{{ $area->id }}">{{ $area->name }}</label>
                </div>
            @endforeach
        @else
            <div class="alert alert-warning" role="alert">
                We do not provide service and products in this area.
            </div>
        @endif

    </div>
</div>

<script type="text/javascript">
    /// Change Stats
    $("body").on("change", ".form-check-input", function() {
        var areaId = $(this).val();
        var isChecked = $(this).is(':checked') ? 1 : 0;
        var delivery_partner_id = '{{ isset($delivery_partner_id) ? $delivery_partner_id : null }}'
        if (delivery_partner_id.length > 0) {
            $.post("{{ route('admin.add_delivery_area') }}", {
                    _token: "{{ csrf_token() }}",
                    id: areaId,
                    delivery_partner_id,
                },
                function(data) {
                    if (data.success) {
                        toastr.success(data.message);
                        if (isChecked) {
                            $(this).prop('checked', true);
                        } else {
                            $(this).prop('checked', false);
                        }
                    } else {
                        toastr.error(data.message);
                        $(this).prop('checked', !isChecked);
                    }
                });
        } else {
            $.post("{{ route('delivery_partner.add_delivery_area') }}", {
                    _token: "{{ csrf_token() }}",
                    id: areaId,
                },
                function(data) {
                    if (data.success) {
                        toastr.success(data.message);
                        if (isChecked) {
                            $(this).prop('checked', true);
                        } else {
                            $(this).prop('checked', false);
                        }
                    } else {
                        toastr.error(data.message);
                        $(this).prop('checked', !isChecked);
                    }
                });
        }

    });
</script>
