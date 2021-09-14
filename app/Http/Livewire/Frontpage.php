<?php

namespace App\Http\Livewire;

use App\Models\Page;
use Livewire\Component;

class Frontpage extends Component
{
    public $urlslug;
    public $title;
    public $content;

    public function mount($urlslug = null)
    {
       $this->retriveContent($urlslug);
    }
    /**
     * retriveContent retrives the content of a page by a url slug
     *
     * @param  mixed $urlslug
     * @return void
     */
    public function  retriveContent($urlslug)
    {
        if (empty($urlslug)){
            $data = Page::where('is_default_home', true)->first();
        } else {
            $data = Page::where('slug', $urlslug)->first();
            if(!$data){
                $data = Page::where('is_default_not_found', true)->first();
            }
        }
        if($data) {
            $this->title = $data->title;
            $this->content = $data->content;
        }
    }
    public function render()
    {
        return view('livewire.frontpage')->layout('layouts.frontpage');
    }
}
