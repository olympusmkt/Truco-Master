@extends('layouts.apps')

@section('title', 'Dashboard')
@section('function_name', 'null')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
    <h1 style="margin-top: 0em;" class="title-dash-admin-draken">Dashboard</h1>
    <div>
        <div class="uk-child-width-1-2@m uk-grid-small uk-grid-match" uk-grid id="top-grids">
            <div>
                <div class="uk-card uk-card-default uk-card-body uk-body-draken">
                    <canvas style="width: 100%; height: 300px;" id="myChart"></canvas>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-body uk-body-draken">
                    <canvas style="width: 100%; height: 300px;" id="myCharts"></canvas>
                </div>
            </div>
        </div>

        <script>
            var ctx = document.getElementById("myChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["jan",	"fev",	"mar",	"abr",	"mai",	"jun", "jul", "ago", "set", "out", "nov", "dez"],
                    datasets: [{
                        label: 'Administradores Cadastrados', // Name the series
                        data: [@php echo substr($count_values_admin_array, 0, -2); @endphp], // Specify the data values array
                        fill: false,
                        borderColor: '#2196f3', // Add custom color border (Line)
                        backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
                        borderWidth: 1 // Specify bar border width
                    }]},
                options: {
                    responsive: true, // Instruct chart js to respond nicely.
                    maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height
                }
            });

            var ctxx = document.getElementById("myCharts").getContext('2d');
            var myChart = new Chart(ctxx, {
                type: 'line',
                data: {
                    labels: ["jan",	"fev",	"mar",	"abr",	"mai",	"jun", "jul", "ago", "set", "out", "nov", "dez"],
                    datasets: [{
                        label: 'Usuários Cadastrados', // Name the series
                        data: [@php echo substr($count_values_user_array, 0, -2); @endphp], // Specify the data values array
                        fill: false,
                        borderColor: 'rgba(255,99,132,5)', // Add custom color border (Line)
                        backgroundColor: 'rgba(255,99,132,0.9)', // Add custom color background (Points and Fill)
                        borderWidth: 1 // Specify bar border width
                    }]},
                options: {
                    responsive: true, // Instruct chart js to respond nicely.
                    maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height
                }
            });
        </script>
        <div id="top-grids">
            <div class="uk-child-width-1-2@m uk-grid-small uk-grid-match" uk-grid>
                <div>
                    <div class="uk-card uk-card-default uk-card-body">
                        <div class="inline-flex-draken">
                            <div>
                                <div class="img-developer-front-s" style="background-image: url({{ asset('assets/icons/developers/efadd560bd4bc4c172a102a837b32d97.gif') }})"></div>
                            </div>
                            <div style="text-align: right">
                                <p>Dexter Valetin</p>
                                <label>Desenvolvedor</label>
                            </div>
                        </div>
                        <hr style="border-top: 1px solid #e5e5e57d;">
                        <div class="inline-flex-draken-left">
                            <label class="label-flex-draken"><label class="label-unique">APP URL:</label> <span class="inner_pass_wel">{{ env('APP_URL'); }}</span></label>
                            <label class="label-flex-draken"><label class="label-unique">TIMEZONE:</label> <span class="inner_pass_wel">{{ env('APP_TIMEZONE'); }}</span></label>
                            <label class="label-flex-draken"><label class="label-unique">APP LOCALE:</label> <span class="inner_pass_wel">{{ env('APP_LOCALE'); }}</span></label>
                            <label class="label-flex-draken"><label class="label-unique">SESSION TIME:</label> <span class="inner_pass_wel">{{ env('SESSION_LIFETIME'); }}</span></label>
                            <label class="label-flex-draken"><label class="label-unique">APP VERSION:</label> <span class="inner_pass_wel">{{ env('APP_VERSION'); }}</span></label>
                        </div>
                    </div>

                </div>
                <div>
                </div>
            </div>
        </div>
    </div>
@endsection
