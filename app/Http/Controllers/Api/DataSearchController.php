<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DataSearchController extends Controller
{
    private function fetchData()
    {
        $dataUrl = env('DATA_SEARCH_URL');
        $response = Http::get($dataUrl);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['DATA']) && !empty($data['DATA'])) {
                $rows = explode("\n", $data['DATA']);
                $headers = explode('|', array_shift($rows));

                $result = [];
                foreach ($rows as $row) {
                    if (empty($row))
                        continue;

                    $values = explode('|', $row);
                    if (count($values) === count($headers)) {
                        $item = [];
                        foreach ($headers as $index => $header) {
                            $item[$header] = $values[$index];
                        }
                        $result[] = $item;
                    }
                }

                return $result;
            }
        }

        return [];
    }

    public function searchByName(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $name = $request->input('name');
        $data = $this->fetchData();

        $filtered = array_filter($data, function ($item) use ($name) {
            return stripos($item['NAMA'], $name) !== false;
        });

        return response()->json([
            'data' => array_values($filtered)
        ]);
    }

    public function searchByNIM(Request $request)
    {
        $request->validate([
            'nim' => 'required|string',
        ]);

        $nim = $request->input('nim');
        $data = $this->fetchData();

        $filtered = array_filter($data, function ($item) use ($nim) {
            return $item['NIM'] === $nim;
        });

        return response()->json([
            'data' => array_values($filtered)
        ]);
    }

    public function searchByYMD(Request $request)
    {
        $request->validate([
            'ymd' => 'required|string|size:8',
        ]);

        $ymd = $request->input('ymd');
        $data = $this->fetchData();

        $filtered = array_filter($data, function ($item) use ($ymd) {
            return $item['YMD'] === $ymd;
        });

        return response()->json([
            'data' => array_values($filtered)
        ]);
    }
}
