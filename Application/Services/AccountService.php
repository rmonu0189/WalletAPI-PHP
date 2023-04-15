<?php

namespace Application\Services;

use Application\Model\Account;
use Application\Model\Response;
use MRPHPSDK\MRValidation\MRValidation;

class AccountService {
    public static function getAccounts($userId) {
        $accounts = Account::where('userId', $userId)->get();
        return Response::data($accounts ? $accounts : [], 1, "");
    }

    public static function addNewAccount($params, $userId) {
        $validation = new MRValidation($params, [
            'type' => 'required',
            'bankName' => 'required',
            'accountNumber' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $params['userId'] = $userId;
        if(array_key_exists("initialBalance", $params)) {
            $params['balance'] = $params['initialBalance'];
        } else {
            $params['balance'] = 0.0;
        }
        $account = new Account($params);
        $account->save();
        return Response::data($account, 1, "Account added successfully.");
    }

    public static function editAccount($params, $userId) {
        $validation = new MRValidation($params, [
            'id' => 'required',
            'type' => 'required',
            'bankName' => 'required',
            'accountNumber' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $account = Account::where('id', $params['id'])->where('userId', $userId)->first();
        if($account) {
            $account->type = $params['type'];
            $account->bankName = $params['bankName'];
            $account->accountNumber = $params['accountNumber'];
            $account->linkedBankId = $params['linkedBankId'];
            $account->initialBalance = $params['initialBalance'];
            $account->updatedAt = date('Y-m-d H:i:s');
            $account->save();
            return Response::data($account, 1, "Account successfully update.");
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