<?php

namespace App\Transformers;

use App\Models\Block;
use League\Fractal\TransformerAbstract;

class BlockTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'transactions'
    ];

    public function transform(Block $block) {
        return [
            'hash' => $block->hash,
            'version' => $block->version,
            'merkle' => $block->merkle,
            'time' => $block->time,
            'size' => $block->size,
            'longest' => $block->longest,
            'nonce' => $block->nonce,
            'height' => $block->height,
            'prev_block_hash' => $block->prev_block_hash,
            'next_block_hash' => $block->next_block_hash,
            'difficulty' => calculateDifficulty($block->nbits),
            'work' => $block->work,
            'sent' => $block->value_in,
            'mining_fee' => 0,//TODO: Calculate mining fee
            'block_reward' => $block->value_out - $block->value_in
        ];
    }

    public function includeTransactions(Block $block) {
        $transactions = $block->transactions;

        return $this->collection($transactions, new TransactionTransformer);
    }

}