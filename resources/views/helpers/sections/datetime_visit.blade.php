@section('datetime-visit-css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('datetime-visit-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Core plugin JavaScript-->
<script src="{{ asset('js/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
@endsection



@section('javascript')
(function($) {
$(function() {
$('#visit_date').datetimepicker({
i18n: {
pl: {
months: [
'Styczeń', 'Luty', 'Marzec', 'Kwiecień',
'Maj', 'Czerwiec', 'Lipiec', 'Sierpień',
'Wrzesień', 'Październik', 'Listopad', 'Grudzień',
],
dayOfWeek: [
"Nd", "Pon", "Wt", "Śr",
"Cz", "Pt", "So",
]
}
},
icons: {
time: 'glyphicon glyphicon-time',
date: 'glyphicon glyphicon-calendar',
up: 'glyphicon glyphicon-chevron-up',
down: 'glyphicon glyphicon-chevron-down',
previous: 'glyphicon glyphicon-chevron-left',
next: 'glyphicon glyphicon-chevron-right',
today: 'glyphicon glyphicon-screenshot',
clear: 'glyphicon glyphicon-trash',
close: 'glyphicon glyphicon-remove'
},
timepicker: false,
format: "Y-m-d",
lang: 'pl',
onChangeDateTime: function(dp, $input) {
//console.log($input.val());
},
mask: true,
minDate: '-1970/01/2',
});
$.datetimepicker.setLocale('pl');
});
})(jQuery);
@endsection