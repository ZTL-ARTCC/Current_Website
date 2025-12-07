<div>
    <div class="row bg-warning w-100 my-2 rounded" wire:loading>
        <h3 class="text-center p-2"><i class="fa-solid fa-hourglass-start me-2"></i> Refreshing Dataset... please wait</h3>
    </div>
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="card mb-4">
                <div class="card-header text-start">
                    <i class="fa-solid fa-note-sticky me-2"></i>TA's Student Notes for Training Staff
                </div>
                <div class="card-body text-start">
                    <div class="row-mb-3">
                        <div class="col-sm-4">
                            <div class="form-group mb-4">
                                <label for="student" class="form-label">Select Student</label>
                                {{ html()->select('student', $controllers, $this->student)->placeholder('Select Student')->class(['form-select'])->attributes(['wire:model' => 'student', 'wire:change' => 'student_select']) }}
                            </div>
                            <button type="button" class="btn btn-primary w-100" wire:click="save"><i class="fa-solid fa-floppy-disk me-2"></i>Save</button>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group" wire:ignore>
                                <label for="content" class="form-label">Note to Training Staff</label>
                                {{ html()->textarea('note_editor', null)->class(['form-control', 'text-editor']) }}
                                <p>*Note* this text is not visible to the student, even if the student is a training staff member.</p>
                            </div>
                        </div>
                        {{ html()->hidden('note', null)->attributes(['wire:model' => 'note']) }}
                    </div>
                </div>
            </div>
            @if($student_notes->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Student</th>
                            <th scope="col">Note</th>
                            <th scope="col">Entered By</th>
                            <th scope="col">Last Modified</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($student_notes as $n)
                        <tr wire:key="{{ $n->id }}">
                        <td>{{ $n->student_name }}</td>
                        <td>{!! $n->note !!}</td>
                        <td>{{ $n->entered_by_name }}</td>
                        <td>{{ $n->last_modified }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="actions">
                                <button class="btn btn-warning" wire:click="update({{ $n->id }})"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="btn btn-danger" wire:click="delete({{ $n->id }})" wire:confirm="Are you sure you want to delete this note?"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                @include('inc.empty_state', ['header' => 'No Notes Found', 'body' => 'There are presently no student notes visible to training staff.', 'icon' => 'fa-solid fa-file'])
            @endif
        </div>
    </div>
</div>
@script
<script>
    window.addEventListener('updateNoteContent', (event) => {
        editors['note_editor'].setData(event.detail.content);
        document.getElementById('note').value = event.detail.content;
    });
    window.addEventListener('updateNoteFromEditor', () => {
        console.log('updating note: ' + editors['note_editor'].getData());
        $wire.set('note', editors['note_editor'].getData());
    });
</script>
@endscript
