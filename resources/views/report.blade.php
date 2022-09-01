<link rel="stylesheet" href="{{ asset('assets/css/pdf.css') }}">
<style>
    body {
        font-size: 14px;
        font-family: sans-serif;
    }
    .table td, .table th, .table thead th {
        border: none !important;
        padding: 5px;
    }
    .custom-progress {
        width:100%;
        background:#cecece;
        height:18px;
        margin: 30px 0px 0px;
    }
    .progress-div {
        height:100%;
        z-index:99;
    }
    .progress-info-div {
        width: 100%;
    }
    .progress-extra-div {
        width:100%;
        margin-bottom: 30px;
    }
</style>
@php
    $total = $taker['correct_marks']*100/$taker['total_marks'];
    $total = ceil($total);
    if(!$total) {
        $total = 1;
    }
    
    $label = '';

    foreach($performance as $element) {
        if($total >= $element['min'] && $total <= $element['max']) {
            $label = $element['name'];
        }
    }
    $color = colorFunction($label);
    $chartArray = [];
    if($categories) {
        foreach($categories as $id=>$category) {
            $marks_total = 0;
            $bar_label = '';

            if(!isset($correct_sections[$id])) {
                $chartArray[$id]['total'] = 0;
                $chartArray[$id]['color'] = '#ff0000';
                $chartArray[$id]['label'] = 'Very Low';
                continue;
            }

            if($correct_sections[$id]) {
               $marks_total = $correct_sections[$id] * 100 / $sections[$id];
               $marks_total = ceil($marks_total);
            }

            foreach($performance as $subelement) {
                if($marks_total >= $subelement['min'] && $marks_total <= $subelement['max']) {
                    $bar_label = $subelement['name'];
                }
            }

            $bar_color = colorFunction($bar_label);

            $chartArray[$id]['total'] = $marks_total;
            $chartArray[$id]['color'] = $bar_color;
            $chartArray[$id]['label'] = $bar_label;

        }
    }   
@endphp

<table class="table">
    <tbody>
        <tr>
            <td class="col-md-12 text-center">
                <h1><strong>RPM</strong></h1>
            </td>
        </tr>
    </tbody>
</table>
<table class="table">
    <tbody>
        <tr>
            <td class="col-md-6">
                <strong>{{ $taker->test_name }}</strong>
            </td>
            <td class="col-md-6">
                <strong>Test Taken on:</strong> {{ Date('j F Y H:i:s', strtotime($taker->created_at)) }}
            </td>
        </tr>
    </tbody>
</table>
<table class="table">
    <thead>
        <tr>
            <th colspan="2">
                <h5><strong>Registration Details</strong></h5>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="col-md-6">
                <table class="table">
                    <tbody>
                        <tr>
                            <td><strong>Name</strong></td>
                            <td>{{ $taker->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>{{ $taker->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Contact</strong></td>
                            <td>{{ $taker->mobile?$taker->mobile:'Not Filled' }}</td>
                        </tr>
                        @if($taker->avatar)
                        <tr>
                            <td colspan=2><p><strong>Profile Picture</strong></p></td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <img src="{{ $taker->avatar?$taker->avatar: public_path('img/dummy-img.png') }}" style="width:200px;"/>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </td>
            <td class="col-md-6">
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
                        <tr>
                            <td><strong>&nbsp;</strong></td>
                            <td>&nbsp;</td>
                        </tr>
                        @if($taker->avatar)
                        <tr>
                            <td colspan=2><p><strong>Id Card</strong></p></td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <img src="{{ $taker->id_card?$taker->id_card: public_path('img/dummy-img.png') }}" style="width:200px;"/>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table class="table" style="page-break-inside: auto;">
    <thead>
        <tr>
            <th><h5><strong>Performance Summary</strong></h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>General Aptitude is defined as potential of the candidate and the ability to grasp new concepts which are crucial to his/ her role as an employee in the organization. It is the innate, learned or acquired ability of an individual to perform certain tasks. It helps assess Individual's capacity for learning in general regardless of any specific skill.</td>
        </tr>
        <tr>
            <td>
                <div class="custom-progress">
                    <div class="progress-div" style="width: {{ $total }}%;background: {{ $color }};">&nbsp;</div>
                </div>
                <div class="progress-info-div">
                    <div style="width: {{ $total }}%;text-align: right;">{{ $total }}%</div>
                </div>
                <div class="progress-extra-div">
                    <div style="text-align: center;margin-top:10px;">Cognitive Abilities: <span style="font-weight:600;">{{ $label }}</span></div>
                </div>                    
            </td>
        </tr>
    </tbody>
</table>
@if(isset($chartArray[3]) && isset($sections[3]))
<table class="table" style="page-break-inside: auto;">
    <thead>
        <tr>
            <th><h5><strong>Reasoning Ability</strong></h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>The ability of an individual to analyse and perceive the given information from different perspectives.</td>
        </tr>
        <tr>
            <td>
                <div class="custom-progress">
                    <div class="progress-div" style="width: {{ $chartArray[3]['total'] }}%;background: {{ $chartArray[3]['color'] }};">&nbsp;</div>
                </div>
                <div class="progress-info-div">
                    <div style="width: {{ $chartArray[3]['total'] }}%;text-align: right;">{{ $chartArray[3]['total'] }}%</div>
                </div>
                <div class="progress-extra-div">
                    <div style="text-align: center;margin-top:10px;">
                        Reasoning Ability: <span style="font-weight:600;">{{ $chartArray[3]['label'] }}</span>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
@endif
@if(isset($chartArray[2]) && isset($sections[2]))
<table class="table" style="page-break-inside: auto;">
    <thead>
        <tr>
            <th><h5><strong>Numerical Ability</strong></h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>The ability to perceive and process numbers and related symbols to perform basic arithmetic operations.</td>
        </tr>
        <tr>
            <td>
                <div class="custom-progress">
                    <div class="progress-div" style="width: {{ $chartArray[2]['total'] }}%;background: {{ $chartArray[2]['color'] }};">&nbsp;</div>
                </div>
                <div class="progress-info-div">
                    <div style="width: {{ $chartArray[2]['total'] }}%;text-align: right;">{{ $chartArray[2]['total'] }}%</div>
                </div>
                <div class="progress-extra-div">
                    <div style="text-align: center;margin-top:10px;">
                        Numerical Ability: <span style="font-weight:600;">{{ $chartArray[2]['label'] }}</span>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
@endif
@if(isset($chartArray[1]) && isset($sections[1]))
<table class="table" style="page-break-inside: auto;">
    <thead>
        <tr>
            <th><h5><strong>Attentions Ability</strong></h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>The ability to structure observations that are obtained by different data sources.</td>
        </tr>
        <tr>
            <td>
                <div class="custom-progress">
                    <div class="progress-div" style="width: {{ $chartArray[1]['total'] }}%;background: {{ $chartArray[1]['color'] }};">&nbsp;</div>
                </div>
                <div class="progress-info-div">
                    <div style="width: {{ $chartArray[1]['total'] }}%;text-align: right;">{{ $chartArray[1]['total'] }}%</div>
                </div>
                <div class="progress-extra-div">
                    <div style="text-align: center;margin-top:10px;">
                        Numerical Ability: <span style="font-weight:600;">{{ $chartArray[1]['label'] }}</span>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
@endif
@if(isset($chartArray[4]) && isset($sections[4]))
<table class="table" style="page-break-inside: auto;">
    <thead>
        <tr>
            <th><h5><strong>Verbal Ability</strong></h5></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>The ability to understand word meanings, word relationships and also interpret detailed information.</td>
        </tr>
        <tr>
            <td>
                <div class="custom-progress">
                    <div class="progress-div" style="width:{{ $chartArray[4]['total'] }}%;background: {{ $chartArray[4]['color'] }};">&nbsp;</div>
                </div>
                <div class="progress-info-div">
                    <div style="width:{{ $chartArray[4]['total'] }}%;text-align: right;">{{ $chartArray[4]['total'] }}%</div>
                </div>
                <div class="progress-extra-div">
                    <div style="text-align: center;margin-top:10px;">
                        Numerical Ability: <span style="font-weight:600;">{{ $chartArray[4]['label'] }}</span>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
@endif
<table class="table" style="page-break-before: always;">
    <thead>
        <tr>
            <th><h5><strong>Test Log</strong></h5></th>
        </tr>
    </thead>
</table>
@if(isset($avatars[0]))
<table class="table" style="page-break-inside: auto;">
    <tbody>
        <tr>
            <td><p><strong>Images of Test Taker</strong></p></td>
        </tr>
        <tr>
            <td>
            @forelse($avatars as $avatar)
                <img src="{{ $avatar->snap }}" style="width: 200px;border:solid 1px #000; padding: 5px;margin:5px;"/>
            @empty

            @endforelse
            </td>
        </tr>
    </tbody>
</table>
@endif
@if(isset($screenshots[0]))
    <table class="table" style="page-break-inside: auto;">
        <tbody>
            <tr>
                <td><p><strong>Screenshots of Test Taker</strong></p></td>
            </tr>
            @forelse($screenshots as $screenshot)
            <tr>
                <td style="text-align: center;vertical-align: bottom;">
                    <img src="{{ $screenshot->snap }}" style="width: 500px;padding: 20px 0px;"/>
                </td>
            </tr>
            @empty
            @endforelse
        </tbody>
    </table>
@endif
<table class="table" style="page-break-inside: auto;">
    <tbody>
        <tr>
            <td>
                <h6><strong>{{ Date('d F, Y', strtotime($taker->created_at)) }}</strong></h6>
            </td>
        </tr>
        <tr>
            <td>
                <ul>
                    <li>
                        <span class="text-muted">{{ Date('h:i a', strtotime($taker->created_at)) }}</span> Started the test.
                    </li>
                    @if(!empty($avatars))
                    <li>
                        <span class="text-muted">{{ Date('h:i a', strtotime($taker->created_at)) }}</span> Candidate gave us right to the following feeds: camera, microphone.
                    </li>
                    @endif
                    @if(!empty($screenshots))
                    <li>
                        <span class="text-muted">{{ Date('h:i a', strtotime($taker->created_at)) }}</span> Candidate gave us right to screencapture.
                    </li>
                    @endif
                    @forelse($logs as $key=>$time)
                    <li>
                        <span class="text-muted">{{ Date('h:i a', strtotime($time))  }}</span> Went to {{ $categories[$key] }} of the test. 
                    </li>
                    @empty
                    @endforelse
                    @if($taker->status == 2)
                    <li>
                        <span class="text-muted">{{ Date('h:i a', strtotime($taker->updated_at)) }}</span> Finished the test.
                    </li>
                    @endif
                </ul>
            </td>
        </tr>
    </tbody>
</table>
