<?php

namespace App\Http\Livewire;

use App\Models\Page;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Pages extends Component
{
    use WithPagination;
    public $showModalForm = false;
    public $modalConfirmDelete = false;
    public $modelId;
    public $slug;
    public $title;
    public $content;

    public function rules()
    {
        return [
           'title' => 'required',
           'slug' => ['required', Rule::unique('pages', 'slug')->ignore($this->modelId)],
           'content' => 'required',
        ];
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($this->title);
    }

    public function create()
    {
        $this->validate();
        Page::create($this->modalData());
        $this->showModalForm = false;
        $this->resetVars();
    }

    public function read()
    {
        return Page::paginate(1);
    }

    public function update()
    {
        $this->validate();
        Page::find($this->modelId)->update($this->modalData());
        $this->showModalForm = false;
    }

    public function delete()
    {
        Page::destroy($modelId);
        $this->modalConfirmDelete = false;
        $this->resetPage();
    }

    public function modalData()
    {
        return [
            'title' => $this->title,
            'slug' => Str::slug($this->slug),
            'content' => $this->content,
        ];
    }


    /**
     * Show the form modal
     *
     * @return void
     */
    public function createShowModal()
    {
        $this->resetValidation();
        $this->resetVars();
        $this->showModalForm = true;
    }

    public function updateShowModal($id)
    {
        $this->resetValidation();
        $this->resetVars();
        $this->modelId = $id;
        $this->showModalForm = true;
        $this->loadModel();
    }

    public function deleteShowModal($id)
    {
        $this->modelId = $id;
        $this->modalConfirmDelete = true;
    }

    public function loadModel()
    {
        $data = Page::find($this->modelId);
        $this->title = $data->title;
        $this->slug = $data->slug;
        $this->content = $data->content;
    }

    public function resetVars()
    {
        $this->title = null;
        $this->slug = null;
        $this->content = null;
        $this->modelId = null;
    }

    public function mount()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.pages', [
            'data' => $this->read(),
        ]);
    }
}
