<?php

namespace App\Models\v0\block;

use App\Models\TransformerAbstract;
use App\Models\v0\block\transaction\TransactionTransformer;

class BlockTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'transactions'
    ];

    public function transform(Block $block) {
        return [
            'hash' => (string) $block->hash,
            'version' => (int) $block->version,
            'merkle' => (string) $block->merkle,
            'time' => (int) $block->time,
            'size' => (int) $block->size,
            'main-chain' => (boolean) $block->longest,
            'nonce' => (int) $block->nonce,
            'height' => (int) $block->height,
            'prev-block-hash' => (string) $block->prev_block_hash,
            'next-block-hash' => (string) $block->next_block_hash,
            'difficulty' => (double) calculateDifficulty($block->nbits),
            'work' => (string) $block->work,
            'sent' => (int) $block->value_in,
            'mining-fee' => (int) $block->miner_reward - ($block->value_out - $block->value_in),
            'block-reward' => (int) $block->value_out - $block->value_in
        ];
    }

    public function includeTransactions(Block $block) {
        $transactions = $block->transactions;

        return $this->collection($transactions, new TransactionTransformer);
    }

}