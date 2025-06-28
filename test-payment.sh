#!/bin/bash

# Script untuk testing payment integration
echo "=== Testing Payment Integration ==="

# Check if Midtrans package is installed
echo "1. Checking Midtrans package installation..."
if composer show midtrans/midtrans-php > /dev/null 2>&1; then
    echo "✓ Midtrans package is installed"
else
    echo "✗ Midtrans package is not installed"
    echo "Run: composer require midtrans/midtrans-php"
    exit 1
fi

# Check environment variables
echo ""
echo "2. Checking environment configuration..."
if grep -q "MIDTRANS_SERVER_KEY" .env; then
    echo "✓ MIDTRANS_SERVER_KEY is configured"
else
    echo "✗ MIDTRANS_SERVER_KEY is missing in .env"
    echo "Add: MIDTRANS_SERVER_KEY=your_server_key"
fi

if grep -q "MIDTRANS_CLIENT_KEY" .env; then
    echo "✓ MIDTRANS_CLIENT_KEY is configured"
else
    echo "✗ MIDTRANS_CLIENT_KEY is missing in .env"
    echo "Add: MIDTRANS_CLIENT_KEY=your_client_key"
fi

# Check database migration
echo ""
echo "3. Checking database migration..."
if php artisan migrate:status | grep -q "add_payment_fields_to_deliveries"; then
    echo "✓ Payment fields migration has been run"
else
    echo "✗ Payment fields migration has not been run"
    echo "Run: php artisan migrate"
fi

# Check if routes are accessible
echo ""
echo "4. Checking routes..."
if php artisan route:list | grep -q "payment.show"; then
    echo "✓ Payment routes are registered"
else
    echo "✗ Payment routes are not registered"
fi

# Check if files exist
echo ""
echo "5. Checking required files..."
files=(
    "app/Services/MidtransService.php"
    "app/Http/Controllers/PaymentController.php"
    "resources/views/payment.blade.php"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "✓ $file exists"
    else
        echo "✗ $file is missing"
    fi
done

echo ""
echo "=== Testing Complete ==="
echo ""
echo "To test payment integration:"
echo "1. Create a delivery booking"
echo "2. Click 'Bayar Sekarang' in the success modal"
echo "3. Complete payment using Midtrans sandbox"
echo ""
echo "Midtrans Sandbox Test Cards:"
echo "- Success: 4811 1111 1111 1114"
echo "- Failure: 4911 1111 1111 1113"
echo "- Challenge: 4411 1111 1111 1118"
