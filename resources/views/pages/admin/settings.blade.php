@extends('layouts.app')

@section('content')
<style>
    .date {
        width: 140px
    }
</style>
    <div class="container mt-3">
        <h3 class="mb-4">
            Settings: 
        </h3>
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>            
        @endif
        <div class="card">
            <div class="card-header">
                Home Section Text:
            </div>
            <form class="card-body" method="POST" action="{{ route('admin.settings.store') }}" 
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="">
                        Headline:
                    </label>
                    <textarea name="home_section_text_1" id="home_section_text_1">{{ 
                        \App\Models\Setting::home_section_text_1()
                        }}</textarea>
                </div>
                <div class="form-group mt-2">
                    <label for="">
                        Paragraph:
                    </label>
                    <textarea name="home_section_text_2" id="home_section_text_2">{{ 
                        \App\Models\Setting::home_section_text_2()
                    }}</textarea>
                </div>
                <button type="submit" class="mt-3 btn btn-info">
                    Save 
                </button>
            </form>
        </div>
        <div class="card mt-2">
            <div class="card-header">
                Home Slider:
            </div>
            <form class="card-body" method="POST" action="{{ route('admin.settings.store') }}" 
                enctype="multipart/form-data">
                @csrf
                Slider #1
                <div class="mt-2">
                    <input type="file" name="home_slider_1" onchange="uploaded_img(this)" accept="image/*" class="form-control">
                    <img src="{{ \App\Models\Setting::home_slider_1() }}" class="mt-2 w-100" style="max-width: 500px">
                </div>
                <hr>
                Slider #2
                <div class="mt-2">
                    <input type="file" name="home_slider_2" onchange="uploaded_img(this)" accept="image/*" class="form-control">
                    <img src="{{ \App\Models\Setting::home_slider_2() }}" class="mt-2 w-100" style="max-width: 500px">
                </div>
                <hr>
                Slider #3
                <div class="mt-2">
                    <input type="file" name="home_slider_3" onchange="uploaded_img(this)" accept="image/*" class="form-control">
                    <img src="{{ \App\Models\Setting::home_slider_3() }}" class="mt-2 w-100" style="max-width: 500px">
                </div>
                <button type="submit" class="mt-3 btn btn-info">
                    Save 
                </button>
            </form>
        </div>
    </div>
    <script>
        function uploaded_img(element) 
        {
            if(element.files[0])
            {
                element.parentElement.querySelector('img').src = URL.createObjectURL(element.files[0])
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            ClassicEditor
                .create( document.querySelector( '#home_section_text_1' ) )
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                        document.querySelector( '#home_section_text_1' ).value = editor.getData();
                    });
                })
                .catch( error => {
                    console.error( error );
                })
            ClassicEditor
                .create( document.querySelector( '#home_section_text_2' ) )
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                        document.querySelector( '#home_section_text_2' ).value = editor.getData();
                    });
                })
                .catch( error => {
                    console.error( error );
                })
        })
    </script>
@endsection 