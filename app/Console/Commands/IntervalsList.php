<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class IntervalsList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'intervals:list {--left=} {--right=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $start = $this->option('left');
       $end = $this->option('right');

        if (!isset($start)) {
            $this->newLine();
            $this->error('   Set the Interval start using --left=X option   ');
            $this->newLine();
        }
        if (!isset($end)) {
            $this->newLine();
            $this->error('   Set the Interval end using --right=X option   ');
            $this->newLine();
        }

        $intersections =  DB::table('intervals')->select('id', 'start', 'end')
            ->whereBetween('start', [(int)$start, (int)$end])
            ->orWhereBetween('end', [(int)$start, (int)$end])
            ->orderBy('id');

        $chunk_size = 50;
        $chunk_amt = ceil($intersections->count() / $chunk_size);
        $counter = 1;
        $intersections->chunk($chunk_size, function ($page) use (&$counter, $chunk_amt) {
            $page = $page->map(function ($interval) {
                return [
                    'id' => $interval->id,
                    'start' => $interval->start,
                    'end' => $interval->end ?? 'null',
                ];
            });

            $this->table(
                ['ID', 'Start', 'End'],
                $page
            );

            if ($counter < $chunk_amt && $this->confirm("Do you want to see next page of intervals?")) {
                $counter++;
                return true;
            } else {
                return false;
            }
        });
    }
}

