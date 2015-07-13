//------Page Load----------
$(document).ready(function(){
    $("#landing-buttons-wrapper").show();
    $("#newReport-ticketNumber-wrapper").hide();
    $("#checkStatus-input-wrapper").hide();
    $(".back").hide();
});

$("#newReport_trigger").on('click',function(){

});

$("#checkStatus_trigger").on('click',function() {
    $("#landing-buttons-wrapper").hide();
    $("#newReport-ticketNumber-wrapper").hide();
    $("#checkStatus-input-wrapper").show();
    $(".back").css('margin-left','47%');
    $(".back").attr('id','fromCheckStatus');
    $(".back").show();
    $("#fromCheckStatus").on('click',function() {
        $("#userQuery").val('');
        $("#landing-buttons-wrapper").show();
        $("#newReport-ticketNumber-wrapper").hide();
        $("#checkStatus-input-wrapper").hide();
        $(".back").hide();
    });
});
