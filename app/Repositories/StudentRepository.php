<?php
namespace App\Repositories;

use App\Models\Student;
use Illuminate\Support\Facades\Log;

class StudentRepository
{
    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function getAll()
    {
        return $this->student->get();
    }

    public function getById($id)
    {
        return $this->student
            ->where('id', $id)
            ->get();
    }

    public function save($data)
    {
        Log::info("this data ", $data);
        $student = new $this->student;

        $student->nim = $data['nim'];
        $student->name = $data['name'];
        $student->major = $data['major'];

        $student->save();

        return $student->fresh();
    }

    public function update($data, $id)
    {

        $student = $this->student->find($id);

        $student->nim = $data['nim'];
        $student->name = $data['name'];
        $student->major = $data['major'];

        $student->update();

        return $student;
    }

    public function delete($id)
    {

        $student = $this->student->find($id);
        $student->delete();

        return $student;
    }

}
