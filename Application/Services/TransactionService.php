<?php

namespace Application\Services;

use Application\Model\Person;
use Application\Model\Response;
use MRPHPSDK\MRValidation\MRValidation;

class TransactionService {
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