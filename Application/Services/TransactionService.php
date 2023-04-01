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

        if($fromAccount && $toAccount) {
            $date = date($params['date']);
            $transaction = new Transaction([
                'userId' => $userId,
                'fromAccountId' => $params['fromAccountId'],
                'toAccountId' => $params['toAccountId'],
                'type' => 'accountToAccount',
                'amount' => $amount,
                'comment' => $params['comment'],
                'date' => $date
            ]);
            $transaction->save();
            return Response::data(null, 1, "Transaction successfully added.");
        } else {
            return Response::data(null, 0, "Invalid from or to account information.");
        }
    }

    public static function getPersons($userId) {
        $persons = Person::where('userId', $userId)->get();
        return Response::data($persons, 1, "");
    }

    public static function addNewPerson($params, $userId) {
        $validation = new MRValidation($params, [
            'name' => 'required',
            'balance' => 'required',
            'initialBalance' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $params['userId'] = $userId;
        $person = new Person($params);
        $person->save();
        return Response::data(null, 1, "Person added successfully.");
    }
}