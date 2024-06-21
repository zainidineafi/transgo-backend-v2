<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Illuminate\Http\Resources\Json\JsonResource;

class FindScheduleByDateResource extends JsonResource
{
    private $date_departure;

    public function __construct($resource, $date_departure)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->date_departure = $date_departure;
    }

    public function toArray($request)
    {
        $start = new \DateTime($this->time_start);
        $end = new \DateTime($this->time_arrive);

        // Periksa apakah waktu kedatangan lebih awal dari waktu keberangkatan
        if ($end < $start) {
            $end->modify('+1 day');
        }

        // Menghitung selisih waktu
        $interval = $start->diff($end);

        // Menghitung total jam dan menit termasuk perbedaan hari
        $totalHours = $interval->days * 24 + $interval->h;
        $totalMinutes = $interval->i;

        // Format hasil perhitungan waktu
        $pwt = sprintf('%d Jam %d Menit', $totalHours, $totalMinutes);

        return [
            'id' => $this->id,
            'type_bus' => $this->bus->class,
            'bus_id' => $this->bus->id,
            'driver_id' => $this->driver->drivers->id,
            'driver_name' => $this->driver->drivers->name,
            'chair' => $this->bus->chair - Reservation::where('schedule_id', $this->id)->where('date_departure', $this->date_departure)->count(),
            'name_station' => $this->fromStation->name,
            'from_station' => $this->fromStation->code_name,
            'to_station' => $this->toStation->code_name,
            'price' => $this->price,
            'time_start' => $this->time_start,
            'time_arrive' => $this->time_arrive,
            'pwt' => $pwt,
            'create_at' => $this->created_at,
            'update_at' => $this->updated_at,
        ];


    }
}
