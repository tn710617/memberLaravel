<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dengueController extends Controller {

    public function dengue(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $district = $request->district;
        $fatality = $request->type;

        if (isset($year) && !isset($month) && isset($district))
        {
            return $this->dengueDistrictMonthNumber($request);
        }
        elseif (isset($year) && ($fatality == 'fatality'))
        {
            return $this->dengueFatalityRate($request);
        }
        elseif (isset($year) && (isset($month)) && (isset($district)))
        {
            return $this->dengueDistrictDailyNumber($request);
        } elseif (isset($year) && (!isset($month)) && (!isset($district)))
        {
            return $this->dengueYearNumber($request);
        } else
        {
            return ["result" => "false", "data" => "Provided condition is incorrect"];
        }
    }

    private function dengueDistrictDailyNumber(Request $request)
    {

        $checkIfDataExists = DB::table('dengue')
            ->whereYear('date', $request->year)
            ->whereMonth('date', $request->month)
            ->where('district', '=', $request->district)
            ->exists();

        if ($checkIfDataExists == false)
        {
            return ['result' => 'false', 'data' => 'The queried data doesn\'t exists'];
        }
        $dengueDistrictYearNumber = DB::table('dengue')
            ->select(DB::raw('day(date) date, count(district) number'))
            ->whereYear('date', $request->year)
            ->whereMonth('date', $request->month)
            ->where('district', $request->district)
            ->groupBy(DB::raw('year(date), month(date), day(date)'))
            ->get()->toArray();

        $finalOutput = array();
        $toBeShownAbsentData = 'required data was not found';
        $howManyDaysInAMonth = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
        for ($daysInAMonth = 1; $daysInAMonth <= $howManyDaysInAMonth; $daysInAMonth ++)
        {
            foreach ($dengueDistrictYearNumber as $data)
            {
                if ($data->date == $daysInAMonth)
                {
                    $finalOutput[$daysInAMonth] = $data->number;
                }
                if (!isset($finalOutput[$daysInAMonth]))
                {
                    $finalOutput[$daysInAMonth] = $toBeShownAbsentData;
                }

            }
        }

        return ['result' => 'true', 'data' => $finalOutput];


//        return ['result' => 'true', 'yearlyDengueDistrictNumber' => $dengueDistrictYearNumber];
    }

    private function dengueFatalityRate(Request $request)
    {
        $checkIfConditionExists = DB::table('fatalityRate')
            ->where('date', '=', $request->year)
            ->exists();

        if ($checkIfConditionExists == false)
        {
            return ['result' => 'false', 'data' => 'The queried data doesn\'t exists'];
        }
        $dengueFatalityRate = DB::table('fatalityRate')
            ->where('date', '=', $request->year)
            ->first();

        return ['result' => 'true', 'data' => $dengueFatalityRate->fatalityRate];
    }

    private function dengueYearNumber(Request $request)
    {
        $this->validate(request(), [
            'year' => 'required',
        ]);

        $checkIfDataExists = DB::table('dengue')
            ->whereYear('date', $request->year)
            ->exists();

        if ($checkIfDataExists == false)
        {
            return ['result' => 'false', 'data' => 'The queried data doesn\'t exists'];
        }

        $monthlyDataInAYearOfDengue = DB::table('dengue')
            ->select(DB::raw("count(district) number, district"))
            ->whereYear('date', $request->year)
            ->groupBy(DB::raw("year(date), district"))
            ->get()->toArray();

        $finalOutput = array();

        foreach ($monthlyDataInAYearOfDengue as $data)
        {
            $finalOutput[$data->district] = $data->number;
        }

        return ['result' => 'true', 'data' => $finalOutput];
    }

    private function dengueDistrictMonthNumber(Request $request)
    {
        $this->validate(request(), [
            'year'     => 'required',
            'district' => 'required'
        ]);

        $checkIfDataExists = DB::table('dengue')
            ->whereYear('date', $request->year)
            ->where('district', '=', $request->district)
            ->exists();

        if ($checkIfDataExists == false)
        {
            return ['result' => 'false', 'data' => 'The queried data doesn\'t exists'];
        }
        $dengueDistrictMonthNumber = DB::table('dengue')
            ->select(DB::raw("count(district) number, month(date) month"))
            ->whereYear("date", $request->year)
            ->where("district", $request->district)
            ->groupBy(DB::raw("month(date)"))
            ->get()->toArray();
//        return $dengueDistrictMonthNumber;

        $finalOutput = array();
        $toBeShownAbsentData = 0;
        for ($monthsInAYear = 1; $monthsInAYear < 13; $monthsInAYear ++)
        {
            foreach ($dengueDistrictMonthNumber as $data)
            {
                if ($monthsInAYear == $data->month)
                {
                    $finalOutput[$monthsInAYear] = $data->number;
                    break;
                }
            }
            if (!isset($finalOutput[$monthsInAYear]))
            {
                $finalOutput[$monthsInAYear] = 0;
            }

        }

        return $finalOutput;
//        return ['result' => 'true', 'monthlyDengueDistrictNumber' => $dengueDistrictMonthNumber];
    }

}