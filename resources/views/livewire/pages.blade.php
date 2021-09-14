<div class="p-6">
    <div class="pb-6">
        <x-jet-button wire:click="createShowModal">
            {{__('Create')}}
        </x-jet-button>

    </div>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Content
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    URL
                                </th>
                                <th scope="col" colspan="2"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Edit
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if ($data->count())
                            @forelse($data as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item->title }}
                                        </div>

                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{!!
                                        \Illuminate\Support\Str::limit($item->content, 50, '...') !!}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="/{{$item->slug}}"> <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{$item->slug}}
                                        </span></a>
                                </td>
                                <td>
                                    <x-jet-button wire:click="updateShowModal({{ $item->id }})">
                                        {{__('Update')}}
                                    </x-jet-button>
                                    <x-jet-danger-button wire:click="deleteShowModal({{ $item->id }})" class="mt-1">
                                        {{__('Delete')}}
                                        </x-jet-button>
                                </td>

                            </tr>
                            @empty
                            <tr>No results</tr>
                            @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="py-6">
        {{ $data->links() }}
    </div>

    <x-jet-dialog-modal wire:model="showModalForm">
        <x-slot name="title">
            {{ __('Save Page') }} {{ $modelId }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="title" value="{{ __('Title') }}" />
                <x-jet-input id="title" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="title" />
                @error('title') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="title" value="{{ __('Slug') }}" />
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        http://localhost:8000/
                    </span>
                    <input wire:model="slug"
                        class="form-input flex-1 block w-full rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        placeholder="url-slug">
                </div>
                @error('slug') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <label>
                    <input class="form-checkbox" type="checkbox" value="" wire:model="isSetToDefaultHomePage" />
                    <span class="ml-2 text-sm text-gray-600">Set as the default home page</span>
                </label>
            </div>
            <div class="mt-4">
                <label>
                    <input class="form-checkbox" type="checkbox" value="" />
                    <span class="ml-2 text-sm text-red-600">Set as the default 404 error page</span>
                </label>
            </div>
            <div class="mt-4">
                <x-jet-label for="title" value="{{ __('Content') }}" />
                <div class="rounded-md shadow-sm">
                    <div class="mt-1 bg-white">
                        <div class="body-content" wire:ignore>
                            <trix-editor class="trix-content" x-ref="trix" wire:model.debounce.100000ms="content"
                                wire:key="trix-content-unique-key"></trix-editor>
                        </div>
                    </div>
                </div>
                @error('content') <span class="error">{{ $message }}</span> @enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('showModalForm')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>
            @if($modelId)
            <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-jet-button>
            @else
            <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
            @endif
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-jet-dialog-modal wire:model="modalConfirmDelete">
        <x-slot name="title">
            {{ __('Delete Page') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this Page? ') }}


        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDelete')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete Page') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
