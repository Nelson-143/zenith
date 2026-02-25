@php
    $activeTab = $tabIndex ?? 1;
@endphp

<table class="table table-striped table-bordered align-middle">
    <thead class="thead-light">
        <tr>
            <th>Product</th>
            <th class="text-center">Quantity</th>
            <th class="text-center">Price</th>
            <th class="text-center">SubTotal</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($carts as $item)
            <tr data-row-id="{{ $item->rowId }}">
                <td>{{ $item->name }}</td>
                <td>
                    <form class="update-cart-form" data-tab="{{ $activeTab }}">
                        @csrf
                        <input type="number" name="qty" value="{{ $item->qty }}" class="form-control">
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                    </form>
                </td>
                <td class="text-center">{{ $item->price }}</td>
                <td class="text-center">{{ $item->subtotal }}</td>
                <td class="text-center">
                    <form class="delete-cart-form" data-tab="{{ $activeTab }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Add Products</td>
            </tr>
        @endforelse
    </tbody>
</table>