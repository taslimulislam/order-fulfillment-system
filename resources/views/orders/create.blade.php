@extends('layout')

@section('content')

<div class="container mt-4">
    <h3>Create Order</h3>
    <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
        @csrf
        <div class="row">
            {{-- Product List Box --}}
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Available Products</strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Select</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr class="product-row" data-id="{{ $product['id'] }}">
                                        <td>
                                            <input type="checkbox" class="product-check" data-id="{{ $product['id'] }}">
                                        </td>
                                        <td>{{ $product['name'] }}</td>
                                        <td>{{ $product['price'] }}</td>
                                        <td>{{ $product['stock_quantity'] }}</td>
                                        <td>
                                            <input type="number" name="items[{{ $product['id'] }}][quantity]" min="1" class="form-control qty-input" disabled>
                                            <input type="hidden" name="items[{{ $product['id'] }}][product_id]" value="{{ $product['id'] }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Summary Box --}}
            <div class="col-md-4">
                <div class="card summary-box">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">Summary</h5>
                            <p>Total Qty: <span id="totalQty">0</span></p>
                            <p>Total Amount: <span id="totalAmount">0.00</span></p>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary w-100 mb-2">Submit Order</button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .summary-box {
        position: sticky;
        top: 66px;
    }
</style>

<script>
    $(document).ready(function () {
        $('.qty-input').on('input', function () {
            const val = parseInt($(this).val());
            if (val < 1 || isNaN(val)) {
                $(this).val(1);
            }
            calculateTotals();
        });

        $('.product-check').on('change', function () {
            const row = $(this).closest('tr');
            const qtyInput = row.find('.qty-input');
            qtyInput.prop('disabled', !this.checked);
            qtyInput.val(this.checked ? 1 : '');
            calculateTotals();
        });

        $('.qty-input').on('input', calculateTotals);

        function calculateTotals() {
            let totalQty = 0, totalAmount = 0;
            $('tbody tr').each(function () {
                const checked = $(this).find('.product-check').is(':checked');
                if (checked) {
                    const qty = parseInt($(this).find('.qty-input').val()) || 0;
                    const price = parseFloat($(this).find('td:nth-child(3)').text());
                    totalQty += qty;
                    totalAmount += qty * price;
                }
            });
            $('#totalQty').text(totalQty);
            $('#totalAmount').text(totalAmount.toFixed(2));
        }

        $('#orderForm').on('submit', function () {
            $('.product-row').each(function () {
                const checked = $(this).find('.product-check').is(':checked');
                if (!checked) {
                    $(this).find('input').remove(); // Remove all inputs from unselected rows
                }
            });
        });
    });
</script>
@endsection