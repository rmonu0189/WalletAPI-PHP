<?php

namespace Application\Services;

use Application\Model\Person;
use Application\Model\Response;
use MRPHPSDK\MRValidation\MRValidation;
use Application\Model\Account;
use Application\Model\Transaction;

class TransactionService {
    public static function addAccountToAccountTransaction($userId, $params) {
        $validation = new MRValidation($params, [
            'fromAccountId' => 'required',
            'toAccountId' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'comment' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $fromAccount = Account::where('id', $params['fromAccountId'])->where('userId', $userId)->first();
        $toAccount = Account::where('id', $params['toAccountId'])->where('userId', $userId)->first();
        $amount = $params['amount'];

        if($fromAccount && $toAccount) {
            if($fromAccount->type === "debitCard") {
                $linkedAccount = Account::where('id', $fromAccount->linkedBankId)->first();
                $linkedAccount->balance = $linkedAccount->balance - $amount;
                $linkedAccount->save();
            } else {
                $fromAccount->balance = $fromAccount->balance - $amount;
                $fromAccount->save();
            }
     
            if($toAccount->type === "debitCard") {
                $linkedAccount = Account::where('id', $toAccount->linkedBankId)->first();
                $linkedAccount->balance = $linkedAccount->balance + $amount;
                $linkedAccount->save();
            } else {
                $toAccount->balance = $toAccount->balance + $amount;
                $toAccount->save();
            }

            $transaction = new Transaction([
                'userId' => $userId,
                'fromAccountId' => $params['fromAccountId'],
                'toAccountId' => $params['toAccountId'],
                'type' => 'accountToAccount',
                'amount' => $amount,
                'comment' => $params['comment'],
                'date' => date($params['date'])
            ]);
            $transaction->save();
            return Response::data(null, 1, "Transaction successfully added.");
        } else {
            return Response::data(null, 0, "Invalid from or to account information.");
        }
    }

    public static function addAccountToPersonTransaction($userId, $params) {
        $validation = new MRValidation($params, [
            'fromAccountId' => 'required',
            'personId' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'comment' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $fromAccount = Account::where('id', $params['fromAccountId'])->where('userId', $userId)->first();
        $Person = Person::where('id', $params['personId'])->where('userId', $userId)->first();
        $amount = $params['amount'];

        if($fromAccount && $Person) {
            if($fromAccount->type === "debitCard") {
                $linkedAccount = Account::where('id', $fromAccount->linkedBankId)->first();
                $linkedAccount->balance = $linkedAccount->balance - $amount;
                $linkedAccount->save();
            } else {
                $fromAccount->balance = $fromAccount->balance - $amount;
                $fromAccount->save();
            }
    
            $Person->balance = $Person->balance + $amount;
            $Person->save();

            $transaction = new Transaction([
                'userId' => $userId,
                'fromAccountId' => $params['fromAccountId'],
                'toAccountId' => $params['personId'],
                'type' => 'accountToPerson',
                'amount' => $amount,
                'comment' => $params['comment'],
                'date' => date($params['date'])
            ]);
            $transaction->save();
            return Response::data(null, 1, "Transaction successfully added.");
        } else {
            return Response::data(null, 0, "Invalid from or to account information.");
        }
    }

    public static function addPersonToAccountTransaction($userId, $params) {
        $validation = new MRValidation($params, [
            'toAccountId' => 'required',
            'personId' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'comment' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $toAccount = Account::where('id', $params['toAccountId'])->where('userId', $userId)->first();
        $Person = Person::where('id', $params['personId'])->where('userId', $userId)->first();
        $amount = $params['amount'];

        if($toAccount && $Person) {
            if($toAccount->type === "debitCard") {
                $linkedAccount = Account::where('id', $toAccount->linkedBankId)->first();
                $linkedAccount->balance = $linkedAccount->balance + $amount;
                $linkedAccount->save();
            } else {
                $toAccount->balance = $toAccount->balance + $amount;
                $toAccount->save();
            }
    
            $Person->balance = $Person->balance - $amount;
            $Person->save();

            $transaction = new Transaction([
                'userId' => $userId,
                'fromAccountId' => $params['personId'],
                'toAccountId' => $params['toAccountId'],
                'type' => 'personToAccount',
                'amount' => $amount,
                'comment' => $params['comment'],
                'date' => date($params['date'])
            ]);
            $transaction->save();
            return Response::data(null, 1, "Transaction successfully added.");
        } else {
            return Response::data(null, 0, "Invalid from or to account information.");
        }
    }
}