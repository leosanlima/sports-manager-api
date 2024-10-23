<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'season' => (int) $this->season,
            'status' => $this->status,
            'period' => (int) $this->period,
            'time' => $this->time,
            'postseason' => $this->postseason,
            'home_team_score' => (int) $this->home_team_score,
            'visitor_team_score' => (int) $this->visitor_team_score,
        ];
    }
}
