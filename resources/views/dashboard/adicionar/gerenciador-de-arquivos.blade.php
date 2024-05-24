@extends('layouts.apps')

@section('title', 'Adicionar Usuário')
@section('function_name', 'filler_manager')

@section('content')
    <div class="">
        <div>
            <h1 style="margin-top: 0em; margin-bottom: 0em;" class="title-dash-admin-draken">Gerenciador de Arquivos</h1>
            <p style="margin-top: 0em;">Gostaria de adicionar algum arquivo?</p>
        </div>
        <hr>
        <div class="js-upload uk-placeholder uk-text-center" style="padding: 0.5em 1em; border-radius: 5px; border: solid 1px #bababa36;">
            <span uk-icon="icon: cloud-upload"></span>
            <span class="uk-text-middle">Selecione arquivos do tipo:</span>
            <div uk-form-custom>
                <input type="file" multiple name="filenames[]" id="file">
                <span class="uk-link" style="text-transform: uppercase">({{ env('APP_UPLOADS_PERMISSIONS') }})</span>
            </div>
            <br>
            <button style="margin-top: 0.5em; margin-bottom: 0em;" class="uk-button uk-button-primary" id="btn-submit">ENVIAR ARQUIVOS <img id="imgloadingupload" style="width: 2em; margin-left: 1em; display: none" src="{{ asset('assets/icons/loading.gif') }}"></button>
        </div>
        <div class="uk-grid-small" uk-grid>
            @foreach ($allMedia as $image)
                <?php
                    $img_explode = explode(".", $image);
                ?>
                <div style="margin-bottom: 0em!important;" class="uk-width-1-4@s">
                    <?php if($img_explode[1] == "mp4") { ?>
                        <div class="uk-card uk-card-default uk-card-body item-gallerys" style="padding: 0.5em 1em; border: solid 1px #bababa36;">
                            <div uk-lightbox>
                                <a href="{{ asset('uploads/'.$image.'') }}" data-caption="{{ $image; }}">
                                    <div class="bg-item-gallery" style="background-image: url('{{ asset('assets/icons/video/video-thumbnail-01.jpg') }}');"></div>
                                </a>
                            </div>
                            <a href="{{ asset('uploads/'.$image.'') }}" target="_blank">{{ $image; }}</a>
                        </div>
                    <?php } else { ?>
                        <div class="uk-card uk-card-default uk-card-body item-gallerys" style="padding: 0.5em 1em; border: solid 1px #bababa36;">
                            <div uk-lightbox>
                                <a href="{{ asset('uploads/'.$image.'') }}" data-caption="{{ $image; }}">
                                    <div class="bg-item-gallery" style="background-image: url('{{ asset('uploads/'.$image.'') }}');"></div>
                                </a>
                            </div>
                            <a href="{{ asset('uploads/'.$image.'') }}" target="_blank">{{ $image; }}</a>
                        </div>
                    <?php } ?>
                </div>
            @endforeach
        </div>


        <script>
            $("#btn-submit").click(function(e) {
                // preventDefault
                e.preventDefault();
                var formData = new FormData();

                // Get files item
                var files =$('#file')[0].files;
                for(var i=0;i<files.length;i++){
                    formData.append("filenames[]", files[i], files[i]['name']);
                }

                // loading section
                $("#btn-submit").attr("disabled", "disabled");
                $("#imgloadingupload").css("display", "unset");

                // ajax item
                $.ajax({
                    url: '{{ route("dashboardHomeManagerFillerPost.post") }}',
                    method: 'post',
                    data: formData,
                    contentType : false,
                    processData : false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response){

                        if(response.errors == undefined) {
                            if(response.status == "true") {
                                // Interval redirect
                                UIkit.notification(response.log, 'success');
                                setTimeout(function() {
                                    window.location= ""+response.redirect+"";
                                }, 1000);
                            } else {
                                UIkit.notification(response.log, 'danger');
                            }
                        } else {
                            $.each(response.errors, function(index, value) {
                                UIkit.notification(value, 'danger');
                            });
                        }

                        // loading remove
                        $("#btn-submit").removeAttr("disabled");
                        $("#imgloadingupload").css("display", "none");
                    }
                });
            });
        </script>
    </div>
@endsection
