<?php

use Nbobtc\Command\Command;
use Nbobtc\Http\Client;
use \DoctorBlue\BaseConvert;

function isAddress($term) {
    if (strlen($term) === 34) {
        try {
            $addrhex = BaseConvert::base58_hex($term);
        } catch (Exception $e) {
            return false;
        }

        if (strlen($addrhex) === 50) {
            return true;
        }
    }

    return false;
}

function pubkeyHashToAddress($pubkey, $addrver = 73) {
    $pubkey = $addrver . $pubkey;
    $pubkeybs = pack("H*", $pubkey);
    $checksum = hash("sha256", hash("sha256", $pubkeybs, true));
    $checksum = substr($checksum, 0, 8);
    $pubkey .= $checksum;

    return BaseConvert::hex_base58($pubkey);
}

function addressToPubkeyHash($address) {
    $hexaddr = BaseConvert::base58_hex($address);
    $unchecked = substr($hexaddr, 2, 40);

    return $unchecked;
}

function targetToDifficulty($target) {
    return ((1 << 224) - 1) * 1000 / ($target + 1) / 1000;
}

function calculateTarget($nBits) {
    return ($nBits & 0xffffff) << (8 * (($nBits >> 24) - 3));
}

function calculateDifficulty($nBits) {
    return targetToDifficulty(calculateTarget($nBits));
}

function calculateReward($height) {
    return 6684999999 * pow(2, floor($height / 100010) * -1);
}

function getOption($name) {
    return DB::table('options')->where('name', $name)->pluck('value');
}

function setOption($name, $value) {
    DB::table('options')->where('name', $name)->update(['value' => $value]);
}

function sendRpcCommand(Client $client, $command, $arguments = []) {
    try {
        return json_decode($client->sendCommand(new Command($command, $arguments))->getBody()->getContents());
    } catch (Exception $e) {
        return false;
    }
}

function getAccountUnspent(Client $client, $userId) {
    $accountAddresses = sendRpcCommand($client, 'getaddressesbyaccount', [$userId]);
    $accountUnspent = [];

    if (!empty($accountAddresses)) {
        $unspents = sendRpcCommand($client, 'listunspent', [0, 9999999]);

        foreach ($unspents as $unspent) {

        }
    }

    return $accountUnspent;
}