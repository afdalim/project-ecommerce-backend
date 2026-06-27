<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Return_;
use Illuminate\Http\Request;



class DashboardController extends Controller
{



    public function index()
    {


        $grossRevenue =
        Order::where(
            'payment_status',
            'completed'
        )
        ->sum(
            'final_amount'
        );



        $totalRefund =
        Return_::where(
            'refund_status',
            'refunded'
        )
        ->sum(
            'refund_amount'
        );



        $netRevenue =
        $grossRevenue - $totalRefund;







        $monthlyRevenue =
        Order::where(
            'payment_status',
            'completed'
        )
        ->where(
            'created_at',
            '>=',
            now()->startOfMonth()
        )
        ->sum(
            'final_amount'
        );



        $monthlyRefund =
        Return_::where(
            'refund_status',
            'refunded'
        )
        ->where(
            'created_at',
            '>=',
            now()->startOfMonth()
        )
        ->sum(
            'refund_amount'
        );








        $stats = [



            'total_orders' =>

            Order::count(),







            'total_revenue' =>

            $netRevenue,








            'total_customers' =>

            User::where(
                'is_active',
                true
            )
            ->where(
                'role',
                'customer'
            )
            ->count(),







            'total_products' =>

            Product::active()
            ->count(),







            'pending_orders' =>

            Order::where(
                'status',
                'pending'
            )
            ->count(),








            'pending_returns' =>

            Return_::where(
                'status',
                'pending'
            )
            ->count(),








            'orders_this_month' =>

            Order::where(
                'created_at',
                '>=',
                now()->startOfMonth()
            )
            ->count(),








            'revenue_this_month' =>

            $monthlyRevenue - $monthlyRefund,



        ];









        $recent_orders =

        Order::with(
            'user'
        )
        ->orderBy(
            'created_at',
            'desc'
        )
        ->limit(
            10
        )
        ->get();








        return response()->json([


            'stats' =>
            $stats,


            'recent_orders' =>
            $recent_orders,


        ]);


    }










    public function analytics(Request $request)
    {


        $period =
        $request->period ?? 'month';



        $revenue_data =
        match($period) {


            'day' =>
            $this->getRevenueByDay(),


            'week' =>
            $this->getRevenueByWeek(),


            'year' =>
            $this->getRevenueByYear(),


            default =>
            $this->getRevenueByMonth(),


        };





        $top_products =
        Product::with(
            'orderItems'
        )
        ->orderBy(
            'rating',
            'desc'
        )
        ->limit(10)
        ->get();




        return response()->json([


            'revenue_data'=>
            $revenue_data,


            'top_products'=>
            $top_products


        ]);


    }









    private function getRevenueByDay()
    {

        return Order::where(
            'status',
            'delivered'
        )
        ->where(
            'created_at',
            '>=',
            now()->subDays(30)
        )
        ->selectRaw(
            'DATE(created_at) as date, SUM(final_amount) as total'
        )
        ->groupBy('date')
        ->get();


    }








    private function getRevenueByMonth()
    {


        return Order::where(
            'status',
            'delivered'
        )
        ->where(
            'created_at',
            '>=',
            now()->subMonths(12)
        )
        ->selectRaw(
            'MONTH(created_at) as month, SUM(final_amount) as total'
        )
        ->groupBy('month')
        ->get();


    }








    private function getRevenueByWeek()
    {


        return Order::where(
            'status',
            'delivered'
        )
        ->where(
            'created_at',
            '>=',
            now()->subWeeks(12)
        )
        ->selectRaw(
            'WEEK(created_at) as week, SUM(final_amount) as total'
        )
        ->groupBy('week')
        ->get();


    }








    private function getRevenueByYear()
    {


        return Order::where(
            'status',
            'delivered'
        )
        ->where(
            'created_at',
            '>=',
            now()->subYears(5)
        )
        ->selectRaw(
            'YEAR(created_at) as year, SUM(final_amount) as total'
        )
        ->groupBy('year')
        ->get();


    }



}