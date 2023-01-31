<?php

namespace Application\Services;

use Application\Model\CategoryItem;
use Application\Model\Response;
use MRPHPSDK\MRValidation\MRValidation;

class CategoryItemService {
    public static function getCategoryItems($userId) {
        $results = CategoryItem::where('userId', $userId)->get();
        return Response::data($results, 1, "");
    }

    public static function addNewCategoryItem($params, $userId) {
        $validation = new MRValidation($params, [
            'name' => 'required',
            'icon' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $params['userId'] = $userId;
        $model = new CategoryItem($params);
        $model->save();
        return Response::data(null, 1, "CategoryItem added successfully.");
    }

    public static function editCategoryItem($params, $userId) {
        $validation = new MRValidation($params, [
            'id' => 'required',
            'name' => 'required',
            'icon' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $model = CategoryItem::where('id', $params['id'])->where('userId', $userId)->first();
        if($model) {
            $model->name = $params['name'];
            $model->icon = $params['icon'];
            $model->save();
            return Response::data(null, 1, "CategoryItem successfully update.");
        } else {
            return Response::data(null, 0, "CategoryItem not found.");
        }
    }

    public static function deleteCategoryItem($params, $userId) {
        $validation = new MRValidation($params, [
            'id' => 'required'
        ], []);

        if($validation->validateFailed()){
            return Response::data([], 0, $validation->getValidationError()[0]);
        }

        $model = CategoryItem::where('id', $params['id'])->where('userId', $userId)->first();
        if($model) {
            $model->remove();
            return Response::data(null, 1, "CategoryItem successfully deleted.");
        } else {
            return Response::data(null, 0, "CategoryItem not found.");
        }
    }
}