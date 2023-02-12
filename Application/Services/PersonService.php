<?php

namespace Application\Services;

use Application\Model\Person;
use Application\Model\Response;
use MRPHPSDK\MRValidation\MRValidation;

class PersonService {
    public static function getPersons($userId) {
        $persons = Person::where('userId', $userId)->get();
        return Response::data($persons, 1, "");
    }

    public static function addNewPerson($params, $userId) {
        $validation = new MRValidation($params, [
            'name' => 'required',
            'mobile' => 'required',
            'initialBalance' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $params['userId'] = $userId;
        $params['balance'] = $params['initialBalance'];
        $person = new Person($params);
        $person->save();
        return Response::data(null, 1, "Person added successfully.");
    }

    public static function editPerson($params, $userId) {
        $validation = new MRValidation($params, [
            'id' => 'required',
            'name' => 'required',
            'mobile' => 'required',
            'initialBalance' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $person = Person::where('id', $params['id'])->where('userId', $userId)->first();
        if($person) {
            $person->name = $params['name'];
            $person->mobile = $params['mobile'];
            $person->initialBalance = $params['initialBalance'];
            $person->updatedAt = date('yyyy-MM-dd HH:mm:ss');
            $person->save();
            return Response::data(null, 1, "Person successfully update.");
        } else {
            return Response::data(null, 0, "Person not found.");
        }
    }

    public static function deletePerson($params, $userId) {
        $validation = new MRValidation($params, [
            'id' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $person = Person::where('id', $params['id'])->where('userId', $userId)->first();
        if($person) {
            $person->remove();
            return Response::data(null, 1, "Person successfully deleted.");
        } else {
            return Response::data(null, 0, "Person not found.");
        }
    }
}