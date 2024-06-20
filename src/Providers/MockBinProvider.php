<?php

namespace CommissionApp\Providers;

use CommissionApp\Interfaces\BinProviderInterface;

class MockBinProvider implements BinProviderInterface {

    private array $mockData = [
        '45717360' => '{
            "number": {},
            "scheme": "visa",
            "type": "debit",
            "brand": "Visa Classic",
            "country": {
                "numeric": "208",
                "alpha2": "DK",
                "name": "Denmark",
                "emoji": "🇩🇰",
                "currency": "DKK",
                "latitude": 56,
                "longitude": 10
            },
            "bank": {
                "name": "Jyske Bank A/S"
            }
        }',
        '516793' => '{
            "number": {},
            "scheme": "mastercard",
            "type": "debit",
            "brand": "Debit Mastercard",
            "country": {
                "numeric": "440",
                "alpha2": "LT",
                "name": "Lithuania",
                "emoji": "🇱🇹",
                "currency": "EUR",
                "latitude": 56,
                "longitude": 24
            },
            "bank": {
                "name": "Swedbank Ab"
            }
        }',
        '41417360' => '{
            "number": null,
            "country": {},
            "bank": {}
        }',
        '4745030' => '{
            "number": {},
            "scheme": "visa",
            "type": "debit",
            "brand": "Visa Classic",
            "country": {
                "numeric": "440",
                "alpha2": "LT",
                "name": "Lithuania",
                "emoji": "🇱🇹",
                "currency": "EUR",
                "latitude": 56,
                "longitude": 24
            },
            "bank": {
                "name": "Uab Finansines Paslaugos Contis"
            }
        }',
        '44411114' => '{
            "number": {},
            "scheme": "visa",
            "type": "credit",
            "brand": "Visa Infinite",
            "country": {
            "numeric": "804",
            "alpha2": "UA",
            "name": "Ukraine",
            "emoji": "🇺🇦",
            "currency": "UAH",
            "latitude": 49,
            "longitude": 32
            },
            "bank": {
            "name": "Jsc Universal Bank"
            }
            }'
    ];

    public function getBinData(string $bin): object {
        if (!isset($this->mockData[$bin])) {
            throw new \RuntimeException("BIN data not found for: $bin");
        }

        return json_decode($this->mockData[$bin]);
    }
}
