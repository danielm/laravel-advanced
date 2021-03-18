<?php

namespace App\Traits;

use App\Models\Rating;
use App\Models\User;

use App\Events\EntityRatedEvent;

trait CanBeRated
{
  public function ratings()
  {
      return $this->morphMany(Rating::class, 'rateable');
        //->withPivot('score', 'user', 'rateable_type')/*'', */
        //->withTimestamps();
        //->as("rating");
  }

  public function rating()
  {
    return $this->ratings()->avg('score');
  }

  public function rate(User $user, float $score)
  {
    $this->ratings()->create([
      'score' => $score,
      'user_id' => $user->id
    ]);

    event(new EntityRatedEvent($this, $user, $score));
  }
}