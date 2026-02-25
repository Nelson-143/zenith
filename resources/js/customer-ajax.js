$(document).ready(function() {
    // Handle customer tab clicks
    $('.customer-tab').on('click', function() {
        const customerId = $(this).data('customer-id');

        // Mark current tab as active
        $('.customer-tab').removeClass('active');
        $(this).addClass('active');

        // Fetch customer-specific content via AJAX
        $.ajax({
            url: `/orders/customer/${customerId}`,
            method: 'GET',
            success: function(response) {
                $('#customer-content').html(response.html);
                initializeCartHandlers();
            },
            error: function(error) {
                console.error('Error fetching customer data:', error);
            }
        });
    });

    // Initialize cart handlers
    function initializeCartHandlers() {
        // Handle "Add to Cart"
        $('.add-to-cart').on('click', function() {
            const customerId = $('.active.customer-tab').data('customer-id');
            const productId = $('.product-select').val();

            if (!productId) {
                alert('{{ __('Select a product first!') }}');
                return;
            }

            $.ajax({
                url: `/orders/add-to-cart/${customerId}/${productId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#customer-content').html(response.html);
                    initializeCartHandlers();
                },
                error: function(error) {
                    alert('{{ __('Failed to add product to cart.') }}');
                }
            });
        });

        // Handle quantity updates
        $('.quantity-input').on('input', function() {
            const customerId = $('.active.customer-tab').data('customer-id');
            const rowId = $(this).data('row-id');
            const newQty = $(this).val();

            $.ajax({
                url: `/orders/update-cart/${customerId}/${rowId}`,
                method: 'POST',
                data: { qty: newQty },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#customer-content').html(response.html);
                    initializeCartHandlers();
                },
                error: function(error) {
                    alert('{{ __('Failed to update quantity.') }}');
                }
            });
        });

        // Handle "Remove from Cart"
        $('.remove-from-cart').on('click', function() {
            const customerId = $('.active.customer-tab').data('customer-id');
            const rowId = $(this).data('row-id');

            $.ajax({
                url: `/orders/remove-from-cart/${customerId}/${rowId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#customer-content').html(response.html);
                    initializeCartHandlers();
                },
                error: function(error) {
                    alert('{{ __('Failed to remove item.') }}');
                }
            });
        });

        // Handle "Create Invoice"
        $('.create-invoice').on('click', function() {
            const customerId = $(this).data('customer-id');

            $.ajax({
                url: `/orders/create-invoice/${customerId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert('{{ __('Invoice created successfully!') }}');
                        window.location.href = '/orders';
                    } else {
                        alert(response.error);
                    }
                },
                error: function(error) {
                    alert('{{ __('Failed to create invoice.') }}');
                }
            });
        });
    }

    // Initialize handlers for initial content
    initializeCartHandlers();
});