<?php

namespace App\Http\Controllers\Api\Reports;

use App\Http\Controllers\Controller;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    protected $request;
    protected $interval;

    public function __construct(Request $request)
    {
        $this->request  = $request;
        $this->interval = (object) $this->setInterval();
    }

    protected function getDatePeriode($date, $num = 11): Collection
    {
        switch ($this->request->interval) {
            case 'years':
                $interval = 'P1Y';
                $format   = 'Y';
                break;
            case 'weeks':
                $interval = 'P1W';
                $format   = 'W Y';
                break;
            case 'days':
                $interval = 'P1D';
                $format   = 'Y-m-d';
                break;
            default:
                $interval = 'P1M';
                $format   = 'Y-m';
                break;
        }

        return collect(new DatePeriod($date, new DateInterval($interval), $num, true))
            ->map(function ($date) use ($format) {
                return $date->format($format);
            });
    }

    protected function setInterval(): array
    {
        $date     = now();
        $duration = $this->request->duration ?? null;

        if ($this->request->interval === 'years') {
            $date->subYears($duration ?? 5)->startOfYear();

            return [
                'select' => "YEAR(date) AS date_key",
                'date'   => $date,
                'dates'  => $this->getDatePeriode($date, $duration ?? 5),
            ];
        }

        if ($this->request->interval === 'weeks') {
            $date->subWeeks($duration ?? 10)->startOfWeek();

            return [
                'select' => "DATE_FORMAT(date, '%u %Y') AS date_key",
                'date'   => $date,
                'dates'  => $this->getDatePeriode($date, $duration ?? 10),
            ];
        }

        if ($this->request->interval === 'days') {
            $date->subDays($duration ?? 10)->startOfDay();

            return [
                'select' => "DATE(date) AS date_key",
                'date'   => $date,
                'dates'  => $this->getDatePeriode($date, $duration ?? 10),
            ];
        }

        $date->subMonths($duration ?? 10)->firstOfMonth();

        return [
            'select' => "DATE_FORMAT(date, '%Y-%m') AS date_key",
            'date'   => $date,
            'dates'  => $this->getDatePeriode($date, $duration ?? 10),
        ];
    }

    protected function transaction($model): Collection
    {
        $chacheKey = "REPORT_TRANSACTION_" . class_basename($model) . "_{$this->request->interval}_{$this->request->duration}";

        return cache()->remember($chacheKey, 60 * 5, function () use ($model) {
            $transaction = $model
                ->selectRaw("{$this->interval->select}, sum(grandtotal) AS gross")
                ->groupBy('date_key')
                ->where('date', '>=', $this->interval->date->format('Y-m-d H:i:s'))
                ->where('date', '<=', now()->format('Y-m-d H:i:s'))
                ->orderBy('date_key')
                ->get();

            return $this->interval->dates->map(function ($date) use ($transaction) {
                switch ($this->request->interval) {
                    case 'years':
                        $dateFormat = $date;
                        break;
                    case 'weeks':
                        $dateFormat = "Minggu Ke-{$date}";
                        break;
                    case 'days':
                        $dateFormat = Carbon::parse($date)->format('d M Y');
                        break;
                    default:
                        $dateFormat = Carbon::parse($date)->format('M Y');
                        break;
                }

                return [
                    'date'   => ltrim($dateFormat, '0'),
                    'amount' => $transaction->where('date_key', $date)->first()['gross'] ?? 0,
                ];
            });
        });
    }

    protected function transactions($models)
    {
        return collect($models)
            ->map(function ($model) {
                return [__(class_basename($model)) => $this->transaction($model)];
            })
            ->collapse();
    }
}
