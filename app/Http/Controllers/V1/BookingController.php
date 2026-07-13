<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\IndexBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\PaymentResource;
use App\Services\BookingService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    use ResponseTrait;
    use AuthorizesWithPermission;

    public function __construct(
        protected BookingService $bookingService
    ) {}
    public function index(IndexBookingRequest $request)
    {
        $this->authorizePermission('booking.view');

        return BookingResource::collection(
            $this->bookingService
                ->getall($request->validated())
        );
    }
    public function show(int $id)
    {
        $this->authorizePermission('booking.show');
        $booking = $this->bookingService
            ->find($id);

        if (!$booking) {
            return $this->errorResponse(
                'Booking not found',
                404
            );
        }
        $booking->load([
            'user',
            'show',
            'bookingSeats.showSeat',
            'payment',
            'coupon',
        ]);
        return $this->successResponse(
            new BookingResource($booking),
            'Booking retrieved successfully'
        );
    }
    public function store(StoreBookingRequest $request)
    {


        $this->authorizePermission('booking.create');
        $result = $this->bookingService->store(

            $request->validated(),

            Auth::id()

        );

        return $this->successResponse(
            [
                'booking' => new BookingResource($result['booking']),
                'payment' => new PaymentResource($result['payment']),
            ],
            'Payment initiated successfully',
            201
        );
    }
    public function update(
        UpdateBookingRequest $request,
        int $id
    ) {
        $this->authorizePermission('booking.update');

        try {

            $booking = $this->bookingService->update(
                $this->bookingService->find($id),
                $request->validated()
            );
        } catch (\Exception $e) {


            return $this->errorResponse(
                $e->getMessage(),
                404
            );
        }
        return $this->successResponse(
            $booking,
            'Booking updated successfully'
        );
    }
    public function destroy(int $id)
    {
        $this->authorizePermission('booking.delete');

        $booking = $this->bookingService->find($id);
        if (!$booking) {

            return $this->errorResponse(
                'Booking not found',
                404
            );
        }
        $booking->delete();
        return $this->successResponse(
            null,
            'Booking deleted successfully'
        );
    }
}
