<?php

namespace App\Services;

use App\Repositories\StudentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class StudentService
{
    protected $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function getAll()
    {
        return $this->studentRepository->getAll();
    }

    public function getById($id)
    {
        return $this->studentRepository->getById($id);
    }

    public function saveStudentData($data)
    {
        $validator = Validator::make($data, [
            'nim' => 'required',
            'name' => 'required',
            'major' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return $this->studentRepository->save($data);
    }

    public function updateStudent($data, $id)
    {
        $validator = Validator::make($data, [
            'nim' => 'bail|min:2',
            'name' => 'bail|max:255',
            'major' => 'bail|max:255'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $post = $this->studentRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update student data');
        }

        DB::commit();

        return $post;
    }

    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $student = $this->studentRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();

        return $student;

    }
}
