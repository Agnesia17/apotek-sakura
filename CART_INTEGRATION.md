# Cart Integration Documentation

## Overview
This document describes the integration of cart functionality in the Apotek Sakura application, including the `viewCart`, `removeItem`, and `updateQuantity` methods in the CartController and their integration with the cart-user.blade.php view.

## Backend Implementation

### CartController Methods

#### 1. viewCart()
- **Purpose**: Displays the customer's cart items
- **Authentication**: Requires customer login
- **Data Structure**: Returns formatted cart items with product details
- **View**: Renders `landing.pages.list-cart-user` with cart data

#### 2. removeItem($id)
- **Purpose**: Removes an item from the cart
- **Method**: DELETE request
- **Authentication**: Requires customer login
- **Response**: JSON response with success message
- **Validation**: Ensures item belongs to logged-in customer

#### 3. updateQuantity(Request $request, $id)
- **Purpose**: Updates the quantity of a cart item
- **Method**: PUT request
- **Authentication**: Requires customer login
- **Validation**: 
  - Quantity must be positive integer
  - Quantity cannot exceed available stock
- **Response**: JSON response with updated quantity and subtotal

#### 4. store() (Checkout)
- **Purpose**: Processes cart checkout and creates sales order
- **Method**: POST request
- **Features**:
  - Validates stock availability
  - Creates Penjualan record
  - Creates PenjualanDetail records
  - Updates product stock
  - Clears cart after successful checkout

#### 5. customerOrders()
- **Purpose**: Displays customer's order history
- **View**: Shows all past orders with details

## Frontend Implementation

### list-cart-user.blade.php Features

#### 1. Dynamic Cart Display
- Shows real cart data from database
- Displays product images, names, brands, prices
- Shows stock availability
- Calculates totals dynamically

#### 2. Interactive Quantity Controls
- Increase/decrease buttons
- Direct input field
- Stock validation
- Real-time AJAX updates

#### 3. Remove Item Functionality
- Confirmation dialog
- Smooth animation
- AJAX removal
- Automatic cart summary update

#### 4. Cart Summary
- Real-time total calculation
- Individual item subtotals
- Checkout button with form submission

#### 5. Empty Cart State
- Shows when cart is empty
- Link to continue shopping
- Responsive design

#### 6. Error Handling
- Success/error notifications
- Loading states
- Input validation
- Stock limit enforcement

## Routes

```php
// Cart routes
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
Route::delete('/cart/{id}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::put('/cart/{id}/quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
Route::post('/cart/checkout', [CartController::class, 'store'])->name('cart.checkout');
Route::get('/orders', [CartController::class, 'customerOrders'])->name('customer.orders');
```

## JavaScript Functions

### 1. removeItem(itemId)
- Sends DELETE request to remove cart item
- Handles loading states
- Updates UI after successful removal
- Shows error messages on failure

### 2. updateQuantity(itemId, newQuantity)
- Sends PUT request to update quantity
- Validates quantity against stock
- Updates cart summary in real-time
- Handles errors gracefully

### 3. increaseQuantity(itemId) / decreaseQuantity(itemId)
- Increment/decrement quantity buttons
- Stock limit validation
- Triggers updateQuantity function

### 4. updateCartSummary()
- Recalculates totals based on current quantities
- Updates individual item subtotals
- Updates grand total
- Formats currency display

## Security Features

1. **Authentication**: All cart operations require customer login
2. **Authorization**: Customers can only access their own cart items
3. **CSRF Protection**: All AJAX requests include CSRF tokens
4. **Input Validation**: Server-side validation for all inputs
5. **Stock Validation**: Prevents ordering more than available stock

## Error Handling

1. **Network Errors**: Graceful handling of AJAX failures
2. **Validation Errors**: User-friendly error messages
3. **Stock Errors**: Clear messaging when stock is insufficient
4. **Authentication Errors**: Redirect to login when needed

## Responsive Design

- Mobile-friendly layout
- Touch-friendly controls
- Adaptive grid system
- Optimized for various screen sizes

## Performance Optimizations

1. **Eager Loading**: Cart items loaded with product relationships
2. **Minimal AJAX**: Only necessary data transferred
3. **Efficient DOM Updates**: Targeted element updates
4. **Debounced Input**: Prevents excessive API calls

## Usage Examples

### Adding to Cart
```javascript
// From product page
fetch('/cart/add', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        id_obat: productId,
        jumlah: quantity
    })
});
```

### Viewing Cart
```php
// Navigate to cart page
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
```

### Removing Item
```javascript
// Remove item from cart
removeItem(cartItemId);
```

### Updating Quantity
```javascript
// Update item quantity
updateQuantity(cartItemId, newQuantity);
```

## Testing

To test the cart functionality:

1. **Login as customer**
2. **Add products to cart** from product pages
3. **View cart** to see added items
4. **Update quantities** using +/- buttons or direct input
5. **Remove items** using the X button
6. **Checkout** to complete the order
7. **View order history** to see past orders

## Dependencies

- Laravel 10+
- jQuery 3.6.0+
- Font Awesome 6.0+
- Bootstrap 5+
- CSRF token support
- Session management

## Future Enhancements

1. **Cart Persistence**: Save cart items across sessions
2. **Wishlist**: Add wishlist functionality
3. **Bulk Operations**: Select multiple items for removal
4. **Cart Sharing**: Share cart with others
5. **Advanced Validation**: More sophisticated stock checking
6. **Cart Analytics**: Track cart abandonment rates 