var project = '/prakerin/public';

$(function () {
	$('input[class="form-control tanggal"]').datepicker({
		autoclose: true,
		format: 'dd-mm-yyyy',
		viewMode: "days", 
	    minViewMode: "days"
	});
	
	$('input[class="form-control bln-thn"]').datepicker({
		autoclose: true,
		format: '01-mm-yyyy',
		viewMode: "months", 
	    minViewMode: "months"
	});
	
	$('input[class="form-control jam"]').timepicker({showMeridian: false, minuteStep: 1});
	$('input.lov').css({'background-color': '#D7DF01'});
});