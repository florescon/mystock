<div>
    <div class="row justify-content-center" wire:ignore>

        <textarea 
            x-data="{ inscription: @entangle('inscription').defer }" 
            x-init="
            new FroalaEditor($el, {
                imageUploadURL: 'http://i.froala.com/upload', 
                events: { 
                    'contentChanged': function () { 
                        inscription = this.html.get(); 
                    }, 
                } 
            });" 

        class="form-control @error('inscription') is-invalid @enderror"
        id="inscription" name="inscription" wire:model.defer="inscription" rows="5"> {{(old('inscription') ? old('inscription')  : '')}}
    </textarea>
    </div>

    <div class="mb-4 mt-4 w-full">
        <x-button type="submit" wire:click="update" primary class="w-full text-center">
            {{ __('Save Changes') }}
        </x-button>
    </div>

</div>
