<script>
    var schema_left = {
        "properties": {
            "promo_title": {
                "type": "string",
                "title": "Title"
            },
            "promo_text": {
                "type": "string",
                "title": "Text"
            },
            "promo_image": {
                "type": "string",
                "format": "uri"
            }
        }
    };

    var schema_right = {
        "properties": {
            "promo_title": {
                "type": "string",
                "title": "Title"
            },
            "promo_text": {
                "type": "string",
                "title": "Text"
            },
            "promo_image": {
                "type": "string"
            }
        }
    };

    var postRenderCallback = function(control) {
        var id = "img-"+$(control.domEl).attr("id");
        $('#'+$(control.domEl).attr("id")+' #promo_image').attr('name', "promo_image_"+$(control.domEl).attr("id"));
        control.getFieldEl().append("<div id='"+id+"' style='display:none'><table><tr><td nowrap='nowrap' class='imagePreview' style='width: 220px'> </td></tr></table></div>");

        if (control.data.promo_image != '') {
            var img = $("#"+id+" .imagePreview").html("").append("<img style='max-width: 200px; max-height: 200px' src='<?php echo Storage::disk('public')->url('/') ?>"+control.data.promo_image+"'>");
            $("#"+id).css({
                "display": "block"
            });
        }
    };
    var options = {
        "fields": {
            "promo_text": {
                "type": "tinymce"
            },
            "promo_image": {
                "type": "file",
                "id": "promo_image",
                "fieldClass": "input-file",
                "selectionHandler": function(files, data) {
                    var id = "img-"+$(this.parent.domEl).attr("id");
                    $("#"+id+" .imagePreview").html("").append("<img style='max-width: 200px; max-height: 200px' src='" + data[0] + "'>");
                    $("#"+id).css({
                        "display": "block"
                    });

                }
            }
        }
    };

    $(document).ready(function() {
        $('#name').blur(function() {
            $("#key").val(slugify($('#name').val()));
        });

        @if (count($campaigns) > 1)
        $('#campaign_id').on('change', function() {
            var campaign_id = $('#campaign_id').val();
            fillEntrypoints(campaign_id);
        });
        @else
            fillEntrypoints({{$campaigns[0]->id}});
        @endif

        $("#submit").click(function () {
            initial_template = $("#initial_page_template").val();
            result_template = $("#result_page_template").val();

            if (initial_template != '') {
                $('#initial_page_data').val(JSON.stringify($("#initial_page_template_" + initial_template).alpaca("get").getValue()));
            }

            if (result_template != '') {
                $('#result_page_data').val(JSON.stringify($("#result_page_template_" + result_template).alpaca("get").getValue()));
            }

            console.log($('#initial_page_data').val());
            console.log($('#result_page_data').val());

            $("#form").submit();
        });

        $("#promo_type").change(function (e) {
            $(".fields-types").hide();
            $("#fields-type-"+$(this).val()).show();
        });

        @if (empty($promotion))
            @if (!empty(old('type_id')))
                $("#fields-type-{{old('type_id')}}").show();
            @endif
        @endif
    });

    function fillEntrypoints(campaign_id) {
        $('#entry_points').empty();
        $('#entry_points').html('<option selected="selected" value="">- Select -</option>\n' +
            '<option value="simple">New Simple Fields</option>\n' +
            '<option value="complete">New Complete Fields</option>');

        if (campaign_id != '') {
            $.ajax({
                url: '/api/v1/entrypoint/list/' + campaign_id,
                method: 'GET',
                datatype: 'json',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $.each(response.data, function(key, value) {
                        $('#entry_points').append('<option value="'+value.key+'">'+value.name+'</option>');
                    });
                }
            });
        }
    }
</script>
