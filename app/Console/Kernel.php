<?php

namespace App\Console;

use App\Models\Todo;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\DeadlineNotification;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $todos = Todo::where('user_id', Auth::id())->get();
            
            foreach ($todos as $todo) {
                $dl = Carbon::parse($todo->dl);
                $now = Carbon::now();
    
                if ($dl->isToday() && !$todo->is_completed) {
                    $todo->user->notify(new DeadlineNotification($todo, 'Dl Tugasmu hari ini nih!'));
                } elseif ($dl->isTomorrow() && !$todo->is_completed) {
                    $todo->user->notify(new DeadlineNotification($todo, 'Dl Tugasmu besok, semangat yaa!'));
                } elseif ($now->greaterThan($dl) && !$todo->is_completed) {
                    $todo->is_completed = -1; 
                    $todo->save();
                    $todo->user->notify(new DeadlineNotification($todo, 'Yah Tugasmu melebihi deadline'));
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
