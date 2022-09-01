<?php
    function colorFunction($total) {
        
        if($total == '') {
            return '#ff0000';
        }

        if($total == 'Very Low') {
            return '#ff0000';
        }
        else if($total == 'Low') {
            return '#cd5757';
        }
        else if($total == 'Very High') {
            return '#5345a1';
        }
        else if($total == 'High') {
            return '#2710a5';
        }
        else if($total == 'Moderate') {
            return '#28af51';
        }
        else if($total == 'Excellent') {
            return '#50cd89';
        }
        else {
            return '#50cd89';
        }
    }