<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
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
            'title' => $this->title,
            'original_title' => $this->original_title,
            'description' => $this->description,
            'duration_min' => $this->duration_min,
            'release_date' => $this->release_date,
            'production_house' => $this->production_house,
            'country' => $this->country,
            'censor_rating' => $this->censor_rating,
            'poster_url' => $this->poster_url,
            'banner_url' => $this->banner_url,
            'trailer_url' => $this->trailer_url,
            'status' => $this->status,
            'imdb_rating' => $this->imdb_rating,
       ];
            
    }
    }

