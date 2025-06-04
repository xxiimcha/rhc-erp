<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Holiday;
use Carbon\Carbon;

class TimekeepingController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        // Check if holidays for the year already exist
        $existingHolidays = Holiday::where('year', $year)->count();

        // If not, fetch from external API and insert into database
        if ($existingHolidays === 0) {
            $this->syncHolidaysFromAPI($year);
        }

        // Load holidays from local database
        $holidays = Holiday::where('year', $year)->orderBy('date')->get();

        return view('admin.settings.timekeeping', compact('holidays', 'year'));
    }

    /**
     * Fetch holidays from Calendarific API and insert into the database.
     */
    protected function syncHolidaysFromAPI($year)
    {
        $response = Http::get("https://calendarific.com/api/v2/holidays", [
            'api_key' => env('CALENDARIFIC_API_KEY'),
            'country' => 'PH',
            'year' => $year,
            'type' => 'national',
        ]);

        if ($response->successful()) {
            $data = $response->json();

            foreach ($data['response']['holidays'] ?? [] as $item) {
                $date = $item['date']['iso'];
                $parsedYear = Carbon::parse($date)->year;

                Holiday::updateOrCreate(
                    ['date' => $date, 'year' => $parsedYear],
                    [
                        'name' => $item['name'],
                        'description' => $item['description'] ?? '',
                        'source' => 'external',
                    ]
                );
            }
        }
    }

    /**
     * Store a manually added holiday.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'date' => 'required|date|unique:holidays,date',
            'description' => 'nullable|string',
        ]);

        Holiday::create([
            'name' => $request->name,
            'date' => $request->date,
            'year' => Carbon::parse($request->date)->year,
            'description' => $request->description,
            'source' => 'manual',
        ]);

        return redirect()->back()->with('success', 'Holiday added successfully.');
    }

    /**
     * Delete any holiday (manual or external).
     */
    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->back()->with('success', 'Holiday deleted.');
    }

    /**
     * Move holiday date (regardless of source).
     */
    public function updateDate(Request $request, $id)
    {
        $request->validate([
            'new_date' => 'required|date|unique:holidays,date,NULL,id,year,' . Carbon::parse($request->new_date)->year,
        ]);

        $holiday = Holiday::findOrFail($id);
        $holiday->update([
            'date' => $request->new_date,
            'year' => Carbon::parse($request->new_date)->year,
        ]);

        return redirect()->back()->with('success', 'Holiday date updated successfully.');
    }
}
