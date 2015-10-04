<?php

namespace App\Transformers;

class InfoTransformer extends TransformerAbstract {

    public function transform($data) {
        return [
            'network-height' => (int) $data->mining_info->blocks,
            'database-height' => (int) $data->last_block->height,
            'database-sync-progress' => (int) round($data->last_block->height / $data->mining_info->blocks * 100 * 100) / 100,
            'difficulty' => (double) calculateDifficulty($data->last_block->nbits),
            'mhps' => (double) $data->mining_info->networkhashps / 1000000,
            'time-since-block' => (int) time() - $data->last_block['time'],
            'total-omc' => (int) $data->last_block->total_value,
            'omc-btc-price' => (double) $data->omc_btc_price,
            'omc-usd-price' => (double) $data->omc_btc_price * $data->btc_usd_price,
            'market-cap' => (double) ($data->last_block->total_value / pow(10, 8)) * $data->omc_btc_price * $data->btc_usd_price,
            'block-reward' => (int) calculateReward($data->last_block->height)
        ];
    }

}