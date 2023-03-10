<?php

namespace Application\Services;

use Application\Model\Account;
use Application\Model\Response;
use MRPHPSDK\MRValidation\MRValidation;

class AccountService {
    public static function getAccounts($userId) {
        $accounts = Account::where('userId', $userId)->get();
        return Response::data($accounts, 1, "");
    }

    public static function addNewAccount($params, $userId) {
        $validation = new MRValidation($params, [
            'type' => 'required',
            'balance' => 'required',
            'info' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $params['userId'] = $userId;
        $account = new Account($params);
        $account->save();
        return Response::data(null, 1, "Account added successfully.");
    }

    public static function editAccount($params, $userId) {
        $validation = new MRValidation($params, [
            'id' => 'required',
            'type' => 'required',
            'balance' => 'required',
            'info' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $account = Account::where('id', $params['id'])->where('userId', $userId)->first();
        if($account) {
            $account->type = $params['type'];
            $account->balance = $params['balance'];
            $account->info = $params['info'];
            $account->save();
            return Response::data(null, 1, "Account successfully update.");
        } else {
            return Response::data(null, 0, "Account not found.");
        }
    }

    public static function deleteAccount($params, $userId) {
        $validation = new MRValidation($params, [
            'id' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $account = Account::where('id', $params['id'])->where('userId', $userId)->first();
        if($account) {
            $account->remove();
            return Response::data(null, 1, "Account successfully deleted.");
        } else {
            return Response::data(null, 0, "Account not found.");
        }
    }
}