<link rel="stylesheet" href="{{ asset('assets/css/pdf.css') }}">
<style>
    body {
        font-size: 14px;
        font-family: Helvetica, sans-serif;
    }
    .table td, .table th, .table thead th {
        border: none !important;
    }
</style>

<table class="table table-borderless">
    <tbody>
        <tr>
            <td class="col-md-12">
                <h1>RPM</h1>
            </td>
        </tr>
    </tbody>
</table>
<table class="table table-borderless">
    <tbody>
        <tr>
            <td class="col-md-6">
                <span class="font-weight-bold">{{ $taker->test_name }}</span>
            </td>
            <td class="col-md-6">
                <p><span class="font-weight-bold">Test Taken on:</span> {{ Date('j F Y H:i:s', strtotime($taker->created_at)) }}</p>
            </td>
        </tr>
    </tbody>
</table>

<table class="table">
    <thead>
        <tr>
            <th colspan="2"><h5>Registration Details</h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="col-md-6 p-0">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold">Name</td>
                            <td>{{ $taker->name }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Email</td>
                            <td>{{ $taker->email }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Contact</td>
                            <td>{{ $taker->mobile?$taker->mobile:'Not Filled' }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td class="col-md-6 p-0">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold">Date of birth</td>
                            <td>{{ $taker->dob?$taker->dob:'Not Filled' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Gender</td>
                            <td>{{ $taker->gender?$taker->gender:'Not Filled' }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td class="col-md-6 p-0">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold pb-4">Profile Picture</td>
                        </tr>
                        <tr>
                            <td class="col-md-6">
                                <img src="{{ $taker->avatar?$taker->avatar: public_path('img/dummy-img.png') }}" width="200px"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td class="col-md-6 p-0">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold pb-4">Id Card</td>
                        </tr>
                        <tr>
                            <td class="col-md-6">
                                <img src="{{ $taker->id_card?$taker->id_card: public_path('img/dummy-img.png') }}" width="200px"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<table class="table">
    <thead>
        <tr>
            <th><h5>Performance Summary</h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>General Aptitude is defined as potential of the candidate and the ability to grasp new concepts which are crucial to his/ her role as an employee in the organization. It is the innate, learned or acquired ability of an individual to perform certain tasks. It helps assess Individual's capacity for learning in general regardless of any specific skill.</td>
        </tr>
        <tr>
            <td>
                <div id="performance_summary"></div>
            </td>
        </tr>
    </tbody>
</table>

<table class="table">
    <thead>
        <tr>
            <th><h5>Reasoning Ability</h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>The ability of an individual to analyse and perceive the given information from different perspectives.</td>
        </tr>
        <tr>
            <td>
                <div id="res_summary"></div>
            </td>
        </tr>
    </tbody>
</table>

<table class="table">
    <thead>
        <tr>
            <th><h5>Numerical Ability</h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>The ability to perceive and process numbers and related symbols to perform basic arithmetic operations.</td>
        </tr>
        <tr>
            <td>
                <div id="num_summary"></div>
            </td>
        </tr>
    </tbody>
</table>

<table class="table">
    <thead>
        <tr>
            <th><h5>Attentions Ability</h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>The ability to structure observations that are obtained by different data sources.</td>
        </tr>
        <tr>
            <td>
                <div id="att_summary"></div>
            </td>
        </tr>
    </tbody>
</table>

<table class="table">
    <thead>
        <tr>
            <th><h5>Verbal Ability</h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>The ability to understand word meanings, word relationships and also interpret detailed information.</td>
        </tr>
        <tr>
            <td>
                <div id="ver_summary"></div>
            </td>
        </tr>
    </tbody>
</table>

<table class="table">
    <thead>
        <tr>
            <th><h5>Test Log</h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Screenshots of Test Taker</td>
        </tr>
        <tr>
            <td>
            @forelse($screenshots as $screenshot)
                <img src="{{ $screenshot->snap }}" width="300" style="border:solid 1px #000; padding: 5px;margin:5px;"/>
            @empty

            @endforelse
            </td>
        </tr>
    </tbody>
</table>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/highcharts.src.js') }}"></script>
<script type="text/javascript">
 window.onload = function() {

    var report = {!! json_encode($taker->toArray()) !!}
    var total = report.correct_marks*100/report.total_marks
    total = Math.ceil(total)
    
    var color = colors(total)
    var label = ''
    
    var performance = {!! json_encode($performance) !!}

    performance.forEach(element => {
        if(total >= element.min && total <= element.max) {
            label = element.name;
        }
    })

    var Obj = { name: label, data: [total], color: color }

    performanceSummary('performance_summary', 'Cognitive Abilities', Obj);

    var categories = {!! json_encode($categories) !!}
    var correct_sections = {!! json_encode($correct_sections) !!}
    var sections = {!! json_encode($sections) !!}

    for(var i = 1; i <= Object.keys(categories).length; i++) {
                
        let marks_total = 0
        let section_label = 'Very Low'

        if(correct_sections[i]) {
           marks_total = correct_sections[i] * 100 / sections[i]
           marks_total = Math.ceil(marks_total)
        }

        let section_color = colors(marks_total)

        performance.forEach(element => {
            if(marks_total >= element.min && marks_total <= element.max) {
                section_label = element.name;
            }
        })

        if(i == 1) {
            var AttObj = { name: section_label, data: [marks_total], color: section_color }
            performanceSummary('att_summary', 'Attentions Abilities', AttObj);
        }

        if(i == 2) {
            var NumObj = { name: section_label, data: [marks_total], color: section_color }
            performanceSummary('num_summary', 'Numerical Abilities', NumObj);
        }

        if(i == 3) {
            var ResObj = { name: section_label, data: [marks_total], color: section_color }
             performanceSummary('res_summary', 'Reasoning Abilities', ResObj);
        }

        if(i == 4) {
            var VerObj = { name: section_label, data: [marks_total], color: section_color }
            performanceSummary('ver_summary', 'Verbal Abilities', VerObj);
        }
    }
};

function colors(val) {
    if(val <= 40) {
        return '#ff0000'
    }
    else if(val > 41 && val <= 60) {
        return '#7239ea'
    }
    else if(val > 61 && val <= 80) {
        return '#ffc700'
    }
    else {
        return '#50cd89'
    }
}

function performanceSummary(id, title, Obj) {
    Highcharts.chart(id, {
        chart: {
            height: 140,
            type: 'bar'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [title]
        },
        yAxis: {
            min: 0,
            max:100,
            title: {
              text: 'Values shown in above chart are percentages'
            }
        },
        legend: {
            enabled: true,
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal'
            }
        },
        credits: {
            enabled: false
        },
        series: [Obj]
    });
}

</script>
