<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Rules\Currency;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use DOMDocument;

class CurrencyController extends Controller
{
    private $client;
    private $url = 'https://pt.wikipedia.org/wiki/ISO_4217';

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'verify' => false
        ]);
    }

    public function scrap(Request $request)
    {
        $data = $request->all();

        //validating input data
        $validator = Validator::make($data, [
            'type' => [
                'required',
                Rule::in(['code', 'code_list', 'number', 'number_list']),
            ],
            'value' => ['required', new Currency($request->type)]
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        try {
            $response = $this->client->get($this->url);
            $content = $response->getBody()->getContents();
            $crawler = new Crawler($content);
            // $filter[0] = 'code';
            // $filter[1] = 'GBP';
            // $filter[0] = 'code_list';
            // $filter[1] = ['GBP', 'AUD'];
            // $filter[0] = 'number';
            // $filter[1] = '036';
            $filter[0] = $request->type;
            $filter[1] = $request->value;

            $table = $crawler->filter('.wikitable>tbody')->filter('tr')->each(function ($tr, $tr_index) use ($filter) {

                $row = $tr->filter('td')->each(function ($td, $td_index) use ($tr_index, $filter) {

                    if ($td_index == 4) {
                        $locations = explode('>,', $td->html());
                        $loc = [];
                        foreach ($locations as $i_loc => $location) {
                            $location .= substr($location, -1) != '>' ? '>' : '';

                            $doc = new DOMDocument();
                            $doc->loadHTML($location);
                            $selector = new \DOMXPath($doc);

                            if (strpos($location, '<img')) {
                                $img = $selector->query('//img');
                                $loc[$i_loc]['icon'] = $img[0]->getAttribute('src');
                            } else {
                                $loc[$i_loc]['icon'] = "";
                            }

                            $link = $doc->getElementsByTagName('a');
                            if ($link[0])
                                $loc[$i_loc]['location'] = $link[0]->nodeValue;
                        }

                        return $loc;
                    } else {
                        return trim($td->text());
                    }
                });

                return $row;
            });

            $result = [];
            foreach ($table as $row) {
                if (count($row)) {
                    if ($filter[0] == 'code' && $row[0] == $filter[1]) {
                        $result[] = $this->formatRow($row);
                    } else if ($filter[0] == 'code_list' && in_array($row[0], $filter[1])) {
                        $result[] =
                            $this->formatRow($row);
                    } else if ($filter[0] == 'number' && $row[1] == $filter[1]) {
                        $result[] =
                            $this->formatRow($row);
                    } else if ($filter[0] == 'number_list' && in_array($row[1], $filter[1])) {
                        $result[] =
                            $this->formatRow($row);
                    }
                }
            }

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function formatRow($row)
    {
        return [
            "code" => $row[0],
            "number" => $row[1],
            "decimal" => $row[2],
            "currency" => $row[3],
            "currency_locations" => $row[4]
        ];
    }
}
