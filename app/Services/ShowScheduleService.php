<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Show;
use App\Models\ShowSchedule;
use App\Repositories\Interfaces\ShowPriceRepositoryInterface;
use App\Repositories\Interfaces\ShowRepositoryInterface;
use App\Repositories\Interfaces\ShowScheduleRepositoryInterface;
use Exception;

class ShowScheduleService
{

    public function __construct(
        protected ShowScheduleRepositoryInterface $repository,
        protected ShowRepositoryInterface $showRepository,
        protected ShowPriceRepositoryInterface $showPriceRepository,
    ) {}


    public function index(array $filters = [])
    {
        return $this->repository->index($filters);
    }


    public function show(int $id)
    {
        return $this->repository->find($id);
    }


    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $this->checkScheduleConflict($data);

            $prices = $data['prices'] ?? [];

            if (empty($prices)) {
                throw new Exception(
                    "At least one seat category price is required"
                );
            }

            // convert days array to SET format
            if (isset($data['days_of_week'])) {
                $data['days_of_week'] =
                    implode(',', $data['days_of_week']);
            }

            // prices don't belong on the schedule table itself
            unset($data['prices']);

            $schedule = $this->repository->store($data);

            // generate shows + their prices
            $this->generateShows($schedule, $prices);

            return $schedule->load([
                'movie',
                'screen',
                'language',
                'shows.prices',
            ]);
        });
    }


    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $schedule = ShowSchedule::findOrFail($id);

            $this->checkScheduleConflict($data, $id);

            $prices = $data['prices'] ?? [];

            if (empty($prices)) {
                throw new Exception(
                    "At least one seat category price is required"
                );
            }

            if (isset($data['days_of_week'])) {
                $data['days_of_week'] =
                    implode(',', $data['days_of_week']);
            }

            unset($data['prices']);

            $schedule = $this->repository->update($id, $data);

            /*
             * Delete only future shows (and their prices)
             * because completed/booked shows should remain history
             */
            $futureShowIds = Show::where('schedule_id', $id)
                ->where('start_time', '>', now())
                ->pluck('id');

            if ($futureShowIds->isNotEmpty()) {
                $this->showPriceRepository
                    ->delete($futureShowIds->toArray());

                Show::whereIn('id', $futureShowIds)->delete();
            }

            $this->generateShows($schedule, $prices);

            return $schedule->load([
                'movie',
                'screen',
                'language',
                'shows.prices',
            ]);
        });
    }


    public function destroy(int $id)
    {
        return DB::transaction(function () use ($id) {

            $schedule = ShowSchedule::findOrFail($id);

            $hasBookedShows = Show::where('schedule_id', $id)
                ->where('status', '!=', 'CANCELLED')
                ->whereHas('showSeats', function ($query) {
                    $query->where('status', 'BOOKED');
                })
                ->exists();

            if ($hasBookedShows) {
                throw new Exception(
                    "Cannot delete schedule with booked shows"
                );
            }

            $showIds = Show::where('schedule_id', $id)->pluck('id');

            if ($showIds->isNotEmpty()) {
                $this->showPriceRepository
                    ->delete($showIds->toArray());
            }

            Show::where('schedule_id', $id)->delete();

            return $this->repository->delete($id);
        });
    }


    /**
     * Generate shows from schedule, and their per-category prices
     */
    private function generateShows(ShowSchedule $schedule, array $prices)
    {
        $date = Carbon::parse($schedule->start_date);
        $endDate = Carbon::parse($schedule->end_date);

        while ($date->lte($endDate)) {

            $day = $date->dayOfWeekIso;

            if (in_array($day, explode(',', $schedule->days_of_week))) {

                $startTime = Carbon::parse($date->format('Y-m-d'))
                    ->setTimeFromTimeString($schedule->show_time);

                $endTime = $startTime->copy()
                    ->addMinutes($schedule->movie->duration_min);

                $show = Show::create([
                    'schedule_id' => $schedule->id,
                    'movie_id' => $schedule->movie_id,
                    'screen_id' => $schedule->screen_id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'language_id' => $schedule->language_id,
                    'format' => $schedule->format,
                    'booking_open_at' => $startTime->copy()
                        ->subMinutes($schedule->booking_opens_offset_min),
                    'booking_close_at' => $startTime->copy()
                        ->subMinutes($schedule->booking_closes_offset_min),
                ]);

                $this->generateShowPrices($show, $prices);
            }

            $date->addDay();
        }
    }


    /**
     * Create ShowPrice rows for a single generated show
     */
    private function generateShowPrices(Show $show, array $prices)
    {
        foreach ($prices as $price) {

            $this->showPriceRepository->create([
                'show_id' => $show->id,
                'category_id' => $price['category_id'],
                'base_price' => $price['base_price'],
                'tax_percent' => $price['tax_percent'] ?? 0,
            ]);
        }
    }


    /**
     * Check screen time conflict
     */
    private function checkScheduleConflict(array $data, $ignoreId = null)
    {
        $exists = ShowSchedule::where('screen_id', $data['screen_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [
                    $data['start_date'],
                    $data['end_date'],
                ])->orWhereBetween('end_date', [
                    $data['start_date'],
                    $data['end_date'],
                ]);
            });

        if ($ignoreId) {
            $exists->where('id', '!=', $ignoreId);
        }

        if ($exists->exists()) {
            throw new Exception(
                "Screen already has a schedule during this period"
            );
        }
    }
}