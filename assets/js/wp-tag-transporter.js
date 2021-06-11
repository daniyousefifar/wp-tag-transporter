(function ($) {
    'use strict';

    $(window).load(function () {
        $('.js-wp-tag-transporter-tags').select2({
            placeholder: wp_tag_transporter_object.tags_placeholder,
            ajax: {
                url: wp_tag_transporter_object.ajax_url,
                dataType: 'json',
                type: "post",
                data: function (params) {
                    var query = {
                        search: params.term,
                        action: 'wp_tag_transporter_load_tags',
                        nonce: wp_tag_transporter_object.nonce,
                    }

                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data.data.payload
                    };
                }
            }
        });
        $('.js-wp-tag-transporter-taxonomies').select2({
            placeholder: wp_tag_transporter_object.taxonomies_placeholder,
            ajax: {
                url: wp_tag_transporter_object.ajax_url,
                dataType: 'json',
                type: "post",
                data: function (params) {
                    var query = {
                        action: 'wp_tag_transporter_load_taxonomies',
                        nonce: wp_tag_transporter_object.nonce,
                    }

                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data.data.payload
                    };
                }
            }
        });
        $('.js-wp_tag_transporter_action__wrapper--btn').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $tags = $('.js-wp-tag-transporter-tags');
            var $taxonomy = $('.js-wp-tag-transporter-taxonomies');

            // Validation Tag: Check is tag selected
            if ($tags.val() === null) {
                alert('Please select a tag.');
                return;
            }

            // Validation Taxonomy: Check is taxonomy selected.
            if ($taxonomy.val() === null) {
                alert('Please select a taxonomy.');
                return;
            }



            $.ajax({
                url: wp_tag_transporter_object.ajax_url,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'wp_tag_transporter_process',
                    nonce: wp_tag_transporter_object.nonce,
                    tag: $tags.val(),
                    taxonomy: $taxonomy.val(),
                },
                beforeSend: function () {
                    $this.attr('disabled', 'disabled');
                },
                success: function (response) {
                    alert(response.data.message);
                },
                complete: function () {
                    $this.removeAttr('disabled');
                }
            });
        });
    });

})(jQuery);
