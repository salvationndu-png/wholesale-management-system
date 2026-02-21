<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Payments;
use App\Models\Products;
use App\Models\Stocks;
use App\Models\Sales;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class HomeController extends Controller
{
    
    
    public function redirect()
    {
      if(Auth::id())
      {
          if (Auth::user() )
           {
        $totalProducts = Products::count();
        $lastMonthProducts = Products::where('created_at', '<', Carbon::now()->subMonth())->count();
        $productsChange = $lastMonthProducts > 0 ? (($totalProducts - $lastMonthProducts) / $lastMonthProducts) * 100 : 0;

        $totalStock = Stocks::sum('quantity');

        $todaysSales = Sales::whereDate('sale_date', Carbon::today())->sum('total');
        $yesterdaySales = Sales::whereDate('sale_date', Carbon::yesterday())->sum('total');
        $salesChange = $yesterdaySales > 0 ? (($todaysSales - $yesterdaySales) / $yesterdaySales) * 100 : 0;

        $lowStockCount = Products::where('quantity', '<', 10)->where('quantity', '>', 0)->count();

        $topProductsRaw = Sales::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $topProducts = ['labels' => [], 'data' => []];
        foreach ($topProductsRaw as $sale) {
            $topProducts['labels'][] = $sale->product ? $sale->product->name : 'Unknown';
            $topProducts['data'][] = $sale->total_qty;
        }

        $salesTrendRaw = Sales::select(
                DB::raw('DATE(sale_date) as date'),
                DB::raw('SUM(total) as total_sales')
            )
            ->where('sale_date', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesTrend = ['labels' => [], 'data' => []];
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::today()->subDays($i)->toDateString());
        }

        foreach ($dates as $date) {
            $sales = $salesTrendRaw->firstWhere('date', $date);
            $salesTrend['labels'][] = Carbon::parse($date)->format('D');
            $salesTrend['data'][] = $sales ? $sales->total_sales : 0;
        }

        $notifications = [];
        $lowStock = Products::where('quantity', '<', 10)->where('quantity', '>', 0)->get();
        if ($lowStock->count() > 0) {
            $notifications[] = ['type' => 'warning', 'title' => 'Low Stock Alert', 'message' => $lowStock->count() . ' product(s) need restocking', 'time' => 'Now'];
        }
        
        $recentSales = Sales::with('product')->orderBy('created_at', 'desc')->take(3)->get();
        foreach ($recentSales as $sale) {
            $notifications[] = ['type' => 'success', 'title' => 'New Sale', 'message' => ($sale->product ? $sale->product->name : 'Product') . ' - ₦' . number_format($sale->total), 'time' => $sale->created_at->diffForHumans()];
        }
        
        $recentStocks = Stocks::with('product')->orderBy('created_at', 'desc')->take(2)->get();
        foreach ($recentStocks as $stock) {
            $notifications[] = ['type' => 'info', 'title' => 'Stock Updated', 'message' => ($stock->product ? $stock->product->name : 'Product') . ' - ' . $stock->quantity . ' bundles added', 'time' => $stock->created_at->diffForHumans()];
        }

        return view('user.home-modern', compact('totalProducts', 'totalStock', 'todaysSales', 'topProducts', 'salesTrend', 'notifications', 'salesChange', 'productsChange', 'lowStockCount'));
    } 
        
      }
      else {
         return redirect()->back();
      }
      }



        public function login()
            {
        
            return view('auth.login');
            }
    public function index()
    {
        // Total products
        $totalProducts = Products::count();
        $lastMonthProducts = Products::where('created_at', '<', Carbon::now()->subMonth())->count();
        $productsChange = $lastMonthProducts > 0 ? (($totalProducts - $lastMonthProducts) / $lastMonthProducts) * 100 : 0;

        // Total stock (bundles)
        $totalStock = Stocks::sum('quantity');

        // Today's sales
        $todaysSales = Sales::whereDate('sale_date', Carbon::today())->sum('total');
        $yesterdaySales = Sales::whereDate('sale_date', Carbon::yesterday())->sum('total');
        $salesChange = $yesterdaySales > 0 ? (($todaysSales - $yesterdaySales) / $yesterdaySales) * 100 : 0;

        // Low stock count
        $lowStockCount = Products::where('quantity', '<', 10)->where('quantity', '>', 0)->count();

        // Top selling products for doughnut chart
        $topProductsRaw = Sales::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $topProducts = [
            'labels' => [],
            'data' => [],
        ];

        foreach ($topProductsRaw as $sale) {
            $topProducts['labels'][] = $sale->product ? $sale->product->name : 'Unknown';
            $topProducts['data'][] = $sale->total_qty;
        }

        // Sales trend for the past 7 days
        $salesTrendRaw = Sales::select(
                DB::raw('DATE(sale_date) as date'),
                DB::raw('SUM(total) as total_sales')
            )
            ->where('sale_date', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesTrend = [
            'labels' => [],
            'data' => [],
        ];

        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::today()->subDays($i)->toDateString());
        }

        foreach ($dates as $date) {
            $sales = $salesTrendRaw->firstWhere('date', $date);
            $salesTrend['labels'][] = Carbon::parse($date)->format('D');
            $salesTrend['data'][] = $sales ? $sales->total_sales : 0;
        }

        // Notifications
        $notifications = [];
        
        $lowStock = Products::where('quantity', '<', 10)->where('quantity', '>', 0)->get();
        if ($lowStock->count() > 0) {
            $notifications[] = [
                'type' => 'warning',
                'title' => 'Low Stock Alert',
                'message' => $lowStock->count() . ' product(s) need restocking',
                'time' => 'Now'
            ];
        }
        
        $recentSales = Sales::with('product')->orderBy('created_at', 'desc')->take(3)->get();
        foreach ($recentSales as $sale) {
            $notifications[] = [
                'type' => 'success',
                'title' => 'New Sale',
                'message' => ($sale->product ? $sale->product->name : 'Product') . ' - ₦' . number_format($sale->total),
                'time' => $sale->created_at->diffForHumans()
            ];
        }
        
        $recentStocks = Stocks::with('product')->orderBy('created_at', 'desc')->take(2)->get();
        foreach ($recentStocks as $stock) {
            $notifications[] = [
                'type' => 'info',
                'title' => 'Stock Updated',
                'message' => ($stock->product ? $stock->product->name : 'Product') . ' - ' . $stock->quantity . ' bundles added',
                'time' => $stock->created_at->diffForHumans()
            ];
        }

        return view('user.home-modern', compact(
            'totalProducts',
            'totalStock',
            'todaysSales',
            'topProducts',
            'salesTrend',
            'notifications',
            'salesChange',
            'productsChange',
            'lowStockCount'
        ));
    }

    public function stock()
    {
     $products= Products::all();  
     
     return view('user.stock',compact('products'));
    }

    public function sales()
    {
     $products= Products::all();    
     return view('user.sales', compact('products'));
    }



    public function track()
    {
 
     return view('user.track');
    }
    public function product()
    {
     return view('user.product-modern');
    }


    




          public function addProduct(Request $request)
      {
          $request->validate([
              'name' => 'required|string|max:255|unique:products,name',
          ]);

          $product = new Products();
          $product->name = $request->name;
          $product->save();

          return response()->json([
              'success' => true,
              'message' => 'Product added successfully.',
              'product' => [
                  'id' => $product->id,
                  'name' => $product->name,
              ]
          ]);
      }
        public function getProducts()
        {
            $products = Products::orderBy('id')->get(['id', 'name']);
            return response()->json($products);
        }

      public function deleteProduct($id)
      {
          $product = Products::find($id);
          if (!$product) {
              return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
          }

          $product->delete();

          return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
      }


        
public function addStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'date'       => 'required|date',
            'price'      => 'required|string', // allow values like 36/34, 35(34), etc.
        ]);

        // Check if this product already has a stock entry
        $existingStock = Stocks::where('product_id', $request->product_id)->first();

        if ($existingStock) {
            return response()->json([
                'success' => false,
                'message' => 'This product already has a stock entry and cannot be stocked again. Please edit its quantity instead.'
            ]);
        }

        // Create new stock entry
        $stock = new Stocks();
        $stock->product_id = $request->product_id;
        $stock->quantity   = $request->quantity;
        $stock->date       = $request->date;
        $stock->price      = $request->price; // ✅ save price here
        $stock->save();

        // Update product quantity
        $product = Products::findOrFail($request->product_id);
        $product->quantity += $request->quantity;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Stock added successfully.'
        ]);
    }

    public function getStockList()
    {
        $stocks = Stocks::with('product')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id'           => $item->id,
                    'product_name' => $item->product ? $item->product->name : 'Unknown',
                    'quantity'     => $item->quantity,
                    'price'        => $item->price, // ✅ include price in JSON
                ];
            });

        return response()->json($stocks);
    }

// public function getStockList()
// {
//     $stocks = Stocks::with('Products')->orderBy('created_at', 'desc')->get();

//     $stockList = $stocks->map(function ($item) {
//         return [
//             'id' => $item->id,
//             'product_name' => $item->Products->name,
//             'quantity' => $item->quantity,
//         ];
//     });

//     return response()->json($stockList);
// }

public function deleteStock($id)
{
    $stock = Stocks::find($id);

    if (!$stock) {
        return response()->json(['success' => false, 'message' => 'Stock not found.']);
    }

    // Optionally adjust product quantity back
    $product = Products::find($stock->product_id);
    if ($product) {
        $product->quantity -= $stock->quantity;
        if ($product->quantity < 0) {
            $product->quantity = 0;
        }
        $product->save();
    }

    $stock->delete();

    return response()->json(['success' => true, 'message' => 'Stock deleted successfully.']);
}
public function updateStock(Request $request, $id)
{
    $request->validate([
        'additional_quantity' => 'required|integer|min:1',
    ]);

    $stock = Stocks::findOrFail($id);
    $stock->quantity += $request->additional_quantity;
    $stock->save();

    // Also update product total if you want
    $product = $stock->product;
    $product->quantity += $request->additional_quantity;
    $product->save();

   return response()->json(['success' => true, 'message' => 'Stock updated successfully.']);
     }
      
     
     
     public function getQuantity($id)
        {
            $product = Products::find($id);

            if ($product) {
                return response()->json([
                    'success' => true,
                    'quantity' => $product->quantity,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ]);
            }
        }





public function addSale(Request $request)
{
    $request->validate([
        'product' => 'required|array',
        'quantity' => 'required|array',
        'price' => 'required|array',
    ]);

    DB::beginTransaction();

    try {
        $totalSaleAmount = 0;

        foreach ($request->product as $index => $productId) {
            $quantityRequested = (int) $request->quantity[$index];
            $pricePerUnit = (int) $request->price[$index];

            $product = Products::lockForUpdate()->find($productId);

            if (!$product) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.',
                ]);
            }

            $stocks = Stocks::where('product_id', $productId)
                ->where('quantity', '>', 0)
                ->orderBy('created_at')
                ->lockForUpdate()
                ->get();

            $totalAvailableStock = $stocks->sum('quantity');

            if ($quantityRequested > $totalAvailableStock) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for {$product->name}. Available: {$totalAvailableStock}, Requested: {$quantityRequested}.",
                ]);
            }

            $remainingToDeduct = $quantityRequested;

            foreach ($stocks as $stock) {
                if ($remainingToDeduct <= 0) break;

                if ($stock->quantity >= $remainingToDeduct) {
                    $stock->quantity -= $remainingToDeduct;
                    $stock->save();
                    $remainingToDeduct = 0;
                } else {
                    $remainingToDeduct -= $stock->quantity;
                    $stock->quantity = 0;
                    $stock->save();
                }
            }

            $product->quantity = Stocks::where('product_id', $productId)->sum('quantity');
            $product->save();

            Sales::create([
                'product_id' => $productId,
                'quantity' => $quantityRequested,
                'price' => $pricePerUnit,
                'total' => $quantityRequested * $pricePerUnit,
                'customer_name' => $request->customer_name ?? null,
                'payment_type' => $request->payment_type ?? 'Cash',
                'sale_date' => $request->sale_date ?? now(),
                'user_id' => Auth::id(),
            ]);

            $totalSaleAmount += $quantityRequested * $pricePerUnit;
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Sale recorded successfully!',
            'total' => $totalSaleAmount,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error recording sale. Please try again.',
            'error' => $e->getMessage(),
        ]);
    }
}


public function getTrackSalesData(Request $request)
{
    $query = Sales::with(['product', 'user'])->latest();

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('sale_date', [$request->start_date, $request->end_date]);
    }

    $sales = $query->get();

    $totalAmount = $sales->sum('total');

    return response()->json([
        'success' => true,
        'sales' => $sales,
        'totalAmount' => $totalAmount,
    ]);
}

public function updatePrice(Request $request, $id) {
    $request->validate(['price' => 'required|string']); // keep string to allow flexibility
    $stock = Stocks::findOrFail($id);
    $stock->price = $request->price;
    $stock->save();

    return response()->json(['success' => true, 'message' => 'Price updated.']);
}


public function getProductDetails($id)
{
    // eager load the stock relation
    $product = Products::with('stock')->find($id);

    if ($product && $product->stock) {
        return response()->json([
            'success' => true,
            'quantity' => $product->quantity,
            'price' => $product->stock->price,
        ]);
    }

    return response()->json(['success' => false]);
}




}