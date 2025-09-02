(function($) {
    'use strict';

    $(document).ready(function() {
        var mediaUploader;
        var editMode = false;
        var editingLogoId = null;

        // Load logos on page load
        loadLogos();

        // Handle logo upload
        $('#upload-logo-btn').on('click', function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media({
                title: 'Choose Logo',
                button: {
                    text: 'Choose Logo'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#logo-image').val(attachment.url);
                $('#logo-preview').html('<img src="' + attachment.url + '" alt="Logo Preview">');
            });

            mediaUploader.open();
        });

        // Handle form submission
        $('#lsfd-add-logo-form').on('submit', function(e) {
            e.preventDefault();

            var formData = {
                action: editMode ? 'lsfd_update_logo' : 'lsfd_save_logo',
                nonce: lsfd_ajax.nonce,
                logo_title: $('#logo-title').val(),
                logo_image: $('#logo-image').val(),
                logo_url: $('#logo-url').val(),
                logo_alt: $('#logo-alt').val()
            };

            if (editMode) {
                formData.logo_id = editingLogoId;
            }

            if (!formData.logo_title || !formData.logo_image) {
                alert('Please fill in required fields (Title and Image).');
                return;
            }

            $.ajax({
                url: lsfd_ajax.ajax_url,
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#lsfd-add-logo-form button[type="submit"]').prop('disabled', true).text('Saving...');
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message);
                        resetForm();
                        loadLogos();
                    } else {
                        alert(response.data.message);
                    }
                },
                error: function() {
                    alert('An error occurred while saving the logo.');
                },
                complete: function() {
                    $('#lsfd-add-logo-form button[type="submit"]').prop('disabled', false);
                    updateFormButton();
                }
            });
        });

        // Handle logo deletion
        $(document).on('click', '.lsfd-delete-btn', function() {
            var logoId = $(this).data('logo-id');
            var logoTitle = $(this).data('logo-title');

            if (confirm('Are you sure you want to delete "' + logoTitle + '"?')) {
                $.ajax({
                    url: lsfd_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'lsfd_delete_logo',
                        nonce: lsfd_ajax.nonce,
                        logo_id: logoId
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.data.message);
                            loadLogos();
                        } else {
                            alert(response.data.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting the logo.');
                    }
                });
            }
        });

        // Handle logo editing
        $(document).on('click', '.lsfd-edit-btn', function() {
            var logoId = $(this).data('logo-id');
            var logoItem = $(this).closest('.lsfd-logo-item');
            
            editMode = true;
            editingLogoId = logoId;

            $('#logo-title').val(logoItem.find('h4').text());
            $('#logo-image').val(logoItem.find('img').attr('src'));
            $('#logo-url').val(logoItem.find('.logo-url').text());
            $('#logo-alt').val(logoItem.find('img').attr('alt'));
            $('#logo-preview').html('<img src="' + logoItem.find('img').attr('src') + '" alt="Logo Preview">');

            updateFormButton();
            
            $('html, body').animate({
                scrollTop: $('.lsfd-add-logo-section').offset().top - 100
            }, 500);
        });

        // Cancel edit mode
        $(document).on('click', '#cancel-edit-btn', function() {
            resetForm();
        });

        // Load logos function
        function loadLogos() {
            $('#logos-list').html('<div class="lsfd-loading"></div>');

            $.ajax({
                url: lsfd_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'lsfd_get_logos',
                    nonce: lsfd_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        displayLogos(response.data);
                    } else {
                        $('#logos-list').html('<div class="lsfd-no-logos">Error loading logos.</div>');
                    }
                },
                error: function() {
                    $('#logos-list').html('<div class="lsfd-no-logos">Error loading logos.</div>');
                }
            });
        }

        // Display logos function
        function displayLogos(logos) {
            if (logos.length === 0) {
                $('#logos-list').html('<div class="lsfd-no-logos">No logos found. Add your first logo above!</div>');
                return;
            }

            var html = '';
            logos.forEach(function(logo) {
                html += '<div class="lsfd-logo-item" data-logo-id="' + logo.id + '">';
                html += '<div class="handle">⋮⋮</div>';
                html += '<img src="' + logo.image + '" alt="' + logo.alt + '">';
                html += '<h4>' + logo.title + '</h4>';
                if (logo.url) {
                    html += '<p class="logo-url">' + logo.url + '</p>';
                }
                html += '<div class="lsfd-logo-actions">';
                html += '<button class="button lsfd-edit-btn" data-logo-id="' + logo.id + '">Edit</button>';
                html += '<button class="button lsfd-delete-btn" data-logo-id="' + logo.id + '" data-logo-title="' + logo.title + '">Delete</button>';
                html += '</div>';
                html += '</div>';
            });

            $('#logos-list').html(html);

            // Make logos sortable
            $('#logos-list').sortable({
                handle: '.handle',
                placeholder: 'lsfd-logo-placeholder',
                update: function(event, ui) {
                    var logoOrder = [];
                    $('.lsfd-logo-item').each(function() {
                        logoOrder.push($(this).data('logo-id'));
                    });

                    $.ajax({
                        url: lsfd_ajax.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'lsfd_reorder_logos',
                            nonce: lsfd_ajax.nonce,
                            logo_order: logoOrder
                        },
                        success: function(response) {
                            if (response.success) {
                                // Optionally show a success message
                            }
                        }
                    });
                }
            });
        }

        // Reset form function
        function resetForm() {
            $('#lsfd-add-logo-form')[0].reset();
            $('#logo-preview').empty();
            editMode = false;
            editingLogoId = null;
            updateFormButton();
        }

        // Update form button text
        function updateFormButton() {
            var submitBtn = $('#lsfd-add-logo-form button[type="submit"]');
            var formTitle = $('.lsfd-add-logo-section h2');
            
            if (editMode) {
                submitBtn.text('Update Logo');
                formTitle.html('Edit Logo <button type="button" id="cancel-edit-btn" class="button" style="margin-left: 10px;">Cancel</button>');
            } else {
                submitBtn.text('Add Logo');
                formTitle.text('Add New Logo');
            }
        }
    });

})(jQuery);
