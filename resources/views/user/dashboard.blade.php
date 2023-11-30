@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')

<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.26.3/dist/apexcharts.min.js"></script>

<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<link href="{{ asset('assets/css/calendar.css') }}" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid px-4">
    <h1 class="mt-4">HELLO</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">{{ strtoupper(Auth::user()->name) }}</li>
    </ol>

    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div id="calendar"></div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="panel panel-default">
                <table class="table table-bordered table-striped table-condensed table-responsive">
                    <tr>
                        <td class="text-center">
                            <div class="card">
                                <div class="card-body">
                                    @if (isset($data1))
                                        <div id="pie-chart1" class="chart-container"></div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <div class="card">
                                <div class="card-body">
                                    @if (isset($data2))
                                        <div id="pie-chart2" class="chart-container"></div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include FullCalendar and Moment.js -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<!-- Load Google Charts library -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- Load the Visualization API and the corechart package -->
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });

    // Set a callback to run when the Google Visualization API is loaded
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        // Chart 1
        var chartData1 = google.visualization.arrayToDataTable([
            ['Name', 'Total Amount'],
            @foreach ($data1 as $item)
                ['{{ $item['name'] }}', {{ $item['total_amount'] }}],
            @endforeach
        ]);

        var options1 = {
            title: 'Chart 1',
        };

        var chart1 = new google.visualization.PieChart(document.getElementById('pie-chart1'));
        chart1.draw(chartData1, options1);

        // Chart 2
        var chartData2 = google.visualization.arrayToDataTable([
            ['Name', 'Total Amount'],
            @foreach ($data2 as $item)
                ['{{ $item['name'] }}', {{ $item['total_amount'] }}],
            @endforeach
        ]);

        var options2 = {
            title: 'Chart 2',
        };

        var chart2 = new google.visualization.PieChart(document.getElementById('pie-chart2'));
        chart2.draw(chartData2, options2);
    }
</script>





<!-- Your JavaScript code for FullCalendar -->
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize the calendar with the initial event source
        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            eventSources: [{
                url: 'get-notes',
                color: 'gray',
                textColor: 'white',
            }],
            dayClick: function(date, jsEvent, view) {
                var note = prompt('Add a note for ' + date.format('YYYY-MM-DD'));
                if (note !== null) {
                    var userId = {{ auth()->user()->id }};
                    $.ajax({
                        type: 'POST',
                        url: 'save-note',
                        data: {
                            note: note,
                            date: date.format('YYYY-MM-DD'),
                            created_by: userId
                        },
                        success: function(response) {
                            alert('Note saved successfully');
                            calendar.fullCalendar('renderEvent', {
                                title: note,
                                start: date,
                                color: 'gray',
                                textColor: 'white',
                                created_by: userId
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            alert('Error saving note. Please try again.');
                        }
                    });
                }
            },
            eventClick: function(calEvent, jsEvent, view) {
                var modal = $(
                    '<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">');
                var modalDialog = $('<div class="modal-dialog modal-lg" role="document">');
                var modalContent = $('<div class="modal-content">');
                var modalBody = $('<div class="modal-body">');
                $.ajax({
                    type: 'GET',
                    url: 'get-username/' + calEvent.created_by,
                    success: function(response) {
                        var userName = response.name;
                        modalBody.html('Note: ' + calEvent.title +
                            '<br><br>Posted By: ' + userName);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
                var closeButton = $(
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
                );
                closeButton.html('<span aria-hidden="true">&times;</span>');
                var modalHeader = $('<div class="modal-header">');
                modalHeader.append(closeButton);
                modalContent.append(modalHeader);
                modalContent.append(modalBody);
                modalDialog.append(modalContent);
                modal.append(modalDialog);
                modal.modal('show');
            }
        });
    });
</script>

@endsection
