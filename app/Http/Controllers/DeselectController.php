<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\DeselectReport;
use App\Product;
use App\User;
use App\Utils\BusinessUtil;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DeselectController extends Controller
{
    protected $businessUtil;

    public function __construct(BusinessUtil $businessUtil)
    {
        $this->businessUtil = $businessUtil;         
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOrUpdate(Request $request)
    {
        $business_details = $this->businessUtil->getDetails(auth()->user()->business_id);
        $pos_settings = empty($business_details->pos_settings) ? $this->businessUtil->defaultPosSettings() : json_decode($business_details->pos_settings, true);
        $output = [
            'success' => false,
            'msg' => __("messages.something_went_wrong")
        ];

        if (auth()->user()->can('deselect_product') && isset($pos_settings['deselect_product']) && $pos_settings['deselect_product'] == 1) {
            if ($request->product_id && $request->business_location_id && $request->quantity) {
                $report_data = (new DeselectReport())->findByFilters([
                    'product_id' => $request->product_id,
                    'business_location_id' => $request->business_location_id,
                    'user_id' => auth()->user()->id,
                ], true);
                $created = false;
                if ($report_data) {
                    (new DeselectReport())->updateData($request->all(), $report_data); 
                } else {
                    (new DeselectReport())->createData($request->all());
                    $created = true;
                }

                $output = [
                    'success' => true,
                    'msg' => $created ? __("Successfully created") : __("Successfully updated")
                ];
            }
        }

        return response()->json($output);
    }

    public function getReportData(Request $request)
    {
        if (!auth()->user()->can('deselected_product_report')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        $locations = BusinessLocation::forDropdown($business_id, true);
        $deselects = (new DeselectReport())->findByFilters(
            $request->all(),
            false,
            false,
            10,
            true,
            $locations
        );
        if (request()->ajax()) {
            $datatable = Datatables::of($deselects)
                ->removeColumn('id')
                ->addColumn('product_name', function ($row) {
                    return $row->product->name;
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user->username;
                })
                ->addColumn('location_name', function ($row) {
                    return $row->location->name;
                })
                ->editColumn('quantity', function ($row) {
                    return $row->quantity;
                })
                ->editColumn('total_amount', function ($row) {
                    return $row->total_amount;
                })
                ->filterColumn('product_name', function ($query, $keyword) {
                    $query->whereHas('product', function($q) use($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereHas('user', function($q) use($keyword) {
                        $q->where('username', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('location_name', function ($query, $keyword) {
                    $query->whereHas('location', function($q) use($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                });
    
            $rawColumns = ['product_name', 'user_name', 'location_name', 'quantity', 'total_amount'];
                
            return $datatable->rawColumns($rawColumns)->make(true);
        }

        $products = Product::where('business_id', $business_id)->pluck('name', 'id')->toArray();
        $users = User::forDropdown($business_id, false);

        return view('deselect_report.index')->with(compact('products', 'users', 'locations'));
    }
}
