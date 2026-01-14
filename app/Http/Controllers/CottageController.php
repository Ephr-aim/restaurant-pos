<?php

namespace App\Http\Controllers;

use App\Models\Cottage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CottageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('cottage_view'), 403);
        if ($request->ajax()) {
            $cottages = Cottage::latest()->get();
            return DataTables::of($cottages)
                ->addIndexColumn()
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('phone', fn($data) => $data->phone)
                ->addColumn('address', fn($data) => $data->address)
                ->addColumn('created_at', fn($data) => $data->created_at->format('d M, Y')) // Using Carbon for formatting
                ->addColumn('action', function ($data) {
                    $actionHtml = '<div class="btn-group">
        <button type="button" class="btn bg-gradient-primary btn-flat">Action</button>
        <button type="button" class="btn bg-gradient-primary btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" role="menu">';

                    // Check if the user has permission to update customers
                    if (auth()->user()->can('cottage_update')) {
                        $actionHtml .= '<a class="dropdown-item" href="' . route('backend.admin.cottages.edit', $data->id) . '" ' . ($data->id == 1 ? 'onclick="event.preventDefault();"' : '') . '>
            <i class="fas fa-edit"></i> Edit
        </a>';
                        $actionHtml .= '<div class="dropdown-divider"></div>';
                    }

                    // Check if the user has permission to delete customers
                    if (auth()->user()->can('cottage_delete')) {
                        $actionHtml .= '<form action="' . route('backend.admin.cottages.destroy', $data->id) . '" method="POST" style="display:inline;">
            ' . csrf_field() . '
            ' . method_field("DELETE") . '
            <button type="submit" ' . ($data->id == 1 ? 'disabled' : '') . ' class="dropdown-item" onclick="return confirm(\'Are you sure?\')">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>';
                        $actionHtml .= '<div class="dropdown-divider"></div>';
                    }

                    if (auth()->user()->can('cottage_sales')) {
                        $actionHtml .= '<a class="dropdown-item" href="' . route('backend.admin.cottages.orders', $data->id) . '">
        <i class="fas fa-cart-plus"></i> Sales
    </a>';
                    }

                    $actionHtml .= '</div></div>';
                    return $actionHtml;
                })

                ->rawColumns(['name', 'phone', 'address', 'created_at', 'action'])
                ->toJson();
        }


        return view('backend.cottages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        abort_if(!auth()->user()->can('cottage_create'), 403);
        return view('backend.cottages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        abort_if(!auth()->user()->can('cottage_create'), 403);

        if ($request->wantsJson()) {
            $request->validate([
                'name' => 'required|string',
            ]);

            $cottage = Cottage::create([
                'name' => $request->name,
            ]);

            return response()->json($cottage);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:cottages,phone',
            'address' => 'nullable|string|max:255',
        ]);

        $cottage = Cottage::create($request->only(['name', 'phone', 'address']));

        session()->flash('success', 'cottage created successfully.');
        return to_route('backend.admin.cottages.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cottage $cottage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        abort_if(!auth()->user()->can('cottage_update'), 403);
        $cottage = Cottage::findOrFail($id);
        return view('backend.cottages.edit', compact('cottage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        abort_if(!auth()->user()->can('cottage_update'), 403);
        $cottage = Cottage::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:cottages,phone,' . $cottage->id, // Corrected syntax
            'address' => 'nullable|string|max:255',
        ]);

        $cottage->update($request->only(['name', 'phone', 'address']));

        session()->flash('success', 'cottage updated successfully.');
        return to_route('backend.admin.cottages.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        abort_if(!auth()->user()->can('cottage_delete'), 403);
        $cottage = Cottage::findOrFail($id);
        $cottage->delete();
        session()->flash('success', 'cottage deleted successfully.');
        return to_route('backend.admin.cottages.index');
    }
    public function getCottages(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(Cottage::latest()->get());
        }
    }
    //get orders by customer id
    public function orders($id)
    {
        $cottage = Cottage::findOrFail($id);
        $orders = $cottage->orders()->paginate(100);
        return view('backend.orders.index', compact('orders'));
    }
}
