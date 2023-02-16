<?php

namespace Application\Services;

use Application\Model\IncomeSource;
use Application\Model\Response;
use MRPHPSDK\MRValidation\MRValidation;

class IncomeSourceService {
    public static function getIncomeSources($userId) {
        $results = IncomeSource::where('userId', $userId)->get();
        return Response::data($results ? $results : [], 1, "");
    }

    public static function addNewIncomeSource($params, $userId) {
        $validation = new MRValidation($params, [
            'name' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $params['userId'] = $userId;
        $params['balance'] = $params['initialBalance'] ?? 0;
        $model = new IncomeSource($params);
        $model->save();
        return Response::data(null, 1, "IncomeSource added successfully.");
    }

    public static function editIncomeSource($params, $userId) {
        $validation = new MRValidation($params, [
            'id' => 'required',
            'name' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $model = IncomeSource::where('id', $params['id'])->where('userId', $userId)->first();
        if($model) {
            $model->name = $params['name'];
            $model->initialBalance = $params['initialBalance'];
            $model->updatedAt = date('Y-m-d H:i:s');
            $model->save();
            return Response::data(null, 1, "IncomeSource successfully update.");
        } else {
            return Response::data(null, 0, "IncomeSource not found.");
        }
    }

    public static function deleteIncomeSource($params, $userId) {
        $validation = new MRValidation($params, [
            'id' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $model = IncomeSource::where('id', $params['id'])->where('userId', $userId)->first();
        if($model) {
            $model->remove();
            return Response::data(null, 1, "IncomeSource successfully deleted.");
        } else {
            return Response::data(null, 0, "IncomeSource not found.");
        }
    }
}