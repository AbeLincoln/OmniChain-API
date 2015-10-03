<?php

namespace App\Transformers;

class InfoTransformer extends TransformerAbstract {

    public function transform($data) {
        return [
            'height' => (int) $data[1]->height,
            'difficulty' => (double) calculateDifficulty($data[1]->nbits),
            'mhps' => (double) $data[0]->networkhashps / 1000000,
            'time-since-block' => (int) time() - $data[1]['time'],
            'total-omc' => (int) $data[1]->total_value,
            'omc-btc-price' => (double) $data[2],
            'omc-usd-price' => (double) $data[2] * $data[3],
            'market-cap' => (double) ($data[1]->total_value / pow(10, 8)) * $data[2] * $data[3],
            'block-reward' => (int) calculateReward($data[1]->height)
        ];
    }

}