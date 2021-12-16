@section('javascript')
//BUY
$('#net_price_buy').keyup(function(e) {
var net_price_buy = e.target.value;
var vat_buy = $( "#vat_buy_id option:selected" ).text();
var gross_price_buy = (net_price_buy * (1+(vat_buy/100))).toFixed(2);
$( "input[type=text][name=gross_price_buy]").val(gross_price_buy);
});
$('#gross_price_buy').keyup(function(e) {
var gross_price_buy = e.target.value;
var vat_buy = $( "#vat_buy_id option:selected" ).text();
var net_price_buy = (gross_price_buy / (1+(vat_buy/100))).toFixed(2);
$( "input[type=text][name=net_price_buy]").val(net_price_buy);
});
$( "#vat_buy_id" ).change(function() {
var net_price_buy = $( "input[type=text][name=net_price_buy]").val();
var vat_buy = $( "#vat_buy_id option:selected" ).text();
var gross_price_buy = (net_price_buy * (1+(vat_buy/100))).toFixed(2);
$( "input[type=text][name=gross_price_buy]").val(gross_price_buy);
});
// SELL
$('#net_price_sell').keyup(function(e) {
var net_price_sell = e.target.value;
var vat_sell = $( "#vat_sell_id option:selected" ).text();
var gross_price_sell = (net_price_sell * (1+(vat_sell/100))).toFixed(2);
$( "input[type=text][name=gross_price_sell]").val(gross_price_sell);
});
$('#gross_price_sell').keyup(function(e) {
var gross_price_sell = e.target.value;
var vat_sell = $( "#vat_sell_id option:selected" ).text();
var net_price_sell = (gross_price_sell / (1+(vat_sell/100))).toFixed(2);
$( "input[type=text][name=net_price_sell]").val(net_price_sell);
});
$( "#vat_sell_id" ).change(function() {
var net_price_sell = $( "input[type=text][name=net_price_sell]").val();
var vat_sell = $( "#vat_sell_id option:selected" ).text();
var gross_price_sell = (net_price_sell * (1+(vat_sell/100))).toFixed(2);
$( "input[type=text][name=gross_price_sell]").val(gross_price_sell);
});
@endsection