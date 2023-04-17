<?php

namespace Application\Services;

use Application\Model\Person;
use Application\Model\Response;
use MRPHPSDK\MRValidation\MRValidation;
use Application\Model\Account;
use Application\Model\Transaction;
use Application\Model\IncomeSource;
use Application\Model\CategoryItem;

class TransactionService {
    public static function getTransactions($userId, $filter) {
        $query = Transaction::where('userId', $userId);
        if(isset($filter['fromDate']) && isset($filter['toDate'])) {
            $query = $query->where('date', $filter['fromDate'], '>=')->where('date', $filter['toDate'], '<=');
        }
        $transactions = $query->orderBy('id', 'DESC')->get();
        return Response::data($transactions, 1, "");
    }

    public static function addAccountToAccountTransaction($userId, $params) {
        $validation = new MRValidation($params, [
            'fromAccountId' => 'required',
            'toAccountId' => 'required',
            'amount' => 'required',
            'date' => 'required'
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
                $fromAccount = $linkedAccount;
            } else {
                $fromAccount->balance = $fromAccount->balance - $amount;
                $fromAccount->save();
            }
     
            if($toAccount->type === "debitCard") {
                $linkedAccount = Account::where('id', $toAccount->linkedBankId)->first();
                $linkedAccount->balance = $linkedAccount->balance + $amount;
                $linkedAccount->save();
                $toAccount = $linkedAccount;
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

            $model = Transaction::where('id', $transaction->id)->first();
            return Response::data(['transaction' => $model, 'fromAccount' => $fromAccount, 'toAccount' => $toAccount], 1, "Transaction successfully added.");
        } else {
            return Response::data(null, 0, "Invalid from or to account information.");
        }
    }

    public static function addAccountToPersonTransaction($userId, $params) {
        $validation = new MRValidation($params, [
            'fromAccountId' => 'required',
            'personId' => 'required',
            'amount' => 'required',
            'date' => 'required'
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
                $fromAccount = $linkedAccount;
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

            $model = Transaction::where('id', $transaction->id)->first();
            return Response::data(['transaction' => $model, 'fromAccount' => $fromAccount, 'person' => $Person], 1, "Transaction successfully added.");
        } else {
            return Response::data(null, 0, "Invalid from or to account information.");
        }
    }

    public static function addPersonToAccountTransaction($userId, $params) {
        $validation = new MRValidation($params, [
            'toAccountId' => 'required',
            'personId' => 'required',
            'amount' => 'required',
            'date' => 'required'
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
                $toAccount = $linkedAccount;
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
            
            $model = Transaction::where('id', $transaction->id)->first();
            return Response::data(['transaction' => $model, 'person' => $Person, 'toAccount' => $toAccount], 1, "Transaction successfully added.");
        } else {
            return Response::data(null, 0, "Invalid from or to account information.");
        }
    }

    public static function adIncome($userId, $params) {
        $validation = new MRValidation($params, [
            'toAccountId' => 'required',
            'incomeSourceId' => 'required',
            'amount' => 'required',
            'date' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $toAccount = Account::where('id', $params['toAccountId'])->where('userId', $userId)->first();
        $incomeSource = IncomeSource::where('id', $params['incomeSourceId'])->where('userId', $userId)->first();
        $amount = $params['amount'];

        if($toAccount && $incomeSource) {
            if($toAccount->type === "debitCard") {
                $linkedAccount = Account::where('id', $toAccount->linkedBankId)->first();
                $linkedAccount->balance = $linkedAccount->balance + $amount;
                $linkedAccount->save();
                $toAccount = $linkedAccount;
            } else {
                $toAccount->balance = $toAccount->balance + $amount;
                $toAccount->save();
            }
    
            $incomeSource->balance = $incomeSource->balance - $amount;
            $incomeSource->save();

            $transaction = new Transaction([
                'userId' => $userId,
                'fromAccountId' => $params['incomeSourceId'],
                'toAccountId' => $params['toAccountId'],
                'type' => 'incomeSourceToAccount',
                'amount' => $amount,
                'comment' => $params['comment'],
                'date' => date($params['date'])
            ]);
            $transaction->save();
            
            $model = Transaction::where('id', $transaction->id)->first();
            return Response::data(['transaction' => $model, 'income' => $incomeSource, 'toAccount' => $toAccount], 1, "Transaction successfully added.");
        } else {
            return Response::data(null, 0, "Invalid from or to account information.");
        }
    }

    public static function adExpenses($userId, $params) {
        $validation = new MRValidation($params, [
            'fromAccountId' => 'required',
            'categoryId' => 'required',
            'amount' => 'required',
            'date' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $fromAccount = Account::where('id', $params['fromAccountId'])->where('userId', $userId)->first();
        $category = CategoryItem::where('id', $params['categoryId'])->where('userId', $userId)->first();
        $amount = $params['amount'];

        if($fromAccount && $category) {
            if($fromAccount->type === "debitCard") {
                $linkedAccount = Account::where('id', $fromAccount->linkedBankId)->first();
                $linkedAccount->balance = $linkedAccount->balance - $amount;
                $linkedAccount->save();
                $fromAccount = $linkedAccount;
            } else {
                $fromAccount->balance = $fromAccount->balance - $amount;
                $fromAccount->save();
            }

            $transaction = new Transaction([
                'userId' => $userId,
                'fromAccountId' => $params['incomeSourceId'],
                'toAccountId' => $params['categoryId'],
                'type' => 'expenses',
                'amount' => $amount,
                'comment' => $params['comment'],
                'date' => date($params['date'])
            ]);
            $transaction->save();
            
            $model = Transaction::where('id', $transaction->id)->first();
            return Response::data(['transaction' => $model, 'fromAccount' => $fromAccount], 1, "Expenses successfully added.");
        } else {
            return Response::data(null, 0, "Invalid from or to account information.");
        }
    }
}