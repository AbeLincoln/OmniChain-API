<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class InfoTransformer extends TransformerAbstract {

    public function transform($data) {
        return [
            'height' => (int) $data[1]->height,
            'difficulty' => (double) calculateDifficulty($data[1]->nbits),
            'mhps' => (double) $data[0]->networkhashps / 1000000,
            'time_since_block' => (int) time() - $data[1]['time'],
            'total_omc' => (int) $data[1]->total_value,
            'omc_btc_price' => (double) $data[2],
            'omc_usd_price' => (double) $data[2] * $data[3],
            'market_cap' => (double) ($data[1]->total_value / pow(10, 8)) * $data[2] * $data[3],
            'block_reward' => (int) calculateReward($data[1]->height)
        ];
    }

}