<?php

namespace App\Services;
use App\Repositories\Interfaces\ShowSeatRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowSeatService
{
    protected ShowSeatRepositoryInterface $showSeatRepository;

    public function __construct(
        ShowSeatRepositoryInterface $showSeatRepository
    ) {
        $this->showSeatRepository = $showSeatRepository;
    }

    /**
     * Display a listing.
     */
    public function index(array $filters = [])
    {
        return $this->showSeatRepository->getAll(
            $filters['per_page'] ?? 10
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(array $data)
    {

        return $this->showSeatRepository->create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $showSeat = $this->showSeatRepository->findById($id);

        if (!$showSeat) {
            throw new ModelNotFoundException('Show seat not found.');
        }

        return $showSeat;
    }

    /**
     * Update the specified resource.
     */
    public function update(int $id, array $data)
    {
        $showSeat = $this->showSeatRepository->findById($id);

        if (!$showSeat) {
            throw new ModelNotFoundException('Show seat not found.');
        }

        $this->showSeatRepository->update($showSeat, $data);

        return $showSeat->fresh();
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(int $id): void
    {
        $showSeat = $this->showSeatRepository->findById($id);

        if (!$showSeat) {
            throw new ModelNotFoundException('Show seat not found.');
        }

        $this->showSeatRepository->delete($showSeat);
    }
}