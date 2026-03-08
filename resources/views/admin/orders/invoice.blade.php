<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #INV-{{ $order->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; }
        .logo { font-size: 28px; font-weight: 900; text-transform: uppercase; color: #4f46e5; }
        .invoice-info { text-align: right; }
        .details { display: grid; grid-template-columns: 1fr 1px 1fr; gap: 40px; margin-bottom: 40px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .table th { background: #f8fafc; padding: 12px; text-align: left; font-size: 12px; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .total-section { float: right; width: 250px; }
        .total-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .grand-total { font-size: 20px; font-weight: 900; border-top: 2px solid #4f46e5; margin-top: 10px; padding-top: 10px; }
        @media print {
            .no-print { display: none; }
            .invoice-box { border: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">Click here to Print</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div class="logo">Notes<span style="color: #1e1b4b;">Share</span></div>
            <div class="invoice-info">
                <h2 style="margin: 0; color: #4f46e5;">INVOICE</h2>
                <p style="margin: 5px 0;">#INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p style="margin: 0; font-size: 12px; color: #666;">Date: {{ $order->created_at->format('d M, Y') }}</p>
            </div>
        </div>

        <div class="details">
            <div>
                <h4 style="text-transform: uppercase; font-size: 10px; color: #666; margin-bottom: 10px;">Billed To:</h4>
                <p style="margin: 0; font-weight: bold;">Name: {{ $order->customer->name }}</p>
                <p style="margin: 0; font-size: 13px;">Phone: {{ $order->customer->phone }}</p>
                <p style="margin: 5px 0; font-size: 13px; color: #444;">Address: {{ $order->customer->address }}</p>
            </div>
            <div style="background: #eee;"></div>
            <div>
                <h4 style="text-transform: uppercase; font-size: 10px; color: #666; margin-bottom: 10px;">Payment Details:</h4>
                <p style="margin: 0; font-size: 13px;">Method: <strong>{{ $order->payment_method }}</strong></p>
                <p style="margin: 5px 0; font-size: 13px;">Status: <span style="color: #059669; font-weight: bold;">{{ $order->status }}</span></p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th style="text-align: center;">Unit</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div style="font-weight: bold;">{{ $item->product->name }}</div>
                        <div style="font-size: 11px; color: #666;">{{ $item->product->generic_name }}</div>
                    </td>
                    <td style="text-align: center;">{{ ucfirst($item->unit_type) }}</td>
                    <td style="text-align: center;">{{ $item->ordered_quantity }}</td>
                    <td style="text-align: right;">৳{{ number_format($item->price, 2) }}</td>
                    <td style="text-align: right; font-weight: bold;">৳{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span style="color: #666;">Subtotal:</span>
                <span>৳{{ number_format($order->total_price, 2) }}</span>
            </div>
            <div class="total-row">
                <span style="color: #666;">Delivery Fee:</span>
                <span>৳0.00</span>
            </div>
            <div class="total-row grand-total">
                <span>Total Amount:</span>
                <span>৳{{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>

        <div style="margin-top: 100px; text-align: center; border-top: 1px dashed #eee; padding-top: 20px;">
            <p style="font-size: 12px; color: #999;">Thank you for choosing NotesShare Online Pharmacy.</p>
            <p style="font-size: 10px; color: #aaa; text-transform: uppercase;">This is a computer generated invoice.</p>
        </div>
    </div>
</body>
</html>
