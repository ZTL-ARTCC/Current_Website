<?php

namespace App\Livewire;

use App\StudentNotes;
use App\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaStudNotes extends Component {

    public $student_notes;
    public $controllers;
    public $student;
    public $note;

    public function mount() {
        $this->controllers = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
    }

    public function render() {
        $this->student_notes = StudentNotes::all();
        return view('livewire.ta-stud-notes');
    }

    public function student_select() {
        $stud_note = StudentNotes::find($this->student);
        if (!$this->student || !$stud_note) {
            $this->note = '';
            $this->dispatch('updateNoteContent', content: $this->note);
            return 1;
        }
        $this->note = $stud_note->note;
        $this->dispatch('updateNoteContent', content: $this->note);
    }

    public function save() {
        $this->dispatch('updateNoteFromEditor');
        StudentNotes::updateOrCreate(
            ['id' => $this->student],
            ['note' => $this->note, 'entered_by' => Auth::id()]
        );
        $this->reset_form();
    }

    public function update($id) {
        $student_note = StudentNotes::find($id);
        $this->student = $student_note->id;
        $this->note = $student_note->note;
        $this->dispatch('updateNoteContent', content: $this->note);
    }

    public function delete($id) {
        $student_note = StudentNotes::find($id);
        if ($student_note) {
            $student_note->delete();
        }
        $this->reset_form();
    }

    private function reset_form() {
        $this->student = '';
        $this->note = '';
        $this->dispatch('updateNoteContent', content: $this->note);
    }
}
