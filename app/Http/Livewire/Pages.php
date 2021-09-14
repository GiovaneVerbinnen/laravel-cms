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
    public $isSetToDefaultHomePage;
    public $isSetToDefaultNotFoundPage;

    private function unassignDefaultHomePage()
    {
        if($this->isSetToDefaultHomePage != null) {
            Page::where('is_default_home', true)->update([
                'is_default_home' => false
            ]);
        }
    }

    private function unassignDefaultNotFoundPage()
    {
        if($this->isSetToDefaultNotFoundPage != null) {
            Page::where('is_default_not_found', true)->update([
                'is_default_not_found' => false
            ]);
        }
    }

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

    public function updatedIsSetToDefaultHomePage()
    {
        $this->isSetToDefaultNotFoundPage = null;
    }

    public function updatedIsSetToDefaultNotFoundPage()
    {
        $this->isSetToDefaultHomePage = null;
    }

    public function create()
    {
        $this->validate();
        $this->unassignDefaultHomePage();
        $this->unassignDefaultNotFoundPage();
        Page::create($this->modalData());
        $this->showModalForm = false;
        $this->reset();
    }

    public function read()
    {
        return Page::paginate(5);
    }

    public function update()
    {
        $this->validate();
        $this->unassignDefaultHomePage();
        $this->unassignDefaultNotFoundPage();
        Page::find($this->modelId)->update($this->modalData());
        $this->showModalForm = false;
        $this->isSetToDefaultHomePage = null;
        $this->isSetToDefaultNotFoundPage = null;
    }

    public function delete()
    {
        Page::destroy($modelId);
        $this->modalConfirmDelete = false;
        $this->resetPage();
    }



    /**
     * Show the form modal
     *
     * @return void
     */
    public function createShowModal()
    {
        $this->resetValidation();
        $this->reset();
        $this->showModalForm = true;
    }

    public function updateShowModal($id)
    {
        $this->resetValidation();
        $this->reset();
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
        $this->isSetToDefaultHomePage = !$data->isSetToDefaultHomePage ? null:true;
        $this->isSetToDefaultNotFoundPage = !$data->isSetToDefaultNotFoundPage ? null:true;
    }

    public function modalData()
    {
        return [
            'title' => $this->title,
            'slug' => Str::slug($this->slug),
            'content' => $this->content,
            'is_default_home' => $this->isSetToDefaultHomePage,
            'is_default_not_found' => $this->isSetToDefaultNotFoundPage,
        ];
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
