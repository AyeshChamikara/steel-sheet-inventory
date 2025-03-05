<?php

namespace App\Http\Controllers;
use App\Models\SteelSheet;
use App\Models\SteelSheetType;
use App\Models\SteelSheetSize;
use App\Models\SteelSheetColor;
use Illuminate\Http\Request;

class SteelSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $steelSheets = SteelSheet::with('type', 'size', 'color')->get();
        return view('steel-sheets.index', compact('steelSheets'));
    }

    public function dashboard(Request $request)
    {
        // Fetch all types, sizes, and colors for filters
        $types = SteelSheetType::all();
        $sizes = SteelSheetSize::all();
        $colors = SteelSheetColor::all();

        // Apply filters based on request parameters
        $query = SteelSheet::with('type', 'size', 'color')->orderBy('type_id', 'asc');

        if ($request->has('type_id') && $request->input('type_id') !== '') {
            $query->where('type_id', $request->input('type_id'));
        }

        if ($request->has('size_id') && $request->input('size_id') !== '' && $request->input('size_id') !== null) {
            $query->where('size_id', $request->input('size_id'));
        }

        $steelSheets = $query->get()->groupBy('type_id'); // Group by type_id

        return view('steel-sheets.dashboard', compact('steelSheets', 'types', 'sizes', 'colors'));
    }

    public function home()
    {
        $totalSheets = SteelSheet::sum('total_count');
        $lowStockSheets = SteelSheet::where('total_count', '<', 100)->with('type', 'size', 'color')->get();
        return view('home', compact('totalSheets', 'lowStockSheets'))->with([
            'types' => SteelSheetType::all(),
            'sizes' => SteelSheetSize::all(),
            'colors' => SteelSheetColor::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = SteelSheetType::all();
        $sizes = SteelSheetSize::all();
        $colors = SteelSheetColor::all();
        return view('steel-sheets.create', compact('types', 'sizes', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_id' => 'nullable|exists:steel_sheet_types,id',
            'size_id' => 'nullable|exists:steel_sheet_sizes,id',
            'color_id' => 'nullable|exists:steel_sheet_colors,id',
            'total_count' => 'required|integer|min:0',
            'new_type' => 'nullable|string|unique:steel_sheet_types,name',
            'new_size' => 'nullable|numeric|unique:steel_sheet_sizes,size',
            'new_color' => 'nullable|string|unique:steel_sheet_colors,color',
        ]);
    
        // Create new type if provided
        if ($request->has('new_type')) {
            $type = SteelSheetType::create(['name' => $request->new_type]);
            $validated['type_id'] = $type->id;
        } elseif (!$request->has('type_id')) {
            return back()->withErrors(['error' => 'Type is required.']);
        }
    
        // Create new size if provided
        if ($request->has('new_size')) {
            $size = SteelSheetSize::create(['size' => $request->new_size]);
            $validated['size_id'] = $size->id;
        } elseif (!$request->has('size_id')) {
            return back()->withErrors(['error' => 'Size is required.']);
        }
    
        // Create new color if provided
        if ($request->has('new_color')) {
            $color = SteelSheetColor::create(['color' => $request->new_color]);
            $validated['color_id'] = $color->id;
        } elseif (!$request->has('color_id')) {
            return back()->withErrors(['error' => 'Color is required.']);
        }
    
        // Create the steel sheet
        SteelSheet::create($validated);
    
        return response()->json([
            'message' => 'Steel sheet added successfully.',
        ]);
    }

    public function typeDashboard($typeName)
    {
        // Find the SteelSheetType by name
        $type = SteelSheetType::where('name', $typeName)->first();

        if (!$type) {
            abort(404); // Return 404 if the type doesn't exist
        }

        // Fetch all steel sheets for the given type, grouped by size
        $steelSheetsBySize = SteelSheet::where('type_id', $type->id)
            ->with('size', 'color')
            ->get()
            ->groupBy('size_id');

        // Fetch all types, sizes, and colors for the Add Steel Sheet modal
        $types = SteelSheetType::all();
        $sizes = SteelSheetSize::all();
        $colors = SteelSheetColor::all();

        return view('steel-sheets.type-dashboard', compact('type', 'steelSheetsBySize', 'types', 'sizes', 'colors'));
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $steelSheet = SteelSheet::findOrFail($id);
        $types = SteelSheetType::all();
        $sizes = SteelSheetSize::all();
        $colors = SteelSheetColor::all();

        return view('steel-sheets.edit', compact('steelSheet', 'types', 'sizes', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $steelSheet = SteelSheet::findOrFail($id);

        $validated = $request->validate([
            'type_id' => 'required|exists:steel_sheet_types,id',
            'size_id' => 'required|exists:steel_sheet_sizes,id',
            'color_id' => 'required|exists:steel_sheet_colors,id',
            'total_count' => 'nullable|integer|min:0',
        ]);

        $steelSheet->update($validated);

        return redirect()->route('steel-sheets.index')->with('success', 'Steel sheet updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $steelSheet = SteelSheet::findOrFail($id);
        $steelSheet->delete();

        return redirect()->route('steel-sheets.dashboard')->with('success', 'Steel sheet deleted successfully.');
    }
}
