<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bookings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        // Normalize times for storage (append :00 seconds)
        if (isset($data['start_time'])) $data['start_time'] = date('H:i:s', strtotime($data['start_time']));
        if (isset($data['end_time'])) $data['end_time'] = date('H:i:s', strtotime($data['end_time']));


        // Perform overlap check in DB transaction
        return DB::transaction(function() use($data, $request) {
            // call helper that checks overlap, returns message if conflict
            $conflict = $this->findBookingConflict($data);
            if ($conflict) {
                return redirect()->back()->withErrors(['booking' => $conflict])->withInput();
            }


            // Create booking
            Booking::create([   
            'user_id' => $request->user()->id,
            'booking_date' => $data['booking_date'],
            'booking_type' => $data['booking_type'],
            'half_slot' => $data['booking_type'] === 'half' ? $data['half_slot'] : null,
            'start_time' => $data['booking_type'] === 'custom' ? $data['start_time'] : null,
            'end_time' => $data['booking_type'] === 'custom' ? $data['end_time'] : null,
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            ]);


            return redirect()->route('bookings.create')->with('status','Booking created successfully.');
        });
    }


    /**
    * Return null if no conflict, or error string if conflict exists.
    */
    protected function findBookingConflict(array $data)
    {
        $date = $data['booking_date'];
        $type = $data['booking_type'];


        // 1) If trying to create FULL day, any existing booking on that date conflicts.
        if ($type === 'full') {
        $exists = Booking::where('booking_date', $date)->exists();
        if ($exists) return 'Full day cannot be booked: date already has bookings.';
            return null;
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
