@section('javascript')
$(document).ready(function(){
$('.price-format').change(function (e) {
console.log('test');
$(this).val(
parseFloat($(this).val().replace(/,/g, '.'), 10).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$1,")
);
});
});
@endsection