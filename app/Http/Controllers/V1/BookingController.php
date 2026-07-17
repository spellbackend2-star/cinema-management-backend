<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\IndexBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\BookingAdminResource;
use App\Http\Resources\PaymentResource;
use App\Services\BookingService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    use ResponseTrait, AuthorizesWithPermission;

    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function index(IndexBookingRequest $request)
    {
       
        $this->authorizePermission('booking.view');

        $bookings = $this->bookingService
            ->getall($request->validated());

        return $this->successResponse(
            $this->resourceCollection($bookings),
            'Bookings retrieved successfully'
        );
    }


    public function show(int $id)
    {
        $this->authorizePermission('booking.show');

        $booking = $this->bookingService->find($id);

        if (!$booking) {
            return $this->errorResponse(
                'Booking not found',
                404
            );
        }

        $booking->load([
            'user',
            'show.movie',
            'bookingSeats.showSeat.seat',
            'payment',
            'coupon',
        ]);

        return $this->successResponse(
            $this->resource($booking),
            'Booking retrieved successfully'
        );
    }


    public function store(StoreBookingRequest $request)
    {
        $this->authorizePermission('booking.create');

        $result = $this->bookingService->store(
            $request->validated(),
            Auth::id(),
            Auth::user()
        );

        return $this->successResponse(
            [
                'booking' => $this->resource($result['booking']),
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

        $booking = $this->bookingService->find($id);

        if (!$booking) {
            return $this->errorResponse(
                'Booking not found',
                404
            );
        }

        $booking = $this->bookingService->update(
            $booking,
            $request->validated()
        );

        return $this->successResponse(
            $this->resource($booking),
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


    private function resource($booking)
    {
        return $this->isAdmin()
            ? new BookingAdminResource($booking)
            : new BookingResource($booking);
    }


    private function resourceCollection($bookings)
    {
        return $this->isAdmin()
            ? BookingAdminResource::collection($bookings)
            : BookingResource::collection($bookings);
    }


    private function isAdmin(): bool
    {
        return Auth::user()
            ->hasAnyRole([
                'company_admin',
                'branch_manager',
                'cashier',
                'ticket_counter'
            ]);
    }
}
